<?php

namespace Damianopetrungaro\MongoTodo\Todo\UseCase;


use Damianopetrungaro\MongoTodo\Todo\Mapper\TodoMapper;
use Damianopetrungaro\MongoTodo\Todo\Repository\Exception\PersistenceException;
use Damianopetrungaro\MongoTodo\Todo\Repository\TodoRepositoryInterface;
use Slim\Http\Response;

final class ListTodo
{
    /**
     * @var TodoRepositoryInterface
     */
    private $todoRepository;
    /**
     * @var Response
     */
    private $response;
    /**
     * @var TodoMapper
     */
    private $todoMapper;

    /**
     * ListTodo constructor.
     * @param TodoRepositoryInterface $todoRepository
     * @param TodoMapper $todoMapper
     * @param Response $response
     */
    public function __construct(TodoRepositoryInterface $todoRepository, TodoMapper $todoMapper, Response $response)
    {
        $this->todoRepository = $todoRepository;
        $this->response = $response;
        $this->todoMapper = $todoMapper;
    }

    public function __invoke()
    {
        try {
            $todoCollection = $this->todoRepository->list();
        } catch (PersistenceException $e) {
            return $this->response->withJson($e->getMessage(), 500);
        }

        $todos = [];
        foreach ($todoCollection->all() as $todo) {
            $todos[] = $this->todoMapper->toArray($todo);
        }

        return $this->response->withJson($todos);
    }
}