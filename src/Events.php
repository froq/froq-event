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
     * @var array<froq\event\Event>
     */
    private array $stack = [];

    /**
     * Constructor.
     */
    public function __construct()
    {}

    /**
     * Get stack.
     * @return array
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
        return isset($this->stack[$this->normalizeName($name)]);
    }

    /**
     * On.
     * @param  string     $name
     * @param  callable   $callback
     * @param  array|null $callbackArguments
     * @param  bool       $once
     * @return void
     */
    public function on(string $name, callable $callback, array $callbackArguments = null,
        bool $once = true): void
    {
        $name = $this->normalizeName($name);

        $this->stack[$name] = new Event($name, $callback, $callbackArguments, $once);
    }

    /**
     * Off.
     * @param  string $name
     * @return void
     */
    public function off(string $name): void
    {
        unset($this->stack[$this->normalizeName($name)]);
    }

    /**
     * Fire.
     * @param  string $name
     * @param  ...    $callbackArguments Runtime arguments if given.
     * @return any
     */
    public function fire(string $name, ...$callbackArguments)
    {
        $event = $this->stack[$this->normalizeName($name)] ?? null;
        if ($event == null) {
            return; // No event.
        }


        // Remove if once.
        if ($event->isOnce()) $this->off($name);

        $callback = $event->getCallback();
        $callbackArguments = array_merge($event->getCallbackArguments(), $callbackArguments);

        return call_user_func_array($callback, $callbackArguments);
    }

    /**
     * Normalize name.
     * @param  string $name
     * @return string
     */
    private function normalizeName(string $name): string
    {
        return strtolower($name);
    }
}
