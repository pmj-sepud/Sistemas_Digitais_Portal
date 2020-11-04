<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora  = now();

  echo "<div class='text-center'>";
  echo "<h2>TAB2: Entrega</h2>";

if($_POST['acao']=="atualizar")
{
  unset($_POST['acao']);
  cleanString();
  logger("Atualização","SAS - BEV", "Benefícios - ENTREGA DEMANDA: ".print_r($_POST, true));

  $demand_status = json_decode($_POST['demand_status']);

  if($_POST['delivery_status']!="agendado" && $_POST['delivery_status']!=""){
    $demand_status[0] = "Fechado";
    if($_POST['delivery_date']=="")
    {
      $_POST['delivery_date'] = $agora['datatimesrv'];
    }
  }else{
    $demand_status[0] = "Aberto";
    $_POST['delivery_date'] = Null;
  }

  $_POST['demand_status']=$demand_status;

  if($_POST['demand_status'][0]!="Aberto" && $_POST['demand_status'][1]!="Aberto" && $_POST['demand_status'][2]!="Aberto")
  { $_POST['status'] = "Fechado"; $_POST['date_closed'] = $agora['datatimesrv'];}else{ $_POST['status'] = "Aberto"; $_POST['date_closed'] = Null;}

  $_POST['demand_status'] = json_encode($demand_status);
  $sql = makeSql("{$schema}sas_request",$_POST,"upd","id");

  pg_query($sql)or die("Error ".__LINE__." - Query:{$sql}");
  header("Location: beneficio_FORM.php?id_request={$_POST['id']}&id_citizen={$_POST['id_citizen']}&tab=tab2");
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
