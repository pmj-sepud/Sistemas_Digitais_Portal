<?
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");


$agora = now();

foreach ($_POST as $var => $val)
{
  if($var != "acao" && $var != "id" && $var != "id_workshift" && $var != "id_garrison")
  {
      if($val ==  ""){ $_POST[$var] = "Null";      }
      else{            $_POST[$var] = "'".$val."'";}
  }
}

extract($_POST);
extract($_GET);
echo "<div class='row'><div class='col-sm-6 col-sm-offset-3'>";

  if($acao == "Inserir") //Inserir os dados gerais da guarnição//
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
    header("Location: guarnicao_FORM.php?id_garrison=".$aux['id']."&id_workshift=".$id_workshift);
    exit();

  }

  if($acao == "Atualizar") //Atualizar os dados gerais da guarnição//
  {
    $sql = "UPDATE sepud.oct_garrison SET
                    name        = $name,
                    opened      = $opened,
                    closed      = $closed,
                    observation = $observation
            WHERE id = '".$id_garrison."'";
    $res = pg_query($sql)or die("SQL error ".__LINE__."<br>Query: ".$sql);
    $aux = pg_fetch_assoc($res);

    header("Location: guarnicao_FORM.php?id_garrison=".$id_garrison."&id_workshift=".$id_workshift);
    exit();

  }

  if($acao == "Remover") //Remove a guarnição e todas as associações//
  {
    $sql = "DELETE FROM sepud.oct_garrison                      WHERE id          = '".$id_garrison."';
            DELETE FROM sepud.oct_rel_garrison_persona          WHERE id_garrison = '".$id_garrison."';
            DELETE FROM sepud.oct_rel_garrison_vehicle          WHERE id_garrison = '".$id_garrison."';
            UPDATE      sepud.oct_events SET id_garrison = null WHERE id_garrison = '".$id_garrison."';";
    $res = pg_query($sql)or die("SQL error ".__LINE__."<br>Query: ".$sql);
    $aux = pg_fetch_assoc($res);

    header("Location: index.php?id_workshift=".$id_workshift);
    exit();

  }

  if($acao == "associar_veiculo")
  {
    $sql = "INSERT INTO sepud.oct_rel_garrison_vehicle(id_fleet, id_garrison, initial_km, final_km, obs)
            VALUES ($id_fleet, $id_garrison, $initial_km, $final_km, $obs)";
    pg_query($sql)or die("SQL error ".__LINE__."<br>Query: ".$sql);
    header("Location: guarnicao_FORM.php?id_garrison=".$id_garrison."&id_workshift=".$id_workshift);
    exit();
  }

  if($acao == "atualizar_veiculo")
  {
    $sql = "UPDATE sepud.oct_rel_garrison_vehicle
               SET
                  initial_km = $initial_km,
                  final_km   = $final_km,
                  obs        = $obs
            WHERE id         = '".$id."'";
    pg_query($sql)or die("SQL error ".__LINE__."<br>Query: ".$sql);
    header("Location: guarnicao_FORM.php?id_garrison=".$id_garrison."&id_workshift=".$id_workshift);
    exit();
  }
  if($acao == "remover_veiculo")
  {
    $sql  = "UPDATE sepud.oct_rel_garrison_persona SET id_rel_garrison_vehicle = null WHERE id_rel_garrison_vehicle = '".$id."';";
    $sql .= "DELETE FROM sepud.oct_rel_garrison_vehicle WHERE id = '".$id."';";
    pg_query($sql)or die("SQL error ".__LINE__."<br>Query: ".$sql);
    header("Location: guarnicao_FORM.php?id_garrison=".$id_garrison."&id_workshift=".$id_workshift);
    exit();

  }
  if($acao == "associar_agente_e_veiculo")
  {

      $sql = "INSERT INTO sepud.oct_rel_garrison_persona(id_garrison, id_user, id_rel_garrison_vehicle, type)
                                                  VALUES('".$id_garrison."',
                                                            $id_user,
                                                            $id_rel_garrison_vehicle,
                                                            $type)";

     pg_query($sql)or die("SQL error ".__LINE__."<br>Query: ".$sql);
     header("Location: guarnicao_FORM.php?id_garrison=".$id_garrison."&id_workshift=".$id_workshift);
     exit();
  }
  if($acao == "remover_pessoa")
  {
    $sql = "DELETE FROM sepud.oct_rel_garrison_persona WHERE id = '".$id."';";
    pg_query($sql)or die("SQL error ".__LINE__."<br>Query: ".$sql);
    header("Location: guarnicao_FORM.php?id_garrison=".$id_garrison."&id_workshift=".$id_workshift);
    exit();

  }


echo "</div></div>";



?>
