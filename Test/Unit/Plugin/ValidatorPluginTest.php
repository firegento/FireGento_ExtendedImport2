<?php
namespace FireGento\ExtendedImport\Test\Unit\Plugin;

use FireGento\ExtendedImport\Plugin\ValidatorPlugin;
use Magento\CatalogImportExport\Model\Import\Product\RowValidatorInterface;
use Magento\Framework\Validator\ValidatorInterface;

class ValidatorPluginTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ValidatorPlugin
     */
    private $pluginUnderTest;
    /**
     * @var ValidatorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $validatorStub;
    protected function setUp()
    {
        $this->pluginUnderTest = new ValidatorPlugin();
        $this->validatorStub = $this->getMockForAbstractClass(ValidatorInterface::class);
    }
    private function stubGetMessages($messages)
    {
        $this->validatorStub->expects($this->any())->method('getMessages')->willReturn($messages);
    }
    /**
     * @test
     * @dataProvider dataInvalidInput
     */
    public function shouldPassThroughValidRow($inputAttrCode, $inputAttrParams, $inputRowData)
    {
        $noMessages = [];
        $this->stubGetMessages($noMessages);
        $stubIsAttributeValid = function() {
            return true;
        };
        $this->assertTrue($this->pluginUnderTest->aroundIsAttributeValid($this->validatorStub, $stubIsAttributeValid,
            $inputAttrCode, $inputAttrParams, $inputRowData),
            'Result "true" should be passed through');
        $this->assertEquals($noMessages, $this->pluginUnderTest->afterGetMessages($this->validatorStub, $noMessages),
            'Messsages should still be empty');
    }
    /**
     * @test
     * @dataProvider dataInvalidInput
     */
    public function shouldAddMessages($inputAttrCode, $inputAttrParams, $inputRowData, $inputMessages, $expectedMessages)
    {
        $this->stubGetMessages($inputMessages);
        $stubIsAttributeValid = function() {
            return false;
        };

        $this->assertFalse($this->pluginUnderTest->aroundIsAttributeValid($this->validatorStub, $stubIsAttributeValid,
            $inputAttrCode, $inputAttrParams, $inputRowData), 'Result "false" should be passed');

        $this->assertEquals($expectedMessages,
            $this->pluginUnderTest->afterGetMessages($this->validatorStub, $inputMessages),
            'Messages modified by getMessages interceptor'
        );
    }

    /**
     * @test
     * @dataProvider dataInvalidInput
     */
    public function shouldClearMessagesForEachRow($inputAttrCode, $inputAttrParams, $inputRowData, $inputMessages, $expectedMessages)
    {
        $noMessages = [];
        $this->stubGetMessages($inputMessages);
        $stubIsAttributeValidFalse = function() {
            return false;
        };
        $stubIsAttributeValidTrue = function() {
            return true;
        };

        $this->assertSame([$inputRowData], $this->pluginUnderTest->beforeIsValid($this->validatorStub, $inputRowData),
            'No change of input in before interceptor');
        $this->assertFalse($this->pluginUnderTest->aroundIsAttributeValid($this->validatorStub, $stubIsAttributeValidFalse,
            $inputAttrCode, $inputAttrParams, $inputRowData), 'Result "false" should be passed');
        $this->assertEquals($expectedMessages,
            $this->pluginUnderTest->afterGetMessages($this->validatorStub, $inputMessages),
            'Messages modified by getMessages interceptor'
        );

        $this->assertSame([$inputRowData], $this->pluginUnderTest->beforeIsValid($this->validatorStub, $inputRowData),
            'No change of input in before interceptor');
        $differentRowData = array_merge($inputRowData, ['sku' => $inputRowData['sku'] . '2']);
        $this->assertTrue($this->pluginUnderTest->aroundIsAttributeValid($this->validatorStub, $stubIsAttributeValidTrue,
            $inputAttrCode, $inputAttrParams, $differentRowData), 'Result "true" should be passed');
        $this->assertEquals($noMessages,
            $this->pluginUnderTest->afterGetMessages($this->validatorStub, $noMessages),
            'Messages empty for different sku'
        );
    }

    /**
     * Data provider
     *
     * @return array
     */
    public static function dataInvalidInput()
    {
        return [
            'invalid_datetime' => [
                'attrCode' => 'custom_design_to',
                'attrParams' => ['code' => 'custom_design_to', 'type' => 'datetime', 'default_value' => null, 'is_required' => '0'],
                'rowData' => ['sku' => 'Test', 'custom_design_to' => 'I am not a valid date'],
                'inputMessages' => [RowValidatorInterface::ERROR_INVALID_ATTRIBUTE_TYPE],
                'expectedMessages' => [
                    RowValidatorInterface::ERROR_INVALID_ATTRIBUTE_TYPE,
                    '[SKU Test] Datetime value for attribute "custom_design_to" expected. Your input: "I am not a valid date"']
            ],
            'invalid_decimal' => [
                'attrCode' => 'msrp',
                'attrParams' => ['code' => 'msrp', 'type' => 'decimal', 'default_value' => null, 'is_required' => '0'],
                'rowData' => ['sku' => 'Test', 'msrp' => 'seven'],
                'inputMessages' => [RowValidatorInterface::ERROR_INVALID_ATTRIBUTE_TYPE],
                'expectedMessages' => [
                    RowValidatorInterface::ERROR_INVALID_ATTRIBUTE_TYPE,
                    '[SKU Test] Decimal value for attribute "msrp" expected. Your input: "seven"']
            ],
            'unknown error' =>  [
                'attrCode' => 'msrp',
                'attrParams' => ['code' => 'msrp', 'type' => 'decimal', 'default_value' => null, 'is_required' => '0'],
                'rowData' => ['sku' => 'Test', 'msrp' => '1.0'],
                'inputMessages' => ['iBetYouDontKnowWhatThisMeans'],
                'expectedMessages' => ['iBetYouDontKnowWhatThisMeans'],
            ]
        ];
    }
}