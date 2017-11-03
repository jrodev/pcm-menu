<?php
namespace App\Controllers;

use Libs\ChangeString;
use Libs\CompleteRange;
use Libs\ClearPar;
/**
 * Acciones para el Controlador Home
 */
class CocinaController
{
    protected $view;
    protected $router;
    protected $dataLoader;

    /**
     * La instanciación del controller se hace en dependencies.php
     * @param Twig $view     Motor de plantillas
     * @param Routes $router   Ruteo
     * @param DataLoader $loadJson Carga el archivo employees,json
     */
    public function __construct($view, $router, $loadJson)
    {
        $this->view = $view;
        $this->router = $router;
        $this->dataLoader = $loadJson;
    }

    public function index ($req, $resp)
    {
        //exit;
        return $this->view->render($resp, 'views/cocina/index.twig');
    }

}
