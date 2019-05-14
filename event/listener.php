<?php
/**
*
* @package phpBB Extension - Hide code for guests
* @copyright (c) 2019 dmzx - https://www.dmzx-web.net & martin https://www.martins-phpbb.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\hidecodeforguests\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use phpbb\language\language;
use phpbb\user;
use phpbb\template\template;

class listener implements EventSubscriberInterface
{
	/** @var language */
	protected $language;

	/** @var user */
	protected $user;

	/** @var template */
	protected $template;

	/** @var string */
	protected $root_path;

	/** @var string */
	protected $php_ext;

	/**
	* Constructor
	*
	* @param language			$language
	* @param user				$user
	* @param template		 	$template
	* @param string				$root_path
	* @param string				$php_ext
	*/
	public function __construct(
		language $language,
		user $user,
		template $template,
		$root_path,
		$php_ext
	)
	{
		$this->language 	= $language;
		$this->user 		= $user;
		$this->template 	= $template;
		$this->root_path 	= $root_path;
		$this->php_ext 		= $php_ext;
	}

	static public function getSubscribedEvents()
	{
		return [
			'core.modify_format_display_text_after'		=> 'message_parser_event',
			'core.modify_text_for_display_after'		=> 'message_parser_event',
		];
	}

	public function message_parser_event($event)
	{
		if (isset($event['text']))
		{
			$text = $event['text'];

			if ($this->user->data['user_id'] == ANONYMOUS)
			{
				$this->language->add_lang('common', 'dmzx/hidecodeforguests');

				$this->template->assign_var('S_HIDECODEFORGUESTS', true);

				$url_login = append_sid("{$this->root_path}ucp.{$this->php_ext}", "mode=login");
				$url_register = append_sid("{$this->root_path}ucp.{$this->php_ext}", "mode=register");

				$hcfg_text =	$this->language->lang('HCFG_TEXT', '<a href="' . $url_login . '">', '</a>', '<a href="' . $url_register . '">', '</a>');

				$text = preg_replace('#<div class="codebox">(.*?)</div>#msi', '<span class="hcfg">' . $hcfg_text . '</span>', $text);
			}
			$event['text'] = $text;
		}
	}
}
