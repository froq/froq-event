<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 <https://opensource.org/licenses/apache-2.0>
 */
declare(strict_types=1);

namespace froq\event;

use froq\event\{EventException, Event};

/**
 * Events.
 *
 * @package froq\event
 * @object  froq\event\Events
 * @author  Kerem Güneş <k-gun@mail.com>
 * @since   1.0
 */
final class Events
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
     * Check whether any event exists with given name.
     *
     * @param  string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return $this->get($name) != null;
    }

    /**
     * Add/register an event.
     *
     * @param  string   $name
     * @param  callable $callback
     * @param  bool     $once
     * @return void
     * @since  4.0
     * @throws froq\event\EventException
     */
    public function add(string $name, callable $callback, bool $once = true): void
    {
        $name = $this->normalizeName($name);
        if ($name == '') {
            throw new EventException('Event name must not be empty');
        }

        $this->stack[$name] = new Event($name, $callback, $once, $this);
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
        $name = $this->normalizeName($name);

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
        $name = $this->normalizeName($name);

        unset($this->stack[$name]);
    }

    /** @alias of add() */
    public function on(...$args) { $this->add(...$args); }

    /** @alias of remove() */
    public function off(...$args) { $this->remove(...$args); }

    /**
     * Fire an event by given name.
     *
     * @param  string $name
     * @param  ...    $args Runtime arguments if given.
     * @return any
     */
    public function fire(string $name, ...$args)
    {
        $event = $this->get($name);
        if ($event == null) {
            return; // No event.
        }

        return self::fireEvent($event, ...$args);
    }

    /**
     * Fire an event object.
     *
     * @param  froq\event\Event $event
     * @param  ...              $args
     * @return any
     * @since  4.0
     */
    public static function fireEvent(Event $event, ...$args)
    {
        // Remove if once.
        if ($event->once()) {
            ($stack = $event->stack())
                && $stack->remove($event->name());
        }

        return call_user_func_array($event->callback(), $args);
    }

    /**
     * Normalize event name.
     *
     * @param  string $name
     * @return string
     * @internal
     */
    private function normalizeName(string $name): string
    {
        return strtolower(trim($name));
    }
}
