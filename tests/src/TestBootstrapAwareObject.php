<?php
namespace Consolidation\TestUtils;

use Consolidation\Bootstrap\BootInterface;
use Consolidation\Bootstrap\BootstrapAwareInterface;
use Consolidation\Bootstrap\BootstrapAwareTrait;

class TestBootstrapAwareObject implements BootstrapAwareInterface
{
    use BootstrapAwareTrait;

    public function getBootstrap()
    {
        return $this->bootstrap;
    }
}
