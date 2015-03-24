<?php
namespace DAG\Xml\Cropper;

use DOMDocument;
use DOMElement;
use DOMNodeList;
use DOMXPath;
use InvalidArgumentException;

/**
 * Class Cropper.
 */
final class Cropper
{
    const VERSION = '0.1.0-dev';

    /**
     * @param string $filePath The file path for the document
     * @param string $docXPath The xpath expression used to crop
     *
     * @return string The cropped result
     *
     * @throws InvalidArgumentException
     */
    public function crop($filePath, $docXPath)
    {
        $domDocument = $this->getDomDocument($filePath);

        $domElement = $this->getDomElementToCrop($domDocument, $docXPath);

        $this->cropElement($domElement);

        return $domDocument->saveXML();
    }

    /**
     * @param DOMElement $domElement
     */
    private function cropElement(DOMElement $domElement)
    {
        if (!$domElement->parentNode instanceof DOMElement) {
            return;
        }

        $siblings = $this->getSiblings($domElement);

        foreach ($siblings as $sibling) {
            $domElement->parentNode->removeChild($sibling);
        }

        $this->cropElement($domElement->parentNode);
    }

    /**
     * @param DOMElement $domElement
     *
     * @return DOMElement[]
     */
    private function getSiblings(DOMElement $domElement)
    {
        $parent = $domElement->parentNode;
        $children = $parent->childNodes;
        $siblings = [];

        foreach ($children as $sibling) {
            if ($sibling instanceof DOMElement) {
                if ($sibling !== $domElement) {
                    $siblings[] = $sibling;
                }
            }
        }

        return $siblings;
    }

    /**
     * @param DOMDocument $domDocument
     * @param string      $xpath
     *
     * @return DomElement
     */
    private function getDomElementToCrop(DOMDocument $domDocument, $xpath)
    {
        $domXPath = new DOMXpath($domDocument);

        $results = $domXPath->query($xpath);

        /*
         * Checks
         */
        if (!$results instanceof DOMNodeList) {
            throw new InvalidArgumentException(
                sprintf('Invalid given xpath expression : "%s"', $xpath),
                101
            );
        }

        /*
         * Throw an exception if there are no result
         */
        if ($results->length == 0) {
            throw new InvalidArgumentException(
                sprintf(
                    'No element found using the following xpath expression : "%s"',
                    $xpath
                ),
                102
            );
        }

        /*
         * Throw an exception if there are more than one results
         * It could lead to weird behaviour
         */
        if ($results->length > 1) {
            throw new InvalidArgumentException(
                sprintf(
                    'The given xpath "%s" should select only one element, %d were found',
                    $xpath,
                    $results->length
                ),
                103
            );
        }

        return $results->item(0);
    }

    /**
     * @param string $filePath
     *
     * @return DOMDocument
     */
    private function getDomDocument($filePath)
    {
        /*
         * Make sure the file exists
         */
        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new InvalidArgumentException(
                sprintf('Can not read "%s"', $filePath),
                100
            );
        }

        $domDocument = new DOMDocument();
        if (!$domDocument->load($filePath)) {
            throw new InvalidArgumentException('Invalid file given', 104);
        }

        return $domDocument;
    }
}
