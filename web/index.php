<?php

require __DIR__ . '/../vendor/autoload.php';

$app = Spark\Application::boot();

$app->setMiddleware([
    'Relay\Middleware\ResponseSender',
    'Spark\Handler\ExceptionHandler',
    'Spark\Handler\RouteHandler',
    'Spark\Handler\ContentHandler',
    'Spark\Handler\ActionHandler',
]);

$app->addRoutes(function (Spark\Router $r) {
    $r->get('/hello[/{employeeID}]', 'Spark\Project\Domain\Hello');
    $r->get('/hello/{startTime}/{endTime}', 'Spark\Project\Domain\Hello');
    $r->get('/hello/{employeeID}/{startTime}/{endTime}', 'Spark\Project\Domain\Hello');


    $r->get('/story4/{employeeID}', 'Spark\Project\Domain\Story4');
    $r->put('/story5/{employeeID}/{startTime}/{endTime}/{break}', 'Spark\Project\Domain\Story5');

    $r->get('/story6/{startTime}/{endTime}' , 'Spark\Project\Domain\Story6');
    $r->put('/story7/{shiftID}/{newStartTime}/{newEndTime}' , 'Spark\Project\Domain\Story7');
    
    $r->post('/hello[/{name}]', 'Spark\Project\Domain\Hello');
    $r->head('/head/{name}','Spark\Project\Domain\Hello');
});

$app->run();
