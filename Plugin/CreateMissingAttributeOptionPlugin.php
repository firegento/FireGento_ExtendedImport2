<?php
namespace FireGento\ExtendedImport\Plugin;

use Magento\Catalog\Api\ProductAttributeOptionManagementInterface;
use Magento\CatalogImportExport\Model\Import\Product;
use Magento\CatalogImportExport\Model\Import\Product\Validator;
use Magento\Eav\Api\Data\AttributeOptionInterfaceFactory;

class CreateMissingAttributeOptionPlugin
{
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
     */
    public function __construct(
        ProductAttributeOptionManagementInterface $attributeOptionManagementInterface,
        AttributeOptionInterfaceFactory $optionDataFactory
    )
    {

        $this->optionDataFactory = $optionDataFactory;
        $this->attributeOptionManagementInterface = $attributeOptionManagementInterface;
    }


    public function beforeIsAttributeValid(Validator $subject, $attrCode, array $attrParams, array $rowData)
    {


        if ($attrParams['type'] == "multiselect" || $attrParams['type'] == "select") {

            $values = explode(Product::PSEUDO_MULTI_LINE_SEPARATOR, $rowData[$attrCode]);

            foreach ($values as $value) {
                $optionName = strtolower($value);
                if (!isset($attrParams['options'][$optionName])) {
                    $option = $this->createAttributeOption($attrCode, $value);

                    $attrParams['options'][$optionName] = $option->getValue();
                    // Delete Common Attributes Cache, for forcing reloading the Values
                    \Magento\CatalogImportExport\Model\Import\Product\Type\AbstractType::$commonAttributesCache = array();
                    
                }
            }

        }

        return array($attrCode, $attrParams, $rowData);

    }


    /**
     * Create a matching attribute option
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
            $this->attributeOptionManagementInterface->add($attributeCode, $option);
            $option = $this->findAttributeOptionByLabel($attributeCode, $label);
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
            if ($attributeOptionInterface->getLabel() == $label) {
                return $attributeOptionInterface;
            }
        }
        return null;
    }
}