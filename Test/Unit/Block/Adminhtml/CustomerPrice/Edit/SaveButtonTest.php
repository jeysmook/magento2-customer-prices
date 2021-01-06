<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Test\Unit\Block\Adminhtml\CustomerPrice\Edit;

use Jeysmook\CustomerPrices\Block\Adminhtml\CustomerPrice\Edit\SaveButton;
use Magento\Ui\Component\Control\Container;

/**
 * @see SaveButton
 */
class SaveButtonTest extends GenericButtonTest
{
    /**
     * @var SaveButton
     */
    private $saveButton;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->saveButton = new SaveButton(
            $this->context,
            $this->customerPriceRepository
        );
    }

    /**
     * @see SaveButton::getButtonData()
     */
    public function testGetButtonData(): void
    {
        $this->assertEquals($this->getButtonData(), $this->saveButton->getButtonData());
    }

    /**
     * Get button data
     *
     * @return array
     */
    private function getButtonData(): array
    {
        return [
            'label' => __('Save'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [
                            [
                                'targetName' => 'jeysmook_customer_price_form.jeysmook_customer_price_form',
                                'actionName' => 'save',
                                'params' => [
                                    true,
                                    [
                                        'back' => 'continue'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'class_name' => Container::SPLIT_BUTTON,
            'options' => [
                [
                    'id_hard' => 'save_and_close',
                    'label' => __('Save & Close'),
                    'data_attribute' => [
                        'mage-init' => [
                            'buttonAdapter' => [
                                'actions' => [
                                    [
                                        'targetName' => 'jeysmook_customer_price_form.jeysmook_customer_price_form',
                                        'actionName' => 'save',
                                        'params' => [
                                            true,
                                            [
                                                'back' => 'close'
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'sort_order' => 40,
        ];
    }
}
