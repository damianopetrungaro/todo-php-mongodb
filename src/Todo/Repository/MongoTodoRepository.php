<?php

namespace Damianopetrungaro\MongoTodo\Todo\Repository;


use Damianopetrungaro\MongoTodo\Todo\Mapper\TodoMapper;
use Damianopetrungaro\MongoTodo\Todo\Repository\Exception\NotFoundException;
use Damianopetrungaro\MongoTodo\Todo\Repository\Exception\PersistenceException;
use Damianopetrungaro\MongoTodo\Todo\Todo;
use Damianopetrungaro\MongoTodo\Todo\TodoCollection;
use Damianopetrungaro\MongoTodo\Todo\ValueObject\TodoId;
use MongoDB\Client;
use MongoDB\Collection;
use Ramsey\Uuid\Uuid;

final class MongoTodoRepository implements TodoRepositoryInterface
{
    /**
     * @var Client
     */
    private $mongo;
    /**
     * @var string
     */
    private $databaseName;
    /**
     * @var TodoMapper
     */
    private $todoMapper;

    /**
     * MongoTodoRepository constructor.
     *
     * @param Client $mongo
     * @param TodoMapper $todoMapper
     * @param string $databaseName
     */
    public function __construct(Client $mongo, TodoMapper $todoMapper, string $databaseName)
    {
        $this->mongo = $mongo;
        $this->databaseName = $databaseName;
        $this->todoMapper = $todoMapper;
    }

    /**
     * {@inheritdoc}
     */
    public function nextId() : TodoId
    {
        return new TodoId(Uuid::uuid1());
    }

    /**
     * {@inheritdoc}
     */
    public function add(Todo $todo) : void
    {
        $extractedTodo = $this->prepareIdField($this->todoMapper->toArray($todo));
        try {
            $this->getTodoCollection()->insertOne($extractedTodo);
        } catch (\Exception $e) {
            throw new PersistenceException('Error storing a todo', $e->getCode(), $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function edit(TodoId $todoId, Todo $todo) : void
    {
        $extractedTodo = $this->prepareIdField($this->todoMapper->toArray($todo));
        try {
            $result = $this->getTodoCollection()->updateOne(['_id' => $extractedTodo['_id']], ['$set' => $extractedTodo]);
        } catch (\Exception $e) {
            throw new PersistenceException('Error updating a todo', $e->getCode(), $e);
        }

        if (!$result->getMatchedCount()) {
            throw new PersistenceException('No todo were updated');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function existsById(TodoId $todoId) : bool
    {
        try {
            return (bool)$this->getTodoCollection()->count(['_id' => $todoId->__toString()]);
        } catch (\Exception $e) {
            throw new PersistenceException('Error checking tool existence');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getById(TodoId $todoId) : Todo
    {
        try {
            $todoDocument = $this->getTodoCollection()->findOne(['_id' => $todoId->id()->__toString()]);
        } catch (\Exception $e) {
            throw new PersistenceException('Error retrieving tool');
        }
        if (!$todoDocument) {
            throw new NotFoundException();
        }

        return $this->todoMapper->toObject((array)$todoDocument);
    }

    /**
     * {@inheritdoc}
     */
    public function list() : TodoCollection
    {
        $results = $this->getTodoCollection()->find();
        $todoCollection = new TodoCollection();
        foreach ($results as $result) {
            $todoCollection = $todoCollection->with($this->todoMapper->toObject((array)$result));
        }

        return $todoCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function remove(TodoId $todoId) : void
    {
        try {
            $result = $this->getTodoCollection()->deleteOne(['_id' => $todoId->__toString()]);
        } catch (\Exception $e) {
            throw new PersistenceException('Error deleting a todo', $e->getCode(), $e);
        }

        if (!$result->getDeletedCount()) {
            throw new PersistenceException('No todo were deleted');
        }
    }

    /**
     * Return the Todo collection
     */
    private function getTodoCollection() : Collection
    {
        return $this->mongo->selectCollection($this->databaseName, 'todos');
    }

    /**
     * Replace id with _id for mongoDB
     *
     * @param array $fields
     *
     * @return array
     */
    private function prepareIdField(array $fields)
    {
        $fields['_id'] = $fields['id'];
        unset($fields['id']);
        return $fields;
    }
}