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
 * Provides RussianPost order shipment service injection.
 *
 * @since 1.0.0
 */
trait RetailCrmOrderToRussianPostShipmentServiceAwareTrait
{
	private ?RetailCrmOrderToRussianPostShipmentService $retailCrmOrderToRussianPostShipmentService = null;

	/**
	 * Get the RussianPost order shipment service.
	 *
	 * @return RetailCrmOrderToRussianPostShipmentService RetailCRM to Russian Post shipment service.
	 *
	 * @throws ContainerExceptionInterface When the service cannot be created.
	 * @throws NotFoundExceptionInterface When the service is not registered.
	 * @throws UnexpectedValueException When the service has not been injected.
	 *
	 * @since 1.0.0
	 */
	public function getRetailCrmOrderToRussianPostShipmentService(): RetailCrmOrderToRussianPostShipmentService
	{
		if ($this->retailCrmOrderToRussianPostShipmentService instanceof RetailCrmOrderToRussianPostShipmentService)
		{
			return $this->retailCrmOrderToRussianPostShipmentService;
		}

		$retailCrmOrderToRussianPostShipmentService = $this->getContainer()
			->get(RetailCrmOrderToRussianPostShipmentService::class);

		if ($retailCrmOrderToRussianPostShipmentService instanceof RetailCrmOrderToRussianPostShipmentService)
		{
			$this->retailCrmOrderToRussianPostShipmentService = $retailCrmOrderToRussianPostShipmentService;

			return $this->retailCrmOrderToRussianPostShipmentService;
		}

		throw new UnexpectedValueException(
			'RetailCrmOrderToRussianPostShipmentService is not available in ' . static::class . '.'
		);
	}

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
	): void
	{
		$this->retailCrmOrderToRussianPostShipmentService = $retailCrmOrderToRussianPostShipmentService;
	}
}
