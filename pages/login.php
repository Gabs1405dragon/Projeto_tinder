<?php  
if(isset($_SESSION['login_tinder'])){
    echo '<script>location.href="'.INCLUDE_PATH_TI.'home"</script>';
};
?>
<title>Login</title>

<?php  
if(isset($_POST['entrar_tinder'])){
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    if(empty($email) || empty($senha)){
        echo '<div class="erro">Preenchar o campo do nome e senha!!</div>';
    }else{
        if(filter_var($email,FILTER_VALIDATE_EMAIL)){
            $sql = MySql::connect()->prepare("SELECT * FROM usuarios WHERE email = ? AND senha = ?");
            $sql->execute(array($email,$senha));
            if($sql->rowCount() >= 1){
                $dados = $sql->fetch();
                $_SESSION['login_tinder'] = $email;
                $_SESSION['user_name'] = $dados['nome'];
                $_SESSION['id'] = $dados['id'];
                $_SESSION['lat'] = $dados['lat'];
                $_SESSION['long'] = $dados['longe'];
                $_SESSION['sexo'] = $dados['sexo'];
                $_SESSION['localizacao'] = $dados['localizacao'];
                echo '<script>location.href="'.INCLUDE_PATH_TI.'home"</script>';
            }else{
                echo '<div class="erro">Senha e email incorretos...</div>';
            }
        }else{
            echo '<div class="erro" >E-mail inválido....</div>';
        }
    }
}
?>

<div class="form__content">
    <form method="post" >
        <h2>Entrar</h2>
        <input type="email" name="email" placeholder="Seu email..">
        <input type="password" name="senha" placeholder="Sua senha...">
        <input type="submit" value="Logar" name="entrar_tinder">
    </form>
</div>

</body>
</html>