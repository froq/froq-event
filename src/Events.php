<?php
/**
 * Copyright (c) 2016 Kerem Güneş
 *    <k-gun@mail.com>
 *
 * GNU General Public License v3.0
 *    <http://www.gnu.org/licenses/gpl-3.0.txt>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
declare(strict_types=1);

namespace Froq\Event;

/**
 * @package    Froq
 * @subpackage Froq\Event
 * @object     Froq\Event\Events
 * @author     Kerem Güneş <k-gun@mail.com>
 */
final class Events
{
    /**
     * Stack.
     * @var array
     */
    private $stack = [];

    /**
     * Constructor.
     * @param array $stack
     */
    public final function __construct(array $stack = [])
    {
        $this->setStack($stack);
    }

    /**
     * Set stack.
     * @param  array $stack
     * @return self
     */
    public final function setStack(array $stack): self
    {
        // reset
        $this->stack = [];

        foreach ($stack as $event) {
            if (!$event instanceof Event) {
                throw new EventException('Stack elements must be instanceof Froq\Event\Event object!');
            }

            $this->stack[$this->normalizeName($event->getName())] = $event;
        }

        return $this;
    }

    /**
     * Get stack.
     * @return array
     */
    public final function getStack(): array
    {
        return $this->stack;
    }

    /**
     * On.
     * @param  string     $name
     * @param  callable   $function
     * @param  array|null $functionArguments
     * @param  bool       $once
     * @return self
     */
    public final function on(string $name, callable $function, array $functionArguments = null,
        bool $once = true): self
    {
        $name = $this->normalizeName($name);

        $this->stack[$name] = new Event($name, $function, $functionArguments, $once);

        return $this;
    }

    /**
     * Off.
     * @param  string $name
     * @return self
     */
    public final function off(string $name): self
    {
        unset($this->stack[$this->normalizeName($name)]);

        return $this;
    }

    /**
     * Has.
     * @param  string $name
     * @return bool
     */
    public final function has(string $name): bool
    {
        return isset($this->stack[$this->normalizeName($name)]);
    }

    /**
     * Fire.
     * @param  string $name
     * @param  ...    $functionArguments   Runtime arguments if given.
     * @return any
     */
    public final function fire(string $name, ...$functionArguments)
    {
        $name = $this->normalizeName($name);
        if (isset($this->stack[$name])) {
            $event = $this->stack[$name];

            // remove if once
            if ($event->isOnce()) {
                $this->off($name);
            }

            $function = $even->getFunction();
            $functionArguments = array_merge($event->getFunctionArguments(), $functionArguments);

            return call_user_func_array($function, $functionArguments);
        }
    }

    /**
     * Normalize name.
     * @param  string $name
     * @return string
     */
    final private function normalizeName(string $name): string
    {
        return strtolower($name);
    }
}
