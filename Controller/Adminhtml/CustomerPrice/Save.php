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
use Jeysmook\CustomerPrices\Api\CustomerPriceRepositoryInterface;
use Jeysmook\CustomerPrices\Api\Data\CustomerPriceInterface;
use Jeysmook\CustomerPrices\Controller\Adminhtml\CustomerPriceAction;
use Jeysmook\CustomerPrices\Model\CustomerPrice;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Save the request entity
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Save extends CustomerPriceAction implements HttpPostActionInterface
{
    /**
     * @var CustomerPrice\Locator
     */
    private CustomerPrice\Locator $locator;

    /**
     * @var CustomerPriceRepositoryInterface
     */
    private CustomerPriceRepositoryInterface $customerPriceRepository;

    /**
     * Save constructor
     *
     * @param Context $context
     * @param CustomerPrice\Locator $locator
     * @param CustomerPriceRepositoryInterface $customerPriceRepository
     */
    public function __construct(
        Context $context,
        CustomerPrice\Locator $locator,
        CustomerPriceRepositoryInterface $customerPriceRepository
    ) {
        parent::__construct($context);

        $this->locator = $locator;
        $this->customerPriceRepository = $customerPriceRepository;
    }

    /**
     * Save the request entity
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            /** @var CustomerPrice $model */
            $model = $this->locator->getCustomerPrice();
            $itemId = (int)$this->getRequest()->getParam('item_id');
            if ($itemId && !$model->getItemId()) {
                $this->messageManager->addErrorMessage(__('This customer price no longer exists.'));
                $resultRedirect->setPath('*/*/');
                return $resultRedirect;
            }

            $model->setData($data);

            try {
                $this->customerPriceRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the customer price.'));
                return $this->processRequestReturn($model, $data, $resultRedirect);
            } catch (LocalizedException $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
            } catch (Exception $exception) {
                $this->messageManager->addExceptionMessage(
                    $exception,
                    __('Something went wrong while saving the customer price.')
                );
            }

            $resultRedirect->setPath('*/*/edit', ['item_id' => $itemId]);
            return $resultRedirect;
        }
        $resultRedirect->setPath('*/*/');
        return $resultRedirect;
    }

    /**
     * Process and set the customer price return
     *
     * @param CustomerPriceInterface $model
     * @param array $data
     * @param Redirect $resultRedirect
     * @return ResultInterface
     */
    private function processRequestReturn(
        CustomerPriceInterface $model,
        array $data,
        Redirect $resultRedirect
    ): ResultInterface {
        $redirect = $data['back'] ?? 'close';
        if ($redirect === 'continue') {
            return $resultRedirect->setPath('*/*/edit', ['item_id' => $model->getItemId()]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
