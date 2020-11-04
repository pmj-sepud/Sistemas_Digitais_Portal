<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora = now();



  if($_POST['acao'] == "Atualizar")
  {

        unset($_POST['acao']);


        logger("Atualização","SAS - BEV", "Cidadãos".print_r($_POST, true));

        cleanString();

        $sql = makeSql("{$schema}sas_citizen",$_POST,"upd","id");
        $res = pg_query($sql)or die("<div class='text-center text-danger'>Error: ".__LINE__."<br>SQL {$sql}</div>");
        $aux = pg_fetch_assoc($res);
        header("Location: cidadao_FORM.php?id={$_POST['id']}");
  }

  if($_POST['acao'] == "Inserir")
  {
    if($_POST['name'] != "")
    {
        unset($_POST['acao']);
        cleanString();
        logger("Inserção","SAS - BEV", "Cidadãos".print_r($_POST, true));

        $sql = makeSql("{$schema}sas_citizen",$_POST,"ins","id");
        $res = pg_query($sql)or die("<div class='text-center text-danger'>Error: ".__LINE__."<br>SQL {$sql}</div>");
        $aux = pg_fetch_assoc($res);
        header("Location: cidadao_FORM.php?id={$aux['id']}");
    }else{
      header("Location: cidadao_FORM.php");
    }
  }

  if($_GET['acao']=="Remover" && $_GET['id']!="")
  {

          $sql = "SELECT id FROM {$schema}sas_request WHERE id_citizen = '{$_GET['id']}' LIMIT 1";
          $res = pg_query($sql)or die("<div class='text-center text-danger'>SQL Error ".__LINE__."<br>Query: {$sql}</div>");

          if(!pg_num_rows($res))
          {
            logger("Remoção","SAS - BEV", "Cidadão ID: ".$_GET['id']);
            $sql = "DELETE FROM {$schema}sas_citizen WHERE id = '{$_GET['id']}'";
            pg_query($sql)or die("<div class='text-center text-danger'>SQL Error ".__LINE__."<br>Query: {$sql}</div>");
            header("Location: cidadao.php");
          }else{
            logger("Remoção","SAS - BEV", "Cidadão ID: ".$_GET['id']." não permitido a remoção pois há benefício(s) associado(s)");
            $_SESSION['error'] = "<span class='text-danger'><b>AVISO: </b>Não é possível remover este cadastro pois possui benefício(s) associado(s).</span>";
            header("Location: cidadao_FORM.php?id={$_GET['id']}");
          }

  }

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
