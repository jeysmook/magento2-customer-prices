<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Test\Unit\Model\Command;

use Jeysmook\CustomerPrices\Api\Data\CustomerPriceInterfaceFactory;
use Jeysmook\CustomerPrices\Model\Command\CustomerPrice\GetById;
use Jeysmook\CustomerPrices\Model\CustomerPrice;
use Jeysmook\CustomerPrices\Model\ResourceModel\CustomerPrice as CustomerPriceResource;
use Magento\Framework\Exception\NoSuchEntityException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @see GetById
 */
class GetByIdTest extends TestCase
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
     * @var GetById
     */
    private $getById;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->entity = $this->getMockBuilder(CustomerPrice::class)
            ->disableOriginalConstructor()
            ->getMock();
        $entityFactory = $this->getMockBuilder(CustomerPriceInterfaceFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $entityFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->entity);
        $this->resource = $this->getMockBuilder(CustomerPriceResource::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->getById = new GetById($entityFactory, $this->resource);
    }

    /**
     * @throws NoSuchEntityException
     *
     * @see GetById::execute()
     */
    public function testExecute(): void
    {
        $this->entity->expects($this->once())
            ->method('getItemId')
            ->willReturn(1);
        $this->entity->expects($this->once())
            ->method('getId')
            ->willReturn(1);
        $itemId = $this->entity->getItemId();
        $this->resource->expects($this->once())
            ->method('load')
            ->with($this->entity, $itemId)
            ->willReturnSelf();
        $this->assertEquals($this->entity, $this->getById->execute($itemId));
    }

    /**
     * @see GetById::execute()
     */
    public function testExecuteWithException(): void
    {
        $this->entity->expects($this->once())
            ->method('getItemId')
            ->willReturn(1);
        $this->entity->expects($this->once())
            ->method('getId')
            ->willReturn(null);
        $itemId = $this->entity->getItemId();
        $this->resource->expects($this->once())
            ->method('load')
            ->with($this->entity, $itemId)
            ->willReturnSelf();
        $this->expectException(NoSuchEntityException::class);
        $this->getById->execute($itemId);
    }
}
