<?
session_start();
error_reporting(~E_NOTICE);


$basedir = "/var/www/html";
$envfile = ".env";
if(file_exists($basedir."/".$envfile))
{
  $arq = file($basedir."/".$envfile);
  for($i=0;$i<count($arq);$i++)
  {
    putenv($arq[$i]);
  }
}

require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");

system("clear");
echo "\n#================================================#";
echo "\n|   Aquisição dos dados de condições climáticas  |";
echo "\n|        https://openweathermap.org/             |";
echo "\n#================================================#";


echo "\n\n> Iniciando processo de aquisição de dados:";

      $agora = now();
      $url   = "http://api.openweathermap.org/data/2.5/weather?q=Joinville,sc,br,uk&APPID=f212dca3c6138121d51e6c4d1f6fe89e&mode=json&units=metric&lang=pt_br";
echo "\n- Buscando dados: ";
      $json  = file_get_contents($url);
      $d = json_decode(json_encode(json_decode($json)), True); //Conversão Obj to array
echo  "ok.";
echo "\n- Montando consulta de inserção: ";
      $data = mkt2date($d['dt']);
      $infos = array("main"=>$d['weather'][0]['main'], "description"=>$d['weather'][0]['description'],
                     "temp"=>$d['main']['temp'], "feels_like"=>$d['main']['feels_like'], "temp_min"=>$d['main']['temp_min'],"temp_max"=>$d['main']['temp_max'],"pressure"=>$d['main']['pressure'], "humidity"=>$d['main']['humidity'],
                     "visibility"=>$d['visibility'],"wind_speed"=>$d['wind']['speed'],"wind_dir"=>$d['wind']['deg'],
                     "date"=>$data['datatimesrv'], "rain"=>$data['rain']['1h']);
      $sql = makeSql("sepud.weather", $infos, "ins");
echo "ok.";
echo "\n- Inserindo no banco de dados: ";
      pg_query($sql)or die("SQL error: ".$sql);
echo "ok.";
echo "\n> Fim da execução do processo.\n\n";
