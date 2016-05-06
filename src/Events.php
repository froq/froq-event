<?php
namespace Froq\Event;

use Froq\Collection\Collection;

final class Events extends Collection
{
    final public function on(string $name, callable $func, array $funcArgs = null): self
    {
        $this->set($name, new Event($name, $func, $funcArgs));
        return $this;
    }

    final public function off(string $name): self
    {
        $this->del($name);
        return $this;
    }

    final public function fire(string $name)
    {
        $event = $this->get($name);
        if ($event) {
            return call_user_func_array($event->getFunc(), $event->getFuncArgs());
        }
    }
}
