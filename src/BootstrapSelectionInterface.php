<?php
namespace Consolidation\Bootstrap;

/**
 * Defines the interface for Boot classes.  Do any work
 * necessary to bootstrap a framework.
 */
interface BootstrapSelectionInterface
{
    /**
     * Determines whether or not the specified path points to
     * the root directory of a framework that can be bootstrapped by
     * this boot object.
     *
     * These functions should be written such that one and only
     * one class will return TRUE for any given $path.
     *
     * @param $path to a directory to test
     *
     * @return boolean
     */
    public function isValid($path);

    /**
     * Return the Bootstrap object associated with this selection object.
     *
     * @param $path to the root directory of a framework supported by this object.
     *
     * @return BootInterface
     */
    public function getBootstrap($path);
}
