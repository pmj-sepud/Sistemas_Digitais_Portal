<?
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");
$schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
$agora = now();

  echo "<div class='text-center'>";

function loggerGsec($id_orgin, $table, $info)
{
 $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
 $id_user = $_SESSION['id'];
 $agora = now();
 $sql = "INSERT INTO {$schema}gsec_logs(id_origin, table_origin, date_added, id_user, info)
			 VALUES ('{$id_orgin}', '{$table}', '{$agora['datatimesrv']}', '{$id_user}', '{$info}')";
 pg_query($sql)or die("Erro ".__LINE__."<hr>".pg_last_error()."<hr>".$sql);
}


  if($_POST['id_new_citizen'] != "" && $_POST['id_callcenter_change_new_citizen'] != "")
  {
     $info = array("status"=>"<b>Alterou a titularidade do atendimento</b>", "dados"=>$_POST['id_new_citizen']);
     loggerGsec($_POST['id_callcenter_change_new_citizen'], "gsec_callcenter", json_encode($info));
     echo $sql = "UPDATE {$schema}gsec_callcenter SET id_citizen = '{$_POST['id_new_citizen']}' WHERE id = '{$_POST['id_callcenter_change_new_citizen']}'";
     pg_query($sql)or die("Error ".__LINE__." - Query:{$sql}");
     header("Location: callcenter_FORM.php?id={$_POST['id_callcenter_change_new_citizen']}");
  }

  function cleanString() {
    $utf8 = array('/[\']/u' => ' ');
    foreach ($_POST as $key => $value) {
          $_POST[$key] = preg_replace(array_keys($utf8), array_values($utf8), $value);
          //$_POST[$key] = htmlentities($value);
    }
  }
?>
