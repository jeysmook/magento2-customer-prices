<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Controller\Adminhtml\CustomerPrice;

use Exception;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultInterface;
use Jeysmook\CustomerPrices\Api\CustomerPriceRepositoryInterface;
use Jeysmook\CustomerPrices\Controller\Adminhtml\CustomerPriceAction;

/**
 * Delete the customer price by ID
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Delete extends CustomerPriceAction
{
    /**
     * @var CustomerPriceRepositoryInterface
     */
    private $customerPriceRepository;

    /**
     * Delete constructor
     *
     * @param Action\Context $context
     * @param CustomerPriceRepositoryInterface $customerPriceRepository
     */
    public function __construct(
        Action\Context $context,
        CustomerPriceRepositoryInterface $customerPriceRepository
    ) {
        parent::__construct($context);
        $this->customerPriceRepository = $customerPriceRepository;
    }

    /**
     * Delete the customer price by ID
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $itemId = (int)$this->getRequest()->getParam('item_id');
        if ($itemId) {
            try {
                $this->customerPriceRepository->deleteById($itemId);
                $this->messageManager->addSuccessMessage(__('You deleted the customer price.'));
                $resultRedirect->setPath('*/*/');
            } catch (Exception $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
                $resultRedirect->setPath('*/*/edit', ['item_id' => $itemId]);
            }
            return $resultRedirect;
        }

        $this->messageManager->addErrorMessage(__('We can\'t find a customer price to delete.'));
        $resultRedirect->setPath('*/*/');
        return $resultRedirect;
    }
}
