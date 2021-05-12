<?
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");
$schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
$agora = now();


if($_POST['acao']=="atualizar")
{
  $tab = $_POST['tabret'];
  unset($_POST['acao'],$_POST['tabret']);
  $agora = now();
  if($_POST['active']=='f' && $_POST['date_closed']==""){ $_POST['date_closed'] = $agora['datatimesrv']; }
  cleanString();
  logger("Atualização","CALLCENTER", "Callcenter - Atualização de atendimento: ".print_r($_POST, true));
  $sql = makeSql("{$schema}gsec_callcenter",$_POST,"upd","id");
  pg_query($sql)or die("Error ".__LINE__." - Query:{$sql}");
  header("Location: callcenter_FORM.php?id={$_POST['id']}&tab={$tab}");
}

if($_POST['acao']=="inserir")
{
  unset($_POST['acao']);
  $agora                      = now();
  if(!isset($_POST['date_added']))
  {
   $_POST['date_added']        = $agora['datatimesrv'];
  }
  $_POST['id_company']        = $_SESSION['id_company'];
  $_POST['id_company_father'] = $_SESSION['id_company_father'];
  $_POST['id_user_added']     = $_SESSION['id'];
  cleanString();
  logger("Inserção","CALLCENTER", "Callcenter - Cadastro atendimento: ".print_r($_POST, true));
  $sql = makeSql("{$schema}gsec_callcenter",$_POST,"ins","id");
  $res = pg_query($sql)or die("<div class='text-center text-danger'>Error: ".__LINE__."<br>SQL {$sql}</div>");
  $aux = pg_fetch_assoc($res);
  header("Location: callcenter_FORM.php?id={$aux['id']}");
}

  function cleanString() {
    $utf8 = array('/[\']/u' => ' ');
    foreach ($_POST as $key => $value) {
          $_POST[$key] = preg_replace(array_keys($utf8), array_values($utf8), $value);
          //$_POST[$key] = htmlentities($value);
    }
  }
?>
