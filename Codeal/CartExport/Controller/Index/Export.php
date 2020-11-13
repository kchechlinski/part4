<?php declare(strict_types=1);

namespace Codeal\CartExport\Controller\Index;

use Codeal\CartExport\Api\QuoteExportManagementInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Store\Model\ScopeInterface;

class Export extends \Magento\Framework\App\Action\Action implements \Magento\Framework\App\ActionInterface
{
    /**
     * @var QuoteExportManagementInterface
     */
    private $quoteExportService;
    /**
     * @var FileFactory
     */
    private $fileFactory;

    private $directory;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        QuoteExportManagementInterface $quoteExportService,
        FileFactory $fileFactory,
        Filesystem $filesystem,
        ScopeConfigInterface $scopeConfig,
        Context $context
    ) {
        parent::__construct($context);
        $this->quoteExportService = $quoteExportService;
        $this->fileFactory = $fileFactory;
        $this->directory = $filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function execute()
    {
        if ($this->scopeConfig->getValue('codeal_export/basic/is_enabled', ScopeInterface::SCOPE_STORE) == 0) {
            throw new LocalizedException(__('Feature disabled'));
        }

        $quoteItemsCsv = $this->quoteExportService->getQuoteItems();
        $filepath = 'export/quote' . uniqid() . '.csv';
        $stream = $this->directory->openFile($filepath, 'w+');
        $stream->lock();
        $stream->write($quoteItemsCsv);

        $content = [
            'type' => 'filename',
            'value' => $filepath,
            'rm' => 1
        ];
        $filename = 'items.csv';

        return $this->fileFactory->create($filename, $content, DirectoryList::VAR_DIR);
    }
}
