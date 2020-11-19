<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Controller\Adminhtml\CustomerPrice;

use Magento\Backend\Model\View\Result\Forward;
use Magento\Framework\Controller\ResultFactory;
use Jeysmook\CustomerPrices\Controller\Adminhtml\CustomerPriceAction;

/**
 * Forward to the edit page
 */
class NewAction extends CustomerPriceAction
{
    /**
     * Forward to the edit page
     *
     * @return Forward
     */
    public function execute(): Forward
    {
        /** @var Forward $forwardResult */
        $forwardResult = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);
        $forwardResult->forward('edit');
        return $forwardResult;
    }
}
