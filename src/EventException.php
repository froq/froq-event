<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-event
 */
declare(strict_types=1);

namespace froq\event;

/**
 * @package froq\event
 * @object  froq\event\EventException
 * @author  Kerem Güneş
 * @since   1.0
 */
class EventException extends \froq\common\Exception
{
    /**
     * Create for no state found.
     *
     * @return static
     */
    public static function forNoStateFound(string $name): static
    {
        return new static('No state found such %q', $name);
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
