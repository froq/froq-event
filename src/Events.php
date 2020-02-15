<?php
/**
 * MIT License <https://opensource.org/licenses/mit>
 *
 * Copyright (c) 2015 Kerem Güneş
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
declare(strict_types=1);

namespace froq\event;

use froq\event\{Event, EventException};

/**
 * Events.
 * @package froq\event
 * @object  froq\event\Events
 * @author  Kerem Güneş <k-gun@mail.com>
 * @since   1.0
 */
final class Events
{
    /**
     * Stack.
     * @var array<string, froq\event\Event>
     */
    private array $stack = [];

    /**
     * Constructor.
     */
    public function __construct()
    {}

    /**
     * Get stack.
     * @return array<string, froq\event\Event>
     */
    public function getStack(): array
    {
        return $this->stack;
    }

    /**
     * Has.
     * @param  string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return $this->get($name) != null;
    }

    /**
     * Add.
     * @param  string     $name
     * @param  callable   $callback
     * @param  bool       $once
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
     * Get.
     * @param  string $name
     * @return ?froq\event\Event
     * @since  4.0
     */
    public function get(string $name): ?Event
    {
        $name = $this->normalizeName($name);

        return $this->stack[$name] ?? null;
    }

    /**
     * Remove.
     * @param  string $name
     * @return void
     * @since  4.0
     */
    public function remove(string $name): void
    {
        $name = $this->normalizeName($name);

        unset($this->stack[$name]);
    }

    /**
     * On.
     * @aliasOf add()
     */
    public function on(...$arguments)
    {
        $this->add(...$arguments);
    }

    /**
     * Off.
     * @aliasOf remove().
     */
    public function off(...$arguments)
    {
        $this->remove(...$arguments);
    }

    /**
     * Fire.
     * @param  string $name
     * @param  ...    $arguments Runtime arguments if given.
     * @return any
     */
    public function fire(string $name, ...$arguments)
    {
        $event = $this->get($name);
        if ($event == null) {
            return; // No event.
        }

        return self::fireEvent($event, ...$arguments);
    }

    /**
     * Fire event.
     * @param  froq\event\Event $event
     * @param  ...              $arguments
     * @return any
     * @since  4.0
     */
    public static function fireEvent(Event $event, ...$arguments)
    {
        // Remove if once.
        if ($event->once()) {
            $event->stack()->remove($event->name());
        }

        return call_user_func_array($event->callback(), $arguments);
    }

    /**
     * Normalize name.
     * @param  string $name
     * @return string
     */
    private function normalizeName(string $name): string
    {
        return strtolower(trim($name));
    }
}
