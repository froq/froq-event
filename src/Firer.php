<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-event
 */
declare(strict_types=1);

namespace froq\event;

/**
 * Internal helper class for firing event objects.
 *
 * @package froq\event
 * @object  froq\event\Firer
 * @author  Kerem Güneş
 * @since   4.0, 6.0
 * @internal
 */
class Firer
{
    /**
     * Fire an event object.
     *
     * @param  froq\event\Event    $event
     * @param  mixed            ...$args
     * @return mixed
     * @since  4.0
     */
    public static function fire(Event $event, mixed ...$args): mixed
    {
        // Remove if once.
        if ($event->once && ($stack = $event->getStack())) {
            $stack->remove($event->name);
        }

        return ($event->callback)(...$args);
    }
}
