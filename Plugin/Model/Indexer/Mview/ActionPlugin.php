<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Plugin\Model\Indexer\Mview;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\PageCache\Model\Cache\Type;

/**
 * Flushing cache after re-index completed
 */
class ActionPlugin
{
    /**
     * @var TypeListInterface
     */
    private $cacheTypeList;

    /**
     * CustomerPricePlugin constructor
     *
     * @param TypeListInterface $cacheTypeList
     */
    public function __construct(
        TypeListInterface $cacheTypeList
    ) {
        $this->cacheTypeList = $cacheTypeList;
    }

    /**
     * Flushing cache after re-index completed
     *
     * @return void
     */
    public function afterExecute(): void
    {
        $this->cacheTypeList->cleanType(Type::TYPE_IDENTIFIER);
    }
}
