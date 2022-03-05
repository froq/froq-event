<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-event
 */
declare(strict_types=1);

namespace froq\event;

use Closure;

/**
 * Event.
 *
 * @package froq\event
 * @object  froq\event\Event
 * @author  Kerem Güneş
 * @since   1.0
 */
final class Event
{
    /** @var string */
    public readonly string $name;

    /** @var Closure */
    public readonly Closure $callback;

    /** @var bool */
    public readonly bool $once;

    /**
     * Stack (link to events stack for remove() etc).
     * @var froq\event\Events|null
     * @see froq\event\Events.fireEvent()
     */
    private Events|null $stack = null;

    /**
     * Constructor.
     *
     * @param string   $name
     * @param callable $callback
     * @param bool     $once
     */
    public function __construct(string $name, callable $callback, bool $once = true)
    {
        // Uniform callback.
        if (!$callback instanceof Closure) {
            $callback = $callback(...);
        }

        $this->name     = $name;
        $this->callback = $callback;
        $this->once     = $once;
    }

    /**
     * Run event.
     *
     * @param  mixed ...$arguments
     * @return mixed|null
     * @since  4.0
     */
    public function __invoke(mixed ...$arguments): mixed
    {
        return Events::fireEvent($this, ...$arguments);
    }

    /** @alias __invoke() */
    public function fire(...$args)
    {
        return $this->__invoke(...$args);
    }

    /**
     * Set stack.
     *
     * @return void
     * @since  6.0
     */
    public function setStack(Events $stack): void
    {
        $this->stack = $stack;
    }

    /**
     * Get stack.
     *
     * @return froq\event\Events|null
     * @since  6.0
     */
    public function getStack(): Events|null
    {
        return $this->stack;
    }
}
