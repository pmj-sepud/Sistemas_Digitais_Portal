<?
    session_start();
    require_once("../libs/php/funcoes.php");
    require_once("../libs/php/conn.php");


    foreach ($_POST as $var => $val)
    {
      if($val ==  ""){ $_POST[$var] = "Null";      }
      else{            $_POST[$var] = "'".$val."'";}
    }

    extract($_POST);

    //echo "<div class='row'><div class='col-md-9 col-md-offset-3'><pre>";
    //print_r($_POST);
    //echo "<hr>";


    if($acao = "Inserir" && $description!="" && $_SESSION['id_company']!="" && $opened_timestamp!="")
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
                         )";
      pg_query($sql)or die("SQL Error ".__LINE__);
    }


    if($_GET['acao']=="Remover" && $_GET['id']!="")
    {
      $sql = "DELETE FROM sepud.oct_administrative_events WHERE id = '".$_GET['id']."'";
      pg_query($sql)or die("SQL Error ".__LINE__);
    }
//echo "</pre></div></div>";

header("Location: eventos_administrativos_INDEX.php");
?>
