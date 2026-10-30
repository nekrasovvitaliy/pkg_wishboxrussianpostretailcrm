<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license     GNU General Public License version 2 or later;
 */

namespace Joomla\Plugin\Webservices\WishboxRussianPostRetailCrm\Extension;

use Joomla\CMS\Event\Application\BeforeApiRouteEvent;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\SubscriberInterface;
use Joomla\Router\Route;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Registers RussianPost RetailCRM API routes.
 *
 * @since 1.0.0
 */
final class WishboxRussianPostRetailCrm extends CMSPlugin implements SubscriberInterface
{
	/**
	 * Return the subscribed Joomla events.
	 *
	 * @return array<string, string>
	 *
	 * @since        1.0.0
	 *
	 * @noinspection PhpUnused
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			'onBeforeApiRoute' => 'onBeforeApiRoute',
		];
	}

	/**
	 * Register the RussianPost RetailCRM API routes.
	 *
	 * @param   BeforeApiRouteEvent  $event  API routing event.
	 *
	 * @return void
	 *
	 * @since        1.0.0
	 *
	 * @noinspection PhpUnused
	 */
	public function onBeforeApiRoute(BeforeApiRouteEvent $event): void
	{
		$shipRoute = new Route(
			['PATCH'],
			'v1/wishboxrussianpostretailcrm/orders/:retailcrm_order_id/ship',
			'orders.ship',
			['retailcrm_order_id' => '(\d+)'],
			[
				'component' => 'com_wishboxrussianpostretailcrm',
				'format'    => ['application/json'],
			]
		);

		$router = $event->getRouter();
		$router->addRoute($shipRoute);
	}
}
