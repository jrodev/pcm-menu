<?php
namespace Libs;

class CompleteRange
{

	public function __construct () {
		/*....*/
		//$this->doArrayAZaz();
	}

	public function build ($arrNums) {
		// Array resultante
		$arrRes = [];

		for ( $i=0; $i<count($arrNums); $i++ ) {

			$prevItem = $i ? $arrNums[$i-1] : FALSE; // anterior item
			$currItem = $arrNums[$i]; // actual Item

			$diff = $currItem-$prevItem; // diferencia
			$existRange = (abs($diff)>1); //hay rango
			//var_dump("<br>-->",$currItem,!$existRange,$existRange);
			// Si no existe anterior item y no hay rango
			if (!$prevItem || !$existRange) {
				array_push($arrRes, $currItem);
				continue;
			}

			if ($existRange) {
				for ($j=$prevItem+1; $j<=$currItem; $j++) {
					array_push($arrRes, $j);
				}
				continue;
			}
		}
		return $arrRes;
	}

}
