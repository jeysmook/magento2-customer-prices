<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Plugin\Model\ResourceModel\Product;

use Jeysmook\CustomerPrices\Api\CustomerProviderInterface;
use Jeysmook\CustomerPrices\Model\Command\ApplyCustomerPriceToProductCollectionSelect;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Zend_Db_Select_Exception;

/**
 * Applying the customer prices to the collection select
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class CollectionPlugin
{
    /**
     * @var CustomerProviderInterface
     */
    private $customerProvider;

    /**
     * @var ApplyCustomerPriceToProductCollectionSelect
     */
    private $applyCustomerPriceToProductCollectionSelect;

    /**
     * CollectionPlugin constructor
     *
     * @param CustomerProviderInterface $customerProvider
     * @param ApplyCustomerPriceToProductCollectionSelect $applyCustomerPriceToProductCollectionSelect
     */
    public function __construct(
        CustomerProviderInterface $customerProvider,
        ApplyCustomerPriceToProductCollectionSelect $applyCustomerPriceToProductCollectionSelect
    ) {
        $this->customerProvider = $customerProvider;
        $this->applyCustomerPriceToProductCollectionSelect = $applyCustomerPriceToProductCollectionSelect;
    }

    /**
     * Applying the customer prices to the collection select
     *
     * @param Collection $collection
     * @return void
     * @throws Zend_Db_Select_Exception
     * @see Collection::load()
     */
    public function beforeLoad(
        Collection $collection
    ): void {
        return;
        if (!$collection->isLoaded()
            && ($customerId = $this->customerProvider->getCustomerId())
            && !$collection->getFlag('customer_prices')) {
            $this->applyCustomerPriceToProductCollectionSelect->execute(
                $collection->getSelect(),
                $customerId,
                $this->customerProvider->getWebsiteId()
            );
            $collection->setFlag('customer_prices', true);
        }
    }
}
