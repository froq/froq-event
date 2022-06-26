<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-event
 */
declare(strict_types=1);

namespace froq\event;

/**
 * @package froq\event
 * @object  froq\event\EventManagerException
 * @author  Kerem Güneş
 * @since   1.0, 6.0
 */
class EventManagerException extends EventException
{
    /**
     * Create for no event found.
     *
     * @return static
     */
    public static function forNoEventFound(string $name): static
    {
        return new static('No event found such %q', $name);
    }
}
