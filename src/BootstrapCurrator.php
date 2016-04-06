<?php
namespace Consolidation\Bootstrap;

/**
 * Keep bootstrap-aware object safe until such a time as
 * a bootstrap object is selected and set.  Once the bootstrap
 * object is known, is is passed along to any object that was
 * initialized before it became available.
 *
 * The purpose of this class is for use as a Dependency Injection
 * inflector. Typically, the bootstrap object will not be known
 * at the time the container is configured; however, adding to the
 * bootstrap object to the container later is not desirable either.
 * It is more efficient to take advantage of the lazy-instantiation
 * capabilities of the container, although doing so means that it
 * is not always obvious at what point a given object might be
 * instantiated.
 *
 * It is presumed that bootstrap-aware objects in general will not
 * be used until after bootstrap-selection has taken place. If they
 * are, they must be prepared to deal with an unset boot object.
 *
 * TODO: Alternately, we could create an "empty" or "no selection"
 * bootstrap object, and provide that temporarily to all listeners
 * until the real object is available. However, this design has the
 * disadvantage the listeners would then need to re-do any work once
 * their setBootstrap method was called. This clearly would not be
 * possible for work that was returned as a function result, and would
 * probably be very hard to debug. With the current design, the
 * unset boot object is, at least, easy to detect.
 */
class BootstrapCurrator
{
    /**
     * @var BootInterface
     */
    protected $bootstrap;

    /**
     * @var BootstrapAwareInterface[]
     */
    protected $listeners = [];

    public function register(BootstrapAwareInterface $listener)
    {
        if (isset($this->bootstrap)) {
            $this->apply($listener, $this->bootstrap);
            return;
        }
        $this->listeners[] = $listener;
    }

    public function setBootstrap(BootInterface $bootstrap)
    {
        $this->bootstrap = $bootstrap;
        $this->applyToListeners($this->listeners, $bootstrap);
        $this->listeners = [];
    }

    public function getBootstrap()
    {
        return $this->bootstrap;
    }

    public function hasBootstrap()
    {
        return isset($this->bootstrap);
    }

    protected function apply(BootstrapAwareInterface $listener, BootInterface $bootstrap)
    {
        $listener->setBootstrap($bootstrap);
    }

    protected function applyToListeners($listeners, BootInterface $bootstrap)
    {
        foreach ($listeners as $listener) {
            $this->apply($listener, $bootstrap);
        }
    }
}
