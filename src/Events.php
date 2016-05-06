<?php
namespace Froq\Event;

use Froq\Collection\Collection;

final class Events extends Collection
{
    final public function on(string $name, callable $func, array $funcArgs = null): self
    {
        $name = $this->normalizeName($name);
        $this->set($name, new Event($name, $func, $funcArgs));
        return $this;
    }

    final public function off(string $name): self
    {
        $name = $this->normalizeName($name);
        $this->del($name);
        return $this;
    }

    final public function fire(string $name)
    {
        $name = $this->normalizeName($name);
        $event = $this->get($name);
        if ($event) {
            return call_user_func_array($event->getFunc(), $event->getFuncArgs());
        }
    }

    final private function normalizeName(string $name): string
    {
        return strtolower($name);
    }
}
