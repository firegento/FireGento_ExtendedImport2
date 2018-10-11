<?php
namespace FireGento\ExtendedImport\Model\Magento\CatalogImportExport\ResourceModel\Import;

use FireGento\ExtendedImport\Model\Magento\CatalogImportExport\Import\Product\Product;

/**
 * Class Data
 * @package FireGento\ExtendedImport\Model\Magento\CatalogImportExport\ResourceModel\Import
 */
class Data extends \Magento\ImportExport\Model\ResourceModel\Import\Data
{
    /**
     * @param string $entity
     * @param string $behavior
     * @param array $data
     * @return int
     */
    public function saveBunch($entity, $behavior, array $data)
    {
        $data = $this->overrideTheUrlKeys($data);

        return parent::saveBunch($entity, $behavior, $data);
    }

    /**
     * @param $rows
     * @return mixed
     */
    private function overrideTheUrlKeys($rows)
    {
        $urlKeys = array_column($rows, Product::URL_KEY);
        $occurrences = array_count_values($urlKeys);
        $occurrencesIndex = [];

        foreach ($urlKeys as $rowIndex => $urlKey) {
            if ($occurrences[$urlKey] === 1) {
                continue;
            }

            $key = isset($occurrencesIndex[$urlKey]) ? (int) $occurrencesIndex[$urlKey] + 1 : 1;
            $occurrencesIndex[$urlKey] = $key;

            $rows[$rowIndex][Product::URL_KEY] = $urlKey.'-'.$key;
        }

        return $rows;
    }
}
