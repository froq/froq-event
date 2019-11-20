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
     * @param array|null $stack
     */
    public function __construct(array $stack = null)
    {
        $stack && $this->setStack($stack);
    }

    /**
     * Set stack.
     * @param  array<froq\event\Event> $stack
     * @return void
     * @throws froq\event\EventException
     */
    public function setStack(array $stack): void
    {
        // Reset.
        $this->stack = [];

        foreach ($stack as $event) {
            if (!$event instanceof Event) {
                throw new EventException(sprintf('Stack elements must be instance of %s object',
                    Event::class));
            }

            $this->stack[$this->normalizeName($event->getName())] = $event;
        }
    }

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
     * @param  callable   $function
     * @param  array|null $functionArguments
     * @param  bool       $once
     * @return void
     */
    public function on(string $name, callable $function, array $functionArguments = null,
        bool $once = true): void
    {
        $name = $this->normalizeName($name);

        $this->stack[$name] = new Event($name, $function, $functionArguments, $once);
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
     * @param  ...    $functionArguments Runtime arguments if given.
     * @return any
     */
    public function fire(string $name, ...$functionArguments)
    {
        $name = $this->normalizeName($name);
        if (!isset($this->stack[$name])) {
            return null; // No event.
        }

        $event = $this->stack[$name];

        // Remove if once.
        if ($event->isOnce()) $this->off($name);

        $function = $event->getFunction();
        $functionArguments = array_merge($event->getFunctionArguments(), $functionArguments);

        return call_user_func_array($function, $functionArguments);
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
