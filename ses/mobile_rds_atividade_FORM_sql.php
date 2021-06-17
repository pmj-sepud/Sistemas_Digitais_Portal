<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora  = now();

echo "<div class='text-center'>";
if($_POST['acao']=="Inserir")
{
    unset($_POST['acao']);
    cleanString();
    $sql = makeSql("{$schema}ses_pncd_registro_diario_atividade",$_POST,"ins","id");
    $res = pg_query($sql)or die("Error ".__LINE__." - Query:{$sql}");
    $aux = pg_fetch_assoc($res);
    header("Location: mobile_rds_atividade_FORM.php?id_rds={$_POST['id_ses_pncd_registro_diario']}&id={$aux['id']}");
}

if($_POST['acao']=="Atualizar")
{
    unset($_POST['acao']);
    cleanString();
    $sql = makeSql("{$schema}ses_pncd_registro_diario_atividade",$_POST,"upd","id");
    $res = pg_query($sql)or die("Error ".__LINE__." - Query:{$sql}");
    header("Location: mobile_rds_atividade_FORM.php?id_rds={$_POST['id_ses_pncd_registro_diario']}&id={$_POST['id']}");
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
