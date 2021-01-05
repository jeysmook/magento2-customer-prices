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
use Jeysmook\CustomerPrices\Model\Command\CustomerPrice\DeleteById;
use Jeysmook\CustomerPrices\Model\Command\CustomerPrice\GetById;
use Jeysmook\CustomerPrices\Model\CustomerPrice;
use Magento\Framework\Exception\CouldNotDeleteException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @see DeleteById
 */
class DeleteByIdTest extends TestCase
{
    /**
     * @var CustomerPrice|MockObject
     */
    private $entity;

    /**
     * @var GetById|MockObject
     */
    private $getById;

    /**
     * @var DeleteById
     */
    private $deleteById;

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
        $this->getById = $this->getMockBuilder(GetById::class)
            ->disableOriginalConstructor()
            ->getMock();
        $delete = $this->getMockBuilder(Delete::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->deleteById = new DeleteById($this->getById, $delete);
    }

    /**
     * @throws CouldNotDeleteException
     *
     * @see DeleteById::execute()
     */
    public function testExecute(): void
    {
        $this->getById->expects($this->once())
            ->method('execute')
            ->with($this->entity->getItemId())
            ->willReturn($this->entity);
        $this->deleteById->execute($this->entity->getItemId());
    }

    /**
     * @throws CouldNotDeleteException
     *
     * @see DeleteById::execute()
     */
    public function testExecuteWithException(): void
    {
        $this->getById->expects($this->once())
            ->method('execute')
            ->with($this->entity->getItemId())
            ->willThrowException(new Exception('error'));
        $this->expectException(CouldNotDeleteException::class);
        $this->deleteById->execute($this->entity->getItemId());
    }
}
