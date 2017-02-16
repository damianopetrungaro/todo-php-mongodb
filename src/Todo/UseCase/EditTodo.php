<?php

namespace Damianopetrungaro\MongoTodo\Todo\UseCase;


use Damianopetrungaro\MongoTodo\Todo\Mapper\TodoMapper;
use Damianopetrungaro\MongoTodo\Todo\Repository\Exception\NotFoundException;
use Damianopetrungaro\MongoTodo\Todo\Repository\Exception\PersistenceException;
use Damianopetrungaro\MongoTodo\Todo\Repository\TodoRepositoryInterface;
use Damianopetrungaro\MongoTodo\Todo\ValueObject\TodoId;
use Ramsey\Uuid\Uuid;
use Slim\Http\Request;
use Slim\Http\Response;

final class EditTodo
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

    public function __invoke($id, Request $request)
    {
        try {
            $todoId = new TodoId(Uuid::fromString($id));
        } catch (\InvalidArgumentException $e) {
            return $this->response->withStatus(404);
        }

        try {
            $todo = $this->todoRepository->getById($todoId);
            $todo->editTitle($request->getParsedBodyParam('title'));
            $this->todoRepository->edit($todoId, $todo);
        } catch (NotFoundException $e) {
            return $this->response->withStatus(404);
        } catch (PersistenceException $e) {
            return $this->response->withJson($e->getMessage(), 500);
        }

        return $this->response->withJson($this->todoMapper->toArray($todo));
    }
}