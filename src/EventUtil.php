<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-event
 */
declare(strict_types=1);

namespace froq\event;

/**
 * Internal helper class.
 *
 * @package froq\event
 * @object  froq\event\EventUtil
 * @author  Kerem Güneş
 * @since   6.0
 * @static
 * @internal
 */
class EventUtil extends \StaticClass
{
    /**
     * Normalize event name.
     *
     * @param  string $name
     * @return string
     * @throws froq\event\EventException
     */
    public static function normalizeName(string $name): string
    {
        $name = trim($name);
        if ($name == '') {
            throw new EventException('Empty event name');
        }

        return strtolower($name);
    }

    /**
     * Normalize event callback.
     *
     * @param  callable $callback
     * @return Closure
     */
    public static function normalizeCallback(callable $callback): \Closure
    {
        // Uniform callback.
        if (!$callback instanceof \Closure) {
            $callback = $callback(...);
        }

        return $callback;
    }
}
