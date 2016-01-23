<?php
namespace FireGento\ExtendedImport\Plugin;

use Magento\CatalogImportExport\Model\Import\Product\RowValidatorInterface;
use Magento\CatalogImportExport\Model\Import\Product\Validator;
use Magento\Framework\Validator\ValidatorInterface;

//TODO make additional error message optional (configurable)
class ValidatorPlugin
{
    /**
     * Contains additional messages (reset for each "isValid()" call, when validator messages are cleared)
     *
     * @var string[]
     */
    private $additionalMessages = [];

    public function beforeIsValid(ValidatorInterface $subject, $value)
    {
        $this->clearAdditionalMessages();
        return [$value];
    }
    public function aroundIsAttributeValid(ValidatorInterface $subject, \Closure $proceed, $attrCode, array $attrParams, array $rowData)
    {
        $result = $proceed($attrCode, $attrParams, $rowData);
        if ($result === false) {
            $messages = (array)$subject->getMessages();
            switch (end($messages)) {
                case RowValidatorInterface::ERROR_INVALID_ATTRIBUTE_TYPE:
                    $this->addAdditionalMessage(sprintf('[SKU %s] %s value for attribute "%s" expected. Your input: "%s"',
                        $rowData['sku'], ucfirst($attrParams['type']), $attrCode, $rowData[$attrCode]));
                    break;
            }
        }
        return $result;
    }

    public function afterGetMessages(ValidatorInterface $subject, $result)
    {
        return array_merge_recursive($result, $this->getAdditionalMessages());
    }

    private function clearAdditionalMessages()
    {
        $this->additionalMessages = [];
    }
    /**
     * @param string $message
     * @return void
     */
    private function addAdditionalMessage($message)
    {
        $this->additionalMessages[] = $message;
    }
    /**
     * @return string[]
     */
    private function getAdditionalMessages()
    {
        return $this->additionalMessages;
    }
}