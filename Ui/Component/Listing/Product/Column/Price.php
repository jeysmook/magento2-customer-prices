<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Ui\Component\Listing\Product\Column;

use Magento\Catalog\Ui\Component\Listing\Columns\Price as PriceBase;
use Magento\Store\Model\Store;

/**
 * The price column for the products
 */
class Price extends PriceBase
{
    /**
     * @inheritDoc
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            $storeId = $this->context->getFilterParam('store_id');
            $storeId = $storeId ?: $this->context->getRequestParam('store_id');
            $storeId = $storeId ?: Store::DEFAULT_STORE_ID;
            $store = $this->storeManager->getStore((int)$storeId);
            $currency = $this->localeCurrency->getCurrency($store->getBaseCurrencyCode());

            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item[$fieldName])) {
                    $item[$fieldName] = $currency->toCurrency(sprintf("%f", $item[$fieldName]));
                }
            }
        }

        return $dataSource;
    }
}
