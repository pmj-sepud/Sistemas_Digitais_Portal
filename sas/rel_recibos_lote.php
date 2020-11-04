<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora  = now();

    logger("Impressão","SAS - BEV", "Recibos em lote. ".print_r($_POST, true));

  if($_POST['data_inicial'] != "" && $_POST['data_final'] != "" && $_POST['id_company'] != "")
  {

    if($_POST['delivery_type_filtro']!=""){
        $sql_filtro_delivery_type = " AND R.delivery_type = '{$_POST['delivery_type_filtro']}'";
        if($_POST['delivery_type_filtro']=="retirada_eqp"){$txt_filtro_delivery = "Filtro: RETIRADA NO EQUIPAMENTO";}
        else{ $txt_filtro_delivery = "Filtro: ENTREGA EM DOMICÍLIO";}
    }


    if($_POST['sas_monitor_filtro']!=""){
        $sql_filtro_sas_monitor = " AND C.sas_monitor = '{$_POST['sas_monitor_filtro']}'";
        if($_POST['sas_monitor_filtro']=="t"){ $txt_filtro_sas_monitor = "Filtro: Apenas famílias acompanhadas.";}
                                         else{ $txt_filtro_sas_monitor = "Filtro: Apenas famílias NÃO acompanhadas.";}
    }


    $sql = "SELECT R.*,
                   C.name, C.phone, C.id_street, C.address_number, C.address_complement, C.address_reference,
                   CO.name as company_name,
                   S.name  as street_name,
                   N.neighborhood
            FROM {$schema}sas_request R
            JOIN {$schema}sas_citizen        C ON C.id  = R.id_citizen
            JOIN {$schema}company           CO ON CO.id = R.id_company
            LEFT JOIN {$schema}streets       S ON S.id  = C.id_street
            LEFT JOIN {$schema}neighborhood  N ON N.id  = C.id_neighborhood
            WHERE
              R.status = 'Aberto' AND
              R.id_company = '{$_POST['id_company']}' AND
              R.date BETWEEN '{$_POST['data_inicial']} 00:00:00' AND '{$_POST['data_final']} 23:59:59'
              {$sql_filtro_delivery_type}
              {$sql_filtro_sas_monitor}
            ORDER BY {$_POST['order_filtro']} ASC";

    $res = pg_query($sql)or die("<div class='text-center'>SQL error ".__LINE__."<br>Query: {$sql}</div>");

    while($d = pg_fetch_assoc($res)){
        $dados[]      = $d;
        $company_name = $d['company_name'];
    }



/*
    $requerente = array("name"          =>$d['name'],
                        "birth"         =>$d['birth'],
                        "rg"            => $d['rg'],
                        "cpf"           => $d['cpf'],
                        "company_name"  =>$d['company_name'],
                        "company_acron" => $d['company_acron']);

    $demandsel      = json_decode($d['demand']);
    $demandstatus   = json_decode($d['demand_status']);
    $varssel        = json_decode($d['vars']);
*/
  }
?>

<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title>Lista de recibos de entrega de benefício - <?=formataData($_POST['data_inicial'],1);?> a <?=formataData($_POST['data_final'],1);?></title>
		<meta name="keywords" content="">
		<meta name="description" content="">
		<meta name="author" content="">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.css" />

		<link rel="stylesheet" href="../assets/vendor/font-awesome/css/font-awesome.css" />
		<!--<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">-->
		<link rel="stylesheet" href="../assets/vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="../assets/vendor/bootstrap-datepicker/css/datepicker3.css" />

		<!-- Specific Page Vendor CSS -->

		<link rel="stylesheet" href="../assets/vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />

		<link rel="stylesheet" href="../assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css" />

		<link rel="stylesheet" href="../assets/vendor/morris/morris.css" />
		<link rel="stylesheet" href="../assets/vendor/pnotify/pnotify.custom.css" />





		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"
			integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
			crossorigin=""/>

			<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"
				integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="
				crossorigin=""></script>

		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">

		<!-- Theme CSS -->
		<link rel="stylesheet" href="../assets/stylesheets/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="../assets/stylesheets/skins/default.css" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="../assets/stylesheets/theme-custom.css">

		<!-- Head Libs -->
		<script src="../assets/vendor/modernizr/modernizr.js"></script>


		<link  href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />


		<style>
			.box_shadow
			{
			  -webkit-box-shadow: 10px 10px 56px -12px rgba(0,0,0,0.3);
			  -moz-box-shadow: 10px 10px 56px -12px rgba(0,0,0,0.3);
			  box-shadow: 10px 10px 56px -12px rgba(0,0,0,0.3);
			}
		</style>
	</head>
	<body>


    <div class="row">
      <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="box_shadow">
          <div class="row">
              <div class='col-md-12 text-center'>
                  <table border='0' width='100%'>
                      <tr>
                          <td><img src="https://www.joinville.sc.gov.br/wp-content/uploads/2017/07/logoPMJ2x.png"></td>
                          <td><h3><?=$company_name;?></h3></td>
                      </tr>
                  </table>
              </div>
          </div>
          <div class='row'>
            <div class='col-md-12 text-center'>
                <h4><i>Lista de recibos de entrega de benefício</i></h4>
                <h3><small>Período: </small><b><?=formataData($_POST['data_inicial'],1);?> a <?=formataData($_POST['data_final'],1);?></b></h3>
                <?="<h4><br>".$txt_filtro_delivery."<br>".$txt_filtro_sas_monitor."</h4>";?>
            </div>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-xs-12">
                <?
                if(isset($dados) && count($dados))
                {
                  echo "<table class='table table-bordered'>";
                  echo "<thead><tr>
                          <th>#</th>
                          <th>Protocolo</th>
                          <th>Nome</th>
                          <th>Endereço</th>
                          <th>Bairro</th>
                          <th>Telefone</th>
                          <th>Cesta</th>
                          <th  width='200px'>Num. DOC. com foto</th>
                          <th  width='300px'>Nome de quem retirou</th>
                        </tr></thead>";

                  echo "</tbody>";
                      $cc=1;
                      for($c=0;$c<count($dados);$c++)
                      {
                        $protocolo = "<small>".str_replace("-","",substr($dados[$c]['date'],0,-12))."</small>.<b>".$dados[$c]['id']."</b>";
                        echo "<tr>";
                            echo "<td class='text-muted'>".$cc++."</td>";
                            echo "<td>{$protocolo}</td>";
                            echo "<td>{$dados[$c]['name']}</td>";
                            echo "<td>".$dados[$c]['street_name'].", ".$dados[$c]['address_number'].
                                       ($dados[$c]['address_complement']!=""? " (".$dados[$c]['address_complement'].")"                        :"").
                                       ($dados[$c]['address_reference'] !=""? "<br><small>Refêrencia: </small>".$dados[$c]['address_reference']:"").
                                  "</td>";
                            echo "<td>{$dados[$c]['neighborhood']}</td>";
                            echo "<td nowrap>{$dados[$c]['phone']}</td>";
                            echo "<td>{$dados[$c]['food_count']}x {$dados[$c]['food_size']}Kg</td>";
                            echo "<td></td>";
                            echo "<td></td>";
                        echo "</tr>";
                      }

                  echo "</tbody>";
                  echo "</table>";

                }else {
                  echo "Nenhum benfício em aberto para este período.";
                }
                  //  print_r_pre($dados);
                ?>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
</body>
</html>
<? if(isset($dados) && count($dados)){ ?>
  <script>
      window.print();
  </script>

<? } ?>
