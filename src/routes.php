<?php
// Routes

/*$app->get('/[{name}]', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");
    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});*/

$app->get('[/[home[/index[/]]]]', 'HomeController:index');

$app->get('/menu[/index[/]]', 'MenuController:index');

$app->get('/cocina[/index[/]]', 'CocinaController:index');
