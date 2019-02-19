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

class listener implements EventSubscriberInterface
{
	/** @var language */
	protected $language;

	/** @var user */
	protected $user;

	/**
	* Constructor
	*
	* @param language				$language
	* @param user					$user
	*/
	public function __construct(
		language $language,
		user $user
	)
	{
		$this->language = $language;
		$this->user 	= $user;
	}

	static public function getSubscribedEvents()
	{
		return [
			'core.user_setup' 							=> 'load_language',
			'core.modify_format_display_text_after'		=> 'message_parser_event',
			'core.modify_text_for_display_after'		=> 'message_parser_event',
		];
	}

	public function load_language($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = [
			'ext_name'	=> 'dmzx/hidecodeforguests',
			'lang_set'	=> 'common'
		];
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function message_parser_event($event)
	{
		if (isset($event['text']))
		{
			$text = $event['text'];

			if ($this->user->data['user_id'] == ANONYMOUS)
			{
				$text = preg_replace('#<div class="codebox">(.*?)</div>#msi', '<span class="hcfg">' . $this->language->lang('HCFG_PLEASE') . '<a href="ucp.php?mode=login">' . $this->language->lang('HCFG_LOGIN') . '</a>' . $this->language->lang('HCFG_OR') . '<a href="ucp.php?mode=register">' . $this->language->lang('HCFG_REGISTER') . '</a>' . $this->language->lang('HCFG_TO_SEE_THIS_CONTENT') . '</span>',	$text);
			}
			$event['text'] = $text;
		}
	}
}
