<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license     GNU General Public License version 2 or later;
 */

namespace WishboxRussianPostRetailCrmLibrary\Service;

use Exception;
use InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RetailCrm\Api\Exception\Api\AccountDoesNotExistException;
use RetailCrm\Api\Exception\Api\ApiErrorException;
use RetailCrm\Api\Exception\Api\MissingCredentialsException;
use RetailCrm\Api\Exception\Api\MissingParameterException;
use RetailCrm\Api\Exception\Api\ValidationException;
use RetailCrm\Api\Exception\Client\HandlerException;
use RetailCrm\Api\Exception\Client\HttpClientException;
use RetailCrm\Api\Interfaces\ApiExceptionInterface;
use RetailCrm\Api\Interfaces\ClientExceptionInterface;
use RuntimeException;
use WishboxRussianPost\Enum\MailCategory;
use WishboxRussianPost\Enum\MailType;
use WishboxRussianPostLibrary\Service\Registration\OrderRegistrationService;
use WishboxRussianPostRetailCrmLibrary\OrderRegistration\DataProvider\RetailCrmOrderRegistrationDataProvider;
use WishboxRetailCrmLibrary\Service\RetailCrmOrderService;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Registers RetailCRM orders in Russian Post.
 *
 * @since 1.0.0
 */
final readonly class RetailCrmOrderToRussianPostShipmentService
{
	/**
	 * Initialize the RetailCRM to Russian Post registration service.
	 *
	 * @param   RetailCrmOrderService     $retailCrmOrderService     RetailCRM order service.
	 * @param   OrderRegistrationService  $orderRegistrationService  Russian Post registration service.
	 * @param   MailType                  $mailType                  Russian Post mail type.
	 * @param   MailCategory              $mailCategory              Russian Post mail category.
	 * @param   string                    $defaultPostOfficeCode      Sender post office code.
	 * @param   bool                      $fragile                    Default fragile flag.
	 * @param   string                    $orderNumberPrefix          Russian Post order number prefix.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function __construct(
		private RetailCrmOrderService    $retailCrmOrderService,
		private OrderRegistrationService $orderRegistrationService,
		private MailType                 $mailType,
		private MailCategory             $mailCategory,
		private string                   $defaultPostOfficeCode,
		private bool                     $fragile,
		private string                   $orderNumberPrefix
	)
	{
	}

	/**
	 * Register a RetailCRM order in Russian Post.
	 *
	 * @param   int  $retailCrmOrderId  RetailCRM order identifier.
	 *
	 * @return void
	 *
	 * @throws AccountDoesNotExistException
	 * @throws ApiErrorException
	 * @throws MissingCredentialsException
	 * @throws MissingParameterException
	 * @throws ValidationException
	 * @throws HandlerException
	 * @throws HttpClientException
	 * @throws ApiExceptionInterface
	 * @throws ClientExceptionInterface
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 * @throws InvalidArgumentException
	 * @throws RuntimeException
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public function ship(int $retailCrmOrderId): void
	{
		if ($retailCrmOrderId < 1)
		{
			throw new InvalidArgumentException('RetailCRM order ID must be more than zero.');
		}

		$order = $this->retailCrmOrderService->getOrder($retailCrmOrderId);

		$dataProvider = new RetailCrmOrderRegistrationDataProvider(
			$order,
			$this->mailType,
			$this->mailCategory,
			$this->defaultPostOfficeCode,
			$this->fragile,
			$this->orderNumberPrefix
		);

		$this->orderRegistrationService->register($dataProvider);
	}
}
