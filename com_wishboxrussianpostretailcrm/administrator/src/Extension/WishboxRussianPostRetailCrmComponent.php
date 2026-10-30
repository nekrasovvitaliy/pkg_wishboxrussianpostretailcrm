<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license     GNU General Public License version 2 or later;
 */

namespace Joomla\Component\WishboxRussianPostRetailCrm\Administrator\Extension;

use Joomla\CMS\Extension\MVCComponent;
use WishboxRussianPostLibrary\Service\ContainerAwareInterface;
use WishboxRussianPostLibrary\Service\ContainerAwareTrait;
use WishboxRussianPostRetailCrmLibrary\Service\RetailCrmOrderToRussianPostShipmentServiceAwareInterface;
use WishboxRussianPostRetailCrmLibrary\Service\RetailCrmOrderToRussianPostShipmentServiceAwareTrait;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * WishBox RussianPost RetailCRM administrator component.
 *
 * @since 1.0.0
 */
final class WishboxRussianPostRetailCrmComponent extends MVCComponent implements
	ContainerAwareInterface,
	RetailCrmOrderToRussianPostShipmentServiceAwareInterface
{
	use ContainerAwareTrait;
	use RetailCrmOrderToRussianPostShipmentServiceAwareTrait;
}
