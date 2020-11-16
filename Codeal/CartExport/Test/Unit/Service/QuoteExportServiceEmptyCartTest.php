<?php declare(strict_types=1);

namespace Codeal\CartExport\Test\Unit\Service;

use Codeal\CartExport\Api\QuoteExportManagementInterface;
use Codeal\CartExport\Model\Quote\Item\Formatter\ItemToStringFormatterInterface;
use Codeal\CartExport\Service\QuoteExportService;
use Magento\Checkout\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\Quote\Item;
use PHPUnit\Framework\MockObject\MockObject;

class QuoteExportServiceEmptyCartTest extends \PHPUnit\Framework\TestCase
{
    /** @var QuoteExportManagementInterface|MockObject $quoteExportManagementStub */
    private $quoteExportManagementStub;

    /** @var Quote|MockObject  */
    private $quoteStub;

    /** @var Session|MockObject  */
    private $checkoutSessionStub;

    /** @var ItemToStringFormatterInterface|MockObject  */
    private $itemToStringFormatterStub;

    /** @var Item|MockObject  */
    private $itemStub;

    public function setUp(): void
    {
        $this->quoteStub = $this->getMockBuilder(CartInterface::class)->setMethods(['getAllVisibleItems'])
            ->getMockForAbstractClass();

        $this->checkoutSessionStub = $this->getMockBuilder(Session::class)
            ->setMethods(['getQuote'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass()
        ;

        $this->itemToStringFormatterStub = $this->getMockBuilder(ItemToStringFormatterInterface::class)
            ->getMockForAbstractClass();

        $this->checkoutSessionStub->method('getQuote')->willReturn($this->quoteStub);

        $this->quoteExportManagementStub = $this->getMockBuilder(QuoteExportManagementInterface::class)
            ->getMockForAbstractClass();

        $this->itemStub = $this->getMockBuilder(Item::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId', 'getQty', 'getName', 'getPrice'])
            ->getMockForAbstractClass();

        $this->itemStub->method('getId')->willReturn(1);
        $this->itemStub->method('getQty')->willReturn(12);
        $this->itemStub->method('getName')->willReturn('Test Item');
        $this->itemStub->method('getPrice')->willReturn(19.99);
    }

    public function testExceptionExpectedWhenCartIsEmpty(): void
    {
        //Given
        $this->quoteStub->method('getAllVisibleItems')
            ->willReturn([]);

        //Expect
        $this->expectException(LocalizedException::class);

        //When
        $service = new QuoteExportService($this->checkoutSessionStub, $this->itemToStringFormatterStub);
        $service->getQuoteItems();
    }

    public function testReturningStringIfCartIsNotEmpty(): void
    {
        $this->quoteStub->method('getAllVisibleItems')
            ->willReturn([$this->itemStub]);

        $service = new QuoteExportService($this->checkoutSessionStub, $this->itemToStringFormatterStub);
        $this->assertNotEmpty($service->getQuoteItems());
    }
}
