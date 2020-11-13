<?php declare(strict_types=1);

namespace Codeal\CartExport\Model\Quote\Item\Formatter;

use Magento\Quote\Model\Quote\Item;

/**
 * Class ItemToCsvFormatter
 * @package Codeal\CartExport\Model\Quote
 */
class ItemToCsvFormatter implements ItemToStringFormatterInterface
{
    const GLUE_CHAR = ';';

    /**
     *{@inheritDoc}
     */
    public function format(Item $item): string
    {
        return $item->getId() . self::GLUE_CHAR . $item->getName() . self::GLUE_CHAR . $item->getQty() . self::GLUE_CHAR . $item->getPrice();
    }

    /**
     * {@inheritDoc}
     */
    public function getHeader(): string
    {
        return __('Id') . self::GLUE_CHAR . __('Name') . self::GLUE_CHAR . __('Qty') . self::GLUE_CHAR . ('Price');
    }
}
