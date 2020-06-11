<?
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");


?>
<style>
.select2-selection__rendered {
line-height: 32px !important;
}

.select2-selection {
height: 34px !important;
}
</style>
<section role="main" class="content-body">

  <header class="page-header">
    <h2>Página para testes de scripts</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span>Configurações</span></li>
        <li><span>Desenvolvimento</span></li>
      </ol>
    </div>
  </header>



  <div class="row">
      <div class="col-md-12">
        <p>Click on the marker for position information.</p>
<div id="map" style="width:400px;height:200px"></div>
<script>
var marker;
var infoWindow;
if (navigator.geolocation) {
var timeoutVal = 10 * 1000 * 1000;
navigator.geolocation.watchPosition(
displayPosition,
displayError,
{ enableHighAccuracy: true, timeout: timeoutVal, maximumAge: 0 }
);
}
else {
alert("Geolocation is not supported by this browser");
}
function displayPosition(position) {
var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
var options = {
zoom: 10,
center: pos,
mapTypeId: google.maps.MapTypeId.ROADMAP
};
var map = new google.maps.Map(document.getElementById("map"), options);
// Remove the current marker, if there is one
if (typeof(marker) != "undefined") marker.setMap(null);
marker = new google.maps.Marker({
position: pos,
map: map,
title: "User location"
});
var contentString = "<b>Timestamp:</b> " + parseTimestamp(position.timestamp) + "<br/><b>User location:</b> lat " + position.coords.latitude + ", long " + position.coords.longitude + ", accuracy " + position.coords.accuracy;
// Remove the current infoWindow, if there is one
if (typeof(infoWindow) != "undefined") infoWindow.setMap(null);
infowindow = new google.maps.InfoWindow({
content: contentString
});
google.maps.event.addListener(marker, 'click', function() {
infowindow.open(map,marker);
});
}
function displayError(error) {
var errors = {
1: 'Permission denied',
2: 'Position unavailable',
3: 'Request timeout'
};
alert("Error: " + errors[error.code]);
}
function parseTimestamp(timestamp) {
var d = new Date(timestamp);
var day = d.getDate();
var month = d.getMonth() + 1;
var year = d.getFullYear();
var hour = d.getHours();
var mins = d.getMinutes();
var secs = d.getSeconds();
var msec = d.getMilliseconds();
return day + "." + month + "." + year + " " + hour + ":" + mins + ":" + secs + "," + msec;
}
</script>
      </div>
  </div>

  <div class="row">
        <div class="col-md-12">
            <?
            $agora = now();


/*
            $schema     = getenv('SCHEMA');
            $schema_dev = getenv('SCHEMA_DEV');
            $waze_url   = getenv('WAZE_URL');

            //print_r_pre($schema);
            //print_r_pre($schema_dev);
            print_r_pre($waze_url);
            $waze_url = base64_decode($waze_url);
            print_r_pre($waze_url);
            //print_r_pre($_SESSION);

            echo "<br>- Buscando dados: ";
            $json  = file_get_contents($waze_url);
            $d = json_decode(json_encode(json_decode($json)), True); //Conversão Obj to array
            echo  "ok.<br>";
            print_r_pre($d);
*/

            echo "<h3>JWT</h3>";
            $key     = getenv("SECRET_KEY");
            $header  = ['typ' => 'JWT','alg' => 'HS256'];


            $payload = [
              "iss"  => "portal.jlle30.com.br",
              "data" => "dados"
            ];
/*
iss (emissor): Emissor da JWT
sub (assunto): Assunto do JWT (o usuário)
aud (público): destinatário para o qual o JWT se destina
exp (tempo de expiração): tempo após o qual o JWT expira
nbf (não antes do tempo): tempo antes do qual o JWT não deve ser aceito para processamento
iat (emitido no momento): hora em que o JWT foi emitido; pode ser usado para determinar a idade do JWT
jti (JWT ID): identificador exclusivo; pode ser usado para impedir que o JWT seja reproduzido (permite que um token seja usado apenas uma vez)
*/

            print_r_pre(json_encode($header));
            print_r_pre(json_encode($payload));

          $header    = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($header)));
          $payload   = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($payload)));
          $signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(hash_hmac('sha256','$header.$payload', $key, true)));

          //concatenating the header, the payload and the signature to obtain the JWT token
          $token = $header.".".$payload.".".$signature;
          echo $token;

          //$_SERVER['Authorization'] = "Bearer ".$token;

          print_r_pre($_SESSION);



            ?>


        </div>
  </div>

</section>
<script>
</script>
<?

function makeSql($table, $fieldvals, $type, $returning="")
{
    switch ($type) {
      case 'ins':
              foreach ($fieldvals as $key => $val)
              {
                $keys[] = $key;
                $vals[] = ($val!=""?"'".$val."'":"Null");
              }

              $sql = "INSERT INTO ".$table." (".implode(", ", $keys).") VALUES (".implode(", ",$vals).") ".($returning!=""?"RETURNING ".$returning:"");
      break;
      case 'upd':
              foreach ($fieldvals as $key => $val)
              {
                if($val!="")
                {
                  $upds[] = $key."='".$val."'";
                }else {
                  $upds[] = $key."=Null";
                }
              }
              if($returning != "")
              {
                $sql = "UPDATE ".$table." SET ".implode(", ",$upds)." WHERE ".$returning;
              }
      break;

      default:
        break;
    }
  return $sql;
}

?>
