<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Jeysmook\CustomerPrices\Model\Config\Source\RequestProcessor;

/**
 * Getting config values from the store configuration area
 */
class ConfigProvider
{
    private const XML_PATH_IS_ENABLED = 'jeysmook_customer_price/general/enabled';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * ConfigProvider constructor
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Is enabled the customer price functionality?
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_IS_ENABLED);
    }
}
