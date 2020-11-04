<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora  = now();

echo "<div class='text-center'>";
if($_POST['acao']=="inserir")
{
    unset($_POST['acao']);
    $_POST['average_income'] = str_replace(".","",$_POST['average_income']);
    $_POST['average_income'] = str_replace(",",".",$_POST['average_income']);
    cleanString();

    logger("Inserção","SAS - BEV", "Benefícios: ".print_r($_POST, true));

    if(in_array("alimentacao",$_POST['demand'])) //Se há demanda de alimentação, seta campos de tipo de entrega para valor default
    {
        $_POST['delivery_type'] = "retirada_eqp";
    }

    if($_POST['demand_status'][0]!="Aberto" && $_POST['demand_status'][1]!="Aberto" && $_POST['demand_status'][2]!="Aberto")
    { $_POST['status'] = "Fechado"; $_POST['date_closed'] = $agora['datatimesrv'];}else{ $_POST['status'] = "Aberto"; }

    $_POST['demand_status'] = json_encode($_POST['demand_status']);
    $_POST['demand']        = json_encode($_POST['demand']);
    $_POST['vars']          = json_encode($_POST['vars']);

    $sql = makeSql("{$schema}sas_request",$_POST,"ins","id");
    $res = pg_query($sql)or die("Error ".__LINE__." - Query:{$sql}");
    $aux = pg_fetch_assoc($res);
           header("Location: beneficio_FORM.php?id_request={$aux['id']}&id_citizen={$_POST['id_citizen']}&tab=tab0");
}

if($_POST['acao']=="atualizar")
{
  unset($_POST['acao']);
  $_POST['average_income'] = str_replace(".","",$_POST['average_income']);
  $_POST['average_income'] = str_replace(",",".",$_POST['average_income']);
  cleanString();
  logger("Atualização","SAS - BEV", "Benefícios: ".print_r($_POST, true));

  if($_POST['demand_status'][0]!="Aberto" && $_POST['demand_status'][1]!="Aberto" && $_POST['demand_status'][2]!="Aberto")
  { $_POST['status'] = "Fechado"; $_POST['date_closed'] = $agora['datatimesrv']; }else{ $_POST['status'] = "Aberto"; $_POST['date_closed'] = Null; }

  $_POST['demand_status'] = json_encode($_POST['demand_status']);
  $_POST['demand']        = json_encode($_POST['demand']);
  $_POST['vars']          = json_encode($_POST['vars']);
  $sql                    = makeSql("{$schema}sas_request",$_POST,"upd","id");
                            pg_query($sql)or die("Error ".__LINE__." - Query:{$sql}");
                            header("Location: beneficio_FORM.php?id_request={$_POST['id']}&id_citizen={$_POST['id_citizen']}&tab=tab0");
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
