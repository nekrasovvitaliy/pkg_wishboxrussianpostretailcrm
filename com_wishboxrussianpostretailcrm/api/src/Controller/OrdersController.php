<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license     GNU General Public License version 2 or later;
 */

namespace Joomla\Component\WishboxRussianPostRetailCrm\Api\Controller;

use InvalidArgumentException;
use Joomla\CMS\Application\ApiApplication;
use Joomla\CMS\Log\Log;
use Joomla\Component\WishboxRussianPostRetailCrm\Administrator\Extension\WishboxRussianPostRetailCrmComponent;
use Throwable;
use WishboxRetailCrmLibrary\Controller\RetailCrmApiController;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Handles RetailCRM requests to the Russian Post delivery integration.
 *
 * @since        1.0.0
 *
 * @noinspection PhpUnused
 */
final class OrdersController extends RetailCrmApiController
{
	/**
	 * Create a Russian Post shipping order requested by RetailCRM.
	 *
	 * @return void
	 *
	 * @since        1.0.0
	 *
	 * @noinspection PhpUnused
	 */
	public function ship(): void
	{
		try
		{
			$retailCrmOrderId = $this->input->getInt('retailcrm_order_id');

			if ($retailCrmOrderId <= 0)
			{
				throw new InvalidArgumentException('retailcrm_order_id must be greater than zero.');
			}

			/** @var ApiApplication $app */
			$app = $this->app;

			/** @var WishboxRussianPostRetailCrmComponent $wishboxRussianPostRetailCrmComponent */
			$wishboxRussianPostRetailCrmComponent = $app->bootComponent('com_wishboxrussianpostretailcrm');

			$retailCrmOrderToRussianPostShipmentService =
				$wishboxRussianPostRetailCrmComponent->getRetailCrmOrderToRussianPostShipmentService();

			$retailCrmOrderToRussianPostShipmentService->ship($retailCrmOrderId);
		}
		catch (InvalidArgumentException $invalidArgumentException)
		{
			$this->setRetailCrmResponse(
				[
					'success'  => false,
					'errorMsg' => $invalidArgumentException->getMessage(),
				],
				400
			);

			return;
		}
		catch (Throwable $throwable)
		{
			Log::add((string) $throwable, Log::ERROR, 'com_wishboxrussianpostretailcrm');
			$this->setRetailCrmResponse(
				[
					'success'  => false,
					'errorMsg' => 'Unable to create the Russian Post shipping order.',
				],
				500
			);

			return;
		}

		$this->setRetailCrmResponse(['success' => true], 200);
	}
}
