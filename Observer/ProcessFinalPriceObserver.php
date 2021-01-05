<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Observer;

use Jeysmook\CustomerPrices\Api\CustomerProviderInterface;
use Jeysmook\CustomerPrices\Model\Command\CustomerPrice\Resolver;
use Magento\Catalog\Model\Product;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Processing the customer price for the product on the frontend area
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class ProcessFinalPriceObserver implements ObserverInterface
{
    /**
     * @var CustomerProviderInterface
     */
    private $customerProvider;

    /**
     * @var Resolver
     */
    private $customerPriceResolver;

    /**
     * ProcessFinalPriceObserver constructor
     *
     * @param CustomerProviderInterface $customerProvider
     * @param Resolver $customerPriceResolver
     */
    public function __construct(
        CustomerProviderInterface $customerProvider,
        Resolver $customerPriceResolver
    ) {
        $this->customerProvider = $customerProvider;
        $this->customerPriceResolver = $customerPriceResolver;
    }

    /**
     * Apply customer price to product on frontend
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        $customerId = $this->customerProvider->getCustomerId();
        if ($customerId) {
            /** @var Product $product */
            $product = $observer->getEvent()->getProduct();
            $price = $this->customerPriceResolver->resolve(
                $customerId,
                $this->customerProvider->getWebsiteId(),
                (int)$product->getId(),
                (float)$observer->getEvent()->getQty()
            );

            if ($price) {
                $product->setFinalPrice($price);
            }
        }
    }
}
