<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora = now();

  foreach ($_POST as $var => $val)
  {
    if($var != "acao" && $var != "id")
    {
        if($val ==  ""){ $_POST[$var] = "Null";      }
        else{            $_POST[$var] = "'".$val."'";}
    }
  }

  extract($_POST);

  if($_GET['acao'] == "Remover" && $_GET['id'] != "")
  {
      logger("Remoção","SAS - BEV", "Variável de ambiente ID: ".$_GET['id']);
      $sql = "SELECT * FROM {$schema}sas_rel_request_vars WHERE id_var = '{$_GET['id']}' LIMIT 1";
      $res = pg_query($sql)or die("<div class='text-center text-danger'>Error ".__LINE__."<br>Query : {$sql}</div>");

      if(pg_num_rows($res))
      {
          $sql = "UPDATE {$schema}sas_vars SET status = 'f' WHERE id = '{$_GET['id']}'";
          pg_query($sql)or die("<div class='text-center text-danger'>Error ".__LINE__."<br>Query : {$sql}</div>");
          header("Location: variaveis_FORM.php?id={$_GET['id']}");
      }else{
        $sql = "DELETE FROM ".$schema."sas_vars WHERE id = '".$_GET['id']."'";
        pg_query($sql)or die("SQL Error ".__LINE__);
        header("Location: variaveis.php");
      }
      exit();
  }

  if($acao == "Atualizar")
  {
    logger("Atualizando","SAS - BEV", "Variável de ambiente: ");
    $sql = "UPDATE {$schema}sas_vars
            SET
                description = $description,
                subgroup    = $subgroup,
                status      = $status
            WHERE
              id = '{$id}'";
    pg_query($sql)or die("<div class='text-center text-danger'>SQL Error ".__LINE__."<br>Query: ".$sql);
    header("Location: variaveis_FORM.php?id={$id}");
    exit();
  }

  if($acao == "Inserir" && $description != "" && $subgroup != "")
  {
    $sql = "INSERT INTO {$schema}sas_vars(description,  subgroup)VALUES($description, $subgroup)RETURNING id";
    $res = pg_query($sql)or die("SQL Error ".__LINE__);
    $aux = pg_fetch_assoc($res);
    header("Location: variaveis_FORM.php?id={$aux['id']}");
    exit();
  }

?>
