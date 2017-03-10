<?php
/**
 *
 */
namespace FireGento\ExtendedImport\Model\Magento\CatalogImportExport\Import\Product;


class CategoryProcessor extends \Magento\CatalogImportExport\Model\Import\Product\CategoryProcessor
{


    /**
     * {@inheritdoc}
     */
    protected function upsertCategory($categoryPath)
    {
        // If Category Path is numeric and category exists:
        if (is_numeric($categoryPath) && isset($this->categoriesCache[$categoryPath])) {
            $categoryId = $categoryPath;
        } else {
            $categoryId = parent::upsertCategory($categoryPath);
        }
        return $categoryId;
    }


}
