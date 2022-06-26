<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-event
 */
declare(strict_types=1);

namespace froq\event;

/**
 * A trait provides `on()` and `off()` methods for classes defining `$eventManager`
 * property as `froq\event\EventManager`.
 *
 * @package froq\event
 * @object  froq\event\EventTrait
 * @author  Kerem Güneş
 * @since   6.0
 */
trait EventTrait
{
    /**
     * Add an event.
     *
     * @param  string   $name
     * @param  callable $callback
     * @param  mixed ...$options
     * @return self
     */
    public function on(string $name, callable $callback, mixed ...$options): self
    {
        $this->eventManager->on($name, $callback, ...$options);

        return $this;
    }

    /**
     * Remove an event.
     *
     * @param  string $name
     * @return self
     */
    public function off(string $name): self
    {
        $this->eventManager->off($name);

        return $this;
    }
}
