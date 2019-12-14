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

use froq\event\Events;
use Closure;

/**
 * Event.
 * @package froq\event
 * @object  froq\event\Event
 * @author  Kerem Güneş <k-gun@mail.com>
 * @since   1.0
 * @internal
 */
final class Event
{
    /**
     * Name.
     * @var string
     */
    private string $name;

    /**
     * Callback.
     * @var callable
     */
    private $callback;

    /**
     * Once.
     * @var bool
     */
    private bool $once = true;

    /**
     * Stack (link to events stack for remove() etc. @see Events.fireEvent()).
     * @var froq\event\Events
     * @since 4.0
     */
    private Events $stack;

    /**
     * Constructor.
     * @param string                $name
     * @param callable              $callback
     * @param bool                  $once
     * @param froq\event\Events $stack
     */
    public function __construct(string $name, callable $callback, bool $once = true,
        Events $stack)
    {
        // Uniform callback (rid of c-all-able weirdo).
        $callback = Closure::fromCallable($callback);

        $this->stack    = $stack;
        $this->name     = $name;
        $this->callback = $callback;
        $this->once     = $once;
    }

    /**
     * Invoke.
     * @param  ... $arguments
     * @return any
     * @since  4.0
     */
    public function __invoke(...$arguments)
    {
        return Events::fireEvent($this, ...$arguments);
    }

    /**
     * Name.
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Callback.
     * @return callable
     */
    public function callback(): callable
    {
        return $this->callback;
    }

    /**
     * Once.
     * @return bool
     */
    public function once(): bool
    {
        return $this->once;
    }

    /**
     * Stack.
     * @return froq\event\Events
     * @since  4.0
     */
    public function stack(): Events
    {
        return $this->stack;
    }
}
