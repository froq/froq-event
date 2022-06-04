<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-event
 */
declare(strict_types=1);

namespace froq\event;

/**
 * Event class.
 *
 * @package froq\event
 * @object  froq\event\Event
 * @author  Kerem Güneş
 * @since   1.0
 */
class Event
{
    /** @var string */
    public readonly string $name;

    /** @var Closure */
    public readonly \Closure $callback;

    /** @var bool */
    public readonly bool $once;

    /**
     * Stack (link to event stack for remove() etc).
     *
     * @var froq\event\EventStack|null
     * @see froq\event\Firer::fire()
     */
    private ?EventStack $stack = null;

    /**
     * Constructor.
     *
     * @param string   $name
     * @param callable $callback
     * @param bool     $once
     */
    public function __construct(string $name, callable $callback, bool $once = true)
    {
        $this->name     = EventUtil::normalizeName($name);
        $this->callback = EventUtil::normalizeCallback($callback);
        $this->once     = $once;
    }

    /**
     * Run event.
     *
     * @param  mixed ...$args
     * @return mixed|null
     * @since  4.0
     */
    public function __invoke(mixed ...$args): mixed
    {
        return Firer::fire($this, ...$args);
    }

    /**
     * Set stack.
     *
     * @return void
     * @since  6.0
     */
    public function setStack(EventStack $stack): void
    {
        $this->stack = $stack;
    }

    /**
     * Get stack.
     *
     * @return froq\event\EventStack|null
     * @since  6.0
     */
    public function getStack(): EventStack|null
    {
        return $this->stack;
    }

    /**
     * @alias __invoke()
     */
    public function fire(...$args)
    {
        return $this->__invoke(...$args);
    }
}
