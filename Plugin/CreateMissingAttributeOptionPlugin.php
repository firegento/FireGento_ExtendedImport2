<?php
namespace FireGento\ExtendedImport\Plugin;

use Magento\Catalog\Api\ProductAttributeOptionManagementInterface;
use Magento\CatalogImportExport\Model\Import\Product;
use Magento\CatalogImportExport\Model\Import\Product\Validator;
use Magento\Eav\Api\Data\AttributeOptionInterfaceFactory;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;

class CreateMissingAttributeOptionPlugin
{
    protected $eavConfig;
    /**
     * @var AttributeOptionInterfaceFactory
     */
    private $optionDataFactory;
    /**
     * @var ProductAttributeOptionManagementInterface
     */
    private $attributeOptionManagementInterface;

    /**
     * CreateMissingAttributeOptionPlugin constructor.
     * @param ProductAttributeOptionManagementInterface $attributeOptionManagementInterface
     * @param AttributeOptionInterfaceFactory $optionDataFactory
     * @param EavConfig $eavConfig
     */
    public function __construct(
        ProductAttributeOptionManagementInterface $attributeOptionManagementInterface,
        AttributeOptionInterfaceFactory $optionDataFactory,
        EavConfig $eavConfig
    ) {
        $this->eavConfig = $eavConfig;
        $this->optionDataFactory = $optionDataFactory;
        $this->attributeOptionManagementInterface = $attributeOptionManagementInterface;
    }

    public function beforeIsAttributeValid(Validator $subject, $attrCode, array $attrParams, array $rowData)
    {
        if ($attrParams['type'] != "multiselect" && $attrParams['type'] != "select") {
            return [$attrCode, $attrParams, $rowData];
        }

        $attribute = $this->eavConfig->getAttribute('catalog_product', $attrCode);
        // phpcs:ignore
        if (!$this->isAttributeApplicable($attribute)) {
            return [$attrCode, $attrParams, $rowData];
        }

        $values = explode(Product::PSEUDO_MULTI_LINE_SEPARATOR, $rowData[$attrCode]);
        $values = array_filter($values, function ($optionName) use ($attrParams) {
            if (!strlen($optionName)) {
                return false;
            }

            $indexValue = strtolower($optionName);
            if (isset($attrParams['options'][$indexValue])) {
                return false;
            }

            return true;
        });

        foreach ($values as $value) {
            $indexValue = strtolower($value);
            $option = $this->createAttributeOption($attrCode, $value);

            $attrParams['options'][$indexValue] = $option->getValue();
            // Delete Common Attributes Cache, for forcing reloading the Values
            \Magento\CatalogImportExport\Model\Import\Product\Type\AbstractType::$commonAttributesCache = [];
        }

        return [$attrCode, $attrParams, $rowData];
    }

    /**
     * Create a matching attribute option.
     *
     * @param string $attributeCode Attribute the option should exist in
     * @param string $label Label to add
     * @return \Magento\Eav\Api\Data\AttributeOptionInterface
     */
    public function createAttributeOption($attributeCode, $label)
    {
        $option = $this->findAttributeOptionByLabel($attributeCode, $label);

        if (!$option) {
            $option = $this->optionDataFactory->create();
            $option->setLabel($label);

            $result = $this->attributeOptionManagementInterface->add($attributeCode, $option);
            if (!$result) {
                die('Could not add ' . $label . ' to ' . $attributeCode);
            }
            // Clear attribute cache to make the new option available immediately
            $this->eavConfig->clear();
            $option = $this->findAttributeOptionByLabel($attributeCode, $label);
        }

        if (!$option) {
            die('Could not find ' . $label . ' in ' . $attributeCode);
        }

        return $option;
    }

    /**
     * @param $attributeCode
     * @param $label
     * @return \Magento\Eav\Api\Data\AttributeOptionInterface|null
     */
    public function findAttributeOptionByLabel($attributeCode, $label)
    {
        $attributeOptionList = $this->attributeOptionManagementInterface->getItems($attributeCode);
        foreach ($attributeOptionList as $attributeOptionInterface) {
            if (strcasecmp($attributeOptionInterface->getLabel(), $label) === 0) {
                return $attributeOptionInterface;
            }
        }

        return null;
    }

    /**
     * Returns true if attribute is applicable for option generation.
     *
     * @param AbstractAttribute $attribute
     * @return bool
     */
    public function isAttributeApplicable(AbstractAttribute $attribute)
    {
        $sourceModel = $attribute->getSourceModel();

        return $sourceModel === null || $sourceModel === 'Magento\Eav\Model\Entity\Attribute\Source\Table';
    }
}
