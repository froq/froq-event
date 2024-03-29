<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-event
 */
namespace froq\event;

/**
 * Event class.
 *
 * @package froq\event
 * @class   froq\event\Event
 * @author  Kerem Güneş
 * @since   1.0
 */
class Event
{
    /** Event id (aka UUID). */
    public readonly string $id;

    /** Event name (aka type). */
    public readonly string $name;

    /** Event target for objects only. */
    public readonly object|null $target;

    /** Return value of callback. */
    private mixed $returnValue = null;

    /** Propagation-stopped status. */
    private bool $propagationStopped = false;

    /** Once/data/fired states. */
    private \State $state;

    /**
     * Link to event manager for remove() etc.
     * @internal
     */
    private ?EventManager $manager = null;

    /**
     * Constructor.
     *
     * @param string   $name
     * @param callable $callback
     * @param mixed ...$options  For "target", "once", "data" stuff.
     */
    public function __construct(string $name, callable $callback, mixed ...$options)
    {
        $id           = uuid();
        $name         = self::normalizeName($name);
        $callback     = self::normalizeCallback($callback);

        $this->id     = $id;
        $this->name   = $name;
        $this->target = $this->getTargetObject($options, $callback);
        $this->state  = $this->getStateObject($options);

        // Store this event with callback.
        EventStorage::add($this, $callback);
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        // Unstore this event.
        EventStorage::remove($this);
    }

    /**
     * Dynamic setter for states in callback (eg: fn($e, $foo) => $e->foo = $foo).
     *
     * @magic
     */
    public function __set(string $name, mixed $value): void
    {
        $this->state->set($name, $value);
    }

    /**
     * Dynamic getter for states in callback (eg: fn($e) => $e->target).
     *
     * @throws froq\event\EventException
     * @magic
     */
    public function __get(string $name): mixed
    {
        return $this->state->has($name) ? $this->state->get($name)
             : throw EventException::forNoStateFound($name);
    }

    /**
     * Dynamic caller for callback (eg: $event(), $event(foo: 123)).
     *
     * @magic
     */
    public function __invoke(mixed ...$arguments): mixed
    {
        if (!$this->isPropagationStopped()) {
            $callback = EventStorage::get($this);

            $this->returnValue = $callback($this, ...$arguments);

            // Remove from manager's stack if once.
            if ($this->state->once) {
                EventStorage::remove($this);

                $this->manager?->remove($this->name);
            }
        }

        // Increase fired count.
        $this->state->fired += 1;

        return $this->returnValue;
    }

    /**
     * @alias __invoke()
     */
    public function fire(...$args)
    {
        return $this(...$args);
    }

    /**
     * Set return value.
     *
     * @param  mixed $returnValue
     * @return void
     */
    public function setReturnValue(mixed $returnValue): void
    {
        $this->returnValue = $returnValue;
    }

    /**
     * Get return value.
     *
     * @return mixed
     */
    public function getReturnValue(): mixed
    {
        return $this->returnValue;
    }

    /**
     * Prevent further propagation of this event and invocation more than one.
     *
     * @return void
     */
    public function stopPropagation(): void
    {
        $this->propagationStopped = true;
    }

    /**
     * Get propagation-stopped status.
     *
     * @return bool
     */
    public function isPropagationStopped(): bool
    {
        return $this->propagationStopped;
    }

    /**
     * Set manager.
     *
     * @param  froq\event\EventManager
     * @return void
     */
    public function setManager(EventManager $manager): void
    {
        $this->manager = $manager;
    }

    /**
     * Get manager.
     *
     * @return froq\event\EventManager|null
     */
    public function getManager(): EventManager|null
    {
        return $this->manager;
    }

    /**
     * Normalize event name.
     *
     * @param  string $name
     * @return string
     */
    public static function normalizeName(string $name): string
    {
        $name = trim($name);

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

    /**
     * Get target object from options or callback (closure's this) for simulating
     * JavaScript's event target stuff.
     */
    private function getTargetObject(array $options, callable $callback): object|null
    {
        return $options['target'] // When given.
            ?? (new \ReflectionCallable($callback))->getClosureThis();
    }

    /**
     * Get state object initializing with options for simulating JavaScript's event
     * once & data stuff (with defaults).
     */
    private function getStateObject(array $options): \State
    {
        return new \State(
            once: $options['once'] ?? true,
            data: $options['data'] ?? null,
            fired: null
        );
    }
}
