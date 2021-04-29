<?
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");
$schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
$agora = now();

  //echo "<div class='text-center'>";

if($_POST['acao']=="atualizar")
{
  unset($_POST['acao']);
  cleanString();
  logger("Atualização","CALLCENTER", "Callcenter - Cadastro Cidadão: ".print_r($_POST, true));
  $sql = makeSql("{$schema}gsec_citizen",$_POST,"upd","id");
  pg_query($sql)or die("Error ".__LINE__." - Query:{$sql}");
  header("Location: citizen_FORM.php?id={$_POST['id']}");
}

if($_POST['acao']=="inserir")
{
     unset($_POST['acao']);
     $agora                  = now();
     $_POST['date_added']    = $agora['datatimesrv'];
     $_POST['id_company']    = $_SESSION['id_company'];
     $_POST['id_user_added'] = $_SESSION['id'];
     cleanString();
     logger("Inserção","CALLCENTER", "Callcenter - Cadastro Cidadão: ".print_r($_POST, true));
     $sql = makeSql("{$schema}gsec_citizen",$_POST,"ins","id");
     $res = pg_query($sql)or die("<div class='text-center text-danger'>Error: ".__LINE__."<br>SQL {$sql}</div>");
     $aux = pg_fetch_assoc($res);
     header("Location: citizen_FORM.php?id={$aux['id']}");
}

if($_GET['acao']=="remover")
{
   $sql = "SELECT count(*) as qtd FROM {$schema}gsec_callcenter WHERE id_citizen = '{$_GET['id']}'";
   $res = pg_query($sql)or die("SQL Error ".__LINE__."<br>Query: ".$sql);
   $count = pg_fetch_assoc($res);
   if($count['qtd']!=0)
   {
      $_SESSION['error'] = "Não foi possível remover. Há solicitações registradas para este cidadão.";
      header("Location: citizen_FORM.php?id={$_GET['id']}");

   }else{
      $sqlR = "DELETE FROM ${schema}gsec_citizen WHERE id = '{$_GET['id']}'";
      pg_query($sqlR)or die("SQL Error ".__LINE__."<br>Query: ".$sqlR);
      header("Location: citizen.php");
   }

}




  //echo "</div>";

  function cleanString() {
    $utf8 = array('/[\']/u' => ' ');
    foreach ($_POST as $key => $value) {
          $_POST[$key] = preg_replace(array_keys($utf8), array_values($utf8), $value);
          //$_POST[$key] = htmlentities($value);
    }
  }
?>
