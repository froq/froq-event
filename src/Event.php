<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 <https://opensource.org/licenses/apache-2.0>
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
 * @author  Kerem Güneş <k-gun@mail.com>
 * @since   1.0
 * @internal
 */
final class Event
{
    /**
     * Name.
     * @var string
     */
    private string $name;

    /**
     * Callback.
     * @var callable
     */
    private $callback;

    /**
     * Once.
     * @var bool
     */
    private bool $once = true;

    /**
     * Stack (link to events stack for remove() etc. @see Events.fireEvent()).
     * @var froq\event\Events
     * @since 4.0
     */
    private Events $stack;

    /**
     * Constructor.
     * @param string                $name
     * @param callable              $callback
     * @param bool                  $once
     * @param froq\event\Events $stack
     */
    public function __construct(string $name, callable $callback, bool $once = true,
        Events $stack)
    {
        // Uniform callback (rid of c-all-able weirdo).
        if (!$callback instanceof Closure) {
            $callback = Closure::fromCallable($callback);
        }

        $this->stack    = $stack;
        $this->name     = $name;
        $this->callback = $callback;
        $this->once     = $once;
    }

    /**
     * Invoke.
     * @param  ... $arguments
     * @return any
     * @since  4.0
     */
    public function __invoke(...$arguments)
    {
        return Events::fireEvent($this, ...$arguments);
    }

    /**
     * Name.
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Callback.
     * @return callable
     */
    public function callback(): callable
    {
        return $this->callback;
    }

    /**
     * Once.
     * @return bool
     */
    public function once(): bool
    {
        return $this->once;
    }

    /**
     * Stack.
     * @return froq\event\Events
     * @since  4.0
     */
    public function stack(): Events
    {
        return $this->stack;
    }
}
