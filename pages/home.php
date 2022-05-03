<?php  
if(!isset($_SESSION['login_tinder'])){
    echo '<script>location.href="'.INCLUDE_PATH_TI.'login"</script>';
};
if(isset($_GET['sair'])){
session_destroy();
echo '<script>location.href="'.INCLUDE_PATH_TI.'login"</script>';
}
?>
<title>Bem vindo(a) <?= ucfirst($_SESSION['user_name']) ;?></title>


<section class="sidebar" >
    <div class="topo__title">
        <p>Seja bem vindo(a) <?= $_SESSION['user_name']?> | <a href="?sair">Sair</a></p>
    </div>
    <div class="btn__side">
        <button onclick="getLocation()" type="submit" >Atualizar Coortenadas!</button>
    </div>
    <div class="coord">
        <p class="lat_text" >Latitude: <?= $_SESSION['lat']; ?></p>
        <p class="long_text" >Longitude: <?= $_SESSION['long']; ?></p>
        <p>Localização: <?= $_SESSION['localizacao'] ?></p>
    </div>
    <div class="lista_friends">
        <h3>Crushs</h3>
        <ul>
            <?php $crushs = Usuarios::pegarCrush(); 
            foreach($crushs as $crush){
            ?>
                <li><?= $crush['nome']; ?> | 
                <span style="display: none;" class="lat_user"><?= $crush['lat']; ?></span>
                <span style="display: none;" class="long_user"><?= $crush['longe']; ?></span>
                Distância: <span class="user_distance"></span></li>
            <?php } ?>
        </ul>
    </div>

</section>

<section class="user">
    <div class="box">
        <?php 
        if(isset($_GET['active'])){
            $active = $_GET['active'];
            if($active == ACTIVE_LIKE){
                Usuarios::executarAcao(ACTIVE_LIKE,$_GET['id']);
            }else if($active == ACTIVE_DESLIKE){
                Usuarios::executarAcao(ACTIVE_DESLIKE,$_GET['id']);
            }
        }
        $usuarios = Usuarios::pegarUsuarioNovo() ?>
        <h2><?= $usuarios['nome']; ?></h2>
        <a href="home?active=1&id=<?= $usuarios['id'] ?>">Gostei</a> | <a href="home?active=0&id=<?= $usuarios['id'] ?>">Não gostei</a>
    </div>
</section>

<div class="clear"></div>

<script>


function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  }

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
    })
}

var myLat = $('.lat_text').html();
var myLong = $('.long_text').html();

$('li').each(function(){
    var coordLat = $(this).find('.lat_user').html();
    var coordLong = $(this).find('.long_user').html();
    var distance = Math.round(getDistanceFromLatLonInKm(myLat,myLong,coordLat,coordLong) * 100) / 100;
    $(this).find('.user_distance').html(distance);
});

function getDistanceFromLatLonInKm(lat1,lon1,lat2,lon2) {
  var R = 6371; // Radius of the earth in km
  var dLat = deg2rad(lat2-lat1);  // deg2rad below
  var dLon = deg2rad(lon2-lon1); 
  var a = 
    Math.sin(dLat/2) * Math.sin(dLat/2) +
    Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
    Math.sin(dLon/2) * Math.sin(dLon/2)
    ; 
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
  var d = R * c; // Distance in km
  return d;
}

function deg2rad(deg) {
  return deg * (Math.PI/180)
}

</script>