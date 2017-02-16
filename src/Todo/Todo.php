<?php

namespace Damianopetrungaro\MongoTodo\Todo;


use Damianopetrungaro\MongoTodo\Todo\ValueObject\TodoId;

class Todo
{
    const STATUS_DONE = 'done';
    const STATUS_TODO = 'todo';

    /**
     * @var TodoId
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $status;

    /**
     * Todo constructor.
     *
     * @param TodoId $todoId
     * @param string $title
     */
    public function __construct(TodoId $todoId, string $title)
    {
        $this->id = $todoId;
        $this->title = $title;
        $this->status = self::STATUS_TODO;
    }

    /**
     * Edit todo title
     *
     * @param string $title
     *
     * @return void
     */
    public function editTitle(string $title) : void
    {
        $this->title = $title;
    }

    /**
     * Set todo as done
     *
     * @return void
     */
    public function setAsDone() : void
    {
        $this->status = self::STATUS_DONE;
    }

    /**
     * Set todo as todo
     *
     * @return void
     */
    public function setAsTodo() : void
    {
        $this->status = self::STATUS_TODO;
    }
}