<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");

  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora  = now();

    $sql = "SELECT
             C.id, C.status, C.date_added, C.coords, C.coords_formattedaddress, C.description, C.external_protocol, C.sei_num,
             T.type, T.request,
             CO.name AS company_name, CO.acron as company_acron,
             CF.name as company_father,
             S.name as street, C.address_num, C.address_complement, C.address_reference,
             SC.name as street_corner,
             N.neighborhood,
             CI.name as citizen, CI.rg, CI.cpf, CI.cnpj, CI.email, CI.phone1,
             U.name  as user_added_name
                  FROM {$schema}gsec_callcenter C
             LEFT JOIN {$schema}gsec_citizen      CI ON CI.id = C.id_citizen
             LEFT JOIN {$schema}streets            S ON  S.id = C.id_address
             LEFT JOIN {$schema}streets           SC ON SC.id = C.id_address_corner
             LEFT JOIN {$schema}neighborhood       N ON  N.id = C.id_neighborhood
             LEFT JOIN {$schema}company           CO ON CO.id = C.id_company
             LEFT JOIN {$schema}company           CF ON CF.id = CO.id_father
             LEFT JOIN {$schema}users             U  ON U.id  = C.id_user_added
             LEFT JOIN {$schema}gsec_request_type  T ON  T.id = id_subject
             WHERE C.id = '{$_GET['id']}'";

    $res = pg_query($sql)or die("<div class='text-center'>SQL error ".__LINE__."<br>Query: {$sql}</div>");
    $d   = pg_fetch_assoc($res);
    $aux = substr(str_replace("-","",$d['date_added']),0,6);
    $protocolo = $aux.".".str_pad($d['id'],4,"0",STR_PAD_LEFT);

    if($d['address_num']!= ""){ $d['street']  .= ", ".$d['address_num'];  }
    if($d['address_complement']!=""){ $complemento[] = $d['address_complement'];}
    if($d['address_reference']!="") { $complemento[] = $d['address_reference']; }
    if(isset($complemento)){ $complemento = ", <small class='text-muted'><i>".implode(", ", $complemento)."</i></small>"; }

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

?>

<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title>Atendimento - protocolo <?=$protocolo;?></title>
		<meta name="keywords" content="">
		<meta name="description" content="">
		<meta name="author" content="">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

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
                          <td>
                             <h3>Ordem de Serviço de Execução</h3>
                             <h4 style="margin-top:-5px"><?=$d['company_father'];?></h4>
                             <h5 style="margin-top:-10px"><b><?=$d['company_name'];?></b></h5>
                          </td>
                      </tr>
                  </table>
              </div>
          </div>
          <div class='row'>
            <div class='col-md-12 text-center'>
               <h3><small>Protocolo nº: </small><b><?=$protocolo;?></b> - <?=$d['type']." : ".$d['request'];?><br><small><?=formataData($d['date_added'],1);?></small></h3>
            </div>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-xs-12">
                 <table class="table table-condensed">
                    <tr>
                        <td><small class='text-muted'><i>Solicitante:</i></small><br><?=$d['citizen'];?></td>
                        <td><small class='text-muted'><i>Endereço:</i></small><br><?=$d['street'].$complemento;?></td>
                        <? if($d['street_corner']!=""){ ?>
                           <td><small class='text-muted'><i>Esquina com:</i></small><br><?=$d['street_corner'];?></td>
                        <? } ?>
                        <td colspan='3'><small class='text-muted'><i>Bairro:</i></small><br><?=$d['neighborhood'];?></td>
                    </tr>
                    <tr>
                       <td><small class='text-muted'><i>Contato:</i></small><br><?=$d['phone1'];?></td>

                       <?
                           if($d['external_protocol']!=""||$d['sei_num']!="")
                           {
                                 echo "<td colspan='3'><small class='text-muted'><i>Relato:</i></small><br>{$d['description']}</td>";
                                 echo "<td><small class='text-muted'><i>Protocolo Interno/Externo:</i></small><br><b>{$d['sei_num']}|{$d['external_protocol']}</b></td>";

                           }else {
                                 echo "<td colspan='4'><small class='text-muted'><i>Relato:</i></small><br>{$d['description']}</td>";
                           }
                        ?>

                     </tr>
                 </table>
                 <script>

                 var defaultColor   = 'blue';
                 var hoverColor     = 'red';
                 var mouseDownColor = 'purple';
                 var map;
                 var pin;

                     function GetMap() {
                          map = new Microsoft.Maps.Map('#myMap', {
                             credentials: 'Ag2oAO30HR3VWnlUOEllUDh6Va6GBmboNrDqG1KZ5fJAt4105Zgnr1uQUqa6DhzX',
                             center: new Microsoft.Maps.Location(<?=($d['coords']!=""?$d['coords']:"-26.301033,-48.840862");?>),
                             mapTypeId: Microsoft.Maps.MapTypeId.street,
                             setLang: "pt-BR",
                             zoom: 17
                          });

                             pin = new Microsoft.Maps.Pushpin(map.getCenter(), {
                             title: <?=($d['type']!=""?"'{$d['type']}:{$d['request']}'":"'Localização do evento'");?>,
                             //subTitle: <?=($d['coords_formattedaddress']!=""?"'".$d['coords_formattedaddress']."'":"'Joinville - Santa Catarina'");?>,
                             text: '',
                             color: defaultColor
                          });
                          map.entities.push(pin);

                     }

                 </script>
                 <script type='text/javascript' src='https://www.bing.com/api/maps/mapcontrol?callback=GetMap' async defer></script>
<style>
#inner {
border: 1px solid black;
}

#outer {
border: 3px solid gray;
width:100%;
display: flex;
justify-content: center;
}
</style>
                 <div class="row" style="margin-top:-20px">
                    <div class="col-md-12">
                       <div id="outer">
                          <div id="inner">
                             <div id="myMap" class="text-center" style='width:790px;height:300px; postion:center'></div>
                         </div>
                     </div>
                    </div>
                 </div>
                 <div class="row">
                    <div class="col-md-12">
                        <table class="table table-border">
                           <tr><td><h4>Descrição do(s) serviço(s) executado(s):<h4><br></td></tr>
                           <tr><td><br></td></tr>
                           <tr><td><br></td></tr>
                           <tr><td><br></td></tr>
                           <tr><td><br></td></tr>
                           <tr><td><br></td></tr>
                        </table>
                    </div>
                 </div>
                 <div class="row">
                    <div class="col-md-12">
                       <table border="0" width="100%">
                           <tr><td valign="top">Responsável:_________________________________________<br>(Nome legível e assinuatura)</td>
                               <td class="text-right" valign="top">Data de execução:_____________________<br><br>Hora inicio:__________Hora fim:__________</td>
                           </tr>
                           <tr><td colspan="2" class="text-right"><br><br><b>ATENÇÃO: </b>Todo material utilizado deve ser informado no verso desta ordem de serviço.</td></tr>
                           <tr><td colspan="2" class="text-right"><i>Inserido por: </i><b><?=$d['user_added_name'];?></b></td></tr>
                        </table>
                     </div>
                  </div>
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
