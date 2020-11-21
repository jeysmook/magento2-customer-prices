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
    public function modifyData(array $data)
    {
        $customerPrice = $this->locator->getCustomerPrice();
        $data[$customerPrice->getItemId()] = array_merge(
            $customerPrice->getData(),
            [
                'currency' => $this->locator->getStore()->getBaseCurrency()->getCurrencySymbol(),
                'website_id' => $this->locator->getStore()->getWebsiteId()
            ]
        );
        return $data;
    }

    /**
     * @inheritDoc
     */
    public function modifyMeta(array $meta)
    {
        return $meta;
    }
}
