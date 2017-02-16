<?php

namespace Damianopetrungaro\MongoTodo\Todo\UseCase;


use Damianopetrungaro\MongoTodo\Todo\Mapper\TodoMapper;
use Damianopetrungaro\MongoTodo\Todo\Repository\Exception\PersistenceException;
use Damianopetrungaro\MongoTodo\Todo\Repository\TodoRepositoryInterface;
use Damianopetrungaro\MongoTodo\Todo\Todo;
use Slim\Http\Request;
use Slim\Http\Response;

final class AddTodo
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

    public function __invoke(Request $request)
    {
        $params = $request->getParsedBody();
        $id = $this->todoRepository->nextId();
        $title = $params['title'];

        $todo = new Todo($id, $title);

        try {
            $this->todoRepository->add($todo);
        } catch (PersistenceException $e) {
            return $this->response->withJson($e->getMessage(), 500);
        }

        return $this->response->withJson($this->todoMapper->toArray($todo));
    }
}