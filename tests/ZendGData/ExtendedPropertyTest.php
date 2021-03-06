<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   ZendGData
 */

namespace ZendGDataTest;

use ZendGData\Extension;

/**
 * @category   Zend
 * @package    ZendGData
 * @subpackage UnitTests
 * @group      ZendGData
 */
class ExtendedPropertyTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->extendedPropertyText = file_get_contents(
                'ZendGData/_files/ExtendedPropertyElementSample1.xml',
                true);
        $this->extendedProperty = new Extension\ExtendedProperty();
    }

    public function testEmptyExtendedPropertyShouldHaveNoExtensionElements()
    {
        $this->assertTrue(is_array($this->extendedProperty->extensionElements));
        $this->assertTrue(count($this->extendedProperty->extensionElements) == 0);
    }

    public function testEmptyExtendedPropertyShouldHaveNoExtensionAttributes()
    {
        $this->assertTrue(is_array($this->extendedProperty->extensionAttributes));
        $this->assertTrue(count($this->extendedProperty->extensionAttributes) == 0);
    }

    public function testSampleExtendedPropertyShouldHaveNoExtensionElements()
    {
        $this->extendedProperty->transferFromXML($this->extendedPropertyText);
        $this->assertTrue(is_array($this->extendedProperty->extensionElements));
        $this->assertTrue(count($this->extendedProperty->extensionElements) == 0);
    }

    public function testSampleExtendedPropertyShouldHaveNoExtensionAttributes()
    {
        $this->extendedProperty->transferFromXML($this->extendedPropertyText);
        $this->assertTrue(is_array($this->extendedProperty->extensionAttributes));
        $this->assertTrue(count($this->extendedProperty->extensionAttributes) == 0);
    }

    public function testNormalExtendedPropertyShouldHaveNoExtensionElements()
    {
        $this->extendedProperty->name = "http://www.example.com/schemas/2007#mycal.foo";
        $this->extendedProperty->value = "5678";

        $this->assertEquals("http://www.example.com/schemas/2007#mycal.foo", $this->extendedProperty->name);
        $this->assertEquals("5678", $this->extendedProperty->value);

        $this->assertEquals(0, count($this->extendedProperty->extensionElements));
        $newExtendedProperty = new Extension\ExtendedProperty();
        $newExtendedProperty->transferFromXML($this->extendedProperty->saveXML());
        $this->assertEquals(0, count($newExtendedProperty->extensionElements));
        $newExtendedProperty->extensionElements = array(
                new \ZendGData\App\Extension\Element('foo', 'atom', null, 'bar'));
        $this->assertEquals(1, count($newExtendedProperty->extensionElements));
        $this->assertEquals("http://www.example.com/schemas/2007#mycal.foo", $newExtendedProperty->name);
        $this->assertEquals("5678", $newExtendedProperty->value);

        /* try constructing using magic factory */
        $gdata = new \ZendGData\GData();
        $newExtendedProperty2 = $gdata->newExtendedProperty();
        $newExtendedProperty2->transferFromXML($newExtendedProperty->saveXML());
        $this->assertEquals(1, count($newExtendedProperty2->extensionElements));
        $this->assertEquals("http://www.example.com/schemas/2007#mycal.foo", $newExtendedProperty2->name);
        $this->assertEquals("5678", $newExtendedProperty2->value);
    }

    public function testEmptyExtendedPropertyToAndFromStringShouldMatch()
    {
        $extendedPropertyXml = $this->extendedProperty->saveXML();
        $newExtendedProperty = new Extension\ExtendedProperty();
        $newExtendedProperty->transferFromXML($extendedPropertyXml);
        $newExtendedPropertyXml = $newExtendedProperty->saveXML();
        $this->assertTrue($extendedPropertyXml == $newExtendedPropertyXml);
    }

    public function testExtendedPropertyWithValueToAndFromStringShouldMatch()
    {
        $this->extendedProperty->name = "http://www.example.com/schemas/2007#mycal.foo";
        $this->extendedProperty->value = "5678";
        $extendedPropertyXml = $this->extendedProperty->saveXML();
        $newExtendedProperty = new Extension\ExtendedProperty();
        $newExtendedProperty->transferFromXML($extendedPropertyXml);
        $newExtendedPropertyXml = $newExtendedProperty->saveXML();
        $this->assertTrue($extendedPropertyXml == $newExtendedPropertyXml);
        $this->assertEquals("http://www.example.com/schemas/2007#mycal.foo", $this->extendedProperty->name);
        $this->assertEquals("5678", $this->extendedProperty->value);
    }

    public function testExtensionAttributes()
    {
        $extensionAttributes = $this->extendedProperty->extensionAttributes;
        $extensionAttributes['foo1'] = array('name'=>'foo1', 'value'=>'bar');
        $extensionAttributes['foo2'] = array('name'=>'foo2', 'value'=>'rab');
        $this->extendedProperty->extensionAttributes = $extensionAttributes;
        $this->assertEquals('bar', $this->extendedProperty->extensionAttributes['foo1']['value']);
        $this->assertEquals('rab', $this->extendedProperty->extensionAttributes['foo2']['value']);
        $extendedPropertyXml = $this->extendedProperty->saveXML();
        $newExtendedProperty = new Extension\ExtendedProperty();
        $newExtendedProperty->transferFromXML($extendedPropertyXml);
        $this->assertEquals('bar', $newExtendedProperty->extensionAttributes['foo1']['value']);
        $this->assertEquals('rab', $newExtendedProperty->extensionAttributes['foo2']['value']);
    }

    public function testConvertFullExtendedPropertyToAndFromString()
    {
        $this->extendedProperty->transferFromXML($this->extendedPropertyText);
        $this->assertEquals("http://www.example.com/schemas/2007#mycal.id", $this->extendedProperty->name);
        $this->assertEquals("1234", $this->extendedProperty->value);
    }

}
