<?
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");


$agora = now();

foreach ($_POST as $var => $val)
{
  if($var != "acao" && $var != "id" && $var != "id_workshift")
  {
      if($val ==  ""){ $_POST[$var] = "Null";      }
      else{            $_POST[$var] = "'".$val."'";}
  }
}

extract($_POST);
extract($_GET);
//echo "<div class='row'><div class='col-sm-6 col-sm-offset-3'><pre>";
//  if($acao == "Atualizar"){  print_r($_POST);  exit();  }

if($acao=="remover" && $id!="")
{
  $sql = "DELETE FROM sepud.oct_rel_garrison_persona WHERE id_garrison = '".$id."';
          DELETE FROM sepud";
  pg_query($sql)or die("Error ".__LINE__."<br>".$sql);
  header("Location: index.php");
}

if($acao=="Atualizar" && $id != "")
{
  $sql = "UPDATE sepud.oct_garrison
          SET
            opened       = ".$opened.",
            closed       = ".$closed.",
            initial_fuel = ".$initial_fuel.",
            final_fuel   = ".$final_fuel.",
            initial_km   = ".$initial_km.",
            final_km     = ".$final_km.",
            observation  = ".$observation."
          WHERE id = '".$id."'";
  $res = pg_query($sql)or die("Error ".__LINE__."<br>".$sql);
  $aux = pg_fetch_assoc($res);
  header("Location: veiculo_turno_FORM.php?id_garrison=".$id."&turno=".$id_workshift);
  exit();
}
/*

Array
(
    [id_fleet] => '9'
    [id_user] => '123'
    [opened] => '2019-07-16T06:30:00'
    [closed] => Null
    [initial_fuel] => '100'
    [final_fuel] => Null
    [initial_km] => '50530'
    [final_km] => Null
    [observation] => 'teste de inserção....'
    [id_workshift] => '118'
    [acao] => Atualizar
    [id_garrison] => '43'
)




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
