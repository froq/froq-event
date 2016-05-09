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
 * @object     Froq\Event\EventException
 * @author     Kerem Güneş <k-gun@mail.com>
 */
final class Event
{
    /**
     * Name.
     * @var string
     */
    private $name;

    /**
     * Function.
     * @var callable
     */
    private $func;

    /**
     * Function arguments.
     * @var array
     */
    private $funcArgs = [];

    /**
     * Once.
     * @var bool
     */
    private $once = true;

    /**
     * Constructor.
     * @param string        $name
     * @param callable|null $func
     * @param array|null    $funcArgs
     * @param bool          $once
     */
    final public function __construct(string $name,
        callable $func = null, array $funcArgs = null, bool $once = true)
    {
        $this->name = $name;

        if ($func) $this->setFunc($func);
        if ($funcArgs) $this->setFuncArgs($funcArgs);

        $this->once = $once;
    }

    /**
     * Set name.
     * @param  string $name
     * @return self
     */
    final public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     * @return string
     */
    final public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set func.
     * @param  callable $func
     * @return self
     */
    final public function setFunc(callable $func): self
    {
        $this->func = $func;

        return $this;
    }

    /**
     * Get func.
     * @return callable
     */
    final public function getFunc(): callable
    {
        return $this->func;
    }

    /**
     * Set func args.
     * @param  array $funcArgs
     * @return self
     */
    final public function setFuncArgs(array $funcArgs): self
    {
        $this->funcArgs = $funcArgs;

        return $this;
    }

    /**
     * Get func args.
     * @return callable
     */
    final public function getFuncArgs(): array
    {
        return $this->funcArgs;
    }

    /**
     * Set once.
     * @param  bool $once
     * @return self
     */
    final public function setOnce(bool $once): self
    {
        $this->once = $once;

        return $this;
    }

    /**
     * Get once.
     * @return bool
     */
    final public function getOnce(): bool
    {
        return $this->once;
    }

    /**
     * Is once.
     * @return bool
     */
    final public function isOnce(): bool
    {
        return ($this->once == true);
    }
}
