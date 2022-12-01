<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-event
 */
namespace froq\event;

/**
 * @package froq\event
 * @class   froq\event\EventException
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
}
