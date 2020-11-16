<?php declare(strict_types=1);

namespace Codeal\CartExport\Test\Unit\Model\Quote\Item\Formatter;

use Codeal\CartExport\Model\Quote\Item\Formatter\ItemToCsvFormatter;
use Magento\Quote\Model\Quote\Item;
use PHPUnit\Framework\MockObject\MockObject;

class ItemToCsvFormatterTest extends \PHPUnit\Framework\TestCase
{
    /** @var Item|MockObject  */
    private $itemStub;

    protected function setUp(): void
    {
        $this->itemStub = $this->getMockBuilder(Item::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId', 'getQty', 'getName', 'getPrice'])
            ->getMockForAbstractClass();

        $this->itemStub->method('getId')->willReturn(1);
        $this->itemStub->method('getQty')->willReturn(12);
        $this->itemStub->method('getName')->willReturn('Test Item');
        $this->itemStub->method('getPrice')->willReturn(19.99);
    }

    public function testHeaderText(): void
    {
        $formatter = new ItemToCsvFormatter();
        $header = 'Id;Name;Qty;Price';

        $this->assertEquals($header, $formatter->getHeader());
    }

    public function testLineWithItemData(): void
    {
        $formatter = new ItemToCsvFormatter();

        $this->assertEquals('1;Test Item;12;19.99', $formatter->format($this->itemStub));
    }

    public function testCountElementsInHeaderAndItemData(): void
    {
        $formatter = new ItemToCsvFormatter();

        $headerElementsCount = count(explode(ItemToCsvFormatter::GLUE_CHAR, $formatter->getHeader()));
        $itemElementsCount = count(explode(ItemToCsvFormatter::GLUE_CHAR, $formatter->format($this->itemStub)));

        $this->assertEquals($headerElementsCount, $itemElementsCount);
    }
}
