<?php

namespace Damianopetrungaro\MongoTodo\Todo\UseCase;


use Damianopetrungaro\MongoTodo\Todo\Repository\Exception\PersistenceException;
use Damianopetrungaro\MongoTodo\Todo\Repository\TodoRepositoryInterface;
use Damianopetrungaro\MongoTodo\Todo\ValueObject\TodoId;
use Ramsey\Uuid\Uuid;
use Slim\Http\Response;

final class DeleteTodo
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
     * ListTodo constructor.
     * @param TodoRepositoryInterface $todoRepository
     * @param Response $response
     */
    public function __construct(TodoRepositoryInterface $todoRepository, Response $response)
    {
        $this->todoRepository = $todoRepository;
        $this->response = $response;
    }

    public function __invoke($id)
    {
        try {
            $todoId = new TodoId(Uuid::fromString($id));
        } catch (\InvalidArgumentException $e) {
            return $this->response->withStatus(404);
        }

        if (!$this->todoRepository->existsById($todoId)) {
            return $this->response->withStatus(404);
        }

        try {
            $this->todoRepository->remove($todoId);
        } catch (PersistenceException $e) {
            return $this->response->withJson($e->getMessage(), 500);
        }

        return $this->response->withStatus(200);
    }
}