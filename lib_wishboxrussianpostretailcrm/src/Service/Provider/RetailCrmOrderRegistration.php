<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license     GNU General Public License version 2 or later;
 */

namespace WishboxRussianPostRetailCrmLibrary\Service\Provider;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use RuntimeException;
use WishboxRussianPost\Enum\MailCategory;
use WishboxRussianPost\Enum\MailType;
use WishboxRussianPostLibrary\Extension\Service\Provider\RequestFactory;
use WishboxRussianPostLibrary\Service\Provider\OrderRegistration as OrderRegistrationProvider;
use WishboxRussianPostLibrary\Service\Provider\RussianPostClientProvider;
use WishboxRussianPostLibrary\Service\Registration\OrderRegistrationService;
use WishboxRussianPostRetailCrmLibrary\Service\RetailCrmOrderToRussianPostShipmentService;
use WishboxRetailCrmLibrary\Service\Provider\RetailCrmOrderServiceProvider;
use WishboxRetailCrmLibrary\Service\Provider\RetailCrmServiceProvider;
use WishboxRetailCrmLibrary\Service\RetailCrmOrderService;
use function class_exists;
use function defined;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Registers the RetailCRM to Russian Post order registration services.
 *
 * @since 1.0.0
 */
final class RetailCrmOrderRegistration implements ServiceProviderInterface
{
	/**
	 * Register integration services in the dependency injection container.
	 *
	 * @param   Container  $container  Dependency injection container.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function register(Container $container): void
	{
		if (
			!class_exists(RetailCrmServiceProvider::class)
			|| !class_exists(RetailCrmOrderServiceProvider::class)
		)
		{
			throw new RuntimeException(
				'The WishBox RetailCRM library must be installed before using this integration.'
			);
		}

		$container->registerServiceProvider(new RequestFactory());
		$container->registerServiceProvider(new RussianPostClientProvider());
		$container->registerServiceProvider(new OrderRegistrationProvider());
		$container->registerServiceProvider(new RetailCrmServiceProvider());
		$container->registerServiceProvider(new RetailCrmOrderServiceProvider());

		$container->share(
			RetailCrmOrderToRussianPostShipmentService::class,
			static function (Container $container): RetailCrmOrderToRussianPostShipmentService {
				$wishboxRussianPostComponentParams          = ComponentHelper::getParams('com_wishboxrussianpost');
				$wishboxRussianPostRetailCrmComponentParams = ComponentHelper::getParams(
					'com_wishboxrussianpostretailcrm'
				);

				return new RetailCrmOrderToRussianPostShipmentService(
					$container->get(RetailCrmOrderService::class),
					$container->get(OrderRegistrationService::class),
					MailType::from(
						(string) $wishboxRussianPostRetailCrmComponentParams->get(
							'mail_type',
							MailType::OnlineParcel->value
						)
					),
					MailCategory::from(
						(string) $wishboxRussianPostRetailCrmComponentParams->get(
							'mail_category',
							MailCategory::Ordinary->value
						)
					),
					(string) $wishboxRussianPostComponentParams->get('default_postoffice_code', ''),
					(bool) $wishboxRussianPostComponentParams->get('fragile', false),
					(string) $wishboxRussianPostRetailCrmComponentParams->get('order_number_prefix', '')
				);
			}
		);
	}
}
