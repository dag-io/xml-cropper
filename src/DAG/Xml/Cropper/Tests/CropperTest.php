<?php
namespace DAG\Xml\Cropper\Tests;

use DAG\Xml\Cropper\Cropper;
use DOMDocument;
use DOMXPath;
use InvalidArgumentException;

/**
 * Class CropperTest.
 */
class CropperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that an exception is thrown is the file does not exist.
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 100
     */
    public function testAnExceptionThrownIfTheFileDoesNotExist()
    {
        $cropper = new Cropper();
        $cropper->crop('/this-file-does-not-exist', null);
    }

    /**
     * Test that an exception is thrown if the xpath expression is invalid.
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 101
     */
    public function testAnExceptionIsThrownIfTheXPathExpressionInvalid()
    {
        /*
         * Do not report warnings.
         * There will be warnings using an invalid xpath.
         */
        error_reporting(E_ERROR);

        $cropper = new Cropper();
        $cropper->crop(__DIR__.'/sample.xml', "*-foo123 bar");
    }

    /**
     * Test that an exception is thrown if the xpath expression return nothing.
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 102
     */
    public function testAnExceptionIsThrownIfTheXPathExpressionReturnNothing()
    {
        $cropper = new Cropper();
        $cropper->crop(__DIR__.'/sample.xml', "//book[@id='IdThatDoesNotExist']");
    }

    /**
     * Test that an exception is thrown if the xpath expression return more than one elements.
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 103
     */
    public function testAnExceptionIsThrownIfTheXPathExpressionReturnMany()
    {
        $cropper = new Cropper();
        $cropper->crop(__DIR__.'/sample.xml', "//book");
    }

    /**
     * Test that an exception is thrown if the file does not have valid content.
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 104
     */
    public function testAnExceptionIsThrownIfFileInvalid()
    {
        $cropper = new Cropper();
        $cropper->crop(__DIR__.'/invalid-sample.xml', "//book");
    }

    /**
     * Test that the cropper remove all siblings.
     */
    public function testCropRemoveAllSiblings()
    {
        $cropper = new Cropper();
        $string = $cropper->crop(__DIR__.'/sample.xml', "//book[@id='bk102']");

        $domDocument = new DOMDocument();
        $domDocument->loadXML($string);

        $domXPath = new DOMXPath($domDocument);

        $books = $domXPath->query('//book');
        $this->assertEquals(1, $books->length);

        $catalogs = $domXPath->query('//catalog');
        $this->assertEquals(1, $catalogs->length);
    }
}
