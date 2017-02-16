<?php

declare(strict_types = 1);

/** @var \Slim\Container $container */
$container = require __DIR__ . '/../bootstrap/app.php';

$app = new \Slim\App($container);

$app->get('/todos', function ($request, $response, $args) use ($container) {
    $usecase = $container->get('list.todos.usecase');
    return $usecase();
});

$app->post('/todos', function ($request, $response, $args) use ($container) {
    $usecase = $container->get('add.todos.usecase');
    return $usecase($request);
});

$app->get('/todos/{id}', function ($request, $response, $args) use ($container) {
    $usecase = $container->get('get.todos.usecase');
    return $usecase($args['id']);
});

$app->put('/todos/{id}', function ($request, $response, $args) use ($container) {
    $usecase = $container->get('edit.todos.usecase');
    return $usecase($args['id'], $request);
});

$app->delete('/todos/{id}', function ($request, $response, $args) use ($container) {
    $usecase = $container->get('delete.todos.usecase');
    return $usecase($args['id'], $request);
});

$app->run();