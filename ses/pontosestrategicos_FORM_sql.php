<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora  = now();

echo "<div class='text-center'>";
echo "<h4>SQL para Pontos Estratégicos:</h4>";
if($_POST['acao']=="Inserir")
{
    unset($_POST['acao']);
    cleanString();
    $sql = makeSql("{$schema}ses_pe",$_POST,"ins","id");
    $res = pg_query($sql)or die("Error ".__LINE__." - Query:{$sql}");
    $aux = pg_fetch_assoc($res);
    header("Location: pontosestrategicos_FORM.php?id={$aux['id']}");
}

if($_POST['acao']=="Atualizar")
{
    unset($_POST['acao']);
    cleanString();
    $sql = makeSql("{$schema}ses_pe",$_POST,"upd","id");
    $res = pg_query($sql)or die("Error ".__LINE__." - Query:{$sql}");
    header("Location: pontosestrategicos_FORM.php?id={$_POST['id']}");
}


if($_GET['acao']=="Remover" && $_GET['id']!="")
{
    $sql = "DELETE FROM {$schema}ses_pe WHERE id = '{$_GET['id']}'";
    $res = pg_query($sql)or die("Error ".__LINE__." - Query:{$sql}");
    header("Location: pontosestrategicos.php");
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
