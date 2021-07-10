<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-event
 */
declare(strict_types=1);

namespace froq\event;

use froq\event\Events;
use Closure;

/**
 * Event.
 *
 * @package froq\event
 * @object  froq\event\Event
 * @author  Kerem Güneş
 * @since   1.0
 * @internal
 */
final class Event
{
    /** @var string */
    private string $name;

    /** @var callable */
    private $callback;

    /** @var bool */
    private bool $once = true;

    /**
     * Stack (link to events stack for remove() etc. @see Events.fireEvent()).
     * @var froq\event\Events|null
     * @since 4.0
     */
    private ?Events $stack = null;

    /**
     * Constructor.
     *
     * @param string                 $name
     * @param callable               $callback
     * @param bool                   $once
     * @param froq\event\Events|null $stack
     */
    public function __construct(string $name, callable $callback, bool $once = true, Events $stack = null)
    {
        // Uniform callback (rid of c-all-able weirdo).
        if (!$callback instanceof Closure) {
            $callback = Closure::fromCallable($callback);
        }

        $this->name     = $name;
        $this->callback = $callback;
        $this->once     = $once;
        $this->stack    = $stack;
    }

    /**
     * Run event.
     *
     * @param  ... $args
     * @return any
     * @since  4.0
     */
    public function __invoke(...$args)
    {
        return Events::fireEvent($this, ...$args);
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Get callback.
     *
     * @return callable
     */
    public function callback(): callable
    {
        return $this->callback;
    }

    /**
     * Get once state.
     *
     * @return bool
     */
    public function once(): bool
    {
        return $this->once;
    }

    /**
     * Get holder stack.
     *
     * @return froq\event\Events|null
     * @since  4.0
     */
    public function stack(): Events|null
    {
        return $this->stack;
    }
}
