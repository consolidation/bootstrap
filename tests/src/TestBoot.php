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
    public function isValid($path)
    {
        return $this->path == $path;
    }

    /**
     * @inheritdoc
     */
    public function getBootstrap($path, $siteSelector = null)
    {
        if (!$this->isValid($path)) {
            throw new \RuntimeException('Invalid path passed to getBootstrap().');
        }
        $this->siteSelection = $siteSelector;
        // Normally we would instantiate a new BootInterface and pass
        // the $path to its constructor.
        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getSiteSelector()
    {
        return $this->siteSelection;
    }

    /**
     * @inheritdoc
     */
    public function getFrameworkVersion()
    {
        return $this->version;
    }

    /**
     * @inheritdoc
     */
    public function getServiceFeatures()
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
