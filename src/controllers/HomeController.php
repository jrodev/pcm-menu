<?php
namespace App\Controllers;

use Libs\ChangeString;
use Libs\CompleteRange;
use Libs\ClearPar;
/**
 * Acciones para el Controlador Home
 */
class HomeController
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

    public function index($req, $resp, $args)
    {
        // echo "home!!";
        // echo (new ChangeString)->build("cAB10Cefg1ooo9k");
        // echo "<br>".implode('|', (new CompleteRange)->build([5,7,14]) );
        $aViewParams = [
            'ChangeString' => [
                'input'=>'cAB10Cefg1ooo9k',
                'output'=>(new ChangeString)->build("cAB10Cefg1ooo9k"),
            ],
            'CompleteRange' => [
                'input'=>'[8,13,19,23]',
                'output'=>'['.implode(',', (new CompleteRange)->build([8,13,19,23]) ).']',
            ],
            'ClearPar' => [
                'input'=>'(()()()()(()))))())((())',
                'output'=> implode('', (new ClearPar)->build('(()()()()(()))))())((())') ),
            ]
        ];

        return $this->view->render($resp, 'views/home/index.twig', $aViewParams);

    }

}
