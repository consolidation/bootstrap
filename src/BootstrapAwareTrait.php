<?php
namespace Consolidation\Bootstrap;

trait BootstrapAwareTrait
{
    /**
     * @var BootInterface
     */
    protected $bootstrap;

    public function setBootstrap(BootInterface $bootstrap)
    {
        $this->bootstrap = $bootstrap;
    }

    public function setBootstrapCurrator(BootstrapCurrator $currator)
    {
        $currator->register($this);
    }
}
