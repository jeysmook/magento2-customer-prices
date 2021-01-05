<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Test\Unit\Model\Command;

use Exception;
use Jeysmook\CustomerPrices\Model\Command\CustomerPrice\Delete;
use Jeysmook\CustomerPrices\Model\CustomerPrice;
use Jeysmook\CustomerPrices\Model\ResourceModel\CustomerPrice as CustomerPriceResource;
use Magento\Framework\Exception\CouldNotDeleteException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @see Delete
 */
class DeleteTest extends TestCase
{
    /**
     * @var CustomerPrice|MockObject
     */
    private $entity;

    /**
     * @var CustomerPriceResource|MockObject
     */
    private $resource;

    /**
     * @var Delete
     */
    private $delete;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->entity = $this->getMockBuilder(CustomerPrice::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->entity->expects($this->any())
            ->method('getItemId')
            ->willReturn(1);
        $this->resource = $this->getMockBuilder(CustomerPriceResource::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->delete = new Delete($this->resource);
    }

    /**
     * @throws CouldNotDeleteException
     *
     * @see Delete::execute()
     */
    public function testExecute(): void
    {
        $this->resource->expects($this->once())
            ->method('delete')
            ->with($this->entity)
            ->willReturnSelf();
        $this->delete->execute($this->entity);
    }

    /**
     * @throws CouldNotDeleteException
     *
     * @see Delete::execute()
     */
    public function testExecuteWithException(): void
    {
        $this->resource->expects($this->once())
            ->method('delete')
            ->willThrowException(new Exception('error'));
        $this->expectException(CouldNotDeleteException::class);
        $this->delete->execute($this->entity);
    }
}
