<?php
// Routes

/*$app->get('/[{name}]', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");
    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});*/

$app->get('[/[home[/index[/]]]]', 'HomeController:index');

// NOTA: en dependencies.php $container['IndiceName'] el indice es
// igual a: IndiceName:controller para Router.
$app->get('/empleados[/[listar[/[{id}[/]]]]]','EmployeesController:listar')->setName('empleados.listar');

// Para buscar por Email
$app->post('/empleados[/[listar[/]]]', 'EmployeesController:listar');

//
//$app->group('/v1', function () {
//$this->group('/employees', function() {
$app->get('/v1/employees/list/xml[/[{min}[/{max}[/]]]]', 'EmployeesController:listarXML');
//});
//});

$app->get('/menu[/index[/]]', 'MenuController:index');

$app->get('/cocina[/index[/]]', 'CocinaController:index');
