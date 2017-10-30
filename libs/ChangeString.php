<?php
namespace Libs;

class ChangeString 
{
	//[NO USADO]
	private $AZaz = [];
	
	public function __construct () {
		/*....*/
		//$this->doArrayAZaz();
	}
	
	// [NO USADO] Llenando array de abecedario Min y May (DEPRECATE)
	private function doArrayAZaz(){
		//De la A-Z y de la a-z;
		$A=65; $Z=90; $a=97; $z=122; $enies=[78=>"Ñ",110=>"ñ"];
		for($i=$A; $i<=$z; $i++){
			if(!($i>$Z && $i<$a)){
				array_push($this->AZaz, chr($i));
				// Si llegamos a N o n agregamos Ñ o ñ
				if($i==78 || $i==110){
					array_push($this->AZaz, $enies[$i]);
				}
			}
		}
	}
	
	// Mensajes de error para funcion preg_match
	private function msgPregMatchError() {
		$errors = array(
			PREG_NO_ERROR               => 'Code 0 : No errors',
			PREG_INTERNAL_ERROR         => 'Code 1 : There was an internal PCRE error',
			PREG_BACKTRACK_LIMIT_ERROR  => 'Code 2 : Backtrack limit was exhausted',
			PREG_RECURSION_LIMIT_ERROR  => 'Code 3 : Recursion limit was exhausted',
			PREG_BAD_UTF8_ERROR         => 'Code 4 : The offset didn\'t correspond to the begin of a valid UTF-8 code point',
			PREG_BAD_UTF8_OFFSET_ERROR  => 'Code 5 : Malformed UTF-8 data',
		);
		return $errors[preg_last_error()];
	}
	
	public function build ($str) {
		// String Resultante
		$resStr = "";
		
		// Limites de codigos ASCII de caracteres
		$A=65; $Z=90; $a=97; $z=122;

		// Recorriendo el String de entrada $str
		for($i=0; $i<strlen($str); $i++){
			// caracter actual en el bucle
			$currentChr = $str[$i];

			//Validando si es caracter A-Za-z (es 1 o 0 y no FALSE)
			$regxRes = preg_match('/[A-Za-z]/', $currentChr);
			
			// Si hay errores RegExp
			if($regxRes===FALSE){
				return "bucleN°".$i."[".$this->msgPregMatchError()."]";
			}
			// ELSE 
			$codChr = ord($currentChr);
			// Si es 1
			if($regxRes===1){
				$codChr++;
				if($codChr==(90+1)){ $codChr=$A; }
				if($codChr==(122+1)){ $codChr=$a; }
				$currentChr = chr($codChr);
			}
			
			// Armando string resultante
			$resStr .= $currentChr;
		}	
		return $resStr;
	}
}








