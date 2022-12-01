<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-event
 */
namespace froq\event;

use froq\util\storage\Storage;
use Closure;

/**
 * Storage class for storing event callbacks (listeners) just to keep
 * the Closure's separate from `Event` classes since debugging contains
 * much verbosity of data recursion.
 *
 * @package froq\event
 * @class   froq\event\EventStorage
 * @author  Kerem Güneş
 * @since   6.0
 * @internal
 */
class EventStorage extends Storage
{
    /**
     * Store an event callback.
     *
     * @param  froq\event\Event $event
     * @param  Closure          $callback
     * @return void
     */
    public static function add(Event $event, Closure $callback): void
    {
        self::store($event->id, $callback);
    }

    /**
     * Get a stored event callback.
     *
     * @param  froq\event\Event $event
     * @return Closure|null
     */
    public static function get(Event $event): Closure|null
    {
        return self::item($event->id);
    }

    /**
     * Unstore an event callback.
     *
     * @param  froq\event\Event $event
     * @return void
     */
    public static function remove(Event $event): void
    {
        self::unstore($event->id);
    }
}
