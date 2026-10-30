<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license     GNU General Public License version 2 or later;
 */

namespace WishboxRussianPostRetailCrmLibrary\OrderRegistration\DataProvider;

use RetailCrm\Api\Model\Entity\Orders\Delivery\OrderDeliveryAddress;
use RetailCrm\Api\Model\Entity\Orders\Delivery\SerializedOrderDelivery;
use RetailCrm\Api\Model\Entity\Orders\Order;
use RuntimeException;
use WishboxRussianPost\Enum\MailCategory;
use WishboxRussianPost\Enum\MailType;
use WishboxRussianPostLibrary\Interface\OrderRegistrationDataProviderInterface;
use function ceil;
use function defined;
use function implode;
use function preg_replace;
use function trim;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Adapts a RetailCRM order to the Russian Post registration contract.
 *
 * @since 1.0.0
 */
final readonly class RetailCrmOrderRegistrationDataProvider implements OrderRegistrationDataProviderInterface
{
	/**
	 * Initialize the RetailCRM order data provider.
	 *
	 * @param   Order         $order                  RetailCRM order.
	 * @param   MailType      $mailType               Russian Post mail type.
	 * @param   MailCategory  $mailCategory           Russian Post mail category.
	 * @param   string        $defaultPostOfficeCode  Sender post office code.
	 * @param   bool          $fragile                Fragile handling flag.
	 * @param   string        $orderNumberPrefix      Russian Post order number prefix.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function __construct(
		private Order        $order,
		private MailType     $mailType,
		private MailCategory $mailCategory,
		private string       $defaultPostOfficeCode,
		private bool         $fragile,
		private string       $orderNumberPrefix
	)
	{
	}

	/**
	 * Return an empty identifier because registration resolves orders by number.
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public function getRussianPostOrderId(): string
	{
		return '';
	}

	/**
	 * Return the integration shipping method identifier.
	 *
	 * @return int
	 *
	 * @since 1.0.0
	 */
	public function getShippingMethodId(): int
	{
		return 0;
	}

	/**
	 * Return the configured Russian Post mail type.
	 *
	 * @return MailType
	 *
	 * @since 1.0.0
	 */
	public function getMailType(): MailType
	{
		return $this->mailType;
	}

	/**
	 * Return the configured Russian Post mail category.
	 *
	 * @return MailCategory
	 *
	 * @since 1.0.0
	 */
	public function getMailCategory(): MailCategory
	{
		return $this->mailCategory;
	}

	/**
	 * Return the shipment mass.
	 *
	 * @return int
	 *
	 * @since 1.0.0
	 */
	public function getMass(): int
	{
		return (int) ceil((float) $this->order->weight);
	}

	/**
	 * Return whether completeness checking is required.
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function hasCompletenessChecking(): bool
	{
		return false;
	}

	/**
	 * Return whether contents checking is required.
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function hasContentsChecking(): bool
	{
		return false;
	}

	/**
	 * Return whether an inventory is required.
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function hasInventory(): bool
	{
		return false;
	}

	/**
	 * Return whether accompanying documents are required.
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function hasVsd(): bool
	{
		return false;
	}

	/**
	 * Return whether an electronic notice is required.
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function hasElectronicNotice(): bool
	{
		return false;
	}

	/**
	 * Return whether an order-of-delivery notice is required.
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function hasOrderOfNotice(): bool
	{
		return false;
	}

	/**
	 * Return whether a simple notice is required.
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function hasSimpleNotice(): bool
	{
		return false;
	}

	/**
	 * Return the sender postal index when known.
	 *
	 * @return string|null
	 *
	 * @since 1.0.0
	 */
	public function getIndexFrom(): ?string
	{
		return null;
	}

	/**
	 * Return the recipient postal index.
	 *
	 * @return string|null
	 *
	 * @since 1.0.0
	 */
	public function getIndexTo(): ?string
	{
		return $this->normaliseString($this->getAddress()->index);
	}

	/**
	 * Return whether courier delivery is required.
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function getCourier(): bool
	{
		return false;
	}

	/**
	 * Return whether fragile handling is required.
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function getFragile(): bool
	{
		return $this->fragile;
	}

	/**
	 * Return the external Russian Post order number.
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public function getOrderNumber(): string
	{
		return trim($this->orderNumberPrefix) . trim((string) $this->order->number);
	}

	/**
	 * Return the accepting post office code.
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public function getPostofficeCode(): string
	{
		return trim($this->defaultPostOfficeCode);
	}

	/**
	 * Return the recipient name.
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public function getRecipientName(): string
	{
		$nameParts = [];

		foreach ([$this->order->lastName, $this->order->firstName, $this->order->patronymic] as $namePart)
		{
			$namePart = $this->normaliseString($namePart);

			if ($namePart !== null)
			{
				$nameParts[] = $namePart;
			}
		}

		return implode(' ', $nameParts);
	}

	/**
	 * Return the recipient region.
	 *
	 * @return string|null
	 *
	 * @since 1.0.0
	 */
	public function getRegionTo(): ?string
	{
		return $this->normaliseString($this->getAddress()->region);
	}

	/**
	 * Return the recipient locality.
	 *
	 * @return string|null
	 *
	 * @since 1.0.0
	 */
	public function getPlaceTo(): ?string
	{
		return $this->normaliseString($this->getAddress()->city);
	}

	/**
	 * Return the unprocessed recipient address.
	 *
	 * @return string|null
	 *
	 * @since 1.0.0
	 */
	public function getRawAddress(): ?string
	{
		return $this->normaliseString($this->getAddress()->text);
	}

	/**
	 * Return the recipient phone number.
	 *
	 * @return int|null
	 *
	 * @since 1.0.0
	 */
	public function getRecipientPhone(): ?int
	{
		$phone = $this->normaliseString($this->order->phone);

		if ($phone === null && isset($this->order->customer->phones[0]->number))
		{
			$phone = (string) $this->order->customer->phones[0]->number;
		}

		return $this->normalisePhone($phone);
	}

	/**
	 * Return the sender name when configured.
	 *
	 * @return string|null
	 *
	 * @since 1.0.0
	 */
	public function getSenderName(): ?string
	{
		return null;
	}

	/**
	 * Return the sender phone when configured.
	 *
	 * @return int|null
	 *
	 * @since 1.0.0
	 */
	public function getSenderPhone(): ?int
	{
		return null;
	}

	/**
	 * Return the RetailCRM customer comment.
	 *
	 * @return string|null
	 *
	 * @since 1.0.0
	 */
	public function getOrderComment(): ?string
	{
		return $this->normaliseString($this->order->customerComment);
	}

	/**
	 * Return the declared value when configured.
	 *
	 * @return int|null
	 *
	 * @since 1.0.0
	 */
	public function getInsuredValue(): ?int
	{
		return null;
	}

	/**
	 * Return the cash-on-delivery amount when configured.
	 *
	 * @return int|null
	 *
	 * @since 1.0.0
	 */
	public function getPayment(): ?int
	{
		return null;
	}

	/**
	 * Return the compulsory payment when configured.
	 *
	 * @return int|null
	 *
	 * @since 1.0.0
	 */
	public function getCompulsoryPayment(): ?int
	{
		return null;
	}

	/**
	 * Return the prepaid amount when configured.
	 *
	 * @return int|null
	 *
	 * @since 1.0.0
	 */
	public function getPrepaidAmount(): ?int
	{
		return null;
	}

	/**
	 * Return the marketplace seller identifier when configured.
	 *
	 * @return string|null
	 *
	 * @since 1.0.0
	 */
	public function getSellerId(): ?string
	{
		return null;
	}

	/**
	 * Return cash-on-delivery payment details when configured.
	 *
	 * @return array<string, mixed>|null
	 *
	 * @since 1.0.0
	 */
	public function getPaymentDetails(): ?array
	{
		return null;
	}

	/**
	 * Return the RetailCRM delivery data.
	 *
	 * @return SerializedOrderDelivery
	 *
	 * @throws RuntimeException When delivery data is unavailable.
	 *
	 * @since 1.0.0
	 */
	private function getDelivery(): SerializedOrderDelivery
	{
		if (!$this->order->delivery instanceof SerializedOrderDelivery)
		{
			throw new RuntimeException('The RetailCRM order does not contain delivery data.');
		}

		return $this->order->delivery;
	}

	/**
	 * Return the RetailCRM recipient address.
	 *
	 * @return OrderDeliveryAddress
	 *
	 * @throws RuntimeException When the delivery address is unavailable.
	 *
	 * @since 1.0.0
	 */
	private function getAddress(): OrderDeliveryAddress
	{
		$address = $this->getDelivery()->address;

		if (!$address instanceof OrderDeliveryAddress)
		{
			throw new RuntimeException('The RetailCRM order does not contain a delivery address.');
		}

		return $address;
	}

	/**
	 * Convert a phone number to the Russian Post numeric format.
	 *
	 * @param   string|null  $value  Raw phone number.
	 *
	 * @return int|null
	 *
	 * @since 1.0.0
	 */
	private function normalisePhone(?string $value): ?int
	{
		$phone = preg_replace('/\D+/', '', (string) $value) ?? '';

		return $phone === '' ? null : (int) $phone;
	}

	/**
	 * Convert an optional scalar value to a trimmed string.
	 *
	 * @param   mixed  $value  Raw scalar value.
	 *
	 * @return string|null
	 *
	 * @since 1.0.0
	 */
	private function normaliseString(mixed $value): ?string
	{
		$value = trim((string) $value);

		return $value === '' ? null : $value;
	}
}
