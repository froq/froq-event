<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-event
 */
declare(strict_types=1);

namespace froq\event;

/**
 * Event stack class, behaves just like a registery object.
 *
 * @package froq\event
 * @object  froq\event\EventStack
 * @author  Kerem Güneş
 * @since   1.0, 6.0
 */
class EventStack
{
    /** @var array<string, froq\event\Event> */
    private array $stack = [];

    /**
     * Constructor.
     */
    public function __construct()
    {}

    /**
     * Get stack property.
     *
     * @return array<string, froq\event\Event>
     */
    public function stack(): array
    {
        return $this->stack;
    }

    /**
     * Add/register an event.
     *
     * @param  string                    $name
     * @param  callable|froq\event\Event $callback
     * @param  bool                      $once
     * @return void
     * @since  4.0
     */
    public function add(string $name, callable|Event $callback, bool $once = true): void
    {
        $event = ($callback instanceof Event) ? $callback
            : new Event($name, $callback, $once, $this);

        // Set stack as self.
        $event->setStack($this);

        $this->stack[$event->name] = $event;
    }

    /**
     * Get an event by given name.
     *
     * @param  string $name
     * @return froq\event\Event|null
     * @since  4.0
     */
    public function get(string $name): Event|null
    {
        $name = EventUtil::normalizeName($name);

        return $this->stack[$name] ?? null;
    }

    /**
     * Remove an event by given name.
     *
     * @param  string $name
     * @return void
     * @since  4.0
     */
    public function remove(string $name): void
    {
        $name = EventUtil::normalizeName($name);

        unset($this->stack[$name]);
    }

    /**
     * Check whether any event exists with given name.
     *
     * @param  string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        $name = EventUtil::normalizeName($name);

        return isset($this->stack[$name]);
    }

    /**
     * Fire an event by given name or throw a `EventStackException` if no event found
     * by given name.
     *
     * @param  string    $name
     * @param  mixed  ...$args Call-time arguments.
     * @return mixed
     * @throws froq\event\EventStackException
     */
    public function fire(string $name, mixed ...$args): mixed
    {
        $event = $this->get($name);

        if (!$event) {
            throw new EventStackException(
                'No event found in stack with name `%s`',
                $name
            );
        }

        return Firer::fire($event, ...$args);
    }

    /**
     * @alias add()
     */
    public function on(...$args)
    {
        $this->add(...$args);
    }

    /**
     * @alias remove()
     */
    public function off(...$args)
    {
        $this->remove(...$args);
    }
}
