<?php

namespace Damianopetrungaro\MongoTodo\Todo\ValueObject;


use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class TodoId
{
    /**
     * @var Uuid
     */
    private $id;

    public function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }

    /**
     * Get the id
     *
     * @return Uuid
     */
    public function id() : Uuid
    {
        return $this->id;
    }

    public function __toString() : string
    {
        return $this->id->__toString();
    }
}