<?php
namespace Libs;

class ClearPar
{

	public function __construct ()
	{
		/*....*/
		//$this->doArrayAZaz();
	}

	/**
	 * Mostrando todos los parentesis que tienen inicio ( y fin )
	 * @param  string $str Cadena de parentesis: '(()()()()(()))))())((())'
	 * @return Array      ['()','()',...,'()']
	 */
	public function build ($str='')
	{
		$result = "";
		$matches = [];

		if($str){
			preg_match_all("/(\(\))/", $str, $matches);
		}

		return $matches[0];
	}

}
