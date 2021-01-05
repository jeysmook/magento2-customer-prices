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
use Jeysmook\CustomerPrices\Model\Command\CustomerPrice\Save;
use Jeysmook\CustomerPrices\Model\CustomerPrice;
use Jeysmook\CustomerPrices\Model\ResourceModel\CustomerPrice as CustomerPriceResource;
use Magento\Framework\Exception\CouldNotSaveException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @see Save
 */
class SaveTest extends TestCase
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
     * @var Save
     */
    private $save;

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
        $this->save = new Save($this->resource);
    }

    /**
     * @throws CouldNotSaveException
     *
     * @see Save::execute()
     */
    public function testExecute(): void
    {
        $this->resource->expects($this->once())
            ->method('save')
            ->with($this->entity)
            ->willReturnSelf();
        $this->assertEquals($this->entity->getItemId(), $this->save->execute($this->entity));
    }

    /**
     * @throws CouldNotSaveException
     *
     * @see Save::execute()
     */
    public function testExecuteWithException(): void
    {
        $this->resource->expects($this->once())
            ->method('save')
            ->willThrowException(new Exception('error'));
        $this->expectException(CouldNotSaveException::class);
        $this->save->execute($this->entity);
    }
}
