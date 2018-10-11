<?php
namespace FireGento\ExtendedImport\Plugin;

use FireGento\ExtendedImport\Model\Magento\CatalogImportExport\Import\Product\Product;
use Magento\ImportExport\Model\Import\Config;

/**
 * Class AfterGetEntities
 * @package FireGento\ExtendedImport\Plugin
 */
class AfterGetEntities
{
    const PRODUCT_ENTITY_CATEGORY = 'catalog_product';
    const PRODUCT_ENTITY_MODEL_FIELD = 'model';
    const PRODUCT_CLASS = Product::class;

    /**
     * @param Config $subject
     * @param array $result
     * @return array
     */
    public function afterGetEntities(Config $subject, array $result)
    {
        return $this->overrideProductModel($result);
    }

    /**
     * @param array $entities
     * @return array
     */
    protected function overrideProductModel(array $entities)
    {
        if (isset($entities[self::PRODUCT_ENTITY_CATEGORY])) {
            $entities[self::PRODUCT_ENTITY_CATEGORY][self::PRODUCT_ENTITY_MODEL_FIELD] = self::PRODUCT_CLASS;
        }

        return $entities;
    }
}
