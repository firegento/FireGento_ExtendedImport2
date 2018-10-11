<?php
namespace FireGento\ExtendedImport\Model\Magento\CatalogImportExport\Import\Product;

use Magento\CatalogImportExport\Model\Import\Product\RowValidatorInterface as ValidatorInterface;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingError;
use FireGento\ExtendedImport\Model\Magento\CatalogImportExport\ResourceModel\Import\Data;

/**
 * Class Product
 * @package FireGento\ExtendedImport\Model\Magento\CatalogImportExport\Import\Product
 */
class Product extends \Magento\CatalogImportExport\Model\Import\Product
{
    /**
     * Add error with corresponding current data source row number.
     *
     * @param string $errorCode Error code or simply column name
     * @param int $errorRowNum Row number.
     * @param string $colName OPTIONAL Column name.
     * @param string $errorMessage OPTIONAL Column name.
     * @param string $errorLevel
     * @param string $errorDescription
     * @return $this
     */
    public function addRowError(
        $errorCode,
        $errorRowNum,
        $colName = null,
        $errorMessage = null,
        $errorLevel = ProcessingError::ERROR_LEVEL_CRITICAL,
        $errorDescription = null
    ) {
        $errorCode = (string)$errorCode;

        if ($errorCode !== ValidatorInterface::ERROR_DUPLICATE_URL_KEY) {
            $this->getErrorAggregator()->addError(
                $errorCode,
                $errorLevel,
                $errorRowNum,
                $colName,
                $errorMessage,
                $errorDescription
            );
        }

        return $this;
    }

    /**
     * @return \Magento\CatalogImportExport\Model\Import\Product|void
     */
    protected function _saveValidatedBunches()
    {
        $this->_dataSourceModel = \Magento\Framework\App\ObjectManager::getInstance()->get(Data::class);
        parent::_saveValidatedBunches();
    }
}
