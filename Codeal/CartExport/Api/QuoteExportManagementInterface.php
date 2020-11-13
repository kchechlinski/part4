<?php

namespace Codeal\CartExport\Api;

/**
 * Interface QuoteExportManagementInterface
 * @package Codeal\CartExport\Api
 */
interface QuoteExportManagementInterface
{
    /**
     * @return string
     */
    public function getQuoteItems(): string;
}
