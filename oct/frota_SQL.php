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
    $sql = "SELECT count(*) as qtd FROM {$schema}oct_rel_garrison_vehicle WHERE id_fleet = '{$_GET['id']}'";
    $res = pg_query($sql)or die("SQL Error ".__LINE__);
    $aux = pg_fetch_assoc($res);

    if($aux['qtd']==0)
    {
      logger("Remoção","Frota", "Removeu o veículo ID: {$_GET['id']}");

      $sql = "DELETE FROM {$schema}oct_fleet WHERE id = '{$_GET['id']}'";
      pg_query($sql)or die("SQL Error ".__LINE__."<pre>{$sql}</pre>");
      header("Location: frota_INDEX.php");
    }else{
      logger("Baixa","Frota", "Baixou o veículo ID: {$_GET['id']}");
      $sql = "UPDATE {$schema}oct_fleet SET active = 'f' WHERE id = '{$_GET['id']}'";
      pg_query($sql)or die("SQL Error ".__LINE__."<pre>{$sql}</pre>");
      header("Location: frota_FORM.php?id=".$_GET['id']);
    }

    exit();
  }

  if($acao == "Atualizar")
  {
    $sql = "UPDATE ".$schema."oct_fleet
            SET
                plate       = $plate,
                type        = $type,
                model       = $model,
                brand       = $brand,
                nickname    = $nickname,
                active      = $active,
                observation = $observation
            WHERE
              id = '".$id."'";
    pg_query($sql)or die("SQL Error ".__LINE__."<pre>{$sql}</pre>");
    header("Location: frota_FORM.php?id=".$id);
    exit();
  }

  if($acao == "Inserir" && $plate != "")
  {
    $sql = "INSERT INTO {$schema}oct_fleet(plate, type, model, brand, nickname, active, observation, id_company)
            VALUES                           ($plate, $type, $model, $brand, $nickname, $active, $observation, {$_SESSION['id_company']})RETURNING  id ";
    $res = pg_query($sql)or die("SQL Error ".__LINE__."<pre>{$sql}</pre>");
    $aux = pg_fetch_assoc($res);
    logger("Inserção","Frota", "Inseriu o veículo ID: {$aux['id']}");
    header("Location: frota_FORM.php?id=".$aux['id']);
    exit();
  }

?>
