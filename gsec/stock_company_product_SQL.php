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
   logger("Atualização","GSEC - Controle de estoque do setor", "Atualização de produto");
   $retorno = $_POST['id'];
   $sql     = makeSql("{$schema}gsec_stock_company",$_POST,"upd","id");
   pg_query($sql)or die("Error ".__LINE__." - Query:{$sql}");
}elseif($_POST['acao']=="inserir"){
   unset($_POST['acao']);
   logger("Inserção","GSEC - Controle de estoque do setor", "Inserção de produto");
   $sql     = makeSql("{$schema}gsec_stock_company",$_POST,"ins","id");
   $res     = pg_query($sql)or die("Error ".__LINE__." - Query:{$sql}");
   $aux     = pg_fetch_assoc($res);
   $retorno = $aux['id'];
}elseif($_POST['acao']=="insere_transacao_modal")
{
   $retorno = $_POST['id_gsec_stock_company'];
   $agora = now();
   $_POST['date_added'] = $agora['datatimesrv'];
   unset($_POST['acao'],$_POST['id_gsec_stock_company']);
   $sql     = makeSql("{$schema}gsec_stock_company_transactions",$_POST,"ins","id");
   pg_query($sql)or die("Error ".__LINE__." - Query:{$sql}");

   if($_POST['type']=="in")//Atualiza saldo//
   {
      $sql = "UPDATE {$schema}gsec_stock_company SET actual_count = (actual_count+{$_POST['count']}) WHERE id = '{$retorno}'";
   }else{
      $sql = "UPDATE {$schema}gsec_stock_company SET actual_count = (actual_count-{$_POST['count']}) WHERE id = '{$retorno}'";
   }
   pg_query($sql)or die("SQL Error ".__LINE__);
}

header("Location: stock_company_product.php?id={$retorno}");

function cleanString(){
   $utf8 = array('/[\']/u' => ' ');
   foreach ($_POST as $key => $value) {
      $_POST[$key] = preg_replace(array_keys($utf8), array_values($utf8), $value);
   }
}
?>
