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
 * @object     Froq\Event\Event
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
    private $function;

    /**
     * Function arguments.
     * @var array
     */
    private $functionArguments = [];

    /**
     * Once.
     * @var bool
     */
    private $once = true;

    /**
     * Constructor.
     * @param string     $name
     * @param callable   $function
     * @param array|null $functionArguments
     * @param bool       $once
     */
    public function __construct(string $name, callable $function, array $functionArguments = null,
        bool $once = true)
    {
        $this->name = $name;
        $this->function = $function;

        if ($functionArguments != null) {
            $this->setFunctionArguments($functionArguments);
        }

        $this->once = $once;
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
     * Set function.
     * @param  callable $function
     * @return self
     */
    public function setFunction(callable $function): self
    {
        $this->function = $function;

        return $this;
    }

    /**
     * Get function.
     * @return callable
     */
    public function getFunction(): callable
    {
        return $this->function;
    }

    /**
     * Set function arguments.
     * @param  array $functionArguments
     * @return self
     */
    public function setFunctionArguments(array $functionArguments): self
    {
        $this->functionArguments = $functionArguments;

        return $this;
    }

    /**
     * Get function arguments.
     * @return array
     */
    public function getFunctionArguments(): array
    {
        return $this->functionArguments;
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
        return $this->once === true;
    }
}
