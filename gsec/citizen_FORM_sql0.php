<?
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");
$schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
$agora = now();

  //echo "<div class='text-center'>";

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
  unset($_POST['acao']);
  cleanString();
  logger("Atualização","CALLCENTER", "Callcenter - Cadastro Cidadão: ".print_r($_POST, true));

  $info = array("status"=>"Atualizou os dados cadastrais", "dados"=>$_POST);
  loggerGsec($_POST['id'], "gsec_citizen", json_encode($info));

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

     $info = array("status"=>"Cadastrou o cidadão", "dados"=>$_POST);
     loggerGsec($aux['id'], "gsec_citizen", json_encode($info));

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
      $sqlR = "DELETE FROM {$schema}gsec_citizen WHERE id = '{$_GET['id']}';
               DELETE FROM {$schema}gsec_logs WHERE id_origin = '{$_GET['id']}' AND table_origin = 'gsec_citizen'";
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
