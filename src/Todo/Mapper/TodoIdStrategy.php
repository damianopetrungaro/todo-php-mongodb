<?php

namespace Damianopetrungaro\MongoTodo\Todo\Mapper;


use Damianopetrungaro\MongoTodo\Todo\ValueObject\TodoId;
use Ramsey\Uuid\Uuid;
use Zend\Hydrator\Strategy\StrategyInterface;

class TodoIdStrategy implements StrategyInterface
{
    public function extract($value)
    {
        /** @var TodoId $value */
        return $value->__toString();
    }

    public function hydrate($value)
    {
        return new TodoId(Uuid::fromString($value));
    }
}