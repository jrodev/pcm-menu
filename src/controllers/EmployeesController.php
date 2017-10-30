<?php

namespace App\Controllers;

use Libs\DataLoader;
use Spatie\ArrayToXml\ArrayToXml;

/**
 * Acciones para el Controlador Empleados
 */
class EmployeesController
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

    /**
     * Lista los empleados segun los paramentros que se pasen por url
     * @param  Request $req
     * @param  Response $resp
     * @param  Array $arg  Contiene las variables que se pasan por url en la ruta
     * @return String renderiza usando Twig
     */
    public function listar($req, $resp, $args)
    {
        //var_dump($args); exit;
        $aKeyVal = [];
        $aViewParams = [];
        // SI es POST buscar
        if ( $req->isPost() ) {
            $aPost = $req->getParsedBody();
            $hasEmail = key_exists('email', $aPost);
            if($hasEmail) {
                $aKeyVal = ['email', $aPost['email']];
                $aViewParams['email'] = $aPost['email'];
            }
        }
        // Si es busqueda por ID
        if (key_exists('id', $args)) {
            $aKeyVal = ['id', $args['id']];
            $aViewParams['id'] = $args['id'];
            $aResult = $this->_getEmployeesEquals($aKeyVal);
            // asignando solo el empleado
            if (count($aResult)) { $aViewParams['employee'] = $aResult[0]; }
        } else {
            // asignando todos los empledos
            $aViewParams['employees'] = $this->_getEmployeesEquals($aKeyVal);
        }
        // SINO rederizando la vista
        return $this->view->render($resp, 'views/empleados/listar.twig', $aViewParams);
    }

    public function listarXML($req, $resp, $args)
    {
        //var_dump($args);
        $aArgs = [];
        $aArgs[] = key_exists('min', $args)?$args['min']:0;
        $aArgs[] = key_exists('max', $args)?$args['max']:0;
        $res = $this->_getEmployeesBySalary($aArgs);
        //var_dump($res);exit;

        $result = ArrayToXml::convert($res);

        $resp->write($result);
        $resp = $resp->withHeader('Content-Type', 'application/xml; charset=utf-8');
        return $resp;
    }

    /**
     * Metodo privado que retorna un json como array segun criterio de busqueda
     * @param  array  $aKeyVal de la forma: ['column','value']
     * @return Array          array de resultados de converitir el json file.
     */
    private function _getEmployeesEquals($aKeyVal=[])
    {
        // All result
        $arrEmpl = $this->dataLoader->load('employees');
        // Si no se manda el array keyVal se devuelve todo
        if (count($aKeyVal)!=2) { return $arrEmpl; }

        $aResult = [];
        $column = trim($aKeyVal[0]);
        $value  = trim($aKeyVal[1]);

        foreach ($arrEmpl as $index => $row) {
            if( key_exists($column, $row) && $row[$column]==$value ){
                $aResult[] = $row;
            }
        }
        return $aResult;
    }

    private function _getEmployeesBySalary($aArgs=[])
    {
        // All result
        $arrEmpl = $this->dataLoader->load('employees');
        // cantidad de elementos en $aArgs
        $lenArg = count($aArgs);
        // Si no se manda el array keyVal se devuelve todo
        if (!$lenArg) { return $arrEmpl; }

        $aResult = ['employee'=>[]];
        $min  = ($lenArg>=1)?((int)trim($aArgs[0])):0;
        $max  = ($lenArg==2)?((int)trim($aArgs[1])):0;
        $num  = 0;
        foreach ($arrEmpl as $index => $row) {

            if ( key_exists('salary', $row) ) {
                // preg_match('/\$(\d*),?(\d*)\.?(\d*)/', '$187,89.980', $matches, 0);
                // Array ( [0] => $187,89.980 [1] => 187 [2] => 89 [3] => 980 )
                preg_match('/\$(\d*),?(\d*)\.?(\d*)/', trim($row['salary']), $aSalary, 0);
                $dSalary = (float)($aSalary[1].$aSalary[2].'.'.$aSalary[3]);

                $bGt = ($min!==0)?($min<$dSalary):TRUE; // mayor que
                $bLt = ($max!==0)?($dSalary<$max):TRUE; // menor que
                //var_dump($min,$max,$aSalary, $dSalary, $bGt, $bLt); echo "<br>";
                if ($bGt && $bLt) { $aResult['employee'][] = $row; }
            }
        }
        return $aResult;
    }

    private function _arrayToXML($data, &$xmlData)
    {
        foreach( $data as $key => $value ) {
            if( is_numeric($key) ){
                $key = 'item'.$key; //dealing with <0/>..<n/> issues
            }
            if( is_array($value) ) {
                $subNode = $xmlData->addChild($key);
                $this->_arrayToXML($value, $subNode);
            } else {
                $xmlData->addChild("$key",htmlspecialchars("$value"));
            }
         }
    }

}
