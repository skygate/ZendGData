<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_GData_Spreadsheets
 * @subpackage UnitTests
 */

namespace ZendTest\GData\Spreadsheets;
use Zend\GData\Spreadsheets\Extension;

/**
 * @category   Zend
 * @package    Zend_GData_Spreadsheets
 * @subpackage UnitTests
 * @group      Zend_GData
 * @group      Zend_GData_Spreadsheets
 */
class RowCountTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->rowCount = new Extension\RowCount();
    }

    public function testToAndFromString()
    {
        $this->rowCount->setText('20');
        $this->assertTrue($this->rowCount->getText() == '20');
        $newRowCount = new Extension\RowCount();
        $doc = new \DOMDocument();
        $doc->loadXML($this->rowCount->saveXML());
        $newRowCount->transferFromDom($doc->documentElement);
        $this->assertTrue($this->rowCount->getText() == $newRowCount->getText());
    }

}
