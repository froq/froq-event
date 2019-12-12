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

/**
 * Event.
 * @package froq\event
 * @object  froq\event\Event
 * @author  Kerem Güneş <k-gun@mail.com>
 * @since   1.0
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
     * Callback arguments.
     * @var array
     */
    private array $callbackArguments = [];

    /**
     * Once.
     * @var bool
     */
    private bool $once = true;

    /**
     * Constructor.
     * @param string     $name
     * @param callable   $callback
     * @param array|null $callbackArguments
     * @param bool       $once
     */
    public function __construct(string $name, callable $callback, array $callbackArguments = null,
        bool $once = true)
    {
        $this->name              = $name;
        $this->callback          = $callback;
        $this->callbackArguments = $callbackArguments ?? [];
        $this->once              = $once;
    }

    /**
     * Set name.
     * @param  string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set callback.
     * @param  callable $callback
     * @return self
     */
    public function setCallback(callable $callback): self
    {
        $this->callback = $callback;

        return $this;
    }

    /**
     * Get callback.
     * @return callable
     */
    public function getCallback(): callable
    {
        return $this->callback;
    }

    /**
     * Set callback arguments.
     * @param  array $callbackArguments
     * @return self
     */
    public function setCallbackArguments(array $callbackArguments): self
    {
        $this->callbackArguments = $callbackArguments;

        return $this;
    }

    /**
     * Get callback arguments.
     * @return array
     */
    public function getCallbackArguments(): array
    {
        return $this->callbackArguments;
    }

    /**
     * Set once.
     * @param  bool $once
     * @return self
     */
    public function setOnce(bool $once): self
    {
        $this->once = $once;

        return $this;
    }

    /**
     * Get once.
     * @return bool
     */
    public function getOnce(): bool
    {
        return $this->once;
    }

    /**
     * Is once.
     * @return bool
     */
    public function isOnce(): bool
    {
        return $this->once == true;
    }
}
