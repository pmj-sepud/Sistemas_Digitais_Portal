<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");

  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora  = now();


  if($_GET['id_request'] != "")
  {
    $acao = "Atualizar";

    $sql = "SELECT CI.*,
                   U.name as name_user_register,
                   C.name as company_name, C.acron as company_acron,
                   R.*
            FROM {$schema}sas_citizen CI
            JOIN {$schema}users       U ON U.id = CI.id_user_register
            JOIN {$schema}company     C ON C.id = U.id_company
            JOIN {$schema}sas_request R ON R.id_citizen = CI.id AND R.id = {$_GET['id_request']}
            WHERE CI.id = '{$_GET['id_citizen']}'";

    $res = pg_query($sql)or die("<div class='text-center'>SQL error ".__LINE__."<br>Query: {$sql}</div>");


    $d   = pg_fetch_assoc($res);
    $protocolo = str_replace("-","",substr($d['date'],0,-12)).".".$_GET['id_request'];

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

		<title>Recibo de entrega de benefício - protocolo <?=$protocolo;?></title>
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
                          <td><h4>Secretaria de Assistência Social</h4><h3 style="margin-top:-10px"><b></b></h3></td>
                      </tr>
                  </table>
              </div>
          </div>
          <div class='row'>
            <div class='col-md-12 text-center'>
                <h3><i>Recibo de entrega de benefício</i></h3><b>Protocolo nº: <?=$protocolo;?></b>
            </div>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-xs-12 text-center">
                <br><br>
                <h5>
                  Eu <b><?=$d['name'];?></b>, portador do documento de identidade RG: <?=($d['rg']!=""?"<b>{$d['rg']}</b>":"<i class='text-muted'>[Não informado]</i>");?> e CPF: <?=($d['cpf']!=""?"<b>{$d['cpf']}</b>":"<i class='text-muted'>[Não informado]</i>");?>,<br>
                  informo que recebi <b><?=$d['food_count']?></b> cestas básicas de <b><?=$d['food_size']?></b> Kg
                </h5>


                <br><br><br><br><i>Assinatura:</i>________________________________________,<br>
                <br><i>Data do recebimento:</i> _____/_____/_____
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
</body>
</html>
<script>
  window.print();
</script>
