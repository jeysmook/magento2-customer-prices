<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Block\Adminhtml\CustomerPrice\Edit;

use Jeysmook\CustomerPrices\Api\CustomerPriceRepositoryInterface;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Parent class for button
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class GenericButton
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @var CustomerPriceRepositoryInterface
     */
    private $customerPriceRepository;

    /**
     * GenericButton constructor
     *
     * @param Context $context
     * @param CustomerPriceRepositoryInterface $customerPriceRepository
     */
    public function __construct(
        Context $context,
        CustomerPriceRepositoryInterface $customerPriceRepository
    ) {
        $this->context = $context;
        $this->customerPriceRepository = $customerPriceRepository;
    }

    /**
     * Return customer price ID
     *
     * @return int|null
     */
    public function getItemId(): ?int
    {
        try {
            return (int)$this->customerPriceRepository->get(
                (int)$this->context->getRequest()->getParam('item_id')
            )->getItemId();
        } catch (NoSuchEntityException $exception) {
            return null;
        }
    }

    /**
     * Generate url by route and parameters
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl(string $route = '', array $params = []): string
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
