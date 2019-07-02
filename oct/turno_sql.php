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
  $dt_abertura = $opened." ".$opened_hour.":00";
  $dt_abertura = formataData($dt_abertura,4);

  $dt_fechamento = $closed." ".$closed_hour.":00";
  $dt_fechamento = formataData($dt_fechamento,4);

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
                  '".$observation."',
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
  $dt_abertura   = $opened." ".$opened_hour;
  $dt_fechamento = ($closed    != ""                        ? "'".$closed." ".$closed_hour."'" : "Null");
  $id_fleet      = ($id_fleet  != ""                        ? "'".$id_fleet."'":"Null");
  $is_driver     = ($is_driver != "" && $id_fleet != "Null" ? "'".t."'" : "'".f."'");

  $sql = "INSERT INTO sepud.oct_rel_workshift_persona(
                      id_shift,
                      id_person,
                      id_fleet,
                      opened,
                      closed,
                      type,
                      is_driver)
          VALUES ('".$id_workshift."',
                  '".$id_user."',
                   ".$id_fleet.",
                  '".$dt_abertura."',
                  ".$dt_fechamento.",
                  '".$type."',
                  ".$is_driver.")";
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
