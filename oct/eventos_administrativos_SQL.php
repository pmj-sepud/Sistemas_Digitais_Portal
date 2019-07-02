<?
    session_start();
    require_once("../libs/php/funcoes.php");
    require_once("../libs/php/conn.php");


    foreach ($_POST as $var => $val)
    {
      if($var != "acao" && $var != "id")
      {
          if($val ==  ""){ $_POST[$var] = "Null";      }
          else{            $_POST[$var] = "'".$val."'";}
      }
    }

    extract($_POST);

    //echo "<div class='row'><div class='col-md-9 col-md-offset-3'><pre>";
    //print_r($_POST);
    //echo "<hr>";
    //exit();

    if($acao == "Inserir" && $description!="Null" && $_SESSION['id_company']!="" && $opened_timestamp!="Null")
    {

      $sql = "INSERT INTO sepud.oct_administrative_events(
                          id_addressbook,
                          id_street,
                          opened_timestamp,
                          closed_timestamp,
                          id_company,
                          id_user,
                          id_workshift,
                          description,
                          street_ref)
                  VALUES (
                          $id_addressbook,
                          $id_street,
                          $opened_timestamp,
                          $closed_timestamp,
                          '".$_SESSION['id_company']."',
                          $id_user,
                          $id_workshift,
                          $description,
                          $street_ref
                        )RETURNING id";
      $res = pg_query($sql)or die("SQL Error ".__LINE__."<hr>SQL:<br>".$sql);
      $aux = pg_fetch_assoc($res);
      //echo "<br>ID: ".$aux['id'];
      //echo "<br>INSERIDO...<br>".$sql;
      header("Location: eventos_administrativos_FORM.php?id=".$aux['id']);
      exit();
    }

    if($acao == "Atualizar" && $id!="")
    {

      $sql = "UPDATE sepud.oct_administrative_events
              SET
                          id_addressbook   = $id_addressbook,
                          id_street        = $id_street,
                          opened_timestamp = $opened_timestamp,
                          closed_timestamp = $closed_timestamp,
                          id_company       = '".$_SESSION['id_company']."',
                          id_user          = $id_user,
                          description      = $description,
                          street_ref       = $street_ref
              WHERE id = ".$id;
      pg_query($sql)or die("SQL Error ".__LINE__."<hr>SQL:<br>".$sql);
      //echo "<br>ATUALIZADO...<br>".$sql;
      header("Location: eventos_administrativos_FORM.php?id=".$id);
      exit();
    }


    if($_GET['acao']=="Remover" && $_GET['id']!="")
    {
      $sql = "DELETE FROM sepud.oct_administrative_events WHERE id = '".$_GET['id']."'";
      pg_query($sql)or die("SQL Error ".__LINE__."<hr>SQL:<br>".$sql);
      //echo "<br>REMOVIDO...<br>".$sql;
      header("Location: eventos_administrativos_INDEX.php");
      exit();
    }

    header("Location: eventos_administrativos_FORM.php");
//echo "</pre></div></div>";
?>
