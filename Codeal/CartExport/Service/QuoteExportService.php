<?php declare(strict_types=1);

namespace Codeal\CartExport\Service;

use Codeal\CartExport\Api\QuoteExportManagementInterface;
use Codeal\CartExport\Model\Quote\Item\Formatter\ItemToStringFormatterInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class QuoteExportService
 * @package Codeal\CartExport\Service
 */
class QuoteExportService implements QuoteExportManagementInterface
{
    /**
     * @var Session
     */
    private $checkoutSession;
    /**
     * @var ItemToStringFormatterInterface
     */
    private $itemFormatter;

    public function __construct(
        Session $checkoutSession,
        ItemToStringFormatterInterface $itemFormatter
    )
    {

        $this->checkoutSession = $checkoutSession;
        $this->itemFormatter = $itemFormatter;
    }

    /**
     * @return string
     * @throws LocalizedException
     */
    public function getQuoteItems(): string
    {
        $items = $this->getQuotes()->getAllVisibleItems();
        $content = '';
        if (empty($items)) {
            throw new LocalizedException(__('Cart is empty.'));
        }
        foreach ($items as $item) {
            $content .= $this->itemFormatter->format($item) . "\n";
        }

        return $this->itemFormatter->getHeader() . "\n" . $content;
    }

    /**
     * @return \Magento\Quote\Api\Data\CartInterface|\Magento\Quote\Model\Quote
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getQuotes()
    {
        return $this->checkoutSession->getQuote();
    }
}
