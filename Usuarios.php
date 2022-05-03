<?php 

class Usuarios{
    public static function pegarUsuarioNovo(){
        if($_SESSION['sexo'] == 'masculino'){
            $pegarUsuarioRamdow = MySql::connect()->prepare("SELECT * FROM usuarios WHERE sexo != 'masculino' ORDER BY RAND() LIMIT 1");
            $pegarUsuarioRamdow->execute();
            $pegarUsuarioRamdow = $pegarUsuarioRamdow->fetch();
            return $pegarUsuarioRamdow;
        }else{
            $pegarUsuarioRamdow = MySql::connect()->prepare("SELECT * FROM usuarios WHERE sexo != 'feminino' ORDER BY RAND() LIMIT 1");
            $pegarUsuarioRamdow->execute();
            $pegarUsuarioRamdow = $pegarUsuarioRamdow->fetch();
            return $pegarUsuarioRamdow;
        }
    }

    public static function executarAcao($acao,$usuarioId){
        $sql = MySql::connect()->prepare("SELECT * FROM likes WHERE user_from = ? AND user_to = ? ");
        $sql->execute(array($_SESSION['id'],$usuarioId));
        if($sql->rowCount() >= 1){
            return true;
        }else{
            $inserir = MySql::connect()->prepare("INSERT INTO likes VALUES (null,?,?,?)");
            $inserir->execute(array($_SESSION['id'],$usuarioId,$acao));
        }
    }

    public static function pegarCrush(){
        $crushes = array();
        $pegar = MySql::connect()->prepare("SELECT * FROM likes WHERE user_from = ? AND active = 1");
        $pegar->execute(array($_SESSION['id']));
        $gostei = $pegar->fetchAll();
        foreach($gostei as $key => $value){
            $gostaramDeVolta = MySql::connect()->prepare("SELECT * FROM likes WHERE user_to = ? AND user_from = ? AND active = 1");
            $gostaramDeVolta->execute(array($_SESSION['id'],$value['user_to']));
            if($gostaramDeVolta->rowCount() == 1){
                $usuarios = MySql::connect()->prepare("SELECT * FROM usuarios WHERE id = ?");
                $usuarios->execute(array($value['user_to']));
                $crushes[] = $usuarios->fetch();
            }
        }
        return $crushes;
    }
}