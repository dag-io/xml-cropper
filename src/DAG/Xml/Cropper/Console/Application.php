<?php
namespace DAG\Xml\Cropper\Console;

use DAG\Xml\Cropper\Console\Command\CropCommand;
use DAG\Xml\Cropper\Cropper;
use Symfony\Component\Console\Application as BaseApplication;

/**
 * Class Application.
 */
final class Application extends BaseApplication
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        error_reporting(-1);
        parent::__construct('XML Cropper', Cropper::VERSION);
        $this->add(new CropCommand());
    }
}
