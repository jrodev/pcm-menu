<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// Cargando motor de plantillas twig
$container['view'] = function ($c) {
    //nos indica el directorio donde están las plantillas
    $settings = $c->get('settings')['renderer'];
    // puede ser false o el directorio donde se guardará la cache
    $view = new Slim\Views\Twig($settings['template_path'], ['cache' => false]);

    // Vie Helpers
    $twig = $view->getEnvironment();
    $twig->addGlobal('twigGlobalVar', 'Hi Global Var!');
    $twig->addFunction(new Twig_SimpleFunction('baseUrl', function ($all=FALSE) {
        $strBaseUrl = sprintf(
            "%s://%s%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['HTTP_HOST'],
            $all ? $_SERVER['REQUEST_URI'] : "/"
        );
        return $strBaseUrl;
    }));

    // instancia y añade la extensión especifica de slim
    $basePath =  rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');

    $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));
    return $view;
};

// Cargando libreria para cargar JSON
$container['loadJson'] = function ($c) {
	$jsonPath = $c->get('settings')['jsonPath'];
    $capsule = new Libs\DataLoader($jsonPath);
    return $capsule;
};

// Agregegando Controller

$container['MenuController'] = function ($c) {
	return new App\Controllers\MenuController($c['view'], $c['router'], $c['loadJson']);
};

$container['EmployeesController'] = function ($c) {
	return new App\Controllers\EmployeesController($c['view'], $c['router'], $c['loadJson']);
};

$container['HomeController'] = function ($c) {
	return new App\Controllers\HomeController($c['view'], $c['router'], $c['loadJson']);
};

$container['MenuController'] = function ($c) {
	return new App\Controllers\MenuController($c['view'], $c['router'], $c['loadJson']);
};
