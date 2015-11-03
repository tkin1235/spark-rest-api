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
//As a manager, I want to be able to assign a shift, by changing the employee that will work a shift.
$app->addRoutes(function (Spark\Router $r) {
    $r->get('/hello[/{employeeID}]', 'Spark\Project\Domain\Hello');
    $r->get('/hello/{startTime}/{endTime}', 'Spark\Project\Domain\Hello');
    $r->get('/hello/{employeeID}/{startTime}/{endTime}', 'Spark\Project\Domain\Hello');

    $r->get('/story4/{employeeID}', 'Spark\Project\Domain\Story4');
    $r->put('/story5/{employeeID}/{startTime}/{endTime}/{break}', 'Spark\Project\Domain\Story5');
    $r->get('/story6/{startTime}/{endTime}' , 'Spark\Project\Domain\Story6');
    $r->put('/story7/{shiftID}/{newStartTime}/{newEndTime}' , 'Spark\Project\Domain\Story7');
    $r->put('/story8/{shiftID}/{employeeID}' , 'Spark\Project\Domain\Story8');

});

$app->run();
