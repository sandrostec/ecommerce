<?php 

namespace Hcode;
    
    class PageAdmin extends Page{ /// (extends) A classe extende da classe page....ele herda as atribuições..
 
        
        public function __construct($opts = array(), $tpl_dir = "/views/admin/"){ /// cria um metodo magico construtor para rebercer  as opções que por padrao e arry...

            ///$tpl_dir = para cair na past ADM
	
            parent::__construct($opts, $tpl_dir);
		
            //aqui chama o construtor da classe pai ou classe base - se nao passar nenhum vai ser o padrao $opts, $tpl_dir
        }
	}
 ?>

