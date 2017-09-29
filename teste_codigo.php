<?php


class Pessoa {
    
    public $nome, $sexo;  /// atributo
    
    public function falar(){
        
  return "o meu nome é".$this->nome." E sou ".$this->sexo;


}
}
$glaucio = new Pessoa();
$glaucio->nome = " Sandro";
$glaucio->sexo = " Masculino";

echo $glaucio->falar();

?>