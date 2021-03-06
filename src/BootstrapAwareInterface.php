<?php
namespace Consolidation\Bootstrap;

interface BootstrapAwareInterface
{
    public function setBootstrap(BootInterface $bootstrap);
    public function setBootstrapCurator(BootstrapCurator $curator);
}
