<?
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");
$schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
$agora = now();

function loggerGsec($id_orgin, $table, $info)
{
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $id_user = $_SESSION['id'];
  $agora = now();
  $sql = "INSERT INTO {$schema}gsec_logs(id_origin, table_origin, date_added, id_user, info)
			 VALUES ('{$id_orgin}', '{$table}', '{$agora['datatimesrv']}', '{$id_user}', '{$info}')";
  pg_query($sql)or die("Erro ".__LINE__."<hr>".pg_last_error()."<hr>".$sql);
}

if($_POST['acao']=="atualizar")
{
  $tab = $_POST['tabret'];
  unset($_POST['acao'],$_POST['tabret']);
  $agora = now();

  cleanString();
  if($_POST['active']=='f' && $_POST['date_closed']==""){
     $_POST['date_closed'] = $agora['datatimesrv'];
     $info = array("status"=>"<b>Finalizou o atendimento</b>", "dados"=>$_POST);
     loggerGsec($_POST['id'], "gsec_callcenter", json_encode($info));
  }else{
     $info = array("status"=>"Atualizou o atendimento", "dados"=>$_POST);
     loggerGsec($_POST['id'], "gsec_callcenter", json_encode($info));
  }
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

  $info = array("status"=>"Inseriu o atendimento");
  loggerGsec($aux['id'], "gsec_callcenter", json_encode($info));
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
