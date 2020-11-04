<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora = now();



  echo "<div class='text-center'>";
  echo "<h2>TAB1: Busca ativa</h2>";

if($_POST['acao']=="atualizar")
{
  unset($_POST['acao']);
  cleanString();
  logger("Atualização","SAS - BEV", "Benefícios - BUSCA ATIVA: ".print_r($_POST, true));
  $sql = makeSql("{$schema}sas_request",$_POST,"upd","id");
  pg_query($sql)or die("Error ".__LINE__." - Query:{$sql}");
  header("Location: beneficio_FORM.php?id_citizen={$_POST['id_citizen']}&id_request={$_POST['id']}&tab=tab1");
}

  echo "</div>";
  function cleanString() {
    $utf8 = array(
        '/[\']/u'    =>   ' '
    );

    foreach ($_POST as $key => $value) {
          $_POST[$key] = preg_replace(array_keys($utf8), array_values($utf8), $value);
          //$_POST[$key] = htmlentities($value);
    }
  }
?>
