<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-event
 */
declare(strict_types=1);

namespace froq\event;

/**
 * Events.
 *
 * @package froq\event
 * @object  froq\event\Events
 * @author  Kerem Güneş
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
        $name = self::normalizeName($name);

        return isset($this->stack[$name]);
    }

    /**
     * Add/register an event.
     *
     * @param  string   $name
     * @param  callable $callback
     * @param  bool     $once
     * @return void
     * @since  4.0
     */
    public function add(string $name, callable $callback, bool $once = true): void
    {
        $name = self::normalizeName($name);

        $event = new Event($name, $callback, $once, $this);
        $event->setStack($this);

        $this->stack[$name] = $event;
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
        $name = self::normalizeName($name);

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
        $name = self::normalizeName($name);

        unset($this->stack[$name]);
    }

    /** @alias add() */
    public function on(...$args)
    {
        $this->add(...$args);
    }

    /** @alias remove() */
    public function off(...$args)
    {
        $this->remove(...$args);
    }

    /**
     * Fire an event by given name.
     *
     * @param  string    $name
     * @param  mixed  ...$arguments Calltime arguments.
     * @return mixed|false
     */
    public function fire(string $name, mixed ...$arguments): mixed
    {
        $event = $this->get($name);

        return $event ? self::fireEvent($event, ...$arguments) : false;
    }

    /**
     * Fire an event object.
     *
     * @param  froq\event\Event    $event
     * @param  mixed            ...$arguments
     * @return mixed
     * @since  4.0
     */
    public static function fireEvent(Event $event, mixed ...$arguments): mixed
    {
        // Remove if once.
        if ($event->once && ($stack = $event->getStack())) {
            $stack->remove($event->name);
        }

        return call_user_func_array($event->callback, $arguments);
    }

    /**
     * Normalize event name.
     *
     * @throws froq\event\EventException
     */
    private static function normalizeName(string $name): string
    {
        $name = trim($name);
        if ($name == '') {
            throw new EventException('Empty event name');
        }

        return strtolower($name);
    }
}
