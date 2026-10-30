<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license     GNU General Public License version 2 or later;
 */

namespace Joomla\Component\WishboxRussianPostRetailCrm\Administrator\View\Dashboard;

use Exception;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Renders the WishBox Russian Post RetailCRM administrator dashboard.
 *
 * @since 1.0.0
 *
 * @noinspection PhpUnused
 */
final class HtmlView extends BaseHtmlView
{
	/**
	 * Display the empty dashboard and its toolbar.
	 *
	 * @param   string|null  $tpl  Template name.
	 *
	 * @return void
	 *
	 * @throws Exception When the dashboard cannot be rendered.
	 *
	 * @since 1.0.0
	 *
	 * @noinspection PhpUnused
	 */
	public function display($tpl = null): void
	{
		$currentUser = $this->getCurrentUser();

		ToolbarHelper::title(Text::_('COM_WISHBOXRUSSIANPOSTRETAILCRM_TITLE'), 'link');

		if ($currentUser->authorise('core.admin', 'com_wishboxrussianpostretailcrm')
			|| $currentUser->authorise('core.options', 'com_wishboxrussianpostretailcrm'))
		{
			ToolbarHelper::preferences('com_wishboxrussianpostretailcrm');
		}

		parent::display($tpl);
	}
}
