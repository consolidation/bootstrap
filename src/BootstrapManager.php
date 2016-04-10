<?php
namespace Consolidation\Bootstrap;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class BootstrapManager implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var Consolidation\Bootstrap\BootInterface[]
     */
    protected $bootstrapCandidates = [];

    /**
     * @var BootstrapCurator
     */
    protected $curator;

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    public function setBootstrapCurator(BootstrapCurator $curator)
    {
        $this->curator = $curator;
    }

    public function getBootstrapCurator()
    {
        if (!isset($this->curator)) {
            $this->curator = new BootstrapCurator();
        }
        return $this->curator;
    }

    /**
     * Add a bootstrap selection object to the list of candidates
     *
     * @param BootstrapSelectionInterface
     *   List of boot candidates
     */
    public function add(BootstrapSelectionInterface $candidate)
    {
        $this->bootstrapCandidates[] = $candidate;
    }

    /**
     * Register a factory with an object-creation listener, and
     * the bootstrap manager will ensure that the bootstrap object
     * will be set on any object that implements BootstrapAwareInterface.
     * This is an alternative to using dependency injection container
     * inflection (e.g. if the commandfile objects are not always
     * created via the container).
     *
     * @param mixed $factory A factory object that creates or uses
     *   bootstrap-aware objects.
     * @param string $listenerMethod The method to call to register a
     *   listener callback function.
     * @example $bootstrapManager->registerFactory($annotationCommandFactory);
     */
    public function registerFactory($factory, $listenerMethod = 'addListener')
    {
        $factory->$listenerMethod(
            function ($object) {
                if ($object instanceof BootstrapAwareInterface) {
                    $object->setBootstrapCurator($this->getBootstrapCurator());
                }
            }
        );
    }

    /**
     * Look up the best bootstrap class for the given location
     * from the set of available candidates.
     *
     * @param string $path Path to the desired framework
     *
     * @return null|BootInterface
     */
    public function selectBootstrap($path)
    {
        if ($this->getBootstrapCurator()->hasBootstrap()) {
            throw new \RuntimeException('Bootstrap object already selected.');
        }
        foreach ($this->bootstrapCandidates as $candidate) {
            if ($candidate->isValid($path)) {
                return $this->setSelectedBootstrap($candidate, $path);
            }
        }
        return null;
    }

    /**
     * Get the bootstrap object for the selected framework.
     *
     * @param BootstrapSelectionInterface $candidate A valid selection object.
     *
     * @return BootInterface
     */
    protected function setSelectedBootstrap(BootstrapSelectionInterface $candidate, $path)
    {
        return $this->setBootstrap($candidate->getBootstrap($path));
    }

    /**
     * Record the bootstrap object that was selected.
     *
     * @param BootInterface $bootstrap
     *
     * @return BootInterface
     */
    protected function setBootstrap(BootInterface $bootstrap)
    {
        $this->getBootstrapCurator()->setBootstrap($bootstrap);
        return $bootstrap;
    }
}
