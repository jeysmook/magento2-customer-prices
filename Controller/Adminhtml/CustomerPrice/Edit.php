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
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Jeysmook\CustomerPrices\Api\Data\CustomerPriceInterfaceFactory;
use Jeysmook\CustomerPrices\Api\CustomerPriceRepositoryInterface;
use Jeysmook\CustomerPrices\Controller\Adminhtml\CustomerPriceAction;

/**
 * Edit the customer price entity
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Edit extends CustomerPriceAction
{
    /**
     * @var CustomerPriceRepositoryInterface
     */
    private $customerPriceRepository;

    /**
     * @var CustomerPriceInterfaceFactory
     */
    private $customerPriceFactory;

    /**
     * Edit constructor
     *
     * @param Action\Context $context
     * @param CustomerPriceRepositoryInterface $customerPriceRepository
     * @param CustomerPriceInterfaceFactory $customerPriceFactory
     */
    public function __construct(
        Action\Context $context,
        CustomerPriceRepositoryInterface $customerPriceRepository,
        CustomerPriceInterfaceFactory $customerPriceFactory
    ) {
        parent::__construct($context);
        $this->customerPriceRepository = $customerPriceRepository;
        $this->customerPriceFactory = $customerPriceFactory;
    }

    /**
     * Edit the customer price entity
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $customerId = (int)$this->getRequest()->getParam('item_id');
        if ($customerId) {
            try {
                $this->customerPriceRepository->get($customerId);
            } catch (NoSuchEntityException $exception) {
                $this->messageManager->addErrorMessage(__('This customer price no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath('*/*/');
                return $resultRedirect;
            }
        }

        $pageTitle = $customerId ? __('Edit') : __('New');
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Jeysmook_CustomerPrices::customer_price');
        $resultPage->addBreadcrumb(__('Catalog'), __('Catalog'));
        $resultPage->addBreadcrumb(__('Inventory'), __('Inventory'));
        $resultPage->addBreadcrumb($pageTitle, $pageTitle);
        $resultPage->getConfig()->getTitle()->prepend(__('Customer Prices'));
        $resultPage->getConfig()->getTitle()->prepend($pageTitle);
        return $resultPage;
    }
}
