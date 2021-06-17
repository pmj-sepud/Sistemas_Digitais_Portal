<?
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");
$schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
$agora = now();

cleanString();

if($_POST['acao']=="atualizar")
{
   unset($_POST['acao']);
   logger("Atualização","GSEC - Time de trabalho", "Atualização");
   $retorno = $_POST['id'];
   $sql     = makeSql("{$schema}gsec_workteam",$_POST,"upd","id");
   pg_query($sql)or die("Error ".__LINE__." - Query:{$sql}");
}else{
   unset($_POST['acao']);
   logger("Inserção","GSEC - Time de trabalho", "Inserção");
   $sql     = makeSql("{$schema}gsec_workteam",$_POST,"ins","id");
   $res     = pg_query($sql)or die("Error ".__LINE__." - Query:{$sql}");
   $aux     = pg_fetch_assoc($res);
   $retorno = $aux['id'];
}

header("Location: workteam_FORM.php?id={$retorno}");

function cleanString(){
   $utf8 = array('/[\']/u' => ' ');
   foreach ($_POST as $key => $value) {
      $_POST[$key] = preg_replace(array_keys($utf8), array_values($utf8), $value);
   }
}
?>
