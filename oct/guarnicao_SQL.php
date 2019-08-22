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
echo "<div class='row'><div class='col-sm-6 col-sm-offset-3'>";

  if($acao == "Inserir") //Inserir guarnição
  {
    $sql = "INSERT INTO sepud.oct_garrison(name, opened, closed, id_workshift, observation)
            VALUES(
                $name,
                $opened,
                $closed,
                $id_workshift,
                $observation)RETURNING id";
    $res = pg_query($sql)or die("SQL error ".__LINE__."<br>Query: ".$sql);
    $aux = pg_fetch_assoc($res);
    echo "<br>Query: ".$sql."<hr>Retorno, ID: ".$aux['id'];
    header("Location: guarnicao_FORM.php?id_garrison=".$aux['id']."&id_garrison=".$id_workshift);
    exit();

  }

  if($acao == "associar_veiculo")
  {
    //( [acao] => associar_veiculo [id_workshift] => 204 [id_garrison] => 1362 [id_fleet] => 58 )
    $sql = "INSERT INTO sepud.oct_rel_garrison_vehicle(id_fleet, id_garrison)
            VALUES ($id_fleet, $id_garrison)";
    pg_query($sql)or die("SQL error ".__LINE__."<br>Query: ".$sql);
    header("Location: guarnicao_FORM.php?id_garrison=".$id_garrison."&id_workshift=".$id_workshift);
    exit();
  }
/*
Array
(
    [name] => 'alfa'
    [opened] => '2019-08-20T11:10'
    [closed] => Null
    [observation] => Null
    [id_workshift] => 204
    [acao] => Inserir
)
*/
echo "</div></div>";



?>
