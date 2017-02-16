<?php

namespace Damianopetrungaro\MongoTodo\Todo\Mapper;


use Damianopetrungaro\MongoTodo\Todo\Todo;
use Zend\Hydrator\NamingStrategy\MapNamingStrategy;
use ZendHydratorUtilities\Reflection;

final class TodoMapper
{
    private $hydrator;

    public function __construct()
    {
        $this->hydrator = new Reflection(new MapNamingStrategy([
            '_id' => 'id'
        ]));

        $todoIdStrategy = new TodoIdStrategy();
        $this->hydrator->addStrategy('id', $todoIdStrategy);
        $this->hydrator->addStrategy('_id', $todoIdStrategy);
    }

    /**
     * Transform a Todo object into an array
     *
     * @param Todo $todo
     *
     * @return array
     */
    public function toArray(Todo $todo) : array
    {
        $array = $this->hydrator->extract($todo);

        if (isset($array['_id'])) {
            $array['id'] = $array['_id'];
            unset($array['_id']);
        }

        return $array;
    }

    /**
     * Return a Todo instance from a list of properties
     *
     * @param array $properties
     *
     * @return Todo
     */
    public function toObject(array $properties) : Todo
    {
        return $this->hydrator->hydrate($properties, Todo::class);
    }
}