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
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Jeysmook\CustomerPrices\Api\Data\CustomerPriceInterface;
use Jeysmook\CustomerPrices\Api\Data\CustomerPriceInterfaceFactory;
use Jeysmook\CustomerPrices\Api\CustomerPriceRepositoryInterface;
use Jeysmook\CustomerPrices\Controller\Adminhtml\CustomerPriceAction;
use Jeysmook\CustomerPrices\Model\CustomerPrice;

/**
 * Save the request entity
 */
class Save extends CustomerPriceAction implements HttpPostActionInterface
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var CustomerPriceInterfaceFactory
     */
    private $customerPriceFactory;

    /**
     * @var CustomerPriceRepositoryInterface
     */
    private $customerPriceRepository;

    /**
     * Save constructor
     *
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param CustomerPriceInterfaceFactory $customerPriceFactory
     * @param CustomerPriceRepositoryInterface $customerPriceRepository
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        CustomerPriceInterfaceFactory $customerPriceFactory,
        CustomerPriceRepositoryInterface $customerPriceRepository
    ) {
        parent::__construct($context);

        $this->dataPersistor = $dataPersistor;
        $this->customerPriceFactory = $customerPriceFactory;
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
            $model = $this->customerPriceFactory->create();
            $itemId = (int)$this->getRequest()->getParam('item_id');
            if ($itemId) {
                try {
                    $model = $this->customerPriceRepository->get($itemId);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This customer price no longer exists.'));
                    $resultRedirect->setPath('*/*/');
                    return $resultRedirect;
                }
            }

            $model->setData($data);

            try {
                $this->customerPriceRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the customer price.'));
                $this->dataPersistor->clear('jeysmook_customer_price');
                return $this->processRequestReturn($model, $data, $resultRedirect);
            } catch (LocalizedException $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
            } catch (Exception $exception) {
                $this->messageManager->addExceptionMessage(
                    $exception,
                    __('Something went wrong while saving the customer price.')
                );
            }

            $this->dataPersistor->set('jeysmook_customer_price', $data);
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
