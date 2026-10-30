<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license     GNU General Public License version 2 or later;
 */

namespace WishboxRussianPostRetailCrmLibrary\Service;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnexpectedValueException;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Defines the contract for classes that depend on RussianPost order shipment.
 *
 * @since 1.0.0
 */
interface RetailCrmOrderToRussianPostShipmentServiceAwareInterface
{
	/**
	 * Get the RussianPost order shipment service.
	 *
	 * @return RetailCrmOrderToRussianPostShipmentService RetailCRM to Russian Post shipment service.
	 *
	 * @throws ContainerExceptionInterface When the service cannot be created.
	 * @throws NotFoundExceptionInterface When the service is not registered.
	 * @throws UnexpectedValueException When the service is unavailable.
	 *
	 * @since 1.0.0
	 */
	public function getRetailCrmOrderToRussianPostShipmentService(): RetailCrmOrderToRussianPostShipmentService;

	/**
	 * Set the RussianPost order shipment service.
	 *
	 * @param   RetailCrmOrderToRussianPostShipmentService  $retailCrmOrderToRussianPostShipmentService  Shipment service.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function setRetailCrmOrderToRussianPostShipmentService(
		RetailCrmOrderToRussianPostShipmentService $retailCrmOrderToRussianPostShipmentService
	): void;
}
