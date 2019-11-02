<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

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
    $sql = "SELECT count(*) as qtd FROM sepud.oct_events WHERE id_addressbook = '".$_GET['id']."'";
    $res = pg_query($sql)or die("SQL Error ".__LINE__);
    $aux = pg_fetch_assoc($res);

    if($aux['qtd']==0)
    {
      $sql = "DELETE FROM sepud.oct_addressbook WHERE id = '".$_GET['id']."'";
      pg_query($sql)or die("SQL Error ".__LINE__);
      header("Location: agenda_de_endereco_INDEX.php");
    }else{
      $sql = "UPDATE sepud.oct_addressbook SET active = 'f' WHERE id = '".$_GET['id']."'";
      pg_query($sql)or die("SQL Error ".__LINE__);
      header("Location: agenda_de_endereco_FORM.php?id=".$_GET['id']);
    }

    exit();
  }

  if($acao == "Atualizar")
  {
    $sql = "UPDATE sepud.oct_addressbook
            SET
                name         = $name,
                num_ref      = $num_ref,
                id_street    = $id_street,
                geoposition  = $geoposition,
                obs          = $obs,
                zipcode      = $zipcode,
                neighborhood = $neighborhood,
                zone         = $zone,
                type         = $type,
                active       = $active
            WHERE
              id = '".$id."'";
    pg_query($sql)or die("SQL Error ".__LINE__);
    header("Location: agenda_de_endereco_FORM.php?id=".$id);
    exit();
  }

  if($acao == "Inserir" && $name != "")
  {
    $sql = "INSERT INTO sepud.oct_addressbook(name,  num_ref,  id_street,  geoposition,  obs,  zipcode,  neighborhood,  zone,  type)
            VALUES                           ($name, $num_ref, $id_street, $geoposition, $obs, $zipcode, $neighborhood, $zone, $type)RETURNING  id ";
    $res = pg_query($sql)or die("SQL Error ".__LINE__);
    $aux = pg_fetch_assoc($res);
    header("Location: agenda_de_endereco_FORM.php?id=".$aux['id']);
    exit();
  }

?>
