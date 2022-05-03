<?php  
define('DADOS',array('dbname'=>'tinder','host'=>'localhost','port'=>'3332','user'=>'root','password'=>'123456'));

class MySql{
    private static $pdo;

    public static function connect(){
        if(is_null(self::$pdo)){
            try{
                self::$pdo = new PDO('mysql:dbname='.DADOS['dbname'].';host='.DADOS['host'].';port='.DADOS['port'],DADOS['user'],DADOS['password'],array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8'));

            }catch(Exception $e){
                echo '<div class="erro">Erro ao tentar connectar ao banco de dados</div>';
            }
        }
        return self::$pdo;
    }
}