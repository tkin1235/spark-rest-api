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

	//API routes
	$app->addRoutes(function (Spark\Router $r) {

		$r->get('/story1[/{employeeID}]', 'Spark\Project\Domain\Story1');
		$r->get('/story2/{startTime}/{endTime}', 'Spark\Project\Domain\Story2');
		$r->get('/story3/{employeeID}/{startTime}/{endTime}', 'Spark\Project\Domain\Story3');
		$r->get('/story4/{employeeID}', 'Spark\Project\Domain\Story4');
		$r->put('/story5/{employeeID}/{startTime}/{endTime}/{break}', 'Spark\Project\Domain\Story5');
		$r->get('/story6/{startTime}/{endTime}', 'Spark\Project\Domain\Story6');
		$r->put('/story7/{shiftID}/{newStartTime}/{newEndTime}', 'Spark\Project\Domain\Story7');
		$r->put('/story8/{shiftID}/{employeeID}', 'Spark\Project\Domain\Story8');

	});

	$app->run();
