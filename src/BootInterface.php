<?php
namespace Consolidation\Bootstrap;

/**
 * Defines the interface for Boot classes.  Do any work
 * necessary to bootstrap a framework.
 */
interface BootInterface
{
    /**
     * Determine the exact version of the software.
     *
     * @return string|NULL
     *   The version string for the current version of the software, e.g. 8.1.3
     */
    public function getFrameworkVersion();

    /**
     * Return the path to the root of this framework.
     *
     * @return string
     */
    public function getPath();

    /**
     * Return the site selection identifier for this site.
     *
     * @return string
     */
    public function getSiteSelector();

    /**
     * Provide the list of service features (e.g. database access)
     * provided by this boot object.
     *
     * @return string[]
     */
    public function getServiceFeatures();

    /**
     * Main entrypoint to bootstrap the selected framework.
     *
     * @param string[] $serviceFeatures List of service features needed
     *   e.g. database access.
     */
    public function boot($serviceFeatures, $siteSelector = null);

    /**
     * This method is called during the shutdown of drush.
     *
     * @return void
     */
    public function terminate();
}
