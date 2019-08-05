<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  $agora        = now();
  $id_workshift = $_GET['id_workshift'];
  logger("Acesso","OCT - Impressão do relatório de turno, turno ID: ".$id_workshift);

  if($id_workshift)
  {

      $sql   = "SELECT
                      	C.name as company_name,
                        C.workshift_rel_config->'autuacoes' AS config_rel_autuacao,
			                  C.workshift_rel_config->'conducoes' AS config_rel_conducao,
                      	W.*
                      FROM
                      	sepud.oct_workshift W
                      JOIN sepud.company C ON C.id = W.id_company
                      WHERE W.id = '".$id_workshift."'";

      $res   = pg_query($sql)or die("SQL error ".__LINE__."<br>Query: ".$sql);
      $turno = pg_fetch_assoc($res);

      $sql = "SELECT
                  U.name as nome, U.id as id_user, U.registration, U.nickname,
                  WP.*
                FROM
                        sepud.oct_rel_workshift_persona WP
                   JOIN sepud.users                      U ON U.id = WP.id_person
                WHERE
                  WP.id_shift =  '".$turno['id']."'
                ORDER BY U.name ASC";
      $resRecursos = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);

      while($d = pg_fetch_assoc($resRecursos))
      {
          if($d['type']=="agente")
          {
                if($d['status']=="ativo"){  $turno_agentes_campo['ativos'][] = $d; $qtd_agentes_ativos[] = $d["id_user"];}
                else                     {  $turno_agentes_campo['outros'][] = $d; $qtd_agentes_afastados[] = $d["id_user"];}
          }else{
                $turno_recursos[$d['type']][] = $d;
                $qtd_agentes_ativos[] = $d["id_user"];
          }

      }

      if(isset($qtd_agentes_ativos) && count($qtd_agentes_ativos))      { $turno_qtd_agentes_ativos    = count(array_values(array_unique($qtd_agentes_ativos)));    }else{$turno_qtd_agentes_ativos=0;}
      if(isset($qtd_agentes_afastados) && count($qtd_agentes_afastados)){ $turno_qtd_agentes_afastados = count(array_values(array_unique($qtd_agentes_afastados))); }else{$turno_qtd_agentes_afastados=0;}

      $sqlG = "SELECT count(*) as qtd, SUM(final_km-initial_km) as km_rodado FROM sepud.oct_garrison G WHERE G.id_workshift = '".$turno['id']."'";
      $resG = pg_query($sqlG)or die("SQL error ".__LINE__);
      $garrison_info = pg_fetch_assoc($resG);

      $sqlOC = "SELECT
                	E.id_garrison, S.name as logradouro, AB.name as addressbook_local, AB.obs as addressbook_ref, T.name as ocorrencia, E.description, E.date, E.closure, E.id
                FROM
                	sepud.oct_events E
                JOIN sepud.oct_event_type T ON T.id = E.id_event_type
                LEFT JOIN sepud.streets S ON S.id = E.id_street
                LEFT JOIN sepud.oct_addressbook AB ON AB.id = E.id_addressbook
                WHERE
                	E.id_workshift = '".$turno['id']."'
                ORDER BY E.date ASC";
      $resOc = pg_query($sqlOC)or die("SQL error ".__LINE__."<br>Query: ".$sqlOC);
      $status_ocs['abertas'] = $status_ocs['fechadas'] = $status_ocs['total'] = 0;
      while($dOc = pg_fetch_assoc($resOc))
      {
        $status_ocs['tipo_oc'][$dOc['ocorrencia']]++;
        $status_ocs['total']++;
        $ocorrencias[] = $dOc;

        $guarnicoes_total_oc[$dOc['id_garrison']]++;


      }

      //Guarniçoes
      $sql = "SELECT
                G.*,
                F.plate, F.type, F.model, F.brand, F.nickname
              FROM
                sepud.oct_garrison
                G JOIN sepud.oct_fleet F ON F.ID = G.id_fleet
              WHERE
                G.id_workshift = '".$turno['id']."' ORDER BY opened ASC";
      $res = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
      while($d = pg_fetch_assoc($res))
      {
        $guarnicoes[] = $d;
        $sqlGP = "SELECT
                      GP.type,
                      U.name, U.nickname, U.registration
                    FROM
                      sepud.oct_rel_garrison_persona GP
                      JOIN sepud.users U ON U.ID = GP.id_user
                    WHERE
                      GP.id_garrison = '".$d['id']."' ORDER BY GP.type ASC";

        $resGP = pg_query($sqlGP)or die("Erro ".__LINE__."<br>".$sqlGP);
        while($dGP = pg_fetch_assoc($resGP))
        {
          $guarnicoes_participantes[$d['id']][] = $dGP;
        }
      }


}
?>
<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title><?=$_SESSION['company_acron']."_ROTSS_Relatorio_de_turno_".str_replace("-","_",substr($turno['opened'],0,10));?></title>
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


  <!-- start: page -->
  <div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
      <section class="box_shadow">
        <div class="row">
            <div class='col-md-12 text-center'>
                <table border='0' width='100%'>
                    <tr>
                        <td><img src="https://www.joinville.sc.gov.br/wp-content/uploads/2017/07/logoPMJ2x.png"></td>
                        <td><h4>Secretaria de Proteção Civil e Segurança Pública</h4><h3 style="margin-top:-10px"><b><?=$turno['company_name'];?></b></h3></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class='row'>
          <div class='col-md-12 text-center'>
              <h3><b><i>Relatório de turno de trabalho</i></b></h3>
          </div>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-xs-6">
              <table class='table table-condensed'>
                <thead><tr><th colspan="2">Informações:</th></tr></thead>
                <tbody>
                    <tr><td nowrap>Turno nº:</td><td><?="<b><span style='font-size:18px'>".str_pad($id_workshift,5,"0",STR_PAD_LEFT)."</span></b>";?></td></tr>
                    <tr><td nowrap>Grupo:</td><td><?=$turno["workshift_group"];?></td></tr>
                    <tr><td nowrap>Início:</td>  <td><?=formataData($turno["opened"],1);?></td></tr>
                    <tr><td nowrap>Fim:</td>     <td><?=formataData($turno["closed"],1);?></td></tr>
                    <tr><td nowrap>Status:</td>     <td><?=ucfirst($turno["status"]);?></td></tr>
                </tbody>
              </table>
            </div>
            <div class="col-xs-6">
              <table class='table table-condensed'>
                <thead><tr><th colspan="2">Resumo operacional:</th></tr></thead>
                <tbody>

                    <tr><td rowspan="1">Ocorrências:</td><td><b><?=$status_ocs['total'];?></b> ocorrências geradas</td></tr>

                    <tr><td rowspan="2">Expediente:</td> <td><b><?=$turno_qtd_agentes_ativos;?></b> agentes ativos</td></tr>
                    <tr>                                 <td><b><?=$turno_qtd_agentes_afastados;?></b> agentes afastados</td></tr>
                    <tr><td rowspan="2">Guarnições:</td> <td><b><?=$garrison_info['qtd'];?></b> guarnições empenhadas</td></tr>
                    <tr>                                 <td><b><?=$garrison_info['km_rodado'];?></b> km percorridos</td></tr>
                </tbody>
              </table>
            </div>
          </div>


          <div class="row">
            <div class="col-xs-12">
                <table class='table table-condensed'>
                  <thead><tr><th>Observações gerais:</th></tr></thead>
                  <tbody><tr><td><?=$turno['observation'];?></td></tr></tbody>
                </table>
            </div>
          </div>

          <div class="row">
            <div class="col-xs-12">
              <table class='table table-condensed'>
                <thead><tr><th>Resumo das ocorrências:</th>
                           <th class='text-center'>Qtd.</th>
                           <th class='text-center'>%</th></tr>
                </thead>
                <tbody>
                    <?

                      if(isset($status_ocs['tipo_oc']))
                      {
                          $ocs = $status_ocs['tipo_oc'];
                          foreach($ocs as $oc => $qtd)
                          {
                            //
                            echo "<tr><td>".$oc."</td>
                                      <td class='text-center'><b>".$qtd."</b></td>
                                      <td class='text-center'><b>".round(($qtd*100)/$status_ocs['total'],1)." %</b></td></tr>";
                          }
                      }else {
                        echo "<tr><td class='text-muted'><i>Nenhuma ocorrência gerada nesta turno de trabalho.</i></td></tr>";
                      }
                    ?>
                </tbody>
              </table>
            </div>
          </div>


<? if($turno['config_rel_autuacao']=="true"){ ?>
          <div class='row'>
            <div class="col-xs-12">
              <table class='table table-condensed'>
              <?
                $sqlAutos = "SELECT
                              	V.id_events,
                              	V.ait,
                              	V.cod_infra,
                              	V.data_rec_auto,
                                V.licence_plate,
                                V.observation,
                                U.registration, U.nickname, U.name
                              FROM
                              	sepud.oct_vehicles V
                              LEFT JOIN sepud.users U ON U.id = V.auto_id_user
                              WHERE V.AIT != ''
                                AND V.id_events in (SELECT id FROM sepud.oct_events E WHERE E.id_workshift = '".$turno['id']."')";
                 $resAutos = pg_query($sqlAutos)or die("SQL error ".__LINE__."<br>Query: ".$sqlAutos);

                        if(pg_num_rows($resAutos))
                        {
                          echo "<thead><tr><th colspan='5'><h4>Autuações de trânsito:</h4></th></tr>
                                       <tr><td class='text-muted'><i>AIT</i></td>
                                           <td class='text-muted'><i>Cód. infração</i></td>
                                           <td class='text-muted'><i>Placa</i></td>
                                           <td class='text-muted'><i>Agente</i></td>
                                           <td class='text-muted'><i>Observações</i></td>
                                       </tr>
                                </thead>
                                <tbody>";
                          while($dAuto = pg_fetch_assoc($resAutos))
                          {
                              $nome = ($dAuto['name']!=""?$dAuto['nickname']."</b> - ".$dAuto['name']." <i>(Matrícula: ".$dAuto['registration'].")</i>":"<span class='text-muted'>- - -</span>");
                              echo "<tr>";
                                  echo "<td>".$dAuto['ait']."</td>";
                                  echo "<td>".$dAuto['cod_infra']."</td>";
                                  echo "<td>".$dAuto['licence_plate']."</td>";
                                  echo "<td><b>".$nome."</td>";
                                  echo "<td>".$dAuto['observation']."</td>";
                              echo "</tr>";
                          }
                          echo "</tbody>";
                        }else{
                          echo "<thead><tr><th>Autuações de trânsito:</th></tr></thead>
                                <tbody><tr><td class='text-muted'><i>Nenhum auto de infração lavrado neste turno.</i></td></tr></tbody>";
                        }
                    ?>
              </table>
          </div>
        </div>
<?
  }

if($turno['config_rel_conducao']=="true"){

?>
        <div class="row">
          <div class='col-xs-12'>
            <table class='table table-condensed'>
              <?
                $sqlCond = "SELECT
                            	E.id_garrison,
                            	V.*
                            FROM
                            	sepud.oct_victim V
                            JOIN sepud.oct_events E ON E.id = V.id_events
                            WHERE
                            	V.conducted = TRUE
                            	AND V.id_events IN (SELECT id	FROM sepud.oct_events E WHERE	E.id_workshift = '".$turno['id']."')";
                $resCond = pg_query($sqlCond)or die("SQL error ".__LINE__."<br>Query: ".$sqlAutos);

                      if(pg_num_rows($resCond))
                      {
                        echo "<thead><tr><th colspan='4'><h4>Conduções a delegacia:</h4></th></tr></thead>
                        <tr><td class='text-muted' width='300px'><i>Nome</i></td>
                            <td class='text-muted'><i>Motivo</i></td>
                            <td class='text-muted'><i>Guarnição</i></td>
                        <tbody>";
                        while($dCond = pg_fetch_assoc($resCond))
                        {

                          echo "<tr>";
                            echo "<td>".$dCond['name']."</td>";
                            echo "<td>".$dCond['description']."</td>";
                            echo "<td>";
                                    unset($txt_integrantes_guarnicao);
                                    if(isset($guarnicoes_participantes[$dCond['id_garrison']]))
                                    {
                                        for($i=0;$i<count($guarnicoes_participantes[$dCond['id_garrison']]);$i++)
                                        {
                                            $dGP = $guarnicoes_participantes[$dCond['id_garrison']][$i];
                                            //$txt_integrantes_guarnicao[] = " <span class='text-muted'>".$dGP['type']."</span>: <b>".$dGP['registration']."</b> - ".$dGP['name'];
                                            $txt_integrantes_guarnicao[] = $dGP['nickname'];
                                        }
                                        echo implode(",",$txt_integrantes_guarnicao);
                                    }else {
                                        echo "<small class='text-muted'><i>Nenhuma guarnição associada para esta ocorrência de condução</i></small>";
                                    }
                            echo "</td>";
                          echo "</tr>";
                        }
                        echo "</tbody>";
                      }else{
                        echo "<thead><tr><th><h4>Conduções a delegacia:</h4></th></tr></thead>
                              <tbody><tr><td class='text-muted'><i>Nenhuma condução realizada neste turno.</i></td></tr></tbody>";
                      }
                  ?>

            </table>
          </div>
        </div>

<? } ?>


          <div class="row">
            <div class="col-xs-12">
              <?
                //print_r_pre($guarnicoes);
              ?>
                      <table class='table table-condensed'>
                      <thead><tr><th colspan="8"><h4>Guarnições:</h4></th></tr></thead>
                        <tbody>
                                  <?
                                          if(isset($guarnicoes) && count($guarnicoes))
                                          {
                                              for($i = 0;$i<count($guarnicoes);$i++)
                                              {
                                                  $d = $guarnicoes[$i];
                                                  //Se data inicial e final são iguais, mostra apenas a hora inicial e final//
                                                  if(substr($d['opened'],0,10) == substr($d['closed'],0,10))
                                                  {
                                                      $data_inicial = substr($d['opened'],11,5);
                                                      $data_final   = substr($d['closed'],11,5);
                                                  }else {
                                                    $data_inicial = formataData($d['opened'],1);
                                                    $data_final   = formataData($d['closed'],1);
                                                  }

                                                  echo "<tr>";
                                                    echo "<td><small class='text-muted'>ID:</small><br><b>".$d['id']."</b></td>";
                                                    echo "<td><small class='text-muted'>Placa:</small><br><b>".$d['plate']."</b></td>";
                                                    echo "<td><small class='text-muted'>Veículo:</small><br><b>".$d['brand']." ".$d['model']."</b></td>";
                                                    echo "<td><small class='text-muted'>Ocorrências atendidas:</small><br><b>".$guarnicoes_total_oc[$d['id']]."</b></td>";
                                                    echo "<td width='100px'><small class='text-muted'>Km inicial:</small><br><b>".number_format($d['initial_km'],0,'','.')."</b></td>";
                                                    echo "<td width='100px'><small class='text-muted'>Km final:</small><br><b>".number_format($d['final_km'],0,'','.')."</b></td>";
                                                    echo "<td width='50px'><small class='text-muted'>Percorrido:</small><br><b>".($d['final_km'] != "" && $d['initial_km'] != ""? $d['final_km'] - $d['initial_km']." Km":"-")."</b></td>";
                                                    echo "<td width='100px'><small class='text-muted'>Hora de início:</small><br><b>".$data_inicial."</b></td>";
                                                    echo "<td width='100px'><small class='text-muted'>Hora de término:</small><br><b>".$data_final."</b></td>";
                                                  echo "</tr>";
                                                  if($d['observation']!="")
                                                  {
                                                    echo "<tr><td colspan='8'><small class='text-muted'>Observações:</small><br>".($d['observation']!=""?$d['observation']:"<span class='text-muted'><i>Nenhuma observação</i></span>")."</td></tr>";
                                                  }


                                                  echo "<tr><td colspan='8'><small class='text-muted'>Motorista e passageiro(s):</small><br>";
                                                  //Seleciona os Integrantes da guarnição//

                                                  unset($txt_integrantes_guarnicao);
                                                  if(isset($guarnicoes_participantes[$d['id']]))
                                                  {
                                                        for($c=0;$c<count($guarnicoes_participantes[$d['id']]);$c++)
                                                        {
                                                            $dGP = $guarnicoes_participantes[$d['id']][$c];
                                                            $txt_integrantes_guarnicao[] = " <span class='text-muted'>".$dGP['type']."</span>: <b>".$dGP['registration']."</b> - ".$dGP['name'];
                                                        }
                                                          echo implode(",",$txt_integrantes_guarnicao);
                                                  }else {
                                                    echo "<span style='color:red'><i>Nenhum motorista ou passageiro associado a esta guarnição.</i></span>";
                                                  }

                                                  echo "</td></tr>";
                                              }

                                          }else {
                                              echo "<tr><td><small class='text-muted'>Nenhuma guarnição criada.</small></td></tr>";
                                          }
                                  ?>

                        </tbody>
                      </table>
            </div><!--<div class="col-xs-12">-->
          </div><!--<div class="row">-->

          <div class="row">
            <div class="col-xs-12">
              <table class='table table-condensed'>
              <thead>
                <tr><td colspan="4"><h4>Coordenação:</h4></td></tr>
                <tr class='text-muted'>
                  <td class='text-center' width='25px'><small><i>Matrícula</i></smalltdth>
                  <td><small><i>Nome</i></small></td>
                  <td class='text-center'><small><i>Entrada</i></small></td>
                  <td class='text-center'><small><i>Saída</i></small></td>
                </tr>
              </thead>
              <tbody>
                <?

                            if(isset($turno_recursos['coordenacao']) && count($turno_recursos['coordenacao']))
                            {
                                      $coord = $turno_recursos['coordenacao'];
                                      for($i=0;$i<count($coord);$i++)
                                      {
                                        if($coord[$i]['opened']!=""){
                                            $aux       = explode(" ",formataData($coord[$i]['opened'],1));
                                            $dt_opened = "<small>".$aux[0]."</small> <b>".$aux[1]."</b>";
                                          }else{
                                            $dt_opened = "";
                                          }

                                          if($coord[$i]['closed']!=""){
                                              $aux       = explode(" ",formataData($coord[$i]['closed'],1));
                                              $dt_closed = "<small>".$aux[0]."</small> <b>".$aux[1]."</b>";
                                            }else{
                                              $dt_closed = "";
                                            }

                                        echo "<tr>";
                                          echo "<td class='text-center'>".$coord[$i]['registration']."</td>";
                                          echo "<td>".$coord[$i]['nome']."</td>";
                                          echo "<td width='125px' class='text-center'>".$dt_opened."</td>";
                                          echo "<td width='125px' class='text-center'>".$dt_closed."</td>";
                                        echo "</tr>";
                                      }
                            }else{
                              echo "<tr><td colspan='4' class='text-muted'><i>Nenhum coordenador designado.</i></td></tr>";
                            }
                ?>
              </tbody>
              </table>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <table class='table table-condensed'>
              <thead>
                <tr><td colspan="4"><h4>Central de atendimento:</h4></td></tr>
                <tr class='text-muted'>
                  <td class='text-center' width='25px'><small><i>Matrícula</i></smalltdth>
                  <td><small><i>Nome</i></small></td>
                  <td class='text-center'><small><i>Entrada</i></small></td>
                  <td class='text-center'><small><i>Saída</i></small></td>
                </tr>
              </thead>
              <tbody>
                <?

                            if(isset($turno_recursos['central']) && count($turno_recursos['central']))
                            {
                                      $coord = $turno_recursos['central'];
                                      for($i=0;$i<count($coord);$i++)
                                      {
                                        if($coord[$i]['opened']!=""){
                                            $aux       = explode(" ",formataData($coord[$i]['opened'],1));
                                            $dt_opened = "<small>".$aux[0]."</small> <b>".$aux[1]."</b>";
                                          }else{
                                            $dt_opened = "";
                                          }

                                          if($coord[$i]['closed']!=""){
                                              $aux       = explode(" ",formataData($coord[$i]['closed'],1));
                                              $dt_closed = "<small>".$aux[0]."</small> <b>".$aux[1]."</b>";
                                            }else{
                                              $dt_closed = "";
                                            }

                                        echo "<tr>";
                                          echo "<td class='text-center'>".$coord[$i]['registration']."</td>";
                                          echo "<td>".$coord[$i]['nome']."</td>";
                                          echo "<td width='125px' class='text-center'>".$dt_opened."</td>";
                                          echo "<td width='125px' class='text-center'>".$dt_closed."</td>";
                                        echo "</tr>";
                                      }
                            }else{
                              echo "<tr><td colspan='4' class='text-muted'><i>Nenhum agente designado.</i></td></tr>";
                            }
                ?>
              </tbody>
              </table>
            </div>
          </div>

          <div class="row">
            <div class="col-xs-12">
                      <table class='table table-condensed'>
                                  <thead><tr><th colspan="6"><h4>Agentes designados:</h4></th></tr></thead>
                                  <tbody>
                                        <?
                                            if(isset($turno_agentes_campo))
                                            {
                                              echo "<tr>
                                                          <td width='25px'><b>Ativos</b></td>
                                                          <td class='text-center' width='25px'><small><i>Matrícula</i></small></td>
                                                          <td><small><i>Nome</i></small></td>
                                                          <td>&nbsp;</td>
                                                          <td class='text-center'><small><i>Entrada</i></small></td>
                                                          <td class='text-center'><small><i>Saída</i></small></td>
                                                    </tr>";
                                              if(isset($turno_agentes_campo['ativos']))
                                              {
                                                $agentes = $turno_agentes_campo['ativos'];
                                                for ($i=0;$i<count($agentes);$i++)
                                                {
                                                        if($agentes[$i]['opened']!=""){
                                                            $aux       = explode(" ",formataData($agentes[$i]['opened'],1));
                                                            $dt_opened = "<small>".$aux[0]."</small> <b>".$aux[1]."</b>";
                                                          }else {
                                                            $dt_opened = "";
                                                          }

                                                          if($agentes[$i]['closed']!=""){
                                                              $aux       = explode(" ",formataData($agentes[$i]['closed'],1));
                                                              $dt_closed = "<small>".$aux[0]."</small> <b>".$aux[1]."</b>";
                                                            }else {
                                                              $dt_closed = "";
                                                            }

                                                        echo "<tr>";
                                                          echo "<td>&nbsp;</td>";
                                                          echo "<td class='text-center'>".$agentes[$i]['registration']."</td>";
                                                          echo "<td>".$agentes[$i]['nome']."</td>";
                                                          //echo "<td>".ucfirst($agentes[$i]['status'])."</td>";
                                                          echo "<td>&nbsp;</td>";
                                                          echo "<td width='125px' class='text-center'>".$dt_opened."</td>";
                                                          echo "<td width='125px' class='text-center'>".$dt_closed."</td>";
                                                        echo "</tr>";
                                                }
                                              }else {
                                                echo "<tr><td colspan='6' class='text-muted'><i>Nenhum agente ativo.</i></td></tr>";
                                              }
                                              echo "<tr>
                                                      <td width='25px'><b>Afastados</b></td>
                                                      <td class='text-center' width='25px'><small><i>Matrícula</i></small></td>
                                                      <td><small><i>Nome</i></small></td>
                                                      <td><small><i>Status</i></small></td>
                                                      <td class='text-center'><small><i>Entrada</i></small></td>
                                                      <td class='text-center'><small><i>Saída</i></small></td>
                                                    </tr>";

                                              if(isset($turno_agentes_campo['outros']))
                                              {
                                                $agentes = $turno_agentes_campo['outros'];
                                                for ($i=0;$i<count($agentes);$i++)
                                                {
                                                        if($agentes[$i]['opened']!=""){
                                                            $aux       = explode(" ",formataData($agentes[$i]['opened'],1));
                                                            $dt_opened = "<small>".$aux[0]."</small> <b>".$aux[1]."</b>";
                                                          }else {
                                                            $dt_opened = "";
                                                          }

                                                          if($agentes[$i]['closed']!=""){
                                                              $aux       = explode(" ",formataData($agentes[$i]['closed'],1));
                                                              $dt_closed = "<small>".$aux[0]."</small> <b>".$aux[1]."</b>";
                                                            }else {
                                                              $dt_closed = "";
                                                            }
                                                        echo "<tr>";
                                                          echo "<td>&nbsp;</td>";
                                                          echo "<td class='text-center'>".$agentes[$i]['registration']."</td>";
                                                          echo "<td>".$agentes[$i]['nome']."</td>";
                                                          echo "<td>".ucfirst($agentes[$i]['status'])."</td>";
                                                          echo "<td width='125px' class='text-center'>".$dt_opened."</td>";
                                                          echo "<td width='125px' class='text-center'>".$dt_closed."</td>";
                                                        echo "</tr>";
                                                }
                                              }else {
                                                echo "<tr><td colspan='6' class='text-muted'><i>Nenhum agente afastado.</i></td></tr>";
                                              }

                                            }else {
                                              echo "<tr><td colspan='6' class='text-muted'><i>Nenhum agente designado.</i></td></tr>";
                                            }
                                        ?>

                                  </tbody>
                                  </table>
            </div><!--<div class="col-xs-12">-->
          </div><!--<div class="row">-->
<?

      $sqlAE = "SELECT
              	U.name, U.nickname, U.registration,
              	AB.name as ad_name, AB.obs as ad_obs,
              	S.name as street_name,
              	AD.*
              FROM
              	sepud.oct_administrative_events AD
              LEFT JOIN sepud.users U 					 ON U.id = AD.id_user
              LEFT JOIN sepud.oct_addressbook AB ON AB.id = AD.id_addressbook
              LEFT JOIN sepud.streets S 				 ON S.id = AD.id_street
              WHERE
              	id_workshift = '".$turno['id']."'
              ORDER BY opened_timestamp ASC";
     $resAE = pg_query($sqlAE)or die("SQL error ".__LINE__."<br>Query: ".$sqlAE);

?>

            <div class="row">
              <div class="col-xs-12">
                        <table class='table table-condensed'>
                        <thead><tr><th colspan="6"><h4>Ocorrências administrativas:</h4></th></tr></thead>
                          <tbody>
                              <?
                                  if(pg_num_rows($resAE))
                                  {
                                      while($dAE = pg_fetch_assoc($resAE))
                                      {
                                        if(substr($dAE['opened_timestamp'],0,10) == substr($dAE['closed_timestamp'],0,10))
                                        {
                                          $dt_inicio = substr($dAE['opened_timestamp'],11,5);
                                          $dt_fim    = substr($dAE['closed_timestamp'],11,5);
                                        }else {
                                          $dt_inicio = formataData($dAE['opened_timestamp'],1);
                                          $dt_fim    = formataData($dAE['closed_timestamp'],1);
                                        }
                                        echo "<tr>";
                                          echo "<td nowrap><small class='text-muted'>Início:<br></small>".$dt_inicio."</td>";
                                          echo "<td nowrap><small class='text-muted'>Fim:<br></small>".$dt_fim."</td>";
                                          echo "<td nowrap><small class='text-muted'>Agente:<br></small>".$dAE['nickname']." - ".$dAE['name']."</td>";
                                          echo "<td nowrap><small class='text-muted'>Local:<br></small>".$dAE['ad_name']."</td>";
                                        echo "</tr>";
                                        echo "<tr><td colspan='4'><small class='text-muted'>Descrição:<br></small>".$dAE['description']."</td></tr>";
                                      }
                                  }else {
                                    echo "<tr><td class='text-muted'><small><i>Nenhuma ocorrência administratva.</i></td></tr>";
                                  }

                              ?>
                          <tbody>
                        </table>
              </div>
          </div>


            <div class="row">
              <div class="col-xs-12">
                        <table class='table table-condensed'>
                        <thead><tr><th colspan="6"><h4>Ocorrências operacionais:</h4></th></tr></thead>
                          <tbody>
                              <?
                                if(isset($ocorrencias))
                                {
                                  for($i=0;$i<count($ocorrencias);$i++)
                                  {

                                    //S.name as logradouro, AB.name as addressbook_local, AB.obs as addressbook_ref,
                                    if($ocorrencias[$i]['addressbook_local']!=""){
                                      $localidade = $ocorrencias[$i]['addressbook_local']." - ".$ocorrencias[$i]['addressbook_ref'];
                                    }else{
                                      $localidade = $ocorrencias[$i]['logradouro'];
                                    }
                                    //Providencias//
                                    unset($sqlP, $resP, $dP);
                                    $sqlP = "SELECT
                                              	P.area AS area_providencia, P.providence AS nome_providencia,
                                              	EP.*
                                              FROM
                                              	sepud.oct_rel_events_providence EP
                                              	JOIN sepud.oct_providence P ON P.id = EP.id_providence
                                              WHERE
                                              	EP.id_event = '".$ocorrencias[$i]['id']."'
                                              ORDER BY EP.opened_date ASC";
                                    $resP = pg_query($sqlP)or die("SQL Error ".__LINE__."<br>Query: ".$sqlP);

                                    echo "<tr>";
                                      echo "<td><small class='text-muted'>Número:</small><br><b>".number_format($ocorrencias[$i]['id'],0,'','.')."</b></td>";
                                      echo "<td width='300px'><small class='text-muted'>Ocorrência:</small><br><b>".$ocorrencias[$i]['ocorrencia']."</b></td>";
                                      echo "<td><small class='text-muted'>Localidade:</small><br>".$localidade."</td>";

                                      //echo "<td width='150px'><small class='text-muted'>Abertura:</small><br>".formataData($ocorrencias[$i]['date'],1)."</td>";
                                      //echo "<td width='150px'><small class='text-muted'>Fechamento:</small><br>".formataData($ocorrencias[$i]['closure'],1)."</td>";

                                      echo "<td width='50px'><small class='text-muted'>Abertura:</small><br>".substr($ocorrencias[$i]['date'],11,5)."</td>";
                                      echo "<td width='50px'><small class='text-muted'>Fechamento:</small><br>".substr($ocorrencias[$i]['closure'],11,5)."</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                      echo "<td colspan='2'><small class='text-muted'>Guarnição:</small><br>";
                                            unset($participantes);
                                            if(isset($guarnicoes_participantes[$ocorrencias[$i]['id_garrison']]))
                                            {
                                                for($g=0;$g<count($guarnicoes_participantes[$ocorrencias[$i]['id_garrison']]);$g++)
                                                {
                                                  $participantes[] = $guarnicoes_participantes[$ocorrencias[$i]['id_garrison']][$g]['nickname'];
                                                }
                                                if(isset($participantes))
                                                {
                                                  echo "<small class='text-muted'>[ID: ".$ocorrencias[$i]['id_garrison']."] </small>";
                                                  echo implode(", ",$participantes);
                                                }
                                            }else {
                                              echo "<small class='text-danger'>Nenhuma guarnição associada a esta ocorrência.</small>";
                                            }
                                      echo "</td>";
                                      echo "<td colspan='3'><small class='text-muted'>Descrição:</small><br>".$ocorrencias[$i]['description']."</td>";
                                    echo "</tr>";

                                    if(pg_num_rows($resP))
                                    {

                                      echo "<tr><td colspan='10'><small class='text-muted'>Providencias:</small><br>";

                                          echo "<table class='table table-condensed'>";
                                          while($dP = pg_fetch_assoc($resP))
                                          {
                                            echo "<tr><td nowrap>".formataData($dP['opened_date'],1)."</td>";
                                            echo "<td nowrap><b><small>".$dP['area_providencia'].":</small><br>".$dP['nome_providencia']."</b></td>";
                                            echo "<td>".$dP['observation']."</td></tr>";
                                          }
                                          echo "</table>";
                                      echo "</td></tr>";
                                    }
                                  }
                                }else {
                                  echo "<tr>";
                                  echo "<td class='text-muted'><i>Nenhuma ocorrência associada a este turno de trabalho.</i></td>";
                                  echo "</tr>";
                                }
                              ?>
                          </tbody>
                        </table>
              </div>
            </div>


            <div class="row">
              <div class="col-md-6 col-md-offset-3 text-center" style="margin-top:50px">

                <?
                if(isset($turno_recursos['coordenacao']) && count($turno_recursos['coordenacao']))
                {
                    //print_r_pre($turno_recursos['coordenacao']);
                    for($i=0;$i<count($turno_recursos['coordenacao']);$i++)
                    {
                      echo "<span class='text-center'>___________________________________________________________________<br>".$turno_recursos['coordenacao'][$i]['nome']."<br><small>matrícula: <b>".number_format($turno_recursos['coordenacao'][$i]['registration'],0,'','.')."</b></small></span><br><br>";
                    }
                }else{
                  echo "<div class='text-muted text-left'>_________________________________________________<br>Responsável:</span>";
                }
                ?>


              </div>
           </div>



            </div>
            <div class='panel-footer' style="height:45px">
              <span class='row pull-right' style="margin-right:5px"><small><i>Relatório gerado em <b><?=$agora['dthm'];?></b>, por <b><?=$_SESSION['name'];?></b></i></small></span>
            </div>
          </section>
          </div>
        </div>
</section>

<!-- Vendor -->
<script src="../assets/vendor/jquery/jquery.js"></script>

<script src="../assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>

<script src="../assets/vendor/bootstrap/js/bootstrap.js"></script>

<script src="../assets/vendor/nanoscroller/nanoscroller.js"></script>

<script src="../assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

<script src="../assets/vendor/magnific-popup/magnific-popup.js"></script>

<script src="../assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>

<!-- Specific Page Vendor -->

<script src="../assets/vendor/jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>

<script src="../assets/vendor/jquery-ui-touch-punch/jquery.ui.touch-punch.js"></script>

<script src="../assets/vendor/jquery-appear/jquery.appear.js"></script>

<script src="../assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>

<script src="../assets/vendor/jquery-easypiechart/jquery.easypiechart.js"></script>

<script src="../assets/vendor/flot/jquery.flot.js"></script>

<script src="../assets/vendor/flot-tooltip/jquery.flot.tooltip.js"></script>

<script src="../assets/vendor/flot/jquery.flot.pie.js"></script>

<script src="../assets/vendor/flot/jquery.flot.categories.js"></script>

<script src="../assets/vendor/flot/jquery.flot.resize.js"></script>

<script src="../assets/vendor/jquery-sparkline/jquery.sparkline.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script src="../assets/vendor/gauge/gauge.js"></script>

<script src="../assets/vendor/snap-svg/snap.svg.js"></script>

<script src="../assets/vendor/liquid-meter/liquid.meter.js"></script>

<script src="../assets/vendor/jqvmap/jquery.vmap.js"></script>

<script src="../assets/vendor/jqvmap/data/jquery.vmap.sampledata.js"></script>

<script src="../assets/vendor/jqvmap/maps/jquery.vmap.world.js"></script>

<script src="../assets/vendor/jqvmap/maps/continents/jquery.vmap.africa.js"></script>

<script src="../assets/vendor/jqvmap/maps/continents/jquery.vmap.asia.js"></script>

<script src="../assets/vendor/jqvmap/maps/continents/jquery.vmap.australia.js"></script>

<script src="../assets/vendor/jqvmap/maps/continents/jquery.vmap.europe.js"></script>

<script src="../assets/vendor/jqvmap/maps/continents/jquery.vmap.north-america.js"></script>

<script src="../assets/vendor/jqvmap/maps/continents/jquery.vmap.south-america.js"></script>
<script src="../assets/vendor/pnotify/pnotify.custom.js"></script>
<script src="../assets/vendor/intercooler/intercooler-0.4.8.js"></script>
<script src="../assets/vendor/jquery-mockjax/jquery.mockjax.js"></script>
<script src="../assets/vendor/gauge/gauge.js"></script>

<script src="http://oss.maxcdn.com/jquery.form/3.50/jquery.form.min.js"></script>



<!-- Theme Base, Components and Settings -->
<script src="../assets/javascripts/theme.js"></script>

<!-- Theme Custom -->
<script src="../assets/javascripts/theme.custom.js"></script>

<!-- Theme Initialization Files -->
<script src="../assets/javascripts/theme.init.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


</body>
</html>
<script>
<? if($_GET['print']!="false"){ ?>
window.print();
<? } ?>
$(".loading").click(function(event){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde</small>");});
//$("#bt_refresh").click(function(){ $("#bt_refresh_icon").addClass('fa-spin'); $("#wrap").load("erg/rel_placa.php");});
</script>
