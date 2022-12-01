<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-event
 */
namespace froq\event;

/**
 * @package froq\event
 * @class   froq\event\EventManagerException
 * @author  Kerem Güneş
 * @since   1.0, 6.0
 */
class EventManagerException extends \froq\common\Exception
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

    /**
     * Create for empty name.
     *
     * @return static
     */
    public static function forEmptyName(): static
    {
        return new static('Empty event name');
    }
}
