<?php

namespace Damianopetrungaro\MongoTodo\Todo;


final class TodoCollection
{
    /**
     * Array of request parameters.
     *
     * @var Todo[] $todos
     */
    protected $todos;

    /**
     * Request constructor.
     *
     * @param Todo[] $todos Populate the todos.
     */
    public function __construct(array $todos = [])
    {
        foreach ($todos as $key => $todo) {
            if ($todo instanceof Todo) {
                throw new \InvalidArgumentException("The todo with key $todo is not an instance of Todo class");
            }
        }

        $this->todos = $todos;
    }

    /**
     * @inheritdoc
     */
    public function all() : array
    {
        return $this->todos;
    }

    /**
     * {@inheritDoc}
     */
    public function clear() : TodoCollection
    {
        $clone = clone $this;
        $clone->todos = [];

        return $clone;
    }

    /**
     * {@inheritDoc}
     */
    public function get($key, $default = null)
    {
        return isset($this->todos[$key]) ? $this->todos[$key] : $default;
    }

    /**
     * {@inheritDoc}
     */
    public function has($key) : bool
    {
        return isset($this->todos[$key]);
    }

    /**
     * {@inheritDoc}
     */
    public function keys() : array
    {
        return array_keys($this->todos);
    }

    /**
     * {@inheritDoc}
     */
    public function length() : int
    {
        return count($this->todos);
    }

    /**
     * {@inheritDoc}
     */
    public function mergeWith(TodoCollection ...$collections) : TodoCollection
    {
        $clone = clone $this;
        foreach ($collections as $collection) {
            $clone->todos = array_merge($clone->all(), $collection->all());
        }

        return $clone;
    }

    /**
     * {@inheritDoc}
     */
    public function without($key) : TodoCollection
    {
        $clone = clone $this;
        unset($clone->todos[$key]);

        return $clone;
    }

    /**
     * {@inheritDoc}
     */
    public function with(Todo $todo, $key = null) : TodoCollection
    {
        $clone = clone $this;
        (func_num_args() == 2) ? $clone->todos[$key] = $todo : $clone->todos[] = $todo;

        return $clone;
    }

    /**
     * {@inheritDoc}
     */
    public function values() : array
    {
        return array_values($this->todos);
    }
}