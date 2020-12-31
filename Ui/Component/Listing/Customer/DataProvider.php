<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Ui\Component\Listing\Customer;

use Magento\Customer\Ui\Component\DataProvider as CustomerDataProvider;

/**
 * Provides information about the customers
 */
class DataProvider extends CustomerDataProvider
{
    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        $websiteId = $this->request->getParam('website_id');
        if ($websiteId) {
            $this->addFilter(
                $this->filterBuilder->setField('website_id')
                    ->setValue($websiteId)
                    ->create()
            );
        }

        return parent::getData();
    }
}
