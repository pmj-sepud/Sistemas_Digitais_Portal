<?
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");


$agora = now();

foreach ($_POST as $var => $val)
{
  if($var != "acao" && $var != "id")
  {
      if($val ==  ""){ $_POST[$var] = "Null";      }
      else{            $_POST[$var] = "'".$val."'";}
  }
}

extract($_POST);
//echo "<div class='row'><div class='col-sm-6 col-sm-offset-3'><pre>";
//print_r($_POST);


/*
Array
(
    [id_fleet] => 19
    [id_user] => 118
    [opened] => 2019-07-04T12:00
    [closed] => 2019-07-04T15:00
    [initial_fuel] => 100
    [final_fuel] => 80
    [initial_kml] => 32470
    [final_km] => 32500
    [description] => teste para inserção de dados.
    [id_workshift] => 93
    [acao] => Inserir
)
*/
if($acao == "Inserir")
{
  $sql = "INSERT INTO sepud.oct_garrison(
                                        id_fleet,
                                        id_workshift,
                                        opened,
                                        closed,
                                        initial_fuel,
                                        final_fuel,
                                        initial_km,
                                        final_km,
                                        observation)
        VALUES(                         $id_fleet,
                                        $id_workshift,
                                        $opened,
                                        $closed,
                                        $initial_fuel,
                                        $final_fuel,
                                        $initial_km,
                                        $final_km,
                                        $observation)RETURNING id";
  $res = pg_query($sql)or die("Error ".__LINE__."<br>".$sql);
  $aux = pg_fetch_assoc($res);
  $id  = $aux['id'];
  $sql = "INSERT INTO sepud.oct_rel_garrison_persona (id_garrison, id_user, type) VALUES ('".$id."', $id_user, 'Motorista')";
  pg_query($sql)or die("Error ".__LINE__."<br>".$sql);
  header("Location: index.php");

}

//echo "</pre></div></div>";
?>
