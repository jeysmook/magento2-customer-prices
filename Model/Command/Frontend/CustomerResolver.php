<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Model\Command\Frontend;

use Jeysmook\CustomerPrices\Api\CustomerResolverInterface;
use Magento\Customer\Model\SessionFactory as CustomerSessionFactory;

/**
 * Resolving the current customer ID for the frontend area and other areas
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class CustomerResolver implements CustomerResolverInterface
{
    /**
     * @var CustomerSessionFactory
     */
    private $customerSessionFactory;

    /**
     * @var int|null|bool
     */
    private $customerId = false;

    /**
     * CustomerResolver constructor
     *
     * @param CustomerSessionFactory $customerSessionFactory
     */
    public function __construct(
        CustomerSessionFactory $customerSessionFactory
    ) {
        $this->customerSessionFactory = $customerSessionFactory;
    }

    /**
     * @inheritDoc
     */
    public function resolve(): ?int
    {
        if (false === $this->customerId) {
            $this->customerId = $this->customerSessionFactory->create()->getCustomerId();
            $this->customerId = (string)$this->customerId !== '' ? (int)$this->customerId : null;
        }

        return $this->customerId;
    }
}
