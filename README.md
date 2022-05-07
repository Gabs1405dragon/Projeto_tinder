<h2>Documentação passo a passo do projeto</h2>
Uma copia basica do backend do tinder.

 Esse projeto não é uma copia visual do sistema original mais sim uma base pequena do original!!
 Vamos começar do começo falo da tela de login .A tela inicial de login basíca é para criar uma sessão para poder entrar no sistema do tider . 
 
 <ul>
  <li>Primeiro verificar se o input type submit existe.</li>
  <li>Segundo recuperar o names do input e atribuir eles para método POST com a "Superglobals" <a href="https://www.php.net/manual/en/reserved.variables.post.php" >$_POST</a>!! </li>
  <li>Terceiro verificar se os campos estão prenchidos com a função <a href="https://www.php.net/manual/en/function.empty.php" >empty</a>,caso esteja vázios vai aparece uma mensagem de erro.</li>
  <li>Quarto verificar se é um email o que esta passando no campo para isso tem que usar uma função do php <a href="https://www.php.net/manual/en/function.filter-var.php">filter_var</a> pasando no parâmetro a variavel do email e a condição <b>FILTER_VALIDATE_EMAIL</b> .</li>
  <li>E por último verificar se o email e a senha já existem cadastrado no banco de dados ,se não existe vai aparece uma mensagem de erro caso ao contrario vai criar uma sessão e logar no sistema!!.</li>
</ul>

É muito importante já fazer um sistema de segurança para deixar o sistema mais seguro.

Por Exemplo: verificar se a tela do dashboard existe uma sessão ,se não existe volta para tela de login. e na tela de login se existi uma sessão voltar para a dashboard.

Já existe uma função nativa do php para verificar se existe uma variavel,sessão,post,get e etc... o nome da função é <a href="https://www.php.net/manual/en/function.isset.php" >isset()</a> essa função checa se tem alguma coisa ou não.

![login](https://user-images.githubusercontent.com/89558456/167228266-aaf3211e-181d-4703-abd7-d935679ddba8.png)

E também para fazer um logout do dashboard é simples eu recomendo fazer pela query da url usando o método <a href="https://www.php.net/manual/en/reserved.variables.get.php" >$_GET</a> ! clicando na tag a com a query url <b>(exemplo:dashboard?sair)</b> 
vai verificar se existe a query com a função isset() e se for verdade vai destruir a sessão com o método <a href="" >session_destroy()</a> do php. e dá um redirect na tela de login!!

Pronto agora voltando para a tela do dashboard ,agora parti para uma tela simples do "front-end" com uma sidebar ao lado esquerdo da tela semelhante com a dá imagem de baixo :

   ![sidebar](https://user-images.githubusercontent.com/89558456/167228902-b9908ba3-3f8e-40bf-adf1-910099955399.png)
   
 Depois do front basíco agora é só voltar para a logíca ,O proximo passo  agora é recupera a latude e a longetude da pessoa logada!! Para isso é necessario usar um arquivo javascript já pronto para facilitar as coisas
 
 vamos utilizamos esse arquivo <a href="https://github.com/Gabs1405dragon/Projeto_tinder/blob/master/func.php" >func.php</a> nesse arquivo a logica já esta toda pronta para ser usada.
 
 Agora nós vamos criar uma tag '<'button'>' para pode ativar a função <b>getLocation()</b> do arquivo para pegar a localização do usuário logado usando o <a href="https://www.w3schools.com/jsref/event_onclick.asp">onClick()</a> dentro da tag do button. e agente vamos fazer uma alteração dentro da função 
 getLocation() mudando para a escrita do jquery para o código ficar menor e mais facil de entender.mais não esqueça de colocar a cdn do jquery antes da inclução do arquivo func.php ,se não a grafia do jquery não irá funcionar.
 
 a alteração da função (getLocation,showPosition,atualizarCondenadas) código vai ficar assim.
       
     function getLocation() {
       if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(showPosition);}
      }
    function showPosition(position) {    
    $('p.lat_text').html("Latitude: " + position.coords.latitude);
    $('p.long_text').html("Longitude: " + position.coords.longitude);
    atualizarCondenadas(position.coords.latitude,position.coords.longitude);
    //console.log(getDistanceFromLatLonInKm(,,-27.441564,-48.491754));
    }
    function atualizarCondenadas(LatitudePar,LongitudePar){
    $.ajax({
        method:'post',
        url:'/Tinder/atualizar_coordenada.php',
        data:{latitude:LatitudePar,longitude:LongitudePar}
    }).done((data)=>{
        console.log("atualizado com sucesso!!");
    })}
Fazendo tudo isso as coordenadas vam se atualizar sozinhas dinâmicamente tambem é possivel mandar a atulização para o banco de dados para atualização se manter permanente basta fazer um <b>"UPDATE"</b> no campo no banco de dados e depois puxar os dados criando uma sessão
quanto ser logado.

     $idUsuario = $_SESSION['id'];
     $lat = $_POST['latitude'];
     $long = $_POST['longitude'];
     $sql = MySql::connect()->prepare("UPDATE usuarios SET lat = ? ,longe = ? WHERE id = $idUsuario");
     $sql->execute(array($lat,$long));
Agora a próxima etapa é criar uma box no front para ela ficar no centro da tela o nome da pessoa ficar aparecendo aleatóriamento toda vez que a tela é atualizada para isso tera que ser feito uma logica no banco de dados
que nessa logica caso o usuário logado seja do sexo "masculino" apareça um nome feminino caso o usuário seja "feminina" apareça um nome maculino. essa logica vai ficar amazenada dentro de um método de uma classe chamada "Usuários" 
também pode ser outro nome esse foi o nome que eu dei no meu código.

depois disso é só colocar o método dentro da caixa que foi criada no front e deixar ela no meio da tela fazer um <a href="https://www.php.net/manual/en/control-structures.foreach.php" >foreach()</a> e puxar o nome do usuário (masculino ou femino) a logica do método vai ser esse que esta monstrando abaixo!!

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
    
agora vai ser para adicionar o usuário como amigo para isso vai ser necessário fazer um relacionamento de tabelas no banco de dados é possivel fazer com chave estrangeira mais no meu caso eu vou pegar o id do usuário
para pode funcionar a relação de tabelas. 

Os campos dessa nova tabela vai ter o "id" do usuário logado e "id" do usuário escolhido e o último campo vai ser,para verificar se os dois aceitaram a amizades um do outro.a logica tambem vai ser encapsulada dentro de um método na mesma classe.

método para chamar a função(metodo da classe) tambem vai ser "$_GET" do foreach da box em baixo do nome vamos colocar duas tags a uma para aceita a amizade outra para rejeitar
no "href" vai tá passando duas query uma com o nome de active e o valor dela vai ser 1 e o outro vai ser o id e o valor vai ser o id do foreach da tabela que está fazendo o loop.
e assim vai ser feito o relacionamento de tabelas.

e agora vai ser feita a verificação se existe o método '$_GET['active']' .e depois é só colocar o valor do GET['active'] dentro de uma nova $variavel!! e depois é só chamar o método da classe Usuários com o 
nome "executarAcao()" no caso foi eu que dei esse nome ao método.

e como parâmetro você vai passa a nova $variavel declarada em cima, e no segundo parâmetro passar o "$_GET['id']".
  
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
    
 E por último depois de fazer tudo isso agora vai ter que puxar todos os amigos(as) que aceitaram amizade para listar na sidebar e vai ser feito o ultimo método também na mesma classe.
 
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
    
  ![tinder](https://user-images.githubusercontent.com/89558456/167232374-16dcc9b6-6019-450e-a70d-4154b78b894e.png)

    
 Pronto!! e chegamos ao fim da documentação muito obrigado por ter lido ela até o fim :) 
 
 <h2>Minhas redes sociais!!!</h2>
 <ul>
    <li><a href="https://www.instagram.com/gabs1405henrique/">Instagram</a></li>
    <li><a href="https://www.linkedin.com/in/gabriel-h-assis-de-souza-60b496207/">Linkedin</a></li>
    <li><a href="https://github.com/Gabs1405dragon/">GitHub</a></li>
</ul>
    
 

 

