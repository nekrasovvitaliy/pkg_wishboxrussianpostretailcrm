<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license     GNU General Public License version 2 or later;
 */

namespace Joomla\Component\WishboxRussianPostRetailCrm\Administrator\Controller;

use Joomla\CMS\MVC\Controller\BaseController;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Displays the WishBox Russian Post RetailCRM administrator dashboard.
 *
 * @since 1.0.0
 *
 * @noinspection PhpUnused
 */
final class DisplayController extends BaseController
{
	/**
	 * Default administrator view.
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	protected $default_view = 'dashboard'; // phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore
}
