<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Controller\Adminhtml;

use Magento\Backend\App\Action;

/**
 * Class RequestItemAction
 */
abstract class CustomerPriceAction extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Jeysmook_CustomerPrices::customer_price';
}
