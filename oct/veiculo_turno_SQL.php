<?
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");
$schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");


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

if($acao=="associar_passageiro" && $turno != "" && $id_garrison != "" && $id_user_pass != "")
{

  $sql = "INSERT INTO ".$schema."oct_rel_garrison_persona (id_garrison, id_user, type) VALUES ('".$id_garrison."', $id_user_pass, 'Passageiro')";
  pg_query($sql)or die("Error ".__LINE__."<br>".$sql);
  header("Location: veiculo_turno_FORM.php?turno=".$turno."&id_garrison=".$id_garrison);
  exit();
}
if($acao=="remover_passageiro" && $turno != "" && $id_garrison != "" && $id_user_pass != "")
{
  $sql = "DELETE FROM ".$schema."oct_rel_garrison_persona WHERE id_garrison = '".$id_garrison."' AND id_user = '".$id_user_pass."' AND type = 'Passageiro'";
  pg_query($sql)or die("Error ".__LINE__."<br>".$sql);
  header("Location: veiculo_turno_FORM.php?turno=".$turno."&id_garrison=".$id_garrison);
  exit();
}


if($acao=="remover" && $id!="")
{
  $sql = "DELETE FROM ".$schema."oct_rel_garrison_persona          WHERE id_garrison = '".$id."';
          DELETE FROM ".$schema."oct_garrison                      WHERE id          = '".$id."';
          UPDATE      ".$schema."oct_events SET id_garrison = null WHERE id_garrison = '".$id."';";
  pg_query($sql)or die("Error ".__LINE__."<br>".$sql);
  header("Location: index.php?id_workshift=".$id_workshift);
  exit();
}

if($acao=="Atualizar" && $id != "")
{
  $sql = "UPDATE ".$schema."oct_garrison
          SET
            opened       = ".$opened.",
            closed       = ".$closed.",
            initial_km   = ".$initial_km.",
            final_km     = ".$final_km.",
            observation  = ".$observation."
          WHERE id = '".$id."'";
  $res = pg_query($sql)or die("Error ".__LINE__."<br>".$sql);
  $aux = pg_fetch_assoc($res);
  header("Location: veiculo_turno_FORM.php?id_garrison=".$id."&turno=".$id_workshift);
  exit();
}

if($acao == "Inserir" && $id_user != "" && $id_fleet != "")
{
  $sql = "INSERT INTO ".$schema."oct_garrison(
                                        id_fleet,
                                        id_workshift,
                                        opened,
                                        closed,
                                        initial_km,
                                        final_km,
                                        observation)
        VALUES(                         $id_fleet,
                                        $id_workshift,
                                        $opened,
                                        $closed,
                                        $initial_km,
                                        $final_km,
                                        $observation)RETURNING id";
  $res = pg_query($sql)or die("Error ".__LINE__."<br>".$sql);
  $aux = pg_fetch_assoc($res);
  $id  = $aux['id'];
  $sql = "INSERT INTO ".$schema."oct_rel_garrison_persona (id_garrison, id_user, type) VALUES ('".$id."', $id_user, 'Motorista')";
  pg_query($sql)or die("Error ".__LINE__."<br>".$sql);
  header("Location: veiculo_turno_FORM.php?id_garrison=".$id."&turno=".$id_workshift);
  exit();
}


?>
