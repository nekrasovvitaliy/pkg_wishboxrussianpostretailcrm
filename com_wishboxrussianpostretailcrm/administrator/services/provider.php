<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license     GNU General Public License version 2 or later;
 */

use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Component\WishboxRussianPostRetailCrm\Administrator\Extension\WishboxRussianPostRetailCrmComponent;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use WishboxRussianPostRetailCrmLibrary\Service\Provider\RetailCrmOrderRegistration;

defined('_JEXEC') or die;

return new class implements ServiceProviderInterface {
	/**
	 * Register the component services.
	 *
	 * @param   Container  $container  Dependency injection container.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 *
	 * @noinspection PhpUnused
	 */
	public function register(Container $container): void
	{
		$container->registerServiceProvider(new RetailCrmOrderRegistration());

		$container->registerServiceProvider(
			new MVCFactory('\\Joomla\\Component\\WishboxRussianPostRetailCrm')
		);
		$container->registerServiceProvider(
			new ComponentDispatcherFactory('\\Joomla\\Component\\WishboxRussianPostRetailCrm')
		);

		$container->set(
			ComponentInterface::class,
			static function (Container $container): ComponentInterface {
				$wishboxRussianPostRetailCrmComponent = new WishboxRussianPostRetailCrmComponent(
					$container->get(ComponentDispatcherFactoryInterface::class)
				);
				$wishboxRussianPostRetailCrmComponent->setMVCFactory(
					$container->get(MVCFactoryInterface::class)
				);
				$wishboxRussianPostRetailCrmComponent->setContainer($container);

				return $wishboxRussianPostRetailCrmComponent;
			}
		);
	}
};
