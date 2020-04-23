<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema   = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $oct_form = ($_SESSION['company_configs']['oct_form']!=""?$_SESSION['company_configs']['oct_form']:"FORM.php");

  $agora        = now();
  $data_atual   = $agora['data'];
  $data_db      = $agora['datasrv'];
  //$filtro_ativo = " OR  EV.active = 't'";
  $hoje         = time();

  if($_GET['rotss_nav_filtro_data_reset']=="true"){ unset($_SESSION['rotss_nav_filtro_data']);}
  $_SESSION['rotss_nav_retorno_origem'] = "ocorrencias.php";
  //unset($_SESSION['rotss_nav_filtro_data']);
  //if(isset($_SESSION['rotss_nav_filtro_data']) && $_SESSION['rotss_nav_filtro_data']!=""){ $_GET['filtro_data'] = $_SESSION['rotss_nav_filtro_data']; }

  if(isset($_POST['filtro_data']) && $_POST['filtro_data'] != "")
  {
    $_GET['filtro_data']               = date2mkt($_POST['filtro_data']);
    $_SESSION['rotss_nav_filtro_data'] = date2mkt($_POST['filtro_data']);
  }

  if(isset($_GET['filtro_data']) && $_GET['filtro_data'] != "")
  {
    $anterior                          = $_GET['filtro_data'] - (24*3600);
    $proximo                           = $_GET['filtro_data'] + (24*3600);
    $data_atual                        = date("d/m/Y", $_GET['filtro_data']);
    $data_db                           = date("Y-m-d", $_GET['filtro_data']);
    $_SESSION['rotss_nav_filtro_data'] = $_GET['filtro_data'];
    //$filtro_ativo = " OR (EV.id_company = '".$_SESSION['id_company']."' AND EV.active = 't')";
  }else {
        if(isset($_SESSION['rotss_nav_filtro_data']) && $_SESSION['rotss_nav_filtro_data']!="")
        {
            $anterior                          = $_SESSION['rotss_nav_filtro_data'] - (24*3600);
            $proximo                           = $_SESSION['rotss_nav_filtro_data'] + (24*3600);
            $data_atual                        = date("d/m/Y", $_SESSION['rotss_nav_filtro_data']);
            $data_db                           = date("Y-m-d", $_SESSION['rotss_nav_filtro_data']);
        }else{
            $anterior = $hoje - (24*3600);
            $proximo  = $hoje + (24*3600);
            //$filtro_ativo = " OR (EV.id_company = '".$_SESSION['id_company']."' AND EV.active = 't')";
        }
  }

  if($proximo >= $hoje){ $proximo = $hoje; $bt_prox = false; /*$filtro_ativo = " OR  EV.active = 't'";*/ }else{ $bt_prox = true; }

  if(isset($_POST['filtro_placaveiculo']) && $_POST['filtro_placaveiculo'] != "")
  {
      $sqlPa="SELECT distinct(id_events)
              FROM ".$schema."oct_vehicles
              WHERE
                  id_events is not null
              AND licence_plate = '".$_POST['filtro_placaveiculo']."'";
      $res = pg_query($sqlPa)or die("Erro ".__LINE__);
      while($dP = pg_fetch_assoc($res)){ $vetPlacas[] = $dP["id_events"];}
      $filtro_placa = implode(",",$vetPlacas);
      $bt_filtro_placa = true;
  }

  if(isset($_POST['filtro_num_oc']) && $_POST['filtro_num_oc'] != "")
  {
        $filtro_num_oc = $_POST['filtro_num_oc'];
  }

/*
  $sql = "SELECT
               EVT.name as event_type,
               EV.id,
               EV.date,
               EV.arrival,
               EV.status,
               EV.victim_inform,
               EV.victim_found,
               EV.active,
               EV.address_reference,
               EV.id_street,
               EV.id_workshift,
               EV.id_garrison,
               C.id    as id_company,
               C.name  as company,
               C.acron as company_acron,
               S.name  as street_name,
               (SELECT COUNT(*) FROM ".$schema."oct_victim                WHERE id_events = EV.ID) as vitimas_encontradas,
               (SELECT COUNT(*) FROM ".$schema."oct_rel_events_providence WHERE id_event  = EV.id) as qtd_providencias,
               (SELECT COUNT(*) FROM ".$schema."oct_rel_events_images     WHERE id_events = EV.id) as qtd_fotos,
               U.NAME  as user_name,
               --UG.name as user_name_garrison, UG.nickname as nickname_garrison,
	             --F.plate, F.brand, F.model,
               AB.name  as addressbook_name
         FROM
               ".$schema."oct_events     AS  EV
        --LEFT JOIN ".$schema."oct_rel_garrison_persona GP ON GP.id_garrison = EV.id_garrison AND GP.type = 'Motorista'
       	--LEFT JOIN ".$schema."users 									 UG ON UG.id = GP.id_user

       	--LEFT JOIN ".$schema."oct_garrison 							G ON G.id  = EV.id_garrison
       	--LEFT JOIN ".$schema."oct_fleet                 F ON F.id  = G.id_fleet
             JOIN  ".$schema."oct_event_type  as EVT ON EV.id_event_type = EVT.id
             JOIN  ".$schema."users           as   U ON U.id             = EV.id_user
         JOIN  ".$schema."company             as   C ON C.id             = EV.id_company
         LEFT JOIN ".$schema."streets         as   S ON S.id             = EV.id_street
         LEFT JOIN ".$schema."oct_addressbook as  AB ON AB.id            = EV.id_addressbook
         WHERE ";
*/
$sql = "SELECT
             EVT.name as event_type,
             EV.id,
             EV.date,
             EV.arrival,
             EV.status,
             EV.victim_inform,
             EV.victim_found,
             EV.active,
             EV.address_reference,
             EV.id_street,
             EV.id_workshift,
             EV.id_garrison,
             C.id    as id_company,
             C.name  as company,
             C.acron as company_acron,
             S.name  as street_name,
             (SELECT COUNT(*) FROM ".$schema."oct_victim                WHERE id_events = EV.ID) as vitimas_encontradas,
             (SELECT COUNT(*) FROM ".$schema."oct_rel_events_providence WHERE id_event  = EV.id) as qtd_providencias,
             (SELECT COUNT(*) FROM ".$schema."oct_rel_events_images     WHERE id_events = EV.id) as qtd_fotos,
             U.NAME  as user_name,
             AB.name  as addressbook_name
       FROM
                 ".$schema."oct_events     as EV
            JOIN ".$schema."oct_event_type as EVT ON EV.id_event_type = EVT.id
            JOIN ".$schema."users          as U   ON U.id             = EV.id_user
            JOIN ".$schema."company        as C   ON C.id             = EV.id_company
       LEFT JOIN ".$schema."streets         as S   ON S.id             = EV.id_street
       LEFT JOIN ".$schema."oct_addressbook as AB  ON AB.id            = EV.id_addressbook
       WHERE ";
         if($filtro_placa != "")
         {
              $sql .= " EV.id in (".$filtro_placa.")";
         }elseif($filtro_num_oc != ""){
              $sql .= " EV.id = '".$filtro_num_oc."'";
         }else{

           $sql .= " (EV.id_company = '".$_SESSION['id_company']."' or EV.id in (SELECT id_event FROM ".$schema."oct_rel_events_providence WHERE id_company_requested = '".$_SESSION['id_company']."' AND id_providence = 53))
                      AND EV.date BETWEEN '".$data_db." 00:00:00' AND '".$data_db." 23:59:59'".$filtro_ativo;
         }
         $sql .= " ORDER BY EV.id DESC";



 $rs  = pg_query($sql);

 $total_oc = 0;
 while($d = pg_fetch_assoc($rs))
 {
     if($d["qtd_providencias"] > 0){ $eventos_com_providencia[] = $d["id"]; }

     if($d['active']=='t'){ $dados[]          = $d; }
     else {                 $dados_baixados[] = $d; }

     if($d['id_garrison']!=""){ $garrison_passenger[] = $d['id_garrison'];}

     $total_oc++;
 }

 if(isset($eventos_com_providencia) && count($eventos_com_providencia))
 {
   $aux  = implode(",", $eventos_com_providencia);
   $sqlP = "SELECT   P.id_event, P.id_vehicle, T.id, T.area, T.providence
            FROM     ".$schema."oct_rel_events_providence P, ".$schema."oct_providence T
            WHERE
                     P.id_event in (".$aux.")
                 AND P.id_providence = T.id
            ORDER BY P.id_event DESC";
   $resP = pg_query($sqlP)or die("Erro: ".__LINE__);
   while($p = pg_fetch_assoc($resP))
   {
     if($p["id"]==11 || $p["id"]==12) //ID 11/12 - Guinchamento
     {
        $providencias[$p["id_event"]]["guin"] = true;
     }
     if($p["id"]==25) //ID 25 - Prov. adminstrativa
     {
        $providencias[$p["id_event"]]["adm"] = true;
     }
     if($p["id"]==14 || $p["id"]==15) //ID 14 - Colocação de cones, 15 - Remoção de cones
     {
        $providencias[$p["id_event"]]["mat"] = true;
     }
   }

 }

 logger("Acesso","OCT", "Filtro data: ".$data_atual.", ".$total_oc." ocorrências listadas");

 if(isset($dados)){
   if(count($dados)==1){ $txt_oc_abertas  = "<b>1</b> ocorrência aberta"; }else{ $txt_oc_abertas  = "<b>".count($dados)."</b> ocorrências abertas";}
 }else{ $txt_oc_abertas  = "Nenhuma ocorrência aberta";}


 if(isset($dados_baixados)){
   if(count($dados_baixados)==1){ $txt_oc_baixadas = "<b>1</b> ocorrência fechada."; }else{ $txt_oc_baixadas = "<b>".count($dados_baixados)."</b> ocorrências fechadas."; }
 }else{ $txt_oc_baixadas = "Nenhuma ocorrência baixada.";}


 if(!isset($dados) && !isset($dados_baixados)){ $txt_oc_abertas = "Nenhuma ocorrência gerada neste dia."; unset($txt_oc_baixadas); }

 function guarnicoes($id_garrison, $id_workshift, $modelo)
 {
        $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");

        if($id_garrison != "")//Buscando uma guarnição especifica
        {
               $sql = "SELECT
                         F.nickname, F.plate, F.model, F.brand,
                         G.*
                       FROM
                                 ".$schema."oct_garrison G
                       LEFT JOIN ".$schema."oct_fleet F ON F.id = G.id_fleet
                       WHERE
                         G.id = '".$id_garrison."'";
               $res = pg_query($sql)or die("SQL error: ".__LINE__."<h4>".$sql."</h4>");
               while($aux = pg_fetch_assoc($res))
               {
                   $guarnicao_empenhada = $aux;
               }


               if($guarnicao_empenhada['name']!="") //Guarnições no modelo novo//
               {
                      //Busca os veículos
                       $sql = "SELECT
                                 F.nickname,
                                 F.brand,
                                 F.model,
                                 F.plate,
                                 V.*
                               FROM
                                      ".$schema."oct_rel_garrison_vehicle V
                                 JOIN ".$schema."oct_fleet F ON F.ID = V.id_fleet
                               WHERE
                                 V.id_garrison = '".$id_garrison."'";

                       $res = pg_query($sql)or die("SQL error ".__LINE__);
                       while($aux = pg_fetch_assoc($res)){ $guarnicao_empenhada['veiculos'][$aux['id']] = $aux;  }
              }
                      //Buscando as pessoas integrantes da guarnição//
                      $sql = "SELECT
                                U.NAME,
                                U.nickname,
                                U.registration,
                                P.*
                              FROM
                                ".$schema."oct_rel_garrison_persona P
                                JOIN ".$schema."users U ON U.ID = P.id_user
                              WHERE
                                P.id_garrison = '".$id_garrison."'
                              ORDER BY U.nickname ASC";

                      $res = pg_query($sql)or die("SQL error ".__LINE__);
                      while($aux = pg_fetch_assoc($res))
                      {
                              if($guarnicao_empenhada['name']!="") //Guarnições no modelo novo//
                              {
                                    if($aux['id_rel_garrison_vehicle']!="")
                                    {
                                      $guarnicao_empenhada['veiculos'][$aux['id_rel_garrison_vehicle']]['pessoas'][] = $aux;
                                    }else{
                                      $guarnicao_empenhada['pessoas_a_pe'][] = $aux;
                                    }
                              }else{
                                      $guarnicao_empenhada['pessoas'][] = $aux;
                              }
                      }

               if($modelo == "resumido")
               {
                     if($guarnicao_empenhada['name']!="")//Modelo novo de guarnição//
                     {
                         if(isset($guarnicao_empenhada['veiculos']))
                         {
                               foreach($guarnicao_empenhada['veiculos'] as $id_rel_garrison_vehicle => $d)
                               {
                                    if(isset($info)){$info .= " | ";}
                                    $info .= $d['nickname'].": ";
                                    unset($pessoas);
                                    for($i=0;isset($d['pessoas']) && $i<count($d['pessoas']);$i++)
                                    {
                                      $pessoas[] = $d['pessoas'][$i]['nickname'];
                                    }
                                    if(isset($pessoas) && count($pessoas)){ $info .= implode(", ",$pessoas); }
                                    else                                  { $info .= "<span class='text-danger'><i>Nenhum integrante</i></span>";}
                               }
                         }
                         //Verificando se há pessoas a pé na guarnição//
                         if(isset($guarnicao_empenhada['pessoas_a_pe']) && count($guarnicao_empenhada['pessoas_a_pe']))
                         {
                           if(isset($info)){$info .= " | ";}
                           $info .= "A PÉ: ";
                           unset($pessoas);
                           for($i=0;isset($guarnicao_empenhada['pessoas_a_pe']) && $i<count($guarnicao_empenhada['pessoas_a_pe']);$i++)
                           {
                             $pessoas[] = $guarnicao_empenhada['pessoas_a_pe'][$i]['nickname'];
                           }
                           $info .= implode(", ",$pessoas);
                         }

                         return array("name" => $guarnicao_empenhada['name'], "info" => $info);

                    }else{ //Guarnição no modelo antigo//
                          $info = $guarnicao_empenhada['nickname'].": ";
                          for($i=0;isset($guarnicao_empenhada['pessoas']) && $i<count($guarnicao_empenhada['pessoas']);$i++)
                          {
                            $pessoas[] = $guarnicao_empenhada['pessoas'][$i]['nickname'];
                          }
                          $info .= implode(", ",$pessoas);
                          return array("name" => number_format($id_garrison,0,'','.'), "info" => $info);
                    }
               }
         }
 }
?>

<section role="main" class="content-body">
  <header class="page-header">
    <h2>Ocorrências</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>Aplicações</span></li>
        <li><span class='text-muted'>Ocorrências do dia</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>


<?

?>
<div class="col-md-12">
								<section class="panel box_shadow">

  <header class="panel-heading visible-xs" style="height:120px">
    <div class="text-center">
        <a href="oct/<?=$oct_form;?>?filtro_data=<?=$_GET['filtro_data'];?>">
          <button type="button" class="mb-xs mt-xs mr-xs btn  btn-danger"><i class="fa fa-exclamation-triangle"></i> Nova ocorrência</button>
        </a>

        <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modalFiltro'><i class='fa fa-search'></i></button><br>
        <?
        if(!$bt_filtro_placa){ ?>
          <a href="oct/ocorrencias.php?filtro_data=<?=$anterior;?>">
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-success"><i class="fa fa-play fa-rotate-180"></i></button>
          </a>
        <? }else{
          echo "<button type='button' class='mb-xs mt-xs mr-xs btn btn-success disabled'><i class='fa fa-play fa-rotate-180'></i></button>";
        }


        ?>
          <? if($bt_prox || $bt_filtro_placa){ ?>
          <a href="oct/ocorrencias.php?filtro_data=">
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-success">Ir para hoje</button>
          </a>
        <? if(!$bt_filtro_placa){ ?>
          <a href="oct/ocorrencias.php?filtro_data=<?=$proximo;?>">
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-success"><i class="fa fa-play"></i></button>
          </a>
        <? }else {
          echo "<button type='button' class='mb-xs mt-xs mr-xs btn btn-success disabled'><i class='fa fa-play'></i></button>";
        }
      }else{
          echo "<button type='button' class='mb-xs mt-xs mr-xs btn btn-success disabled'>Ir para hoje</button>";
          echo "<button type='button' class='mb-xs mt-xs mr-xs btn btn-success disabled'><i class='fa fa-play'></i></button>";
          } ?>
    </div>
  </header>

                  <header class="panel-heading hidden-xs" style="height:120px">
                    <span class="text-muted"> Data de referência: </span><b><?=$data_atual;?></b>
                    <br><small class="text-muted"><?=$txt_oc_abertas;?><?=($txt_oc_baixadas!=""?",<br>".$txt_oc_baixadas:"");?>
                        </small>
                    <div class="panel-actions" style='margin-top:0px;'>

                      <a href="oct/<?=$oct_form;?>">
                        <button type="button" class="mb-xs mt-xs mr-xs btn  btn-danger"><i class="fa fa-exclamation-triangle"></i> Nova ocorrência</button>
                      </a>

  <?

    echo "<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modalFiltro'>
          <i class='fa fa-search'></i>
          </button><br>";

if(!$bt_filtro_placa){ ?>
  <a href="oct/ocorrencias.php?filtro_data=<?=$anterior;?>">
    <button type="button" class="mb-xs mt-xs mr-xs btn btn-success"><i class="fa fa-play fa-rotate-180"></i></button>
  </a>
<? }else{
  echo "<button type='button' class='mb-xs mt-xs mr-xs btn btn-success disabled'><i class='fa fa-play fa-rotate-180'></i></button>";
}


?>
  <? if($bt_prox || $bt_filtro_placa){ ?>
  <a href="oct/ocorrencias.php?rotss_nav_filtro_data_reset=true">
    <button type="button" class="mb-xs mt-xs mr-xs btn btn-success">Ir para hoje</button>
  </a>
<? if(!$bt_filtro_placa){ ?>
  <a href="oct/ocorrencias.php?filtro_data=<?=$proximo;?>">
    <button type="button" class="mb-xs mt-xs mr-xs btn btn-success"><i class="fa fa-play"></i></button>
  </a>
<? }else {
  echo "<button type='button' class='mb-xs mt-xs mr-xs btn btn-success disabled'><i class='fa fa-play'></i></button>";
}


?>
<? }else{
  echo "<button type='button' class='mb-xs mt-xs mr-xs btn btn-success disabled'>Ir para hoje</button>";
  echo "<button type='button' class='mb-xs mt-xs mr-xs btn btn-success disabled'><i class='fa fa-play'></i></button>";
  } ?>



                      <!--<a href="#" ic-get-from="sistema/logs.php" ic-target="#wrap" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary"><i class="fa fa-user-plus"></i> Novo usuário !</a>-->
									  </div>
                  </header>
									<div class="panel-body">

<?/*
    if($_SESSION['id']==1)
    {
      print_r_pre($_POST);
      print_r_pre($sql);
    }
  */
?>



                    <div class="table-responsive">

                      <table class="table mb-none">

<?
echo "<tr><td colspan='10' class='success'>Ocorrências ativas</td><tr>";
if(isset($dados) && count($dados))
{
    for($i = 0; $i < count($dados); $i++)
    {

      $dt_abertura = formataData($dados[$i]['date'],1);
      $dt_chegada  = formataData($dados[$i]['arrival'],1);
      $qtd_prov    = ($dados[$i]['qtd_providencias'] > 0 ? "<span class='text-center mb-xs mt-xs mr-xs btn btn-default disabled'>".$dados[$i]['qtd_providencias']."</span>":"-");
      $qtd_fotos   = ($dados[$i]['qtd_fotos']        > 0 ? "<span class='text-center mb-xs mt-xs mr-xs btn btn-default disabled'><b>".$dados[$i]['qtd_fotos']."</b> <sup><i class='fa fa-camera'></i></sup></span>":"-");

      if($providencias[$dados[$i]["id"]]["adm"]==1){  $prov_adm = "<span class='text-center mb-xs mt-xs mr-xs btn btn-success'>ADM</span>"; }
      else{                                           $prov_adm = "<span class='text-center text-muted'>-</span>";}
      if($providencias[$dados[$i]["id"]]["guin"]==1){ $prov_gui = "<span class='text-center mb-xs mt-xs mr-xs btn btn-info'>GUI</span>";    }
      else{                                           $prov_gui = "<span class='text-center text-muted'>-</span>";}
      if($providencias[$dados[$i]["id"]]["mat"]==1){  $prov_mat = "<span class='text-center mb-xs mt-xs mr-xs btn btn-warning'>MAT</span>";    }
      else{                                           $prov_mat = "<span class='text-center text-muted'>-</span>";}

      if($dados[$i]['status']=="Inativa"){$class_status="info";}else{ $class_status = "success"; }

    ?>
                          <tr>
                              <td rowspan="3" style="vertical-align: middle;" class="text-center <?=$class_status;?>"><?=$dados[$i]['id']; if($dados[$i]['id_workshift']!=""){ echo ".".$dados[$i]['id_workshift'];}?></td>
                              <td colspan="9" style="background-color:#e2fee2"><b><?=$dados[$i]['event_type'];?></b></td>
                            </tr>
                            <tr>

                              <?
                                  $local = "";
                                  if($dados[$i]["addressbook_name"]!=""){$local = $dados[$i]['addressbook_name'];}
                                  if($dados[$i]["addressbook_name"] != "")
                                  {
                                    if($dados[$i]["street_name"]!=""){$local .= " - ".$dados[$i]['street_name'];}
                                  }else{
                                    if($dados[$i]["street_name"]!=""){$local = $dados[$i]['street_name'];}
                                  }
                              ?>

                              <td><small><i class="text-muted">Logradouro:</i></small><br><?=$local;?></td>
                              <td><small><i class="text-muted">Data de abertura:</i></small><br><?=$dt_abertura;?></td>
                              <td><small><i class="text-muted">Origem:</i></small><br><?=($dados[$i]['company_acron']==$_SESSION['company_acron']?$dados[$i]['company_acron']:"<b class='text-success'>".$dados[$i]['company_acron']."</b>");?></td>
                              <td><small><i class="text-muted">Status:</i></small><br><?=($dados[$i]['status']=="Inativa"?"<b class='text-info'>Inativa</b>":$dados[$i]['status']);?></td>

                              <td style="vertical-align: middle;" class="text-center"><?=$prov_adm;?></td>
                              <td style="vertical-align: middle;" class="text-center"><?=$prov_gui;?></td>
                              <td style="vertical-align: middle;" class="text-center"><?=$prov_mat;?></td>
                              <td style="vertical-align: middle;" class="text-center"><?=$qtd_fotos;?></td>
                              <td style="vertical-align: middle;" class="text-center"><a href='oct/<?=$oct_form;?>?id=<?=$dados[$i]['id'];?>' class='mb-xs mt-xs mr-xs btn btn-default loading2'><i class='fa fa-pencil'></i></a></td>
                            </tr>
                            <tr>
                              <td colspan='2'><small><i class="text-muted">Responsável:</i></small><br><?=$dados[$i]['user_name'];?></td>
                              <td colspan='7'>
                                <?
                                        unset($passenger, $garrison_txt, $guarnicao_empenhada, $str, $info);
                                        $sql = "SELECT
                                                	U.name, U.nickname
                                                FROM
                                                	".$schema."oct_rel_events_providence P
                                                JOIN ".$schema."users U ON U.id = P.id_user_resp
                                                WHERE
                                                	P.id_providence IN (52)
                                                	AND P.id_event = '".$dados[$i]['id']."'
                                                ORDER BY
                                                	P.opened_date DESC
                                                	LIMIT 1";

                                        $resResp = pg_query($sql)or die("SQl Error ".$sql);
                                        if(pg_num_rows($resResp))
                                        {
                                            $nomeResponsavel = pg_fetch_assoc($resResp);
                                            echo "<small><i class='text-muted'>Responsável atual:</i></small><br>";
                                            echo "<small><b>".strtoupper($nomeResponsavel['name'])."</b></small><br>";
                                        }elseif($dados[$i]['id_garrison']!="")
                                        {
                                          unset($guarnicao_empenhada);
                                          $guarnicao_empenhada = guarnicoes($dados[$i]['id_garrison'],"","resumido");
                                          echo "<small><i class='text-muted'>Guarnição empenhada:</i></small> ";
                                          echo "<small><b>Grupamento ".strtoupper($guarnicao_empenhada['name'])."</b></small><br>";
                                          echo $guarnicao_empenhada['info'];
                                        }else
                                        {
                                            echo  "<small><i class='text-danger'>Nenhuma guarnição designada ou responsável empenhado.</i></small>";
                                        }
                                ?>
                              </td>
                            </tr>
    <? }
}else {
  echo "<tr><td class='success'>&nbsp;</td>
            <td colspan='9' class='text-center'>
                <div class='alert alert-warning'>
                    Nenhuma ocorrênca aberta para esta data.<br><b>".$data_atual."</b>
                </div>
            </td>
        </tr>";
}

echo "<tr><td colspan='10' class='warning'>Ocorrências terminadas</td><tr>";
if(isset($dados_baixados) && count($dados_baixados))
{
      $dados = $dados_baixados;
      for($i = 0; $i < count($dados); $i++)
      {

        $dt_abertura = formataData($dados[$i]['date'],1);
        $dt_chegada  = formataData($dados[$i]['arrival'],1);
        $qtd_prov    = ($dados[$i]['qtd_providencias'] > 0 ? "<span class='text-center mb-xs mt-xs mr-xs btn btn-default disabled'>".$dados[$i]['qtd_providencias']."</span>":"-");
        $qtd_fotos   = ($dados[$i]['qtd_fotos']        > 0 ? "<span class='text-center mb-xs mt-xs mr-xs btn btn-default disabled'><b>".$dados[$i]['qtd_fotos']."</b> <sup><i class='fa fa-camera'></i></sup></span>":"-");

        if($providencias[$dados[$i]["id"]]["adm"]==1){  $prov_adm = "<span class='text-center mb-xs mt-xs mr-xs btn btn-success'>ADM</span>"; }
        else{                                           $prov_adm = "<span class='text-center text-muted'>-</span>";}
        if($providencias[$dados[$i]["id"]]["guin"]==1){ $prov_gui = "<span class='text-center mb-xs mt-xs mr-xs btn btn-info'>GUI</span>";    }
        else{                                           $prov_gui = "<span class='text-center text-muted'>-</span>";}
        if($providencias[$dados[$i]["id"]]["mat"]==1){  $prov_mat = "<span class='text-center mb-xs mt-xs mr-xs btn btn-warning'>MAT</span>";    }
        else{                                           $prov_mat = "<span class='text-center text-muted'>-</span>";}

      ?>
      <tr>
          <td rowspan="3" style="vertical-align: middle;" class="text-center warning"><a id='<?=$dados[$i]['id'];?>'></a><?=$dados[$i]['id']; if($dados[$i]['id_workshift']!=""){ echo ".".$dados[$i]['id_workshift'];}?></td>
          <td colspan="8"><b><?=$dados[$i]['event_type'];?></b></td>
        </tr>
        <tr>
          <?
              $local = "";
              if($dados[$i]["addressbook_name"]!=""){$local = $dados[$i]['addressbook_name'];}
              if($dados[$i]["addressbook_name"] != "")
              {
                if($dados[$i]["street_name"]!=""){$local .= " - ".$dados[$i]['street_name'];}
              }else {
                if($dados[$i]["street_name"]!=""){$local = $dados[$i]['street_name'];}
              }
          ?>
          <td><small><i class="text-muted">Logradouro:</i></small><br><?=$local;?></td>
          <td><small><i class="text-muted">Data de abertura:</i></small><br><?=$dt_abertura;?></td>
          <td><small><i class="text-muted">Origem:</i></small><br><?=($dados[$i]['company_acron']==$_SESSION['company_acron']?$dados[$i]['company_acron']:"<b class='text-warning'>".$dados[$i]['company_acron']."</b>");?></td>
          <td><small><i class="text-muted">Status:</i></small><br><?=$dados[$i]['status'];?></td>

          <td style="vertical-align: middle;" class="text-center"><?=$prov_adm;?></td>
          <td style="vertical-align: middle;" class="text-center"><?=$prov_gui;?></td>
          <td style="vertical-align: middle;" class="text-center"><?=$prov_mat;?></td>
          <td style="vertical-align: middle;" class="text-center"><?=$qtd_fotos;?></td>
          <td style="vertical-align: middle;" class="text-center"><a href='oct/<?=$oct_form;?>?id=<?=$dados[$i]['id'];?>' class='mb-xs mt-xs mr-xs btn btn-default loading2'><i class='fa fa-pencil'></i></a></td>
        </tr>
        <tr>
          <td colspan='2'><small><i class="text-muted">Responsável:</i></small><br><?=$dados[$i]['user_name'];?></td>
          <td colspan='7'>
            <?
                      $sql = "SELECT
                                U.name, U.nickname
                              FROM
                                ".$schema."oct_rel_events_providence P
                              JOIN ".$schema."users U ON U.id = P.id_user_resp
                              WHERE
                                P.id_providence IN (52)
                                AND P.id_event = '".$dados[$i]['id']."'
                              ORDER BY
                                P.opened_date DESC
                                LIMIT 1";

                      $resResp = pg_query($sql)or die("SQl Error ".$sql);
                      if(pg_num_rows($resResp))
                      {
                          $nomeResponsavel = pg_fetch_assoc($resResp);
                          echo "<small><i class='text-muted'>Responsável atual:</i></small><br>";
                          echo "<small><b>".strtoupper($nomeResponsavel['name'])."</b></small><br>";
                      }elseif($dados[$i]['id_garrison']!="")
                      {
                          unset($guarnicao_empenhada);
                          $guarnicao_empenhada = guarnicoes($dados[$i]['id_garrison'],"","resumido");
                          echo "<small><i class='text-muted'>Guarnição empenhada:</i></small> ";
                          echo "<small><b>Grupamento ".strtoupper($guarnicao_empenhada['name'])."</b></small><br>";
                          echo $guarnicao_empenhada['info'];
                      }else{
                          echo  "<small><i class='text-danger'>Nenhuma guarnição designada ou responsável empenhado.</i></small>";
                      }
            ?>
          </td>
        </tr>

      <? }
      }else{
      echo "<tr><td class='warning'>&nbsp;</td>
                <td colspan='9' class='text-center'>
                    <div class='alert alert-warning'>
                        Nenhuma ocorrênca fechada ou cadastrada para esta data.<br><b>".$data_atual."</b>
                    </div>
                </td>
            </tr>";
    }

      ?>


                      </table>





										</div>
									</div>
								</section>
							</div>


                  <!-- Modal FILTROS -->
                  <div class="modal fade"  id="modalFiltro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Filtros de pesquisa:</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-20px">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form id="form_filtro" action="oct/ocorrencias.php" method="post">
                        <div class="modal-body">
                          <? require_once("filtros.php"); ?>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                          <button type="button" class="btn btn-primary" id="bt_submit">Filtrar</button>
                        </div>
                        </form>
                      </div>
                    </div>
                  </div>

</section>

<script>
$("#bt_submit").click(function(){
    $('#modalFiltro').modal('hide');
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
    $("#form_filtro").submit();
});
// $(".loading").click(function(event){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde</small>");});
// $(".loading2").click(function(event){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i>");});

</script>
