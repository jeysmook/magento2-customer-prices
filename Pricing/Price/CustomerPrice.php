<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Pricing\Price;

use Jeysmook\CustomerPrices\Api\CustomerProviderInterface;
use Jeysmook\CustomerPrices\Model\Command\CustomerPriceResolver;
use Magento\Catalog\Model\Product;
use Magento\Framework\Pricing\Adjustment\CalculatorInterface;
use Magento\Framework\Pricing\Price\AbstractPrice;
use Magento\Framework\Pricing\Price\BasePriceProviderInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Pricing\SaleableInterface;

/**
 * The customer price model
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class CustomerPrice extends AbstractPrice implements BasePriceProviderInterface
{
    /**
     * Price type identifier string
     */
    public const PRICE_CODE = 'jeysmook_customer_price';

    /**
     * @var CustomerProviderInterface
     */
    private CustomerProviderInterface $customerProvider;

    /**
     * @var CustomerPriceResolver
     */
    private CustomerPriceResolver $customerPriceResolver;

    /**
     * CustomerPrice constructor
     *
     * @param SaleableInterface $saleableItem
     * @param $quantity
     * @param CalculatorInterface $calculator
     * @param PriceCurrencyInterface $priceCurrency
     * @param CustomerProviderInterface $customerProvider
     * @param CustomerPriceResolver $customerPriceResolver
     */
    public function __construct(
        SaleableInterface $saleableItem,
        $quantity,
        CalculatorInterface $calculator,
        PriceCurrencyInterface $priceCurrency,
        CustomerProviderInterface $customerProvider,
        CustomerPriceResolver $customerPriceResolver
    ) {
        parent::__construct($saleableItem, $quantity, $calculator, $priceCurrency);

        $this->customerProvider = $customerProvider;
        $this->customerPriceResolver = $customerPriceResolver;
    }

    /**
     * Returns the customer price
     *
     * @return float|boolean
     */
    public function getValue()
    {
        if (null !== $this->value) {
            return $this->value;
        }

        /** @var Product $product */
        $product = $this->getProduct();
        if ($product->hasData(self::PRICE_CODE)) {
            return $this->value = (float)$product->getData(self::PRICE_CODE);
        }

        $customerId = $this->customerProvider->getCustomerId();
        if (!$customerId) {
            return $this->value = false;
        }

        $price = $this->customerPriceResolver->resolve(
            $customerId,
            (int)$product->getId(),
            (float)$this->getQuantity()
        );

        if ($price) {
            $this->value = $this->priceCurrency->convertAndRound($price);
            $product->setData(Product::PRICE, $price);
        }

        return $this->value = $this->value ? (float)$this->value : false;
    }
}
