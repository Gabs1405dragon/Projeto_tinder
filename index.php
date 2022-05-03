<?php  
session_start();
define('INCLUDE_PATH_TI','http://localhost/Tinder/');
date_default_timezone_set("America/Sao_Paulo");

define('ACTIVE_LIKE','1');
define('ACTIVE_DESLIKE','0');

$autoload = function($class){
    if(file_exists($class.'.php')){
        include($class.'.php');
    }else{
        die('Esse arquivo não existe..');
    }
};

spl_autoload_register($autoload);

if(!isset($_SESSION['login_tinder']) && $_GET['url'] != 'login'){
    echo '<script>location.href="'.INCLUDE_PATH_TI.'login"</script>';
    die();
};

$url = (isset($_GET['url']) ? explode('/',$_GET['url'])[0] : 'home' );

if(file_exists('pages/'.$url.'.php')){
    include('pages/include/header.php');
    include('pages/'.$url.'.php');
}else{
    die('Pagina não existe....');
}