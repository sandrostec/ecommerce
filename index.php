<?php

session_start();

require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {
    
    $page = new Page();
    
    $page->setTpl("index");
    
    
});    

//rota para para direcionar para a pagina ADM...
$app->get('/admin', function() {
    
    User::verifyLogin();
   
    $page = new PageAdmin();
    
    $page->setTpl("index");
    
    
});

//rota para direcionar para tela do LOGIN...

$app->get('/admin/login', function() {
    
    $page = new PageAdmin([
    	"header" => false,
    	"footer" => false
    ]);
    $page->setTpl("login"); //Aqui chama o templait do login...

});

//rota para validar o LOGIN...

$app->post('/admin/login', function() {
   
    User::login($_POST["login"], $_POST["password"]);
    
    header ("Location:/admin");
    
    exit;

});

//Redireciona para saida do usuário da tela de admistrador
    $app->get('/admin/logout',function() {
	
        User::logout(); //Clicou em sair, chama metodo static de saída
	
	header("Location: /admin/login");
	
        exit;
        
});
///AULA: 24 - 102
//01:10 minutos.


//Redireciona para saida do usuário da tela de admistrador

// Rota para tela que vai listar todos os usarios via GET...

///Ela vai ter uma Tabela....
$app->get("/admin/users", function() {
       
    User::verifyLogin(); 
    
    $users = User::listAll();  //--> Rotina para execultar o Sql para chamar todos os usarios...
    
    $page = new PageAdmin();
    
    $page->setTpl("users", array(
       "users"=>$users
    
    ));

});

/// Rota para tela que vai criar um novo usario...//(Para pg templaite (users/create)...
$app->get("/admin/users/create", function() {
       
    User::verifyLogin();
    
    $page = new PageAdmin();
    
    $page->setTpl("users-create");

});

// rota para salvar a deletar o usuario no banco de dados...
$app->get("/admin/users/:iduser/delete", function($iduser) {
    User::verifyLogin();
    $user = new User();
	$user->get((int)$iduser);
	$user->delete();
    
    header("Location: /admin/users");

    exit;
         
});


// Para listar alterar senha dos usarios via GET...
$app->get("/admin/users/:iduser", function($iduser)
{
	User::verifyLogin();
	$user = new User();
	$user->get((int)$iduser);
	$page = new PageAdmin();
	$page->setTpl("users-update", array(
		"user"=>$user->getValues()		
	));
});
//03:52 minutos.

///ROTAS PARA DE FATO MANIPULAR OS DADOS.....

// rota para fazer o insert do usuario no banco de dados...
$app->post("/admin/users/create", function() {
    
    User::verifyLogin(); /// verifica se realmente a pessoa esta logado no sistema...
    
    $user = new User(); 
   
    $_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;
    
    $user->setData($_POST);
    
    $user->save();
    
    header("Location: /admin/users");

    exit;
    
});

// rota para fazer o insert do usuario no banco de dados...
$app->post("/admin/users/:iduser", function($iduser) {
    
    User::verifyLogin(); /// verifica se realmente a pessoa esta logado no sistema...
    
    $user = new User(); 
   
    $_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;
    
    $user->get((int)$iduser);
    
    $user->setData($_POST);
    
    $user->update();
    
    header("Location: /admin/users");

    exit;
});
$app->get("/admin/forgot", function() {
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
		]);
	$page->setTpl("forgot");
});
$app->post("/admin/forgot", function(){
	
	$user = User::getForgot($_POST["email"]);
	header("Location: /admin/forgot/sent");
	exit;
	
});
$app->get("/admin/forgot/sent", function(){
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
		]);
	$page->setTpl("forgot-sent");
	
});
$app->get("/admin/forgot/reset", function(){
	$user = User::validForgotDecrypt($_GET["code"]);
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
		]);
	$page->setTpl("forgot-reset", array(
		"name"=>$user["desperson"],
		"code"=>$_GET["code"]
		));
});
$app->post("/admin/forgot/reset", function(){
	$forgot = User::validForgotDecrypt($_POST["code"]);
	User::setForgotUsed($forgot["idrecovery"]);
	$user = new User();
	$user->get((int)$forgot["iduser"]);
	$password = password_hash($_POST["password"], PASSWORD_DEFAULT, [
		"cost"=>12
		]);
	$user->setPassword($password);
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
		]);
	$page->setTpl("forgot-reset-success");
});
$app->get("/admin/categories", function(){
	User::verifyLogin();
	$categories = Category::listAll();
	$page = new PageAdmin();
	$page->setTpl("categories", ['categories'=>$categories
		]);
});
$app->get("/admin/categories/create", function(){
	User::verifyLogin();
	$page = new PageAdmin();
	$page->setTpl("categories-create");
});
$app->post("/admin/categories/create", function(){
	User::verifyLogin();
	$category = new Category();
	$category->setData($_POST);
	$category->save();
	header('Location: /admin/categories');
	exit;
});
$app->get("/admin/categories/:idcategory/delete", function($idcategory){
	User::verifyLogin();
	$category = new Category();
	$category->get((int)$idcategory);
	$category->delete();
	header('Location: /admin/categories');
	exit;
});
$app->get("/admin/categories/:idcategory", function($idcategory){
	User::verifyLogin();
	$category = new Category();
	$category->get((int)$idcategory);
	$page = new PageAdmin();
	$page->setTpl("categories-update", [
		'category'=>$category->getValues()
		]);
});
$app->post("/admin/categories/:idcategory", function($idcategory){
	User::verifyLogin();
	
	$category = new Category();
	$category->get((int)$idcategory);
	$category->setData($_POST);
	$category->save();
	header('Location: /admin/categories');
	exit;
});


$app->run();
