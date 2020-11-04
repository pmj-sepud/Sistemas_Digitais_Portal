<?
    session_start();
    require_once("../libs/php/funcoes.php");
    require_once("../libs/php/conn.php");
    $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");

    extract($_POST);


      if($acao == "inserir")
      {
        if($name != "")
        {
            unset($_POST['acao']);
            $sql = makeSql($schema."company",$_POST,"ins","id");
            $res = pg_query($sql)or die("Error: ".__LINE__."<br>SQL {$sql}");
            $aux = pg_fetch_assoc($res);
            header("Location: company_FORM.php?id={$aux['id']}");
        }else{
          header("Location: company_FORM.php");
        }
      }

      if($acao == "atualizar")
      {
        unset($_POST['acao']);
        if($sql = makeSql($schema."company",$_POST,"upd","id"))
        {
          pg_query($sql)or die("Error: ".__LINE__."<br>SQL {$sql}");
          header("Location: company_FORM.php?id={$id}");
        }else{
          echo "<div class='text-center'>Erro na passagem dos parametros da query</div>";
        }
      }


?>
