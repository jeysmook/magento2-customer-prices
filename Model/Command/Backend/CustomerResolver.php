<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Model\Command\Backend;

use Jeysmook\CustomerPrices\Api\CustomerResolverInterface;
use Magento\Backend\Model\Session\QuoteFactory as QuoteSessionFactory;

/**
 * Resolving the current customer ID for the adminhtml area
 */
class CustomerResolver implements CustomerResolverInterface
{
    /**
     * @var QuoteSessionFactory
     */
    private $quoteSessionFactory;

    /**
     * @var int|null|bool
     */
    private $customerId = false;

    /**
     * CustomerResolver constructor
     *
     * @param QuoteSessionFactory $quoteSessionFactory
     */
    public function __construct(
        QuoteSessionFactory $quoteSessionFactory
    ) {
        $this->quoteSessionFactory = $quoteSessionFactory;
    }

    /**
     * @inheritDoc
     */
    public function resolve(): ?int
    {
        if (false === $this->customerId) {
            $this->customerId = $this->quoteSessionFactory->create()->getCustomerId();
            $this->customerId = (string)$this->customerId !== '' ? (int)$this->customerId : null;
        }
        return $this->customerId;
    }
}
