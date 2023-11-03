<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-event
 */
namespace froq\event;

/**
 * Event manager class for stacking & firing events.
 *
 * @package froq\event
 * @class   froq\event\EventManager
 * @author  Kerem Güneş
 * @since   1.0, 6.0
 */
class EventManager
{
    /** Event map. */
    private array $events = [];

    /** Event target. */
    private object|null $target;

    /**
     * Constructor.
     *
     * @param object|null $target
     */
    public function __construct(object $target = null)
    {
        $this->target = $target;
    }

    /**
     * Get events property.
     *
     * @return array
     */
    public function events(): array
    {
        return $this->events;
    }

    /**
     * Add/register an event.
     *
     * @param  string                    $name
     * @param  callable|froq\event\Event $callback
     * @param  mixed                  ...$options
     * @return void
     */
    public function add(string $name, callable|Event $callback, mixed ...$options): void
    {
        if ($callback instanceof Event) {
            $event = $callback;
        } else {
            // Add self target as event target.
            $options['target'] ??= $this->target;

            $event = new Event($name, $callback, ...$options);
        }

        // Set self as manager.
        $event->setManager($this);

        $this->events[$event->name] = $event;
    }

    /**
     * Get an event by given name.
     *
     * @param  string $name
     * @return froq\event\Event|null
     * @throws froq\event\EventManagerException
     */
    public function get(string $name): Event|null
    {
        $name = Event::normalizeName($name)
            ?: throw EventManagerException::forEmptyName();

        return $this->events[$name] ?? null;
    }

    /**
     * Remove an event by given name.
     *
     * @param  string $name
     * @return void
     * @throws froq\event\EventManagerException
     */
    public function remove(string $name): void
    {
        $name = Event::normalizeName($name)
            ?: throw EventManagerException::forEmptyName();

        unset($this->events[$name]);
    }

    /**
     * Check whether any event exists by given name.
     *
     * @param  string $name
     * @return bool
     * @throws froq\event\EventManagerException
     */
    public function has(string $name): bool
    {
        $name = Event::normalizeName($name)
            ?: throw EventManagerException::forEmptyName();

        return isset($this->events[$name]);
    }

    /**
     * Fire an event by given name or throw a `EventManagerException` if no event found
     * by that name.
     *
     * @param  string    $name
     * @param  mixed  ...$arguments Call-time arguments.
     * @return mixed
     * @throws froq\event\EventManagerException
     */
    public function fire(string $name, mixed ...$arguments): mixed
    {
        $event = $this->get($name)
            ?: throw EventManagerException::forNoEventFound($name);

        return $event(...$arguments);
    }

    /**
     * Create an event.
     *
     * @param  string   $name
     * @param  callable $callback
     * @param  mixed ...$options
     * @return froq\event\Event
     */
    public static function createEvent(string $name, callable $callback, mixed ...$options): Event
    {
        return new Event($name, $callback, ...$options);
    }

    /**
     * Fire an event.
     *
     * @param  froq\event\Event $event
     * @param  mixed         ...$arguments Call-time arguments.
     * @return mixed
     */
    public static function fireEvent(Event $event, mixed ...$arguments): mixed
    {
        return $event(...$arguments);
    }
}
