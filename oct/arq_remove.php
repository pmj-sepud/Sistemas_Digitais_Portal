<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");

  extract($_GET);
  $arq = str_replace("oct/","",$arq);
  logger("Remoção","OCT - arquivo", "Ocorrência n.".$id_oc.", Arquivo: ".$arq);


//echo "<div align='center'>";
//print_r_pre($_GET);

  if(file_exists($arq))
  {

  //  echo "<br><span class='text-success'><b><i class='fa fa-check'></i></b> Arquivo encontrado.</span>";

    if(unlink($arq))
    {
    //  echo "<br><span class='text-success'><b><i class='fa fa-check'></i></b> Arquivo físico removido com sucesso.</span>";
      $sql = "DELETE FROM ".$schema."oct_rel_events_images WHERE id = '".$id."'";
      pg_query($sql)or die("Erro ".__LINE__."<br>Arquivo físico removido porém registro no banco de dados não.<br>{$sql}");
    //  echo "<br><span class='text-success'><b><i class='fa fa-check'></i></b> Registro no banco de dados removido com sucesso.</span>";
    }else {
    //  echo "<br><span class='text-danger'><b><i class='fa fa-times'></i></b> Arquivo físico NÃO removido.</span>";
    }

  }else
  {
  //  echo "<br><span class='text-danger'><b><i class='fa fa-times'></i></b> Arquivo não encontrado.</span>";
  }
//echo "</div>";
header("Location: FORM.php?id={$id}");
?>
