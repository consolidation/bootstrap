<?php
namespace Consolidation\TestUtils;

use Consolidation\Bootstrap\BootInterface;
use Consolidation\Bootstrap\BootstrapSelectionInterface;

class TestBoot implements BootInterface, BootstrapSelectionInterface
{
    protected $path;
    protected $siteSelector;
    protected $version;

    public function __construct($path, $version, $services)
    {
        $this->path = $path;
        $this->version = $version;
        $this->services = $services;
    }

    /**
     * @inheritdoc
     */
    function isValid($path)
    {
        return $this->path == $path;
    }

    /**
     * @inheritdoc
     */
    function getBootstrap($path, $siteSelector = null)
    {
        if (!$this->isValid($path)) {
            throw new \RuntimeException('Invalid path passed to getBootstrap().');
        }
        $this->siteSelection = $siteSelector;
        // Normally we would instantiate a new BootInterface and pass
        // the $path to its constructor.
        return $this;
    }

    function getPath()
    {
        return $this->path;
    }

    function getSiteSelector()
    {
        return $this->siteSelection;
    }

    /**
     * @inheritdoc
     */
    function getFrameworkVersion()
    {
        return $this->version;
    }

    /**
     * @inheritdoc
     */
    function getServiceFeatures()
    {
        return $this->services();
    }

    /**
     * @inheritdoc
     */
    public function boot($serviceFeatures, $siteSelector = null)
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function terminate()
    {
    }
}
