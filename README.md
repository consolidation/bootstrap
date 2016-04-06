# Consolidation\Bootstrap

Locate and bootstrap components needed to run code from a framework.

[![Travis CI](https://travis-ci.org/consolidation-org/bootstrap.svg?branch=master)](https://travis-ci.org/consolidation-org/bootstrap) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/consolidation-org/bootstrap/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/consolidation-org/bootstrap/?branch=master) [![License](https://poser.pugx.org/consolidation/bootstrap/license)](https://packagist.org/packages/consolidation/bootstrap)

This component is designed to be called from a commandline application, such as [Drush](https://github.com/drush-ops/drush) or [Drupal Console](https://github.com/hechoendrupal/DrupalConsole). It is the responsibility of the application to provide basic information about the operating environment, such as:

- The location of the application's code.
- The location of the framework selected by the user, if any.
- Other user-supplied options, as needed (e.g. which specific site in a multi-site configuration should be selected).

Given this information, this component is responsible for:

- Locating code assets.
  - Command classes in the commandline application.
  - Global plugin command classes in different locations (e.g. in user's home directory).
  - Site-specific plugin command classes stored in the framework.
  - Module and theme-provided command classes.
- Identifying the specific framework and major version that the user selected.
- Providing a mechanism for bootstrapping the framework.
  - Bootstrapping can be done up to a level that provides services needed by the command:
    - Access to the code in the selected framework (include the autoloader).
    - Access to the settings for the specific site (include settings.php or equivalent).
    - Access to the site's database.
    - Access to the site's configuration.
    - Access to the full site's runtime.
    - Access to a logged-in user.
  - Each service level defines which other services are needed, and all are bootstrapped in order.
  - Bootstrap classes for specific frameworks can add more services.
- Provide a mechanism for filtering usable commands.
  - Some commands may require APIs from a specific commandline application.
  - Some commands may require APIs from a specific framework.
  - Some commands may require APIs from specific Composer libraries, which may be provided by the commandline application or the selected framework.

For more details, see Bootstrapping Procedure, below.

## Component Status

Under development.

## Motivation

Reduce maintenance by providing a common central library for bootstrapping, and allow for better sharing of general-purpose commands across frameworks. For example, the Drush sql-* commands, which operate by calling SQL commandline tools, could be usable in other contexts if a general-purpose bootstrapping class provides the services it needs. This component aims to fill that need.

## Bootstrapping Procedure
In general, bootstrapping is not done for the command being run, but there are some exceptions.

The bootstrap object will automatically bootstrap to the framework code (known as DRUSH_BOOTSTRAP_ROOT in Drush) if necessary to identify the framework. This will allow frameworks to provide their own bootstrap object, if necessary.

If the selected command is not bootstrap-aware (signaled by implementing BootstrapAwareInterface), then its bootstrap level will depend on where the command was provided:

- Global commands are not bootstrapped at all. They have no access to the selected site unless they are bootstrap-aware.
- Site-specific commands are bootstrapped to the site code level.
- Module-provided commands are fully bootstrapped.

## Services
Bootstrap-aware classes can ask the bootstrap object for a service. Services are requested by a well-known identifier (string); the result will be either NOT FOUND, or a well-known interface, as determined by the service identifier. For example, the aforementioned SQL services might have a service identifier of 'sql', and might return an object that implements SQLInterface.

Each class of service will be provided by its own project.

## Usage
```
$manager = new BootstrapManager();
$manager->add(new FrameworkBoot1());
$manager->add(new FrameworkBoot2());

$bootstrapObject = $manager->selectBootstrap('/path/to/framework/root');
$bootstrapObject->boot(['database']); // TODO: improve service feature specification
```
## Dependency Injection
If your Dependency Injection container supports inflection, that feature may be used to ensure that any bootstrap-aware object created via the container will have its setBootstrap method called once a bootstrap object has been selected.

The example below shows configuration for league/container:
```
$container->share('bootstrapCurrator', 'Consolidation\Bootstrap\BootstrapCurrator');
$container->share('bootstrapManager', 'Consolidation\Bootstrap\BootstrapManager')
    ->withMethodCall('setBootstrapCurrator', ['bootstrapCurrator']);
$container->inflector('Consolidation\Bootstrap\BootstrapAwareInterface')
    ->invokeMethod('setBootstrapCurrator', ['bootstrapCurrator']);
```
If you are not using a container that supports inflection, or if you do not wish to instantiate all of your bootstrap-aware object instances via the container, then you may register your factory with the bootstrap manager, and it will ensure that the bootstrap object is injected as needed. Note that in order for this feature to work, the factory must provide a listener API, and notify the bootstrap manager when objects that might need to be set up are encountered.
```
$factory = new AnnotationCommandFactory();
$manager = new BootstrapManager();
$manager->registerFactory($factory);
```
## Comparison to Existing Solutions

Drush has an existing `Boot` interface that is very similar to what is needed here. However, this class is tightly coupled with Drush; therefore, the implementation here will be slightly different.
