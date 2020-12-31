<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Ui\Component\Form\CustomerPrice\Modifier;

use Jeysmook\CustomerPrices\Model\CustomerPrice\Locator;
use Magento\Store\Model\Store;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;

/**
 * Provide the current customer price data
 */
class General implements ModifierInterface
{
    /**
     * @var Locator
     */
    private $locator;

    /**
     * General constructor
     *
     * @param Locator $locator
     */
    public function __construct(
        Locator $locator
    ) {
        $this->locator = $locator;
    }

    /**
     * @inheritDoc
     */
    public function modifyData(array $data): array
    {
        $store = $this->locator->getStore();
        $itemId = $this->locator->getCustomerPrice()->getItemId();
        if ($itemId) {
            $data[$itemId] = $this->locator->getCustomerPrice()->getData();
        }

        $data[$itemId] = array_merge(
            $this->locator->getCustomerPrice()->getData(),
            [
                'currency' => $store->getBaseCurrency()->getCurrencySymbol(),
                'store_id' => $store->getId()
            ]
        );

        if ($this->locator->getRequestStoreId() || $itemId) {
            $data[$itemId]['website_id'] = $store->getWebsiteId();
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function modifyMeta(array $meta): array
    {
        return $meta;
    }
}
