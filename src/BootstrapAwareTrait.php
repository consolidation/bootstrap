<?php
namespace Consolidation\Bootstrap;

trait BootstrapAwareTrait
{
    protected $bootstrap;

    public function setBootstrap($bootstrap)
    {
        $this->bootstrap = $bootstrap;
    }
}
