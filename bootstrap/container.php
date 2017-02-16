<?php

$entries = [];

$entries['settings']['displayErrorDetails'] = true;

$entries['app.logger'] = function () {
    return new Monolog\Logger('app', [new Monolog\Handler\RotatingFileHandler(getenv('LOG_DIR') . '/app-log')]);
};

$entries['app.connection'] = function () {
    return new \MongoDB\Client(getenv('MONGODB_URL_CONNECTION'));
};

$entries['errorHandler'] = function (\Slim\Container $container) {
    return function ($request, $response, $exception) use ($container) {
        $uuid = \Ramsey\Uuid\Uuid::uuid1();
        /** @var Exception $exception */
        $container->get('app.logger')->addError($exception->getMessage(), [
            'uuid' => $uuid->toString(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ]);

        /** @var \Slim\Http\Response $response */
        return $response->withJson('Something went wrong', (getenv('DEBUG')) ? [$exception->getMessage()] : ['Contact the admin', 500, [], $uuid]);
    };
};

$entries['todos.repository'] = function (\Slim\Container $container) {
    return new \Damianopetrungaro\MongoTodo\Todo\Repository\MongoTodoRepository(
        $container->get('app.connection'),
        new Damianopetrungaro\MongoTodo\Todo\Mapper\TodoMapper(),
        getenv('DATABASE_NAME')
    );
};

$entries['list.todos.usecase'] = function (\Slim\Container $container) {
    return new \Damianopetrungaro\MongoTodo\Todo\UseCase\ListTodo(
        $container->get('todos.repository'),
        new Damianopetrungaro\MongoTodo\Todo\Mapper\TodoMapper(),
        $container->get('response')
    );
};

$entries['add.todos.usecase'] = function (\Slim\Container $container) {
    return new \Damianopetrungaro\MongoTodo\Todo\UseCase\AddTodo(
        $container->get('todos.repository'),
        new Damianopetrungaro\MongoTodo\Todo\Mapper\TodoMapper(),
        $container->get('response')
    );
};

$entries['get.todos.usecase'] = function (\Slim\Container $container) {
    return new \Damianopetrungaro\MongoTodo\Todo\UseCase\GetTodo(
        $container->get('todos.repository'),
        new Damianopetrungaro\MongoTodo\Todo\Mapper\TodoMapper(),
        $container->get('response')
    );
};

$entries['edit.todos.usecase'] = function (\Slim\Container $container) {
    return new \Damianopetrungaro\MongoTodo\Todo\UseCase\EditTodo(
        $container->get('todos.repository'),
        new Damianopetrungaro\MongoTodo\Todo\Mapper\TodoMapper(),
        $container->get('response')
    );
};

$entries['delete.todos.usecase'] = function (\Slim\Container $container) {
    return new \Damianopetrungaro\MongoTodo\Todo\UseCase\DeleteTodo(
        $container->get('todos.repository'),
        $container->get('response')
    );
};

return new \Slim\Container($entries);