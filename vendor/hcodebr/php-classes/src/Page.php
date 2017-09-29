<?php
namespace Hcode;

use Rain\Tpl;

class Page{
    private $tpl;
    private $options = [];
    private $defaults = [
        "header"=>true,
        "footer"=>true,
        "data"=>[]
    ];
    
    //método contrutor que é métod mágico
    public function __construct($opts = array(), $tpl_dir = "/views/"){ // essa variavel ($tpl_dir) por padrao apontara para o diretorio /views/ 
        ///$this->defaults["data"]["session"] = $SESSION;
        $this->options = array_merge($this->defaults, $opts); //se $opst estiver vazio vai mostrar a $defaults | merge vai mesclar os dois atributos
        
        
        // configuração do template
        $config = array(
            "tpl_dir"   => $_SERVER["DOCUMENT_ROOT"].$tpl_dir, //$_SERVER["DOCUMENT_ROOT"] vai trazer onde esta a pasta o diretório root
            "cache_dir" => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
            "debug"     => false // set to false to improve the speed
        );
        
        Tpl::configure($config);
        
        $this->tpl = new Tpl();
        
        $this->setData($this->options["data"]); //chamando método abaixo
        
        if($this->options["header"] === true) $this->tpl->draw("header");//desenha o tamplate na tela
    }
    
    //método que se repete no foreach criamos um método pra ele
    private function setData($data = array()){
        foreach($data as $key => $value){
            $this->tpl->assign($key, $value); //é o template instanciado na linha 25
        }
    }
    
    //método do conteúdo
    public function setTpl($name, $data = array(), $returnHTML = false){ //nome do template, array vazio, o e html false
        $this->setData($data);
        
        return $this->tpl->draw($name, $returnHTML); //passa o nome do template
    }
    
    //método destrutor que é métod mágico
    public function __destruct(){
        if($this->options["footer"] === true) $this->tpl->draw("footer");
    }
}
