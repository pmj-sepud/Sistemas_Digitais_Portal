<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");

  $id = $_GET['id'];
  $sistema_nao_config = false;

  if($_SESSION['rotss_nav_retorno_origem']==""){ $retorno_origem = "ocorrencias.php?lastoc={$id}";}else{ $retorno_origem = $_SESSION['rotss_nav_retorno_origem']."?lastoc={$id}";}

  if(!$_SERVER['HTTP_X_REQUESTED_WITH']){
      header("Location: ../index_sistema.php?url=".base64_encode($_SERVER['REQUEST_URI']));
  }


  if($id != "")
  {
      $sql   = "SELECT
                    F.plate, F.brand, F.model, F.nickname as fleet_nickname,
                    UG.name  as user_name_garrison, UG.nickname as nickname_garrison, UG.registration as registration_garrison,
                    G.closed as closed_garrison, G.name as name_garrison,
                    W.opened as workshift_opened,
                    W.closed as workshift_closed,
                    W.workshift_group as workshift_period,
                    W.status as workshift_status,
                    U.NAME AS user_name, C.name AS company_name,
                    EV.*

                FROM
                          ".$schema."oct_events    EV
                     JOIN ".$schema."users         U  ON U.ID = EV.id_user
                     JOIN ".$schema."company       C  ON C.id = U.id_company
                LEFT JOIN ".$schema."oct_workshift W  ON W.id = EV.id_workshift
                LEFT JOIN ".$schema."oct_garrison  G  ON G.ID = EV.id_garrison
              	LEFT JOIN ".$schema."oct_fleet     F  ON F.id = G.id_fleet
              	LEFT JOIN ".$schema."oct_rel_garrison_persona GP ON GP.id_garrison = EV.id_garrison AND GP.type = 'Motorista'
              	LEFT JOIN ".$schema."users        UG ON UG.id = GP.id_user
                WHERE EV.ID =  '".$id."'";

      $res   = pg_query($conn_neogrid,$sql)or die("Error ".__LINE__);
      $dados = pg_fetch_assoc($res);

      if($dados['id_workshift']!="")
      {
        $turno_oc['id']     = $dados['id_workshift'];
        $turno_oc['opened'] = $dados['workshift_opened'];
        $turno_oc['closed'] = $dados['workshift_closed'];
        $turno_oc['period'] = $dados['workshift_period'];
        $turno_oc['status'] = $dados['workshift_status'];
      }

      $sql = "SELECT * FROM ".$schema."oct_rel_events_event_conditions WHERE id_events = '".$id."'";
      $res   = pg_query($conn_neogrid,$sql)or die("Error ".__LINE__);
      while($d = pg_fetch_assoc($res))
      {
        $dadosCondicoes[] = $d['id_event_conditions'];
      }

      $sql        = "SELECT * FROM ".$schema."oct_workshift WHERE id_company = ".$_SESSION['id_company']." AND status = 'aberto'";
      $resTurno   = pg_query($sql)or die("Erro ".__LINE__);
      if(pg_num_rows($resTurno))
      {
          $turno_aberto = pg_fetch_assoc($resTurno);
      }

      $acao  = "atualizar";

      $dt = formataData($dados['date'],1);
      $data = $dt;
      $aux  = explode(" ",$data);
      $data = $aux[0];
      $hora = $aux[1];
      $txt_bread = "Ocorrência n.".$id;

      logger("Acesso detalhado","OCT", "Ocorrência n.".$id);

  }else{
      $acao                   = "inserir";
      $dados['status']        = "Nova ocorrência";
      $dados['company_acron'] = $_SESSION['company_acron'];
      $dados['company_name']  = $_SESSION['company_name'];
      $dados['user_name']     = $_SESSION['name'];
      $agora = now();
      $txt_bread = "Nova ocorrência";
      $sql        = "SELECT * FROM ".$schema."oct_workshift WHERE id_company = ".$_SESSION['id_company']." AND status = 'aberto'";
      $resTurno   = pg_query($sql)or die("Erro ".__LINE__);
      if(pg_num_rows($resTurno))
      {
          $turno_aberto = pg_fetch_assoc($resTurno);
      }
  }



?>

<form id="form_oct" action="oct/FORM_sql.php" method="post">
<section role="main" class="content-body">
    <header class="page-header">
      <?
          if($acao == "inserir")
          {
              echo "<h2>Nova ocorrência</h2>";
          }else{
              echo "<h2>Ocorrência n° ".str_pad($_GET['id'],3,"0",STR_PAD_LEFT)."</h2>";
          }
      ?>

      <div class="right-wrapper pull-right" style='margin-right:15px;'>
        <ol class="breadcrumbs">
          <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
          <li><span class='text-muted'>Aplicações</span></li>
          <li><a href='oct/<?=$retorno_origem;?>?filtro_data=<?=$_GET['filtro_data'];?>'>Ocorrências</a></li>
          <li><span class='text-muted'><?=$txt_bread;?></span></li>
        </ol>
      </div>
    </header>
    <section class="panel">

      <div class='row'>
          <div class='col-sm-6'>
                        <h4><span class="text-muted">Status: </span><strong><i id='txt_status'><?=$dados['status'];?></i></strong>
                                  <?
                                    if(isset($turno_oc))
                                    {
                                      echo "<br><small class='text-muted'>Turno nº <b>".str_pad($turno_oc['id'],5,"0",STR_PAD_LEFT)."</b> - ".$turno_oc['period']. " - ";
                                      if($turno_oc['status']=="fechado"){ echo " <span class='text-warning'>Turno fechado</span>";}else{ echo " <span class='text-success'>Turno aberto</span>";}
                                      echo "<br>Início: <b>".formataData($turno_oc['opened'],1)."</b>";
                                      if($turno_oc['closed']!=""){echo ", fim: <b>".formataData($turno_oc['closed'],1)."</b>";}
                                      echo "</small>";
                                      echo "<input type='hidden' name='id_workshift' value='".$turno_oc['id']."' />";
                                    }else {
                                      if(isset($turno_aberto))
                                      {
                                        echo "<br><small class='text-muted'>Turno nº <b>".str_pad($turno_aberto['id'],5,"0",STR_PAD_LEFT)."</b> - ".$turno_aberto['period']. " - ";
                                        if($turno_aberto['status']=="fechado"){ echo " <span class='text-warning'>Turno fechado</span>";}else{ echo " <span class='text-success'>Turno aberto</span>";}
                                        echo "<br>Início: <b>".formataData($turno_aberto['opened'],1)."</b>";
                                        if($turno_aberto['closed']!=""){echo ", fim: <b>".formataData($turno_aberto['closed'],1)."</b>";}
                                        echo "</small>";
                                        echo "<input type='hidden' name='id_workshift' value='".$turno_aberto['id']."' />";
                                      }else{
                                        echo "<br><small class='text-danger'>Nenhum turno de trabalho aberto.</small>";
                                      }
                                    }
                                  ?>
                       </h4>
        </div>
        <div class='col-sm-6'>
                          <h4 class="text-right">
                                <small class='text-muted'><sup>Responsável:</sup></small><br>
                                <strong><i><?=$dados['user_name'];?></i><br>
                                <small><?=$dados['company_name'];?></small></strong>
                          </h4>
                          <input type="hidden" id="status" name="status" value="<?=$dados['status'];?>" />
        </div>
      </div>

      <header class="panel-heading" style="height:50px">

          <div class='row' style="margin-top:-8px">
              <div class='col-sm-12 text-right'>
                <? if($acao=="atualizar"){ ?>
                  <a href="#" id="bt_print" ajax="false" class='btn btn-sm btn-default'><i class='fa fa-print'></i> Imprimir</a>
                <? } ?>
                  <a href='oct/<?=$retorno_origem;?>' class="btn btn-sm btn-default loading"><i class='fa fa-mail-reply'></i> Voltar</a>
              </div>
            </div>

      </header>
      <div class="panel-body">
        <div class="row">
          <div class="col-sm-8">
            <!-- ========================================================= -->
            <div class="row">
              <div class="col-sm-10">
                    <div class="form-group">
          						<label class="control-label" for="tipo_oc">Ocorrência:</label>
              							<select id="tipo_oc" name="tipo_oc" class="form-control select2 changefield">
              								<?
                              if(isset($_SESSION["id_company"]))
                              {
                                  $sql = "SELECT T.* FROM ".$schema."oct_event_type T
                                          JOIN ".$schema."oct_rel_event_type_company R ON R.id_event_type = T.id AND R.id_company = '".$_SESSION["id_company"]."'
                                          WHERE T.active = true
                                          ORDER BY T.name ASC";

                                  $res = pg_query($conn_neogrid,$sql)or die("Error: ".__LINE__);

                                  if(pg_num_rows($res))
                                  {
                                          while($d = pg_fetch_assoc($res))
                                          {
                                              $vet[$d['type']][] = $d;
                                          }
                                          foreach($vet as $type => $d)
                                          {
                                            echo "<optgroup label='".$type."'>";
                                              for($i = 0; $i < count($d); $i++)
                                              {
                                                if($d[$i]['name_acron'] != ""){ $acron = $d[$i]['name_acron']." - ";}else{$acron = "";}
                                                if($dados['id_event_type'] == $d[$i]['id']){ $sel = "selected"; }else{ $sel = ""; }
                                                echo "<option value='".$d[$i]['id']."' $sel>".$acron.$d[$i]['name']."</option>";
                                              }
                                            echo "</optgroup>";
                                          }
                                  }else{
                                      echo "<option value=''>Nenhuma ocorrência associada a este orgão</option>";
                                      $sistema_nao_config = true;
                                  }
                              }else {
                                echo "<option value=''>Usuário não associado ao orgão em que trabalho</option>";
                                $sistema_nao_config = true;
                              }
                              ?>
              							</select>
          					</div>
              </div>

                  <div class="col-sm-2">
                        <div class="form-group">
                          <label class="control-label" for="victim_inform">Qtd:</label>
                          <input type="number" id="victim_inform" name="victim_inform" class="form-control changefield" value="<?=$dados['victim_inform'];?>">
<!--
                                <select id="victim_inform" name="victim_inform" class="form-control changefield">
                                  <?
                                    for($i = 0; $i <= 200; $i++)
                                    {
                                      if($dados['victim_inform'] == $i){ $sel = "selected"; }else{ $sel = ""; }
                                      echo "<option value='".$i."' $sel>".$i."</option>";
                                    }
                                  ?>
                                </select>
-->
                        </div>
                  </div>
            </div>


            <div class="row">
                  <div class="col-sm-8">
                    <div class="form-group">
                    <label class="control-label">Solicitante/Reclamante:</label>
                        <input type="text" name="requester" class="form-control changefield" value="<?=$dados['requester'];?>">
                   </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                    <label class="control-label">Telefone:</label>
                        <input type="text" name="requester_phone" class="form-control changefield" value="<?=$dados['requester_phone'];?>">
                   </div>
                  </div>
             </div>

             <div class="row">
               <div class="col-sm-8">
                 <div class="form-group">
                 <label class="control-label">Origem da solicitação:</label>
                     <select name="requester_origin" class="form-control changefield select2">
                        <option value=''></option>
                        <option value='Carta' <?=($dados['requester_origin']=="Carta"?"selected":"");?>>Carta</option>
                        <option value='Central 153' <?=($dados['requester_origin']=="Central 153"?"selected":"");?>>Central 153</option>
                        <option value='Comunicado' <?=($dados['requester_origin']=="Comunicado"?"selected":"");?>>Comunicado</option>
                        <option value='E-mail' <?=($dados['requester_origin']=="E-mail"?"selected":"");?>>E-mail</option>
                        <option value='Indicação' <?=($dados['requester_origin']=="Indicação"?"selected":"");?>>Indicação</option>
                        <option value='Memorando' <?=($dados['requester_origin']=="Memorando"?"selected":"");?>>Memorando</option>
                        <option value='Ocorrência' <?=($dados['requester_origin']=="Ocorrência"?"selected":"");?>>Ocorrência</option>
                        <option value='Ofício' <?=($dados['requester_origin']=="Ofício"?"selected":"");?>>Ofício</option>
                        <option value='Ouvidoria' <?=($dados['requester_origin']=="Ouvidoria"?"selected":"");?>>Ouvidoria</option>
                        <option value='Pessoalmente' <?=($dados['requester_origin']=="Pessoalmente"?"selected":"");?>>Pessoalmente</option>
                        <option value='Protocolo' <?=($dados['requester_origin']=="Protocolo"?"selected":"");?>>Protocolo</option>
                        <option value='Requerimento' <?=($dados['requester_origin']=="Requerimento"?"selected":"");?>>Requerimento</option>
                        <option value='SEI' <?=($dados['requester_origin']=="SEI"?"selected":"");?>>SEI</option>
                        <option value='Telefone' <?=($dados['requester_origin']=="Telefone"?"selected":"");?>>Telefone</option>
                     </select>
                </div>
               </div>
               <div class="col-sm-4">
                 <div class="form-group">
                 <label class="control-label">Protocolo:</label>
                     <input type="text" name="requester_protocol" class="form-control changefield" value="<?=$dados['requester_protocol'];?>">
                </div>
               </div>
            </div>



            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                <label class="control-label">Logradouro:</label>
                    <select id="id_street" name="id_street" class="form-control select2 changefield" style="width: 100%; height:100%">
                      <option value="">- - -</option>
                      <?
                        $sql = "SELECT * FROM ".$schema."streets ORDER BY name ASC";
                        $res = pg_query($sql)or die();
                        while($s = pg_fetch_assoc($res))
                        {
                          if($dados["id_street"] == $s["id"]){ $sel = "selected";}else{$sel="";}
                          if($dados["id_street_conner"] == $s["id"]){ $selconner = "selected";}else{$selconner="";}

                          echo "<option value='".$s['id']."' ".$sel.">".$s['name']."</option>";
                          $select_street .= "<option value='".$s['id']."' ".$selconner.">".$s['name']."</option>";
                        }
                      ?>
                    </select>
               </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                <label class="control-label">Esquina com:</label>
                    <select id="id_street_conner" name="id_street_conner" class="form-control select2 changefield" style="width: 100%; height:100%">
                      <option value="">- - -</option>
                      <?=$select_street;?>
                    </select>
               </div>
              </div>

            </div>
            <div class="row">
                  <div class="col-sm-2">
                    <div class="form-group">
                    <label class="control-label">Numero:</label>
                        <input type="number" id="street_number" name="street_number" class="form-control changefield" value="<?=$dados['street_number'];?>">
                   </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                    <label class="control-label">Bairro:</label>
                        <select id="id_neighborhood" name="id_neighborhood" class="form-control select2 changefield" style="width: 100%; height:100%">
                          <option value="">- - -</option>
                          <?
                            $sql = "SELECT * FROM ".$schema."neighborhood ORDER BY neighborhood ASC";
                            $res = pg_query($sql)or die();
                            while($n = pg_fetch_assoc($res))
                            {
                              if($dados["id_neighborhood"]  == $n["id"]){ $sel = "selected";}else{$sel="";}
                              echo "<option value='".$n['id']."' ".$sel.">".$n['neighborhood']."</option>";
                            }
                          ?>
                        </select>
                   </div>
                  </div>

                  <div class="col-md-2">
                    <div class="form-group">
                    <label class="control-label">Região:</label>
                    <select id="region" name="region" class="form-control changefield">
                      <option value="">- - -</option>
                      <option value="Norte"    <?=($dados['region']=="Norte"?"selected":"");?>   >Norte</option>
                      <option value="Sul"      <?=($dados['region']=="Sul"?"selected":"");?>     >Sul</option>
                      <option value="Leste"    <?=($dados['region']=="Leste"?"selected":"");?>   >Leste</option>
                      <option value="Oeste"    <?=($dados['region']=="Oeste"?"selected":"");?>   >Oeste</option>
                      <option value="Nordeste" <?=($dados['region']=="Nordeste"?"selected":"");?>>Nordeste</option>
                      <option value="Sudeste"  <?=($dados['region']=="Sudeste"?"selected":"");?> >Sudeste</option>
                      <option value="Noroeste" <?=($dados['region']=="Noroeste"?"selected":"");?>>Noroeste</option>
                      <option value="Sudoeste" <?=($dados['region']=="Sudoeste"?"selected":"");?>>Sudoeste</option>
                    </select>
                  </div>
                  </div>

                  <div class="form-group">
											<div class="col-md-4 text-center" style="margin-top:28px">
												<button id="geocode" type="button" class="btn btn-sm btn-primary" style="width:100%"><i class="fa fa-map-marker"></i> Localizar no mapa</button>
											</div>
										</div>
           </div>

           <div class="row">
                 <div class="col-sm-6">
                   <div class="form-group">
                   <label class="control-label">Complemento do endereço/pontos de referencia:</label>
                       <input type="text" id="endereco_complemento" name="endereco_complemento" class="form-control changefield" value="<?=$dados['address_complement'];?>">
                  </div>
                 </div>


                 <div class="col-md-6">
                   <script>
                    var livro_de_endereco = [];
                   <?
                        //$sql = "SELECT * FROM ".$schema."oct_addressbook WHERE id_company = '".$_SESSION['id_company']."' ORDER BY name ASC";

                        $sql = "SELECT
                                	S.name as street_name, A.*
                                FROM
                                	".$schema."oct_addressbook A
                                LEFT JOIN ".$schema."streets S ON S.id = A.id_street
                                --WHERE A.id_company = '".$_SESSION['id_company']."'
                                WHERE active = 't'
                                ORDER BY
                                A.NAME ASC";

                         $res = pg_query($sql)or die("Erro ".__LINE__);
                         if(pg_num_rows($res))
                         {
                             while($d = pg_fetch_assoc($res))
                             {
                               $vet_livro_end[$d['neighborhood']][] = $d;

                               if($d['id_street']!=""){
                                 //echo "livro_de_endereco.push({id_street:'".$d['id_street']."', street_name: '".$d['street_name']."'});";
                                 echo "livro_de_endereco[".$d['id']."] = {id_street:'".$d['id_street']."', street_name: '".$d['street_name']."', num_ref: '".$d['num_ref']."', geoposition: '".$d['geoposition']."'};";
                               }
                             }
                         }
                   ?>
                   </script>
                                 <div class="form-group">
                                 <label class="control-label">Agenda de Endereço:</label>
                                 <select id="id_addressbook" name="id_addressbook" class="form-control select2 changefield">
                                    <?

                                            if(isset($vet_livro_end) && count($vet_livro_end))
                                            {
                                               echo "<option value=''></option>";
                                               foreach ($vet_livro_end as $bairro => $livro_end) {
                                                   echo "<optgroup label='".$bairro."'>";
                                                   for($i=0;$i<count($livro_end);$i++)
                                                   {

                                                       if($dados['id_addressbook']==$livro_end[$i]["id"]){ $sel = "selected";}else{$sel="";}
                                                       echo "<option value='".$livro_end[$i]["id"]."' ".$sel.">".$livro_end[$i]["name"]."</option>";
                                                   }
                                                   echo "</optgroup>";
                                               }
                                          }
                                    ?>
                                 </select>
                                </div>
               </div>



             </div>

           <div class="row">


             <div class="col-sm-6">
               <?
                //if($_SESSION['id']==1 && $turno_aberto['id']!="")
                if($turno_aberto['id']!="")
                {
                  $sql = "SELECT
                          	F.nickname, F.plate, F.model, F.brand,
                          	G.*
                          FROM
                          	".$schema."oct_garrison G
                          LEFT JOIN ".$schema."oct_fleet F ON F.id = G.id_fleet
                          WHERE
                          	G.id_workshift = '".$turno_aberto['id']."' AND G.closed is null AND G.name is not null";
                  $res = pg_query($sql)or die("SQL error ".__LINE__);
                  while($aux = pg_fetch_assoc($res)){
                    if($aux['name']!=""){ //Nome da guarnição, novo modelo de gestão//
                      $vetor_nova_guarnicao[$aux['id']] = $aux;
                    }
                  }

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
                          	V.id_garrison IN (SELECT G.ID FROM ".$schema."oct_garrison G WHERE G.id_workshift = '".$turno_aberto['id']."' AND G.closed is null AND G.name is not null)";

                  $res = pg_query($sql)or die("SQL error ".__LINE__);
                  while($aux = pg_fetch_assoc($res)){
                    $vetor_nova_guarnicao[$aux['id_garrison']]['veiculos'][$aux['id']] = $aux;
                  }

                  $sql = "SELECT
                          	U.NAME,
                          	U.nickname,
                          	U.registration,
                          	P.*
                          FROM
                          	".$schema."oct_rel_garrison_persona
                          	P JOIN ".$schema."users U ON U.ID = P.id_user
                          WHERE
                          	P.id_garrison IN (SELECT G.ID FROM ".$schema."oct_garrison G WHERE G.id_workshift = '".$turno_aberto['id']."' AND G.closed is null AND G.name is not null)
                          ORDER BY U.nickname ASC";

                  $res = pg_query($sql)or die("SQL error ".__LINE__);
                  while($aux = pg_fetch_assoc($res)){

                    if($aux['id_rel_garrison_vehicle']!="")
                    {
                      $vetor_nova_guarnicao[$aux['id_garrison']]['veiculos'][$aux['id_rel_garrison_vehicle']]['pessoas'][] = $aux;
                    }else {
                      $vetor_nova_guarnicao[$aux['id_garrison']]['pessoas_a_pe'][] = $aux;
                    }
                  }
                  echo "<div class='form-group'>
                        <label class='control-label'>Guarnição</label>
                        <select class='form-control changefield select2' name='id_garrison'>
                          <option value=''></option>";
                          if($dados['closed_garrison']!="")
                          {
                              if($dados['name_garrison']=="")//Guarnição modelo antigo//
                              {
                                echo "<optgroup label='Guarnição empenhada (baixada):'>
                                      <option value='".$dados['id_garrison']."' selected>".$dados['fleet_nickname']." - ".$dados['plate'].": ".$dados['nickname_garrison']."</option>
                                      </optgroup>";
                              }else{

                                $sql = "SELECT F.nickname as fleet_nickname, G.name, R.* FROM ".$schema."oct_rel_garrison_vehicle R, ".$schema."oct_garrison G, ".$schema."oct_fleet F WHERE R.id_fleet = F.id AND id_garrison = '".$dados['id_garrison']."' AND R.id_garrison = G.id";
                                $res = pg_query($sql)or die("Erro ".__LINE__);
                                while($auxg = pg_fetch_assoc($res)){
                                    $guarnicao_nova_baixada[$auxg['id_garrison']]['name']=$auxg['name'];
                                    $guarnicao_nova_baixada[$auxg['id_garrison']]['veiculos'][$auxg['id']] = $auxg;
                                }
                                $sql = "SELECT G.name, P.*, U.nickname FROM ".$schema."oct_rel_garrison_persona P, ".$schema."oct_garrison G, ".$schema."users U WHERE id_garrison = '".$dados['id_garrison']."' AND P.id_garrison = G.id AND P.id_user = U.id";
                                $res = pg_query($sql)or die("Erro ".__LINE__);
                                while($auxg = pg_fetch_assoc($res)){
                                    $guarnicao_nova_baixada[$auxg['id_garrison']]['veiculos'][$auxg['id_rel_garrison_vehicle']]['pessoas'][] = $auxg;
                                }
                                unset($str);
                                foreach ($guarnicao_nova_baixada as $id_garrison_baixada => $info_veic) {
                                echo "<optgroup label='Guarnição ".strtoupper($info_veic['name'])." (baixada):'>";
                                  foreach ($info_veic['veiculos'] as $id_rel_gar_veic => $values)
                                  {
                                    if(isset($str)){ $str .= " | ";}
                                    $str .= $values['fleet_nickname'].": ";
                                    unset($straux);
                                    for($i=0;$i<count($values['pessoas']);$i++)
                                    {
                                      $straux[] = $values['pessoas'][$i]['nickname'];
                                    }
                                    $str .= implode(", ",$straux);
                                  }
                                  echo "<option value='".$id_garrison_baixada."' selected>".$str."</option>";
                                echo "</optgroup>";
                                }
                              }
                          }
                        foreach ($vetor_nova_guarnicao as $id_garrison => $info)
                        {
                          echo "<optgroup label='Guarnição ".strtoupper($info['name'])."'>";
                          unset($str_veic, $str_pess);

                          if(isset($info['veiculos']) && count($info['veiculos']))
                          {
                                foreach ($info['veiculos'] as $id_fleet => $info_veic)
                                {
                                  if(isset($str_veic)){ $str_veic .= " | "; }
                                  $str_veic .= $info_veic['nickname'].": ";
                                  unset($str_pess);
                                  for($i=0;$i<count($info_veic['pessoas']);$i++)
                                  {
                                    $str_pess[] = $info_veic['pessoas'][$i]['nickname'];
                                  }
                                  $str_veic .= implode(", ",$str_pess);
                                }
                          }
                          if(isset($info['pessoas_a_pe']) && count($info['pessoas_a_pe']))
                          {
                                unset($str_pess);
                                for($i=0;$i<count($info['pessoas_a_pe']);$i++)
                                {
                                  $str_pess[] = $info['pessoas_a_pe'][$i]['nickname'];
                                }
                                if(isset($str_veic)){ $str_veic .= " | ";}
                                $str_veic .= "A PÉ: ".implode(", ",$str_pess);
                          }
                          if($dados['id_garrison']==$id_garrison){ $sel = "selected";}else{$sel="";}
                          echo "<option value='".$id_garrison."' ".$sel.">".strtoupper($info['name'])." - ".$str_veic."</option>";
                          echo "</optgroup>";
                        }

                        $sql = "SELECT
                                   U.name as user_name, U.registration, U.nickname as user_nickname,
                                   G.*,
                                   F.plate,
                                   F.TYPE,
                                   F.model,
                                   F.brand,
                                   F.nickname
                                 FROM
                                   ".$schema."oct_garrison G
                                 JOIN ".$schema."oct_fleet F ON F.id = G.id_fleet
                                 JOIN ".$schema."oct_rel_garrison_persona GP ON GP.id_garrison = G.id AND GP.type = 'Motorista'
                                 JOIN ".$schema."users U ON U.id = GP.id_user
                                 WHERE
                                     G.id_workshift = '".$turno_aberto['id']."'
                                 AND G.closed is null";
                        $res = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);;
                        if(pg_num_rows($res))
                        {
                          echo "<optgroup label='Guarnições ativas - Modelo antigo:'>";
                          while($dg = pg_fetch_assoc($res))
                          {
                            if($dados['id_garrison']==$dg['id']){ $sel = "selected";}else{$sel="";}
                            echo "<option value='".$dg['id']."' ".$sel.">".$dg['nickname']." - ".$dg['plate'].": ".$dg['user_nickname']."</option>";
                          }
                          echo "</optgroup>";
                        }

                  echo "</select></div>";


                }else {
                  //echo "<br><span class='text-center text-muted'>Nenhuma guarnição e turno de trabalho ativo.</span>";
                }

               ?>
             </div>


                 <div class="col-sm-2">
                   <div class="form-group">
                   <label class="control-label">Data:</label>
                       <input onclick="$(this).val('');" type="text" name="data" class="form-control changefield campo_data" value="<?=($acao=="inserir"?$agora['data']:$data);?>">
                  </div>
                 </div>
                 <div class="col-sm-2">
                   <div class="form-group">
                   <label class="control-label">Hora:</label>
                       <input onclick="$(this).val('');" type="text" name="hora" class="form-control changefield campo_hora" value="<?=($acao=="inserir"?$agora['hm']:$hora);?>">
                  </div>
                 </div>

                 <div class="col-sm-2">
                   <div class="form-group">
                   <label class="control-label">Coordenadas:</label>
                       <input type="text" id="coordenadas" name="coordenadas" class="form-control text-center changefield" value="<?=$dados['geoposition'];?>">
                  </div>
                 </div>
          </div>

<? if($acao == "inserir"){ ?>
  <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Status inicial da ocorrência:</label>
                    <select name="initial_status" class="form-control">
                        <option value="Aberta">Aberta</option>
                        <option value="Em deslocamento">Em deslocamento</option>
                        <option value="Inativa">Inativa (futura)</option>
                    </select>
                  </div>
                </div>
  </div>

<? }else{ ?>
           <div class="row">
                         <div class="col-sm-8">
                           <div class="form-group">
                             <label class="control-label">Descrição detalhada:</label>
                             <textarea name="description" id="description" class="form-control changefield" rows="10" placeholder="Descreva a ocorrência."><?=$dados['description'];?></textarea>
                           </div>
                         </div>

                         <div class="col-sm-4">
                           <?
                           $sql = "SELECT * FROM ".$schema."oct_event_conditions ORDER BY subtype ASC";
                           $res = pg_query($conn_neogrid,$sql)or die("Error: ".__LINE__);
                           while($d = pg_fetch_assoc($res)){ $v[$d['type']][] = $d; }
                           unset($d);
                           ?>
                           <div class="form-group">
                                 <label class="control-label" for="condicoes[]">Condições:</label>
                                   <select size='10' multiple data-plugin-selectTwo id="condicoes[]" name="condicoes[]" class="form-control populate changefield">
                                     <?
                                       foreach($v as $optg => $d){
                                         echo "<optgroup label='".$optg."'>";
                                           for($i = 0; $i < count($d); $i++){
                                              if(isset($dadosCondicoes) && in_array($d[$i]['id'],$dadosCondicoes)){ $sel = "selected"; }
                                              else                                                                { $sel = "";         }
                                              echo "<option value='".$d[$i]['id']."' ".$sel.">".ucfirst($d[$i]['subtype'])."</option>";
                                           }
                                         echo "</optgroup>";
                                       }
                                     ?>
                                   </select>
                           </div>
                         </div>
              </div>


  <div class="row">
    <div class="col-sm-12" style="margin-top:10px">

      <div class="btn-group">
          <a href='oct/<?=$retorno_origem;?>' class="btn btn-default loading">Voltar</a>
          <div class="btn-group dropup">
            <!--<a class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><?=$dados['status'];?> <span class="caret"></span></a>-->
            <button type="button" id="bt_status" class="btn btn-warning dropdown-toggle disable_button" data-toggle="dropdown" ajax="false">Alterar Status <span class="caret"></span></button>
            <ul class="dropdown-menu" role="menu">
              <li><a class="bt_status_action" href="oct/FORM_sql.php?id=<?=$id;?>&status_acao=atualizar&status_alterar=ab">Aberta</a></li>
              <li><a class="bt_status_action" href="oct/FORM_sql.php?id=<?=$id;?>&status_acao=atualizar&status_alterar=d">Em deslocamento</a></li>
              <li><a class="bt_status_action" href="oct/FORM_sql.php?id=<?=$id;?>&status_acao=atualizar&status_alterar=a">Em atendimento</a></li>
              <li><a class="bt_status_action" href="oct/FORM_sql.php?id=<?=$id;?>&status_acao=atualizar&status_alterar=e">Encaminhamento</a></li>
              <li class="divider"></li>
              <li><a class="bt_status_action" href="oct/FORM_sql.php?id=<?=$id;?>&status_acao=atualizar&status_alterar=f">Ocorrência Terminada</a></li>
              <li class="divider"></li>
              <li class="divider"></li>
              <li><a class="bt_status_action" href="oct/FORM_sql.php?id=<?=$id;?>&status_acao=atualizar&status_alterar=in">Oc. Inativa (Futura)</a></li>
              <li class="divider"></li>
              <li class="text-center"><span class='text-warning'><i>Cancelar ocorrência</i></span></li>
              <li><a class="bt_status_action" href="oct/FORM_sql.php?id=<?=$id;?>&status_acao=atualizar&status_alterar=ce">Evadido/Não Localiz.</a></li>
              <li><a class="bt_status_action" href="oct/FORM_sql.php?id=<?=$id;?>&status_acao=atualizar&status_alterar=ct">Trote</a></li>
              <li><a class="bt_status_action" href="oct/FORM_sql.php?id=<?=$id;?>&status_acao=atualizar&status_alterar=cc">Central</a></li>
              <li><a class="bt_status_action" href="oct/FORM_sql.php?id=<?=$id;?>&status_acao=atualizar&status_alterar=cs">Sem recurso</a></li>
            </ul>
          </div>
          <button id='bt_atualizar_oc'  type='submit' class="btn btn-primary bt_atualizar_oc" role="button">Atualizar Ocorrência</button>
          <button type='button' class="btn btn-danger bt_recarregar_oc" style="display:none" role="button">Cancelar atualizações</button>

      </div>


    </div>
  </div>






<?
  if($_SESSION["company_configs"]["oct_submodule_envolvidos"]!="false")
  {
?>
              <div class="row" style="margin-top:15px">
                    <div class="col-md-12">
            							<section class="panel panel-featured panel-featured-warning">
            								<header class="panel-heading">
            									<div class="panel-actions" style="margin-top:-10px">
                                  <div class="btn-group">
                  										<button id="bt_add_veic" type="button" class="mb-xs mt-xs mr-xs btn btn-default loading disable_button"><small class='text-muted'>1.</small> <i class="fa fa-car"></i> <sup><i class="fa fa-plus"></i></sup> Veículos</button>
                                      <button  id="bt_add_vit"  type="button"  class="mb-xs mt-xs mr-xs btn btn-default loading disable_button"><small class='text-muted'>2.</small> <i class="fa fa-user"></i> <sup><i class="fa fa-plus"></i></sup> Envolvidos</button>
                                  </div>
            									</div>
            									<h2 class="panel-title">Envolvidos:</h2>
            								</header>
            								<div class="panel-body">

            								<?
                                $sqlVei = "SELECT * FROM ".$schema."oct_vehicles WHERE id_events = '".$id."'";
                                $resVei = pg_query($conn_neogrid,$sqlVei)or die("Error ".__LINE__);
                                while($d = pg_fetch_assoc($resVei)){ $veics[$d['id']] = $d; }


                                $sqlVit = "SELECT * FROM ".$schema."oct_victim WHERE id_events = '".$id."'";
                                $resVit = pg_query($conn_neogrid,$sqlVit)or die("Error ".__LINE__);
                                while($d = pg_fetch_assoc($resVit)){
                                  if($d['id_vehicle'] != ""){ $veics[$d['id_vehicle']]['vitimas'][] = $d; }
                                                        else{ $vits[$d['id']] = $d;                       }

                                }

                                if(isset($veics))
                                {

                                    foreach($veics as $id_veic => $info)
                                    {
                                      echo "<table class='table  table-striped table-bordered table-condensed'>";
                                      echo "<tr>";
                                        echo "<td width='20px' class='text-muted'>".$id_veic."</td>";
                                        echo "<td>".$info['description']."</td>";
                                        echo "<td width='200px'>Cor: ".$info['color']."</td>";
                                        echo "<td width='200px'>Placa: ".$info['licence_plate']."</td>";
                                      echo "</tr>";
                                      echo "<tr>";
                                        echo "<td colspan='4'><b>Observações: </b>".$info['observation']."</td>";
                                      echo "</tr>";
                                      if(isset($info['vitimas']))
                                      {
                                        echo "<tr>";
                                          echo "<td colspan='4'>";
                                              //if(count($info['vitimas'])){ echo "<h6>Vítimas:</h6>";}
                                              for($i = 0; $i < count($info['vitimas']);$i++)
                                              {
                                                echo "<table class='table  table-striped table-bordered table-condensed'>";
                                                echo "<thead><tr bgcolor='#dbe9ff'><th>#</th>
                                                             <th width='300px'>Nome</th>
                                                             <th>Idade</th>
                                                             <th>Sexo</th>
                                                             <th>Posição</th>
                                                             <th>Encaminhado</th>
                                                             <th>Estado</th>
                                                             </tr></thead>";
                                                echo "<tbody>";

                                                echo "<tr>";
                                                  echo "<td class='text-muted'>".$info['vitimas'][$i]['id']."</td>";
                                                  echo "<td>".$info['vitimas'][$i]['name']."</td>";
                                                  echo "<td>".$info['vitimas'][$i]['age']."</td>";
                                                  echo "<td>".$info['vitimas'][$i]['genre']."</td>";
                                                  echo "<td>".$info['vitimas'][$i]['postion_in_vehicle']."</td>";
                                                  echo "<td>".$info['vitimas'][$i]['forwarded_to']."</td>";
                                                  echo "<td>".$info['vitimas'][$i]['state']."</td>";
                                                echo "</tr>";
                                                echo "<tr>";
                                                  echo "<td colspan='7'><b>Observações: </b>".$info['vitimas'][$i]['description']."</td>";
                                                echo "</tr>";
                                                echo "</tbody></table>";
                                              }

                                          echo "</td>";
                                        echo "</tr>";
                                      }
                                      echo "</table>";
                                    }

                                }

                                if(isset($vits))
                                {
                                  echo "<table class='table table-condensed'>";
                                  echo "<thead><th>#</th>
                                               <th>Nome</th>
                                               <th>Idade</th>
                                               <th>Sexo</th>
                                               <th>Encaminhado</th>
                                               <th>Estado</th>
                                               <th>Descrição</th></thead>";
                                  echo "<tbody>";
                                    foreach($vits as $id_vit => $info)
                                    {
                                      echo "<tr>";
                                        echo "<td>".$info['id']."</td>";
                                        echo "<td>".$info['name']."</td>";
                                        echo "<td>".$info['age']."</td>";
                                        echo "<td>".$info['genre']."</td>";
                                        echo "<td>".$info['forwarded_to']."</td>";
                                        echo "<td>".$info['state']."</td>";
                                        echo "<td>".$info['description']."</td>";
                                      echo "</tr>";
                                    }
                                  echo "</tbody>";
                                  echo "</table>";
                                }
                            ?>
            								</div>
            							</section>
    						      </div>
              </div>
<? } ?>



              <div class="row" style="margin-top:15px">
                    <div class="col-md-12">
                          <section class="panel panel-featured panel-featured-warning">
                            <header class="panel-heading">
                              <div class="panel-actions" style="margin-top:-10px">
                                  <div class="btn-group">
                                      <button id="bt_add_prov" type="button" class="mb-xs mt-xs mr-xs btn btn-default loading disable_button"><small class='text-muted'>3.</small> <i class="fa fa-file-powerpoint-o"></i> <sup><i class="fa fa-plus"></i></sup> Providências</button>
                                  </div>
                              </div>
                              <h2 class="panel-title">Providências:</h2>
                            </header>
                            <div class="panel-body">

                                <?
                                  $sql = "SELECT
                                                 U.name,
                                                 C.acron, C.name as company,
                                                 VE.description as vehicle, VE.color as vehicle_color, VE.licence_plate,
                                                 VI.name as victim_name, VI.age as victim_age,
                                                 H.name as hospital,
                                                 CO.acron as company_acron, CO.name as company_name,
                                                 PR.area, PR.providence,
                                                 UR.name AS responsavel,
                                                 P.*
                                          FROM ".$schema."oct_rel_events_providence P
                                          JOIN ".$schema."users U ON U.id = P.id_owner
                                          JOIN ".$schema."company C ON C.id = U.id_company
                                          LEFT JOIN ".$schema."oct_vehicles VE ON VE.id = P.id_vehicle
                                          LEFT JOIN ".$schema."oct_victim   VI ON VI.id = P.id_victim
                                          LEFT JOIN ".$schema."hospital      H ON  H.id = P.id_hospital
                                          LEFT JOIN ".$schema."company      CO ON CO.id = P.id_company_requested
                                          LEFT JOIN ".$schema."users        UR ON UR.id = P.id_user_resp
                                          JOIN ".$schema."oct_providence    PR ON PR.id = P.id_providence
                                          WHERE P.id_event = '".$id."'
                                          ORDER BY P.opened_date DESC";
                                    $res = pg_query($sql)or die("Erro ".__LINE__."<hr><pre>".$sql."</pre>");


                                  if(pg_num_rows($res))
                                  {
                                      while($p = pg_fetch_assoc($res))
                                      {
                                        echo "<table class='table table-bordered table-condensed'>";
                                          echo "<tr bgcolor='#dbe9ff'>";
                                            echo "<td width='10'>".$p['area']."</td>";
                                            echo "<td>".$p['providence']."</td>";
                                            echo "<td  width='150' align='center'>".formataData($p['opened_date'],1)."</td>";
                                            //echo "<td  width='150px' align='center'>".formataData($p['closed_date'],1)."</td>";
                                          echo "</tr>";
                                          echo "<tr>";
                                            echo "<td colspan='3'>";


                                            echo "<table class='table'>";
                                            if(isset($p['responsavel'])!=""){     echo "<td width='50'><span style='color:#CCCCCC'>Responsável:</span></td><td>".$p['responsavel']."</td>"; }
                                            echo "<tr><td width='50'><span style='color:#CCCCCC'>Observações:</span></td><td>";
                                            if($p['observation'] != ""){ echo $p['observation']; }else{ echo "<span style='color:#CCCCCC'>Nenhuma anotação de observação para essa providência.</span>";}
                                            echo "</td></tr>";

                                              if(isset($p['vehicle']) || isset($p['victim_name']) || isset($p['hospital']) || isset($p['company_name']))
                                              {
                                                //echo "<hr><span style='color:#CCCCCC'>Envolvidos: </span>";

                                                echo "<tr>";
                                                if(isset($p['vehicle'])){      echo "<td width='50'><span style='color:#CCCCCC'>Veículo:</span></td><td>".$p['vehicle'].", ".$p['vehicle_color']." - ".$p['licence_plate']."</td>"; }
                                                if(isset($p['victim_name'])){  echo "<td width='50'><span style='color:#CCCCCC'>Envolvido:</span></td><td>".$p['victim_name'];
                                                                               if(isset($p['victim_age'])){ echo ", idade: ".$p['victim_age']." ano(s)"; }
                                                                               echo  "</td>"; }

                                                echo "</tr><tr>";

                                                if(isset($p['hospital'])){     echo "<td width='50'><span style='color:#CCCCCC'>Hospital:</span></td><td>".$p['hospital']."</td>"; }
                                                if(isset($p['company_name'])){ echo "<td width='50'><span style='color:#CCCCCC'>Orgão:</span></td><td>".$p['company_name']."</td>";}

                                                echo "</tr>";

                                              }
                                              echo "</table>";
                                            echo "</td>";
                                          echo "</tr>";

                                          echo "<tr bgcolor='#eeeeee'>";
                                            echo "<td colspan='4' align='right'>".$p['name']."<br><small>".$p['acron']." - ".$p["company"]."</small></td>";
                                          echo "</tr>";



                                        echo "</table>";
                                      }
                                  }else{
                                        echo "<div class='alert alert-warning text-center'>Nenhuma providência cadastrada para esta ocorrência.</div>";
                                  }



                                ?>


                          </div>
                        </section>
                  </div>
              </div>

<? } ?>


            <!-- ========================================================= -->
          </div><!--<div class="col-sm-8"> FORM PRINCIPAL-->
          <div class="col-sm-4">
            <!-- ========================================================= -->
            <div class="row">
            <div class="col-sm-12">
              <div class="row">
                <div class="col-sm-12">
                  <div id="map" style="width:100%;height:240px"></div>
                </div>
                <div class="col-sm-12">
                  <div id="mapinfo" class="text-muted" align="right" style="width:100%;margin:5px;">Debug</div>
                </div>
              </div>
            </div>
            </div>
<? if($acao != "inserir"){ ?>
            <div class="row">
            <div class="col-sm-12">

<table class="table table-condensed">
  <thead><tr><th colspan='3' class='text-right info'>Programação de prazo:</th></thead>
  <tbody>
    <tr>
      <td>Inicia em:</td><td>
        <input class='changefield' style='border-width:0px;border:none;height:15px;font-weight:bold;' type='datetime-local' name='init' value='<?=substr(str_replace(" ","T",$dados['init']),0,16);?>'> <i class='fa fa-pencil'></i>
      </td></tr>
    <tr><td>Encerra em:</td><td>
        <input class='changefield' style='border-width:0px;border:none;height:15px;font-weight:bold;' type='datetime-local' name='finish' value='<?=substr(str_replace(" ","T",$dados['finish']),0,16);?>'> <i class='fa fa-pencil'></i>
    </td></tr>

<tr><th colspan='3'  class='text-right info'>Operação:</th></thead>
                <tr><td>Abertura:</td>     <td class=""><b><?=substr(formataData($dados['date'],1),0,16);?></b></td></tr>
                <?
                    if($dados['on_way']!=""){
                        echo "<tr><td>Deslocamento:</td><td class=''><input class='changefield' style='border-width:0px;border:none;height:15px;font-weight:bold;' type='datetime-local' name='on_way' value='".substr(str_replace(" ","T",$dados['on_way']),0,16)."'> <i class='fa fa-pencil'></i></td></tr>";
                    }else{
                          echo "<tr><td>Deslocamento:</td><td class='text-center'></td></tr>";
                    }

                    if($dados['arrival']!=""){
                        echo "<tr><td>Chegada:</td><td class=''><input class='changefield' style='border-width:0px;border:none;height:15px;font-weight:bold;' type='datetime-local' name='arrival' value='".substr(str_replace(" ","T",$dados['arrival']),0,16)."'> <i class='fa fa-pencil'></i></td></tr>";
                    }else{
                          echo "<tr><td>Chegada:</td><td class='text-center'></td></tr>";
                    }
                    if($dados['closure']!=""){
                        echo "<tr><td>Encerramento:</td><td class=''><input class='changefield' style='border-width:0px;border:none;height:15px;font-weight:bold;' type='datetime-local' name='closure' value='".substr(str_replace(" ","T",$dados['closure']),0,16)."'> <i class='fa fa-pencil'></i></td></tr>";
                    }else{
                          echo "<tr><td>Encerramento:</td><td class='text-center'></td></tr>";
                    }
                ?>

              </tbody>
            </table>
              <hr>
            </div>
          </div>


<? } ?>



<div class="row">
    <div class="col-sm-12">


<? if($acao == "atualizar"){

  $sql = "SELECT * FROM ".$schema."oct_rel_events_images WHERE id_events = '".$id."' ORDER BY id DESC";
  $res = pg_query($sql)or die("Erro ".__LINE__."SQL: ".$sql);
?>

      <section class="panel panel-featured panel-featured-primary">
        <header class="panel-heading">
          <h4><span class="text-primary">Fotos e arquivos:</h4>
          <div class="panel-actions">
            <button id="bt_upload_imgs" type="button" class="mb-xs mt-xs mr-xs btn  btn-default disable_button"><i class="fa fa-camera"></i> <sup><i class="fa fa-plus"></i></sup></button>
            <input type="file" id="input_img_files" name="files[]" multiple="multiple" style="display:none" />

            <? if(pg_num_rows($res)){   ?>
                <button id="bt_ver_imgs" type="button" class="mb-xs mt-xs mr-xs btn  btn-default disable_button" data-toggle='modal' data-target='#modalFotos'><i class="fa fa-image"></i> <sup><i class="fa fa-eye"></i></sup></button>
            <? } ?>
          </div>
        </header>
        <?
            if(pg_num_rows($res))
            {
              while($f = pg_fetch_assoc($res))
              {
                if($_SESSION['origem'] == "devops"){ $dirdev   = "dev/"; }
                $aux = explode(".", $f['image']);
                $extensao = strtolower($aux[1]);
                if($extensao == "png" || $extensao == "jpg" || $extensao == "jpeg")
                {
                  $fotos      .=  "<img src='oct/uploads/{$dirdev}{$id}/{$f['image']}' style='padding:2px; width:100px' data-toggle='modal' data-target='#modalFotos' />";
                  $arqs_imgs[] = "oct/uploads/{$dirdev}{$id}/{$f['image']}";
                  $qtd_fotos++;
                }else{
                  $qtd_arqs++;
                  $arqs .= "<tr><td>{$f['image']}</td>
                                <td class='text-center'><a href='oct/uploads/{$dirdev}{$id}/{$f['image']}' target='_blank' ajax='false'><i class='fa fa-eye text-success'></i></a></td>
                                <td class='text-center remover_arq' style='display:none'><a href='oct/arq_remove.php?id={$f['id']}&id_oc={$id}&arq=oct/uploads/{$dirdev}{$id}/{$f['image']}'><i class='fa fa-trash text-danger'></i></a></td>
                            </tr>";
                }
              }

              unset($aux,$id_img);

            }
        ?>




        <div class="panel-body">

            <?
            if(!pg_num_rows($res)) //Não há nenhum tipo de arquivo
            {
              echo "<div class='row'>
                      <div class='col-sm-12 text-center'>
                          <i class='fa fa-camera fa-5x text-muted'></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa fa-file-o fa-5x text-muted'></i>
                      </div>
                    </div>";
            }else{
              //FOTOS
              echo  "<div class='row'>
                        <div class='col-sm-12 text-center'>";
                            if($qtd_fotos>0){ echo $fotos; }
                            else{ echo "<i class='fa fa-camera fa-5x text-muted'></i>";}
              echo      "</div>
                    </div>";

             //ARQUIVOS
              echo "<div class='row'>
                      <div class='col-sm-12'>";
                            if($qtd_arqs>0){
                              echo "<table class='table table-condensed'>
                                    <thead><tr><td class='text-muted'><i>Arquivo</i></td>
                                               <td class='text-muted text-center'><i>Visualizar</i></td>
                                               <td class='text-muted text-center remover_arq' style='display:none'><i>Remover</i></td></div>
                                    </thead><tbody>";
                                echo $arqs;
                              echo "</tbody><tfoot>";
                                  echo "<tr><td colspan='3' class='text-right'><button class='btn btn-danger btn-xs' onclick='$(\".remover_arq\").toggle(\"slow\", function(){}); return false;'><i class='fa fa-trash'></i> Remover arquivo</button></td></tr>";
                              echo "</tfoot></table>";
                            }else{
                              echo "<hr><div class='text-center' style='margin-bottom:20px'><i class='fa fa-file-o fa-5x text-muted'></i></div>";
                            }
              echo "</div>
                </div>";

}
?>
       </div>
       <footer class="panel-footer text-right">
         <span id="msg" class="text-muted"><b><?=$qtd_fotos;?></b> foto(s) e <b><?=$qtd_arqs?></b> arquivo(s)</span>
       </footer>
     </section>
<? } ?>

    </div>
</div>


            <!-- ========================================================= -->
          </div><!--<div class="col-sm-4"> FORM LATERAL-->
        </div><!--<div class="row">-->



    </div>
    <footer class="panel-footer">

          <input type="hidden" name="userid" value="<?=$_SESSION['id']?>">
          <input type="hidden" name="acao"   value="<?=$acao;?>">
          <input type="hidden" name="id"     value="<?=$id;?>">

      <?
          if($acao == "inserir")
          {
            echo "<a href='oct/".$retorno_origem."' class='btn btn-default loading'>Voltar</a> ";
            if(!$sistema_nao_config)
            {
              echo "<button id='bt_inserir_oc' type='submit' class='btn btn-primary'>Inserir ocorrência</button>";
            }else {
              echo "<div class='row'><div class='col-md-6 col-md-offset-3'><div class='alert alert-danger text-center'>Sistema ou perfil de usuário não esta completamente configurado.</div></div></div>";
            }
          }else {

      ?>
          <div class="btn-group">
              <a href='oct/<?=$retorno_origem;?>' class="btn btn-default loading">Voltar</a>
              <div class="btn-group dropup">
    						<!--<a class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><?=$dados['status'];?> <span class="caret"></span></a>-->
                <button type="button" id="bt_status" class="btn btn-warning dropdown-toggle disable_button" data-toggle="dropdown" ajax="false">Alterar Status <span class="caret"></span></button>
    						<ul class="dropdown-menu" role="menu">
    							<li><a class="bt_status_action" href="oct/FORM_sql.php?id=<?=$id;?>&status_acao=atualizar&status_alterar=d">Em deslocamento</a></li>
    							<li><a class="bt_status_action" href="oct/FORM_sql.php?id=<?=$id;?>&status_acao=atualizar&status_alterar=a">Em atendimento</a></li>
    							<li><a class="bt_status_action" href="oct/FORM_sql.php?id=<?=$id;?>&status_acao=atualizar&status_alterar=e">Encaminhamento</a></li>
    							<li class="divider"></li>
    							<li><a class="bt_status_action" href="oct/FORM_sql.php?id=<?=$id;?>&status_acao=atualizar&status_alterar=f">Ocorrência Terminada</a></li>
                  <li class="divider"></li>
                  <li class="text-center"><span class='text-warning'><i>Cancelar ocorrência</i></span></li>
                  <li><a class="bt_status_action" href="oct/FORM_sql.php?id=<?=$id;?>&status_acao=atualizar&status_alterar=ce">Evadido/Não Localiz.</a></li>
                  <li><a class="bt_status_action" href="oct/FORM_sql.php?id=<?=$id;?>&status_acao=atualizar&status_alterar=ct">Trote</a></li>
                  <li><a class="bt_status_action" href="oct/FORM_sql.php?id=<?=$id;?>&status_acao=atualizar&status_alterar=cc">Central</a></li>
                  <li><a class="bt_status_action" href="oct/FORM_sql.php?id=<?=$id;?>&status_acao=atualizar&status_alterar=cs">Sem recurso</a></li>
    						</ul>
    					</div>
              <button id='bt_atualizar_oc' type='submit' class="btn btn-primary bt_atualizar_oc" role="button">Atualizar Ocorrência</button>
              <button type='button' class="btn btn-danger bt_recarregar_oc" style="display:none" role="button">Cancelar atualizações</button>

    			</div>
      <? } ?>
    </footer>


    </section>
</section>
</form>



<div class="modal fade"  id="modalFotos" tabindex="-1" role="dialog" aria-labelledby="modalDeFotos" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title text-primary" id="modalDeFotos">Visualização das fotos da ocorrência:
          <br><small>Ocorrência nº. <?=$id;?>
        </h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-20px">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form_filtro" action="oct/ocorrencias.php" method="post">
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-4" style="">
            <?
                for($i = 0; isset($arqs_imgs) && $i < count($arqs_imgs); $i++)
                {
                  $aux    = explode(".",$arqs_imgs[$i]);
                  $aux    = explode("_",$aux[0]);
                  $id_img = ltrim(end($aux),"0");
                  echo  "<img id='".$id_img."' class='loadimage img-responsive img-rounded img-thumbnail' src='".$arqs_imgs[$i]."' style='max-width:100px; max-height:90px' />";
                }
            ?>
          </div>
          <div class="col-sm-8">
              <div class="row">
                <div class="col-sm-12" style="min-height:300px;text-align:center;display:table-cell;vertical-align:middle;" id="area_foto">
                  <i class='fa fa-camera fa-5x text-muted'></i>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-4" style="">
                    <button type="button" id="bt_remover_foto" class="btn btn-danger disabled"><i class="fa fa-trash"></i></button>
                </div>
                <div class="col-sm-8 text-right" id="debug_fotos">. . .</div>
             </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
      </div>
      </form>
    </div>
  </div>
</div>

<script>
 $(window).scrollTop(0);

$("#bt_print").click(function(){
	var vw = window.open('oct/oc_print.php?id_oc=<?=$id;?>&id_workshift=<?=$turno_oc['id'];?>',
									     'popup',
								 	     'width=800, height=600, top=10, left=10, scrollbars=no,location=no,status=no');
});

$("#id_addressbook").change(function(){
  $("#street_number").val('');
  $("#coordenadas").val('');
  $('#id_street').val(null).trigger('change');

  if(typeof (livro_de_endereco[$("#id_addressbook").val()]) !== "undefined")
  {
      var address = livro_de_endereco[$("#id_addressbook").val()];
      console.log(address);
      if(address.id_street)
      {
        $("#id_street").val(address.id_street).trigger('change');
      }
      if(address.num_ref)
      {
        $("#street_number").val(address.num_ref);
      }
      if(address.geoposition)
      {
        $("#coordenadas").val(address.geoposition);
      }
  }
});

$(".campo_hora").mask('00:00');
$(".campo_data").mask('00/00/0000');

function bloqueio()
{
  //alert("Descrição da ocorrência alterada, não esqueça de atualizar os dados antes de qualquer outra interação com o sistema");
  $(".disable_button").addClass("disabled");
  $(".bt_recarregar_oc").show();
  //$(".bt_atualizar_oc").html("Atualizar Ocorrência <sup>(Houve alteração)</sup>");
}

$(".changefield").change(function(){ bloqueio(); });

$("#bt_add_prov").click(function(){ $("#wrap").load("oct/FORM_providencias.php?id_workshift=<?=$turno_oc['id'];?>&id=<?=$id;?>");   })
$("#bt_add_veic").click(function(){ $("#wrap").load("oct/FORM_veiculo.php?id_workshift=<?=$turno_oc['id'];?>&id=<?=$id;?>"); })
$("#bt_add_vit").click(function() { $("#wrap").load("oct/FORM_vitima.php?id_workshift=<?=$turno_oc['id'];?>&id=<?=$id;?>");  })

$(".bt_recarregar_oc").click(function(){ $('#wrap').load("oct/FORM.php?id=<?=$id;?>"); });

var id_img_remover;
var removeu_image = false;


function removeArq(id_oc, id_arq, arq)
{

//  var srcDel = $("#foto_ativa").attr("src");
//  $("#debug_fotos").html("Removendo foto: "+srcDel);
//  $("#area_foto").html("<i class='fa fa-camera fa-5x text-muted'></i>");
//  $("#"+id_img_remover).hide();
//  $("#debug_fotos").load("oct/image_remove.php?id_oc=<?=$id;?>&id="+id_img_remover+"&arq="+srcDel);
//  removeu_image = true;

};

$("#bt_remover_foto").click(function(e){

  var srcDel = $("#foto_ativa").attr("src");
  $("#debug_fotos").html("Removendo foto: "+srcDel);
  $("#area_foto").html("<i class='fa fa-camera fa-5x text-muted'></i>");
  $("#"+id_img_remover).hide();
  $("#debug_fotos").load("oct/image_remove.php?id_oc=<?=$id;?>&id="+id_img_remover+"&arq="+srcDel);
  removeu_image = true;

});

$('#modalFotos').on('hidden.bs.modal',function(e){

    if(removeu_image){ $('#wrap').load("oct/FORM.php?id=<?=$id;?>");}
    removeu_image = false;

});


$(".loadimage").click(function(){
    var src        = $(this).attr("src");
    id_img_remover = $(this).attr("id");
    $("#debug_fotos").html("Carregando foto: "+src);
    $("#area_foto").html("<img id='foto_ativa' src='"+src+"' class='img-responsive img-rounded img-thumbnail' style='max-height:430px'/>");
    $("#debug_fotos").html(src);
    $("#bt_remover_foto").removeClass("disabled");
});

/*
$(document).ready(function (e) {

  $('#bt_upload').on('click',function(){
    $("#msg").html("Clicou para inserir imagens<br>");
    $("#arq_imgs").click();

  });
   $("#arq_imgs").change(function(){ alert( "Upload de imagens..." );});


 $('#bt_uploadsss').on('click', function () {

                   var form_data = new FormData();
                   var ins = document.getElementById('arq_imgs').files.length;
                   for (var x = 0; x < ins; x++) {
                       form_data.append("files[]", document.getElementById('arq_imgs').files[x]);
                   }
                   $.ajax({
                       url: 'oct/image_upload.php?id_oc=<?=$id?>', // point to server-side PHP script
                       dataType: 'text', // what to expect back from the PHP script
                       cache: false,
                       contentType: false,
                       processData: false,
                       data: form_data,
                       type: 'post',
                       success: function (response) {
                           alert(response);
                           $('#msg').html(response); // display success response from the PHP script
                       },
                       error: function (response) {
                           alert(response);
                           $('#msg').html(response); // display error response from the PHP script
                       }
                   });
               });
           });
*/

$("#bt_upload_imgs").click(function(){ $("#msg").html("Buscando imagens."); $("#input_img_files").trigger("click"); });
$("#input_img_files").change(function(e){
    if(e.target.files.length == 1)
    {
      $("#msg").html("<br>Imagen selecionada:");
    }else {
      $("#msg").html("<br>Imagens selecionadas:");
    }

    for(i = 0; i < e.target.files.length; i++)
    {
      $("#msg").append("<br><b>"+e.target.files[i].name+"</b>");
    }

    $("#msg").append("<br><b class='text-danger'>Aguarde, enviando arquivos...</b>");

    var form_data = new FormData();
    var ins = document.getElementById('input_img_files').files.length;
    for (var x=0;x<ins;x++){form_data.append("files[]", document.getElementById('input_img_files').files[x]);  }
    $.ajax({
        url: 'oct/image_upload.php?id_oc=<?=$id?>',
        dataType: 'text', // what to expect back from the PHP script
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function (response) {
            $('#msg').html(response);
            $('#wrap').load("oct/FORM.php?id=<?=$id;?>");
        },
        error: function (response) {
            $('#msg').html(response);
        }
    });
})

//$('#id_street').select2();
//$('#tipo_oc').select2();
$('.select2').select2({
  language: {
        noResults: function() {
          return 'Nenhum resultado encontrado.';
        }
      }
});

$("#geocode").click(function(){geocode();});
$("#bt_inserir_oc").click(function(){ $("#bt_inserir_oc").html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde, inserindo ocorrência</small>");});
$(".bt_status_action").click(function(){ $("#bt_status").html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde, atualizando ocorrência</small>");});
$("#bt_atualizar_oc").click(function(){
          $("#bt_atualizar_oc").html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde, atualizando ocorrência</small>");
          $("#bt_atualizar_oc").attr("disabled", "disabled");
          $("#form_oct").submit();
});



<?
  if($dados['geoposition'] != "")
  {
    $zoommap = 16;
    $posicao = $dados['geoposition'];
  }else{
    $zoommap = 13;
    $posicao = "-26.301033,-48.840862";
  }
?>
zoommap 		= <?=$zoommap;?>;
var latlon  = new L.latLng(<?=$posicao;?>);
var map 		= new L.map('map', {attributionControl: false, zoomControl: true}).setView(latlon, zoommap);


L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1Ijoiam9uc25pZSIsImEiOiJjazdvdHg2cmQwY3NoM2VwOXg1YWdzNWN0In0.teqXLAHyVSJwut8hqAMONw', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox.streets',
    accessToken: 'pk.eyJ1Ijoiam9uc25pZSIsImEiOiJjazdvdHg2cmQwY3NoM2VwOXg1YWdzNWN0In0.teqXLAHyVSJwut8hqAMONw'
}).addTo(map);

map.on("dragend",   function (e) {$("#mapinfo").html("MAPA: dragend");	count=0;	});
map.on("dragstart", function (e) {});
map.on("drag",      function (e) {$("#mapinfo").html("MAPA: drag    ["+count+++"]");});
map.on("zoom",      function (e) {$("#mapinfo").html("MAPA: zoom"); map.flyTo(marco.getLatLng()); });

map.removeControl(map.zoomControl);
if (!L.Browser.mobile){  L.control.zoom({position:'bottomright'}).addTo(map);}

//var marker = L.marker([<?=$posicao;?>]).addTo(map);
var latlon  = new L.latLng(<?=$posicao;?>);
marco 			= false;
marco = L.marker(latlon, {draggable:'true', autoPan: 'true', autoPanSpeed: '1' })
         .addTo(map)
         .on('dragstart', function(){ map.dragging.disable(); $('#mapinfo').html('MARKER: dragstart'); 				  })
         .on('drag',      function(){ 												$('#mapinfo').html('MARKER: drag ['+count+++']');  })
         .on('dragend',   function(){ map.dragging.enable();
                                      $('#mapinfo').html('MARKER: dragend: '+marco.getLatLng());
                                      count=0;
                                      map.flyTo(marco.getLatLng());
                                      $("#coordenadas").val(marco.getLatLng().lat+","+marco.getLatLng().lng);
                                      bloqueio();
                                    });



function geocode(){

          $("#mapinfo").html('Pesquisa iniciada.');
//  if(marco){ map.removeLayer(marco); marco = false;}
          cidade = "Joinville";
          estado = "Santa Catarina";
          pais   = "Brasil";

          endereco = $("#id_street option:selected").text()+" "+$("#street_number").val();

//https://nominatim.openstreetmap.org/search?street=Rua%20Max%20Colin,%201265&city=Joinville&state=Santa%20Catarina&country=Brasil&format=json
//https://nominatim.openstreetmap.org/search?street=Rua Dr Joao Colin, 2008, 401A&city=Joinville&state=Santa Catarina&country=Brasil&format=json

      var query = "street="+endereco+"&city="+cidade+"&state="+estado+"&country="+pais;
      var url = 'https://nominatim.openstreetmap.org/search?format=json&'+query
      //$("#mapinfo").html(url);

      $.getJSON(url, function(data) {
          $("#mapinfo").html('');
          var nome  = "";
          if(data.length)
          {
              $.each(data, function(key, val)
              {
                $("#mapinfo").html("<br>Geocode retornado, tipo: "+val.type);
                if(val.type == "tertiary" ||  val.type=="city" || val.type=="residential" || val.type=="house" || val.type=="bus_station" || val.type=="secondary" || val.type=="primary")
                {
                    notFound = false; //Para travar na primeira ocorrencia
                    nome = val.display_name.split(',',3).join();
                    //$("#mapinfo").val("Geocode encontrado: "+nome+" Coords:["+val.lat+","+val.lon+"]");
                    if(marco){ map.removeLayer(marco); }
                    marco = L.marker([val.lat, val.lon], {draggable:'true', autoPan: 'true', autoPanSpeed: '1' })
                               .addTo(map)
                               .on('dragstart', function(){ map.dragging.disable(); $('#mapinfo').html('ADDR START MARCO'); 						 })
                               .on('drag',      function(){ 												$('#mapinfo').html('ADDR MOVENDO MARCO: '+count++);  })
                               .on('dragend',   function(){ map.dragging.enable();
                                                            $('#mapinfo').html('ADDR Fim Pos: '+marco.getLatLng());
                                                            count=0;
                                                            map.flyTo(marco.getLatLng());
                                                            $("#coordenadas").val(marco.getLatLng().lat+","+marco.getLatLng().lng);
                                                          });
                    map.flyTo(marco.getLatLng(),12);
                    $("#coordenadas").val(val.lat+","+val.lon);
                    bloqueio();
                    //$("#geocoderet").removeClass("text-muted text-danger").addClass("text-success").html("<b>O marcador pode ser posicionado manualmente para um melhor ajuste. Para concluir, clique em atualizar.</b>");
                }
              });
          }else{
              $("#mapinfo").html("Geocode não encontrado.");
              //$("#geocoderet").removeClass("text-muted text-success").addClass("text-danger").html("<b>Endereço não encontrado no mapa, especifique melhor ou posicione manualmente o marcador sobre o mapa.</b>");
          }
      });

      return false;
};
</script>
