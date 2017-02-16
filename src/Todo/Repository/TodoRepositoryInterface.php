<?php

namespace Damianopetrungaro\MongoTodo\Todo\Repository;


use Damianopetrungaro\MongoTodo\Todo\Todo;
use Damianopetrungaro\MongoTodo\Todo\TodoCollection;
use Damianopetrungaro\MongoTodo\Todo\ValueObject\TodoId;

interface TodoRepositoryInterface
{
    /**
     * Add a todo to persistence
     *
     * @param Todo $todo
     *
     * @return void
     */
    public function add(Todo $todo) : void;

    /**
     * Edit a specific Todo by TodoId
     *
     * @param TodoId $todoId
     * @param Todo $todo
     *
     * @return void
     */
    public function edit(TodoId $todoId, Todo $todo) : void;

    /**
     * Check if exists by specific Todo by TodoId
     *
     * @param TodoId $todoId
     *
     * @return bool
     */
    public function existsById(TodoId $todoId) : bool;

    /**
     * Get a specific Todo by TodoId
     *
     * @param TodoId $todoId
     *
     * @return Todo
     */
    public function getById(TodoId $todoId) : Todo;

    /**
     * Get the next available id
     *
     * @return TodoId
     */
    public function nextId() : TodoId;

    /**
     * Return all the todos
     *
     * @return TodoCollection
     */
    public function list() : TodoCollection;
    
    /**
     * Delete specific Todo by TodoId
     *
     * @param TodoId $todoId
     *
     * @return void
     */
    public function remove(TodoId $todoId) : void;
}