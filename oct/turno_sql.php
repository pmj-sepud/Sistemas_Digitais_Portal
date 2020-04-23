<?
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");
$schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");


$agora = now();

if($_POST['acao']=="atualizar_associado" && $_POST['id_user']!="" && $_POST['id_workshift'])
{
  extract($_POST);

  $dt_abertura   = $opened." ".$opened_hour;
  $dt_fechamento = ($closed != ""?"'".$closed." ".$closed_hour."'" : "Null");


  echo $sql = "UPDATE ".$schema."oct_rel_workshift_persona SET
                      opened      = '".$dt_abertura."',
                      closed      = ".$dt_fechamento.",
                      type        = '".$type."',
                      status      = '".$status."',
                      observation = '".$observation."'
          WHERE id = '".$id_rel_workshift_persona."'";

  pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
  header("location: turno_associar_pessoa.php?id_rel_workshift=".$id_rel_workshift_persona."&id_workshift=".$id_workshift);
  exit();
}

if($_GET['acao'] == "fechar" && $_GET['id'] != "")
{
  $sqlU  = "UPDATE ".$schema."oct_workshift             SET status = 'fechado' WHERE       id = '".$_GET['id']."';";
  //$sqlU2 = "UPDATE ".$schema."oct_rel_workshift_persona SET status = '".$agora['datatimesrv']."' WHERE id_shift = '".$_GET['id']."';";
  pg_query($sqlU)or die("Erro ".__LINE__."<br>SQL: ".$sqlU);
  header("location: index.php");
  exit();
}

if($_POST['acao'] == "inserir")
{
  extract($_POST);

  if($opened_hour == ""){ $opened_hour = "00:00"; }
  if($closed_hour == ""){ $closed_hour = "23:59"; }

  $dt_abertura = $opened." ".$opened_hour.":00";
  $dt_abertura = formataData($dt_abertura,4);

  $dt_fechamento = $closed." ".$closed_hour.":00";
  $dt_fechamento = formataData($dt_fechamento,4);

  $obs = pg_escape_string($observation);

  $sql = "INSERT INTO ".$schema."oct_workshift(
                      opened,
                      closed,
                      id_company,
                      observation,
                      workshift_group,
                      status)
          VALUES ('".$dt_abertura."',
                  '".$dt_fechamento."',
                     $id_company,
                  '".$obs."',
                  '".$workshift_group."',
                  '".$status."')RETURNING id";
   $res = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
   $id  = pg_fetch_assoc($res);
   header("location: turno.php?id=".$id['id']);
   exit();
}

//Associar recurso ao turno ativo//
if($_POST['acao'] == "novo_associado")
{

  extract($_POST);

  if($opened_hour != ""){$_SESSION['user_opened_hour'] = $opened_hour;}
  if($closed_hour != ""){$_SESSION['user_closed_hour'] = $closed_hour;}

  if($opened!= ""){$_SESSION['user_opened'] = $opened;}
  if($closed!= ""){$_SESSION['user_closed'] = $closed;}


  $dt_abertura   = $opened." ".$opened_hour;
  $dt_fechamento = ($closed    != ""                        ? "'".$closed." ".$closed_hour."'" : "Null");

  $sqlupdshift = "UPDATE ".$schema."oct_workshift SET is_populate = 't' WHERE id = '".$id_workshift."';";

  $sql = "INSERT INTO ".$schema."oct_rel_workshift_persona(
                      id_shift,
                      id_person,
                      opened,
                      closed,
                      type,
                      status,
                      observation)
          VALUES ('".$id_workshift."',
                  '".$id_user."',
                  '".$dt_abertura."',
                  ".$dt_fechamento.",
                  '".$type."',
                  '".$status."',
                  '".$observation."')RETURNING id;";
  $res = pg_query($sqlupdshift.$sql)or die("Erro ".__LINE__."<br>Query 01: ".$sqlupdshift."<br>Query 02: ".$sql);
  $aux = pg_fetch_assoc($res);
  header("location: turno_associar_pessoa.php?id_workshift=".$id_workshift."&id_rel_workshift=".$aux['id']);
}


if($_POST['acao'] == "atualizar" && $_POST['id_workshift'] != "")
{
  extract($_POST);

  if($opened_hour == ""){ $opened_hour = "00:00"; }
  if($closed_hour == ""){ $closed_hour = "23:59"; }

  $dt_abertura = $opened." ".$opened_hour.":00";
  $dt_abertura = formataData($dt_abertura,4);

  $dt_fechamento = $closed." ".$closed_hour.":00";
  $dt_fechamento = formataData($dt_fechamento,4);

  $obs = pg_escape_string($observation);

  $sql = "UPDATE ".$schema."oct_workshift SET
                      opened          = '".$dt_abertura."',
                      closed          = '".$dt_fechamento."',
                      observation     = '".$obs."',
                      workshift_group = '".$workshift_group."',
                      status          = '".$status."'
          WHERE       id              = '".$id_workshift."'";

   $res = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
   $id  = pg_fetch_assoc($res);
   header("location: turno.php?id=".$id_workshift);
   exit();
}

if($_GET['acao']=="remover_associado" && $_GET['id']!="" && $_GET["id_workshift"]!="")
{
  $sql = "DELETE FROM ".$schema."oct_rel_workshift_persona WHERE id = '".$_GET['id']."' RETURNING (SELECT count(*) as registros FROM ".$schema."oct_rel_workshift_persona WHERE id_shift = '".$_GET["id_workshift"]."');";
  $res = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
  $aux = pg_fetch_assoc($res);
  if(($aux['registros']-1) <= 0){ //Não possui mais funcionarios associados ao turno, libera botão para população automatica do turno//
    $sqlu = "UPDATE ".$schema."oct_workshift SET is_populate = 'f' WHERE id = '".$_GET["id_workshift"]."'";
    pg_query($sqlu)or die("SQL Error ".__LINE__."<br>Query: ".$sqlu);
  }
  header("location: turno_associar_pessoa.php?id_workshift=".$_GET['id_workshift']);
  exit();
}
?>
