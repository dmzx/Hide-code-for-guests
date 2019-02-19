<?php
/**
*
* @package phpBB Extension - Hide code for guests
* @copyright (c) 2019 dmzx - https://www.dmzx-web.net & martin https://www.martins-phpbb.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\hidecodeforguests;

use phpbb\extension\base;

class ext extends base
{
	public function is_enableable()
	{
		return phpbb_version_compare(PHPBB_VERSION, '3.2.0', '>=');
	}
}
