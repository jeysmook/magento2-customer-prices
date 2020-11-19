<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Controller\Adminhtml\CustomerPrice;

use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\Page;
use Jeysmook\CustomerPrices\Controller\Adminhtml\CustomerPriceAction;

/**
 * Render the grid of the request items
 */
class Index extends CustomerPriceAction
{
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * Index constructor
     *
     * @param Action\Context $context
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * Render the grid of the request items
     *
     * @return Page
     */
    public function execute(): Page
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Jeysmook_CustomerPrices::customer_price');
        $resultPage->addBreadcrumb(__('Catalog'), __('Catalog'));
        $resultPage->addBreadcrumb(__('Inventory'), __('Inventory'));
        $resultPage->getConfig()->getTitle()->prepend(__('Customer Prices'));
        $this->dataPersistor->clear('jeysmook_customer_price');
        return $resultPage;
    }
}
