<?
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");


$agora = now();

if($_GET['acao'] == "fechar" && $_GET['id'] != "")
{
  $sqlU  = "UPDATE sepud.oct_workshift             SET status = 'fechado' WHERE       id = '".$_GET['id']."';";
  //$sqlU2 = "UPDATE sepud.oct_rel_workshift_persona SET status = '".$agora['datatimesrv']."' WHERE id_shift = '".$_GET['id']."';";
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

  $sql = "INSERT INTO sepud.oct_workshift(
                      opened,
                      closed,
                      id_company,
                      observation,
                      period,
                      status)
          VALUES ('".$dt_abertura."',
                  '".$dt_fechamento."',
                     $id_company,
                  '".$obs."',
                  '".$period."',
                  'aberto')RETURNING id";
   $res = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
   $id  = pg_fetch_assoc($res);
   header("location: turno.php?id=".$id['id']);
   exit();
}

//Associar recurso ao turno ativo//
if($_POST['acao'] == "associar")
{

  extract($_POST);

  if($opened_hour != ""){$_SESSION['user_opened_hour'] = $opened_hour;}
  if($closed_hour != ""){$_SESSION['user_closed_hour'] = $closed_hour;}

  if($opened!= ""){$_SESSION['user_opened'] = $opened;}
  if($closed!= ""){$_SESSION['user_closed'] = $closed;}


  $dt_abertura   = $opened." ".$opened_hour;
  $dt_fechamento = ($closed    != ""                        ? "'".$closed." ".$closed_hour."'" : "Null");


  $sql = "INSERT INTO sepud.oct_rel_workshift_persona(
                      id_shift,
                      id_person,
                      opened,
                      closed,
                      type)
          VALUES ('".$id_workshift."',
                  '".$id_user."',
                  '".$dt_abertura."',
                  ".$dt_fechamento.",
                  '".$type."')";
  pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
  header("location: turno.php?id=".$id_workshift);
}


if($_POST['acao'] == "atualizar" && $_POST['id'] != "")
{
  echo "<div class='text-center'>";
    print_r($_POST);
  echo "</div>";
}
?>
