<?php

namespace Codeal\CartExport\Model\Quote\Item\Formatter;

use Magento\Quote\Model\Quote\Item;

/**
 * Interface ItemToStringFormatterInterface
 * @package Codeal\CartExport\Model\Quote
 */
interface ItemToStringFormatterInterface
{
    /**
     * @param Item $item
     * @return string
     */
    public function format(Item $item): string;

    /**
     * @return string
     */
    public function getHeader(): string;
}
