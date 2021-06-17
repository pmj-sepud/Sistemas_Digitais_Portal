<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora = now();


  if($_GET['tab']!=""){ $tab[$_GET['tab']] = "active";}
                  else{ $tab["cadastro"]   = "active";}


  if($_GET['id']!="")
  {
      $acao = "atualizar";
      $sql  = "SELECT
                  	C.*,
                     T.type, T.request,
                  	CO.name as company_name, CO.acron as company_acron,
                  	U.name  as user_added_name,
                     STC.id  as street_corner_id, STC.name as street_corner_name,
                     MST.id  as main_street_id, MST.name as main_street_name
                  FROM {$schema}gsec_callcenter        C
                  LEFT JOIN {$schema}company           CO ON  CO.id = C.id_company
                  LEFT JOIN {$schema}users             U  ON   U.id = C.id_user_added
                  LEFT JOIN {$schema}gsec_request_type T  ON   T.id = C.id_subject
                  LEFT JOIN {$schema}streets          STC ON STC.id = C.id_address_corner
                  LEFT JOIN {$schema}streets          MST ON MST.id = C.id_address
                  WHERE C.id = '{$_GET['id']}'";
      $res  = pg_query($sql)or die("<div class='text-center text-danger'>Error: ".__LINE__."<br>SQL {$sql}</div>");
      $d    = pg_fetch_assoc($res);
      $enderecoMapa[] = $d['main_street_name'];
      logger("Acesso","GSEC - CALLCENTER", "Cadastro de atendimento - Visualização detalhada ID: {$_GET['id']}");

      //Fotos
      $sql = "SELECT * FROM {$schema}gsec_files WHERE id_callcenter='{$_GET['id']}' ORDER BY file_path DESC";
      $resF = pg_query($sql)or die("SQL Error ".__LINE__);
      $qtd_fotos = pg_num_rows($resF);

  }else{
      $acao = "inserir";
      $d['id_citizen'] = $_GET['id_user_request'];
      logger("Acesso","GSEC - CALLCENTER", "Cadastro de atendimento - Novo cadastro");
  }


      $sql  = "SELECT C.id, C.name,
                      C.rg, C.cpf, C.email,
                  	 C.phone1,C.phone2,C.phone3,C.phone4,
                      C.id_residence_address, C.id_neighborhood, C.num_residence_address, C.complement_residence_address,
                      N.neighborhood,
                      S.name as street
               FROM {$schema}gsec_citizen C
               LEFT JOIN {$schema}streets S ON S.id = C.id_residence_address
               LEFT JOIN {$schema}neighborhood N ON N.id = C.id_neighborhood
               WHERE C.id = '{$d['id_citizen']}'";
      $resC = pg_query($sql)or die("SQL Error: ".__LINE__);
      $dC   = pg_fetch_assoc($resC);


      if($acao == "inserir" && $dC['id_residence_address']!= "")       { $d['main_street_id']     = $dC['id_residence_address'];$d['main_street_name'] = $dC['street']; }
      if($acao == "inserir" && $dC['id_neighborhood']!= "")            { $d['id_neighborhood']    = $dC['id_neighborhood']; }
      if($acao == "inserir" && $dC['num_residence_address']!= "")      { $d['address_num']        = $dC['num_residence_address']; }
      if($acao == "inserir" && $dC['complement_residence_address']!=""){ $d['address_complement'] = $dC['complement_residence_address']; }

      if($dC['num_residence_address']!= "")      { $dC['street'] .= ", ".$dC['num_residence_address'];  }
      if($dC['complement_residence_address']!=""){ $dC['street'] .= " (<small class='text-muted'><i>".$dC['complement_residence_address']."</i></small>)"; }

      if($dC['rg']  !=""){ $dPess[]="RG: ".$dC['rg'];    }
      if($dC['cpf'] !=""){ $dPess[]="CPF: ".$dC['cpf'];  }
      if($dC['cnpj']!=""){ $dPess[]="CNPJ: ".$dC['cnpj'];}
      if(isset($dPess))  { $dPess = implode(", ",$dPess);}

      if($dC['phone1']!=""){ $contato[]=$dC['phone1'];}
      if($dC['phone2']!=""){ $contato[]=$dC['phone2'];}
      if($dC['phone3']!=""){ $contato[]=$dC['phone3'];}
      if($dC['phone4']!=""){ $contato[]=$dC['phone4'];}
      if(isset($contato))  { $contato = implode(", ",$contato);}

?>
<style>
.imgThumb {
 border: 2px solid #ddd; /* Gray border */
 border-radius: 4px;  /* Rounded border */
 padding: 5px; /* Some padding */
 margin: 5px;

}
.imgThumb:hover {
 box-shadow: 0 0 2px 1px rgba(0, 140, 186, 0.5);
 cursor:pointer;
}
.fullphoto
{
   border-radius: 10px;
   border: 2px solid #ddd;
   -webkit-box-shadow: 16px 19px 22px -12px rgba(0,0,0,0.75);
   -moz-box-shadow: 16px 19px 22px -12px rgba(0,0,0,0.75);
   box-shadow: 16px 19px 22px -12px rgba(0,0,0,0.75);
}
</style>
<section role="main" class="content-body">
      <header class="page-header">
       <h2>Central de atendimentos</h2>
       <div class="right-wrapper pull-right" style='margin-right:15px;'>
         <ol class="breadcrumbs">
           <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
           <li><a href="gsec/callcenter.php">Atendimento</a></li>
           <li><span class='#'>Cadastro</span></li>
         </ol>
       </div>
      </header>


      <section class="panel box_shadow">

               <header class="panel-heading" style="height:70px">
                  <?
                  if($acao == "atualizar")
                  {
                     $aux = substr(str_replace("-","",$d['date_added']),0,6);
                     $numprotocolo = $aux.".".str_pad($d['id'],4,"0",STR_PAD_LEFT);
                     echo "<h3 style='margin-top:-10px'><small class='text-muted'><i>Protocolo número:</i></small><br><b>".$numprotocolo."</b></h3>";
                  }else{
                     echo "<h3 style='margin-top:-10px'><small class='text-muted'><i>Cadastrar um novo atendimento</i></h3>";
                  }
                  ?>
                  <div class="panel-actions">
                     <div class="hidden-xs">
                        <a href="gsec/citizen_FORM.php?id=<?=$d["id_citizen"];?>"><button class="btn btn-primary" id="bt_visualizar"><i class='fa fa-user'></i><sup><i class='fa fa-search'></i></sup> Visualizar cidadão</button></a>
                        <? if($acao=="atualizar"){ echo "<button type='button' class='btn btn-default' id='bt_print'><i class='fa fa-print'></i> Imprimir</button>"; } ?>
                     </div>
                     <div class="visible-xs" style="margin-top:-45px">
                        <a href="gsec/citizen_FORM.php?id=<?=$d["id_citizen"];?>"><button class="btn btn-primary" id="bt_visualizar"><i class='fa fa-user'></i><sup><i class='fa fa-search'></i></sup></button></a>
                        <? if($acao=="atualizar"){ echo "<button type='button' class='btn btn-default' id='bt_print'><i class='fa fa-print'></i></button>"; } ?>
                     </div>
                  </div>
               </header>




               <div class="panel-body">


                  <div class="row">
                     <div class="col-md-12">
<div class="tabs">
<ul class="nav nav-tabs">
   <li class="<?=$tab['cadastro'];?>"><a href="#cadastro" data-toggle="tab" ajax="false"><i class="fa fa-file-text-o"></i> Solicitação</a></li>
   <? if($acao=="atualizar"){ ?>
      <li class="<?=$tab['mapa'];?>"><a href="#mapa" data-toggle="tab" ajax="false"><i class="fa fa-map-marker"></i> Mapa</a></li>
      <li class="<?=$tab['fotos'];?>"><a href="#fotos" data-toggle="tab" ajax="false"><i class="fa fa-camera"></i> Fotos <?=($qtd_fotos!=0?"<sup><span class='badge bg-success'>{$qtd_fotos}</span></sup>":"<sup class='text-muted'>(0)</sup>");?></a></li>
      <li class="<?=$tab['historico'];?>"><a href="#historico" data-toggle="tab" ajax="false"><i class="fa fa-file-o"></i> Histórico</a></li>
   <? } ?>
</ul>
<div class="tab-content">
   <div id="cadastro" class="tab-pane <?=$tab['cadastro'];?>">
<!---------------------------------------------------------------------->
<!---------------------------------------------------------------------->
<div class="row">
   <div class="col-md-12">
<form action="gsec/callcenter_FORM_sql.php" method="post">
      <div class="row" style="margin-top:10px">
         <div class="col-md-12">
            <section class="panel box_shadow">
               <header class="panel-heading">
                  <p class="panel-subtitle text-muted"><i>Requisitante:</i></p>
                  <h5 class="panel-title text-muted"><b><?=$dC['name'];?></b></h5>

                  <div class='pull-right' style="margin-top:-35px">
                     <? if(check_perm("9_37")){ ?>
                        <button type="button" class='btn btn-sm btn-warning' data-toggle='modal' data-target='#modalFiltroTitularidade'><i class='fa fa-exchange'></i> Transferir Titularidade</button>
                     <? }else{ ?>
                        <button type="button" class='btn btn-sm btn-warning disabled'><i class='fa fa-exchange'></i> Transferir Titularidade</button>
                     <? } ?>
                  </div>

                  </header>
                  <div class="panel-body" style="background-color:white">
                        <div class="table-responsive">
                        <table class="table">
                           <tbody>
                              <tr>
                                  <td style="border: none;"><small class='text-muted'><i>Contato(s):</i></small><br><?=$contato;?></td>
                                  <td style="border: none;"><small class='text-muted'><i>Endereço RESIDÊNCIAL:</i></small><br><?=$dC['street'];?></td>
                                  <td style="border: none;"><small class='text-muted'><i>Bairro:</i></small><br><?=$dC['neighborhood'];?></td>
                              </tr>
                              <tr>
                                 <td colspan='3'><small class='text-muted'><i>E-mail:</i></small><br><?=$dC['email'];?></td>
                               </tr>
                           </tbody>
                        </table>
                        </div>
                  </div>
            </section>
         </div>
      </div>

      <div class='row'>
         <div class='col-md-2'>
            <div class='form-group'>
               <label class='control-label' for='call_origin'>Origem:</label>
               <select class='form-control select2' id='call_origin' name='call_origin'>
                  <option value="">- - -</option>
                  <option value="Telefone"     <?=($d['call_origin']=="Telefone"||$acao=="inserir"?"Selected":"");?>>Telefone</option>
                  <option value="Interno"      <?=($d['call_origin']=="Interno"?"Selected":"");?>     >Interno</option>
                  <option value="E-mail"       <?=($d['call_origin']=="E-mail"?"Selected":"");?>      >E-mail</option>
                  <option value="Pessoalmente" <?=($d['call_origin']=="Pessoalmente"?"Selected":"");?>>Pessoalmente</option>
                  <option value="Carta"        <?=($d['call_origin']=="Carta"?"Selected":"");?>       >Carta</option>
                  <option value="Ouvidoria"    <?=($d['call_origin']=="Ouvidoria"?"Selected":"");?>   >Ouvidoria</option>
                  <option value="Oficio"       <?=($d['call_origin']=="Oficio"?"Selected":"");?>      >Ofício/SEI/Indicação</option>
                  <option value="Aplicativo"   <?=($d['call_origin']=="Aplicativo"?"Selected":"");?>  >Aplicativo</option>
               </select>
            </div>
         </div>
         <div class='col-md-2'>
            <div class='form-group'>
               <label class='control-label' for='origin_type'>Requisitante:</label>
               <select class='form-control select2' id='origin_type' name='origin_type'>
                  <option value="">- - -</option>
                  <option value="Cidadão"   <?=($d['origin_type']=="Cidadão"||$acao=="inserir"?"Selected":"");?>  >Cidadão</option>
                  <option value="Vereador"  <?=($d['origin_type']=="Vereador"?"Selected":"");?> >Vereador</option>
                  <!--<option value="Prefeito"  <?=($d['origin_type']=="Prefeito"?"Selected":"");?> >Prefeito</option>-->
                  <option value="Órgão PMJ" <?=($d['origin_type']=="Órgão PMJ"?"Selected":"");?>>Órgão PMJ</option>
                  <option value="CAJ" <?=($d['origin_type']=="CAJ"?"Selected":"");?>>CAJ</option>
                  <option value="Empresa" <?=($d['origin_type']=="Empresa"?"Selected":"");?>>Empresa</option>
               </select>
            </div>
         </div>

         <div class='col-md-4'>
            <div class='form-group'>
               <label class='control-label' for='sei_num'><b>Interno:</b> Nº SEI/Ouvidoria:</label>
               <input type='text' class='form-control' id='sei_num' name='sei_num' placeholder='Nº SEI/Nº Ouvidoria' value='<?=$d['sei_num'];?>' >
            </div>
         </div>

         <div class='col-md-4'>
            <div class='form-group'>
               <label class='control-label' for='sei_num'><b>Externo:</b> Nº Protocolo/Indicação:</label>
               <input type='text' class='form-control' id='external_protocol' name='external_protocol' placeholder='Nº procolo externo' value='<?=$d['external_protocol'];?>' >
            </div>
         </div>
      </div>

      <div class="row"><div class="col-md-12"><hr></div></div>

      <div class='row'>
            <div class='col-md-4'>

               <div class='form-group'>
                  <label class='control-label' for='id_address'>Endereço:</label>
                  <select id="id_address" name="id_address" class="form-control select2SearchStreet" style="width: 100%; height:100%">
                  <option value="">- - -</option>
                  <?
                     if($d['main_street_id'] != "" && $d['main_street_name']!=""){ echo "<option value='{$d['main_street_id']}' selected>{$d['main_street_name']}</option>"; }
                  ?>
                  </select>
               </div>
            </div>

            <div class='col-md-2'>
               <div class='form-group'>
                  <label class='control-label' for='address_num'>Num.:</label>
                  <input type='number' class='form-control' id='address_num' name='address_num' placeholder='' value='<?=$d['address_num'];?>' >
                  <? if($d['address_num']!=""){ $enderecoMapa[] = ", Nº ".$d['address_num']; }?>
               </div>
            </div>

            <div class='col-md-2'>
               <div class='form-group'>
                  <label class='control-label' for='address_complement'>Complemento:</label>
                  <input type='text' class='form-control' id='address_complement' name='address_complement' placeholder='' value='<?=$d['address_complement'];?>' >
               </div>
            </div>

            <div class='col-md-4'>
               <div class='form-group'>
                  <label class='control-label' for='id_neighborhood'>Bairro:</label>
                  <select id="id_neighborhood" name="id_neighborhood" class="form-control select2" style="width: 100%; height:100%" required>
                  <option value="">- - -</option>
                  <?
                     $sql = "SELECT * FROM ".$schema."neighborhood ORDER BY neighborhood ASC";
                     $res = pg_query($sql)or die();
                     while($s = pg_fetch_assoc($res))
                     {
                       if($d["id_neighborhood"] == $s["id"]){ $sel = "selected"; $enderecoMapa[] = ", <i class='text-muted'>Bairro:</i> ".$s['neighborhood']; }else{$sel="";}
                       echo "<option value='".$s['id']."' ".$sel.">".$s['neighborhood']."</option>";
                     }
                  ?>
                  </select>
                  <? if($d['address_complement']!=""){ $enderecoMapa[] = "<br><small>Complemento: ".$d['address_complement']."</small>"; }?>
               </div>
            </div>
      </div>

      <div class="row">
         <div class='col-md-4'>
            <div class='form-group'>
               <label class='control-label' for='id_address_corner'>Esquina com:</label>
               <select id="id_address_corner" name="id_address_corner" class="form-control select2SearchStreet" style="width: 100%; height:100%">
                   <option value="">- - -</option>
                   <?
                     if($d['street_corner_id'] != "" && $d['street_corner_name'] != ""){ echo "<option value='{$d['street_corner_id']}' selected>{$d['street_corner_name']}</option>"; }
                     //echo $optCorner;
                   ?>
               </select>

            </div>
         </div>
         <div class='col-md-8'>
            <div class='form-group'>
               <label class='control-label' for='address_reference'>Ponto de referência:</label>
               <input type='text' class='form-control' id='address_reference' name='address_reference' placeholder='' value='<?=$d['address_reference'];?>' >
            </div>
         </div>
      </div>

      <div class='row'>
         <div class='col-md-6'>
            <div class='form-group'>
               <label class='control-label' for='id_subject'>Solicitação:</label>
               <?
                     if($acao == "inserir"){
                           $sql = "SELECT * FROM {$schema}gsec_request_type
                                   WHERE id_company = '{$_SESSION['id_company']}' OR id_company_father = '{$_SESSION['id_company_father']}'
                                   ORDER BY type DESC, request ASC";
                           $res = pg_query($sql)or die("<div class='text-center text-danger'>Error: ".__LINE__."<br>SQL {$sql}</div>");
                           while($t = pg_fetch_assoc($res)){ $opttype[$t['type']][$t['request']] = $t['id']; }
                     }else{
                           //id_company or id_company_father da solicitação original
                           $sql = "SELECT id_company, id_company_father
                                   FROM {$schema}gsec_request_type
                                   WHERE id = '{$d['id_subject']}'";
                           $res = pg_query($sql)or die("Query error ".__LINE__);
                           $aux = pg_fetch_assoc($res);
                           if($aux['id_company']!="")
                           {
                              $sql = "SELECT * FROM {$schema}gsec_request_type
                                       WHERE id_company = '{$aux['id_company']}'
                                       ORDER BY type DESC, request ASC";
                           }else{
                              $sql = "SELECT * FROM {$schema}gsec_request_type
                                       WHERE id_company_father = '{$aux['id_company_father']}'
                                       ORDER BY type DESC, request ASC";
                           }
                           $res = pg_query($sql)or die("<div class='text-center text-danger'>Error: ".__LINE__."<br>SQL {$sql}</div>");
                           while($t = pg_fetch_assoc($res)){ $opttype[$t['type']][$t['request']] = $t['id']; }
                     }


               ?>
               <select class='form-control select2' id='id_subject' name='id_subject' required>
                  <option value="">- - -</option>
                  <?
                     foreach ($opttype as $type => $requests) {
                        echo "<optgroup label='{$type}'>";
                           foreach ($requests as $request => $id){

                              if($d['id_subject'] == $id){$sel="selected";}else{$sel="";}
                              echo "<option value='{$id}' {$sel}>{$request}</option>";
                           }
                        echo "</optgroup>";
                     }
                  ?>
               </select>
            </div>
         </div>
      </div>

      <div class='row'>
         <div class='col-md-6'>
            <div class='form-group'>
               <label class='control-label' for='description'>Descrição:</label>
               <textarea class='form-control' id='description' name='description' rows='5' style="height:100%;" placeholder='Descrição detalhada da solicitação.'><?=$d['description'];?></textarea>
            </div>
         </div>
         <div class='col-md-6'>
            <div class='form-group'>
               <label class='control-label' for='description'>Parecer técnico:</label>
               <textarea class='form-control' id='response' name='response' rows='5' style="height:100%;" placeholder='Descrição detalhada do parecer técnico.'><?=$d['response'];?></textarea>
            </div>
         </div>
      </div>

      <div class='row'>
         <div class='col-md-2'>
            <div class='form-group'>
               <label class='control-label' for='status'>Status:</label>
               <select id="status" name="status" class="form-control select2" style="width: 100%; height:100%">
               <option value="">- - -</option>
               <optgroup label="Aberto">
                  <option value="Em análise"  <?=($d['status']=="Em análise"||$acao=="inserir"?"Selected":"");?> >Em análise</option>
                  <option value="Agendado"    <?=($d['status']=="Agendado"?"Selected":"");?>                     >Agendado</option>
                  <option value="Em execução" <?=($d['status']=="Em execução"?"Selected":"");?>                  >Em execução</option>
               </optgroup>
               <optgroup label="Fechado">
                  <option value="Não procede" <?=($d['status']=="Não procede"?"Selected":"");?>>Não procede</option>
                  <option value="Executado"   <?=($d['status']=="Executado"?"Selected":"");?>  >Executado</option>
                  <option value="Respondido"  <?=($d['status']=="Respondido"?"Selected":"");?> >Respondido</option>
                  <option value="Encaminhado" <?=($d['status']=="Encaminhado"?"Selected":"");?>>Encaminhado</option>
                  <option value="Cancelado"   <?=($d['status']=="Cancelado"?"Selected":"");?>  >Cancelado</option>
               </optgroup>
               </select>
            </div>
         </div>

         <div class='col-md-2'>
            <div class='form-group'>
               <label class='control-label' for='active'>Ativo:</label>
               <select id="active" name="active" class="form-control select2" style="width: 100%; height:100%">
                  <option value="t" <?=($d['active']=="t"||$acao=="inserir"?"Selected":"");?> >Aberto</option>
                  <option value="f" <?=($d['active']=="f"?"Selected":"");?>                   >Fechado</option>
               </select>
            </div>
         </div>

         <div class='col-md-2'>
            <div class='form-group'>
               <label class='control-label' for='priority'>Prioridade:</label>
               <select id="priority" name="priority" class="form-control select2" style="width: 100%; height:100%">
                  <option value="2-Baixo"       <?=($d['priority']=="2-Baixo"?"Selected":"");?>                    >1-Baixo</option>
                  <option value="3-Normal"      <?=($d['priority']=="3-Normal"||$acao=="inserir"?"Selected":"");?> >2-Normal</option>
                  <option value="4-Alto"        <?=($d['priority']=="4-Alto"?"Selected":"");?>                     >3-Alto</option>
               </select>
            </div>
         </div>

         <div class='col-md-3'>
            <div class='form-group'>
               <label class='control-label' for='date_added'>Data de abertura:</label>
                  <input type='datetime-local' class='form-control' id='date_added' name='date_added' placeholder='' value='<?=($acao=="atualizar"?str_replace(" ","T",substr($d['date_added'],0,16)):str_replace(" ","T",substr($agora['datatimesrv'],0,16)));?>' >
            </div>
         </div>
         <div class='col-md-3'>
            <div class='form-group'>
               <label class='control-label' for='date_added'>Data de fechamento:</label>
                  <input type='datetime-local' class='form-control' id='date_closed' name='date_closed' placeholder='' value='<?=($acao=="atualizar"?str_replace(" ","T",substr($d['date_closed'],0,16)):"");?>' >
            </div>
         </div>

      </div>

      <div class='row'>
         <div class='col-md-12 text-right'>
            <?
            if($acao == "atualizar")
            {
               echo "<i class='text-muted'>Inserido por</i> <b>{$d['user_added_name']}</b><br><small>{$d['company_acron']} - {$d['company_name']}</small>";
            }
            ?>
         </div>
      </div>

      <div class="panel-footer text-center">
         <a href='gsec/callcenter.php'><button type='button' class='btn btn-default loading'>Voltar</button></a>
         <input type="hidden" id="id_citizen"              name="id_citizen"              value="<?=$d['id_citizen']?>" >
         <input type="hidden" id="coords"                  name="coords"                  value="<?=$d['coords']?>" >
         <input type='hidden' id='acao'                    name='acao'                    value='<?=$acao;?>' >
         <input type='hidden' id='coords_formattedaddress' name='coords_formattedaddress' value='<?=$d['coords_formattedaddress'];?>' >
         <? if($acao=="atualizar"){ ?>
                  <input type="hidden" id="tabret"     name="tabret"  value="">
                  <input type='hidden' id='id'         name='id'         value='<?=$d['id'];?>' >
                  <button id="bt_atualizar" type='submit' class='btn btn-primary loading'>Atualizar</button>
         <? }else{
               if(check_perm("9_28","C")){
                  echo "<button type='submit' class='btn btn-success loading'>Inserir</button>";
               }else {
                  echo "<button type='button' class='btn btn-success disabled'>Inserir</button>";
               }
            } ?>
      </div>
      <script>
      $('.select2').select2({
        language: {
              noResults: function() {
                return 'Nenhum resultado encontrado.';
              }
            }
      });

      $('.select2SearchStreet').select2({
        language: "pt-BR",
        minimumInputLength: 3,
        cache: true,
        allowClear: true,
        placeholder: "Pesquisar logradouro",
        ajax: {
          url: 'gsec/searchstreet.php',
          dataType: 'json',
          delay: 250,
          data: function (params) { return { searchTerm: params.term }; },
          processResults: function (data, params) {
             return {
                results: data.results
            };
          }
      }
      });


      </script>
</form>

   </div>
</div>
<!---------------------------------------------------------------------->
<!---------------------------------------------------------------------->
   </div>
   <div id="fotos" class="tab-pane <?=$tab['fotos'];?>">
<!---------------------------------------------------------------------->
<!---------------------------------------------------------------------->
   <div class="row">
      <div class="col-sm-2">

         <div class="row">
            <div class="col-sm-12">
               <button type="button" class="btn btn-block btn-success loadingfoto" id="bt_foto"><i class="fa fa-camera"></i><sup><i class="fa fa-plus"></i></sup> Inserir foto</button>
               <input type="file" id="arqimg" accept = "image/*" class="hidden">
               <small class="text-muted" id="statusuploadimg"></small>
               <canvas id="canvasboxthumb" width="170px" height="128px"  style="background-color:#FFFF00;display:none"></canvas>
               <canvas id="canvasbox"      width="170px" height="128px"  style="background-color:#FFFFF0;display:none"></canvas>
            </div>
         </div>

         <div class="row">
            <div class="col-sm-12">
               <?

                  if(pg_num_rows($resF))
                  {
                     echo "<hr><h5><i>Miniaturas:</i></h5>";
                     while($arq = pg_fetch_assoc($resF)){ $arqs[] = $arq['file_path']; }
                     if(isset($arqs))
                     {
                        for($c = 0;$c<count($arqs);$c++)
                        {
                           echo "<img class='imgThumb' src='{$arqs[$c]}' width='78' height='60' />";
                        }
                     }
                  }
               ?>
            </div>
         </div>

      </div>
      <div class="col-sm-10">

         <div class="row">
            <div class="col-sm-12">
                  <div id="viewphoto" align="center">
                     <div class="alert alert-info">Você pode utilizar diretamente o celular para registrar e enviar fotos para este sistema e também o computador para arquivos recebidos de outras fontes.</div>
                        <div class="alert alert-success">
                              <b>[Dicas para um bom registro fotográfico]</b><br><br>
                              1 - Sempre utilize o celular na posição deitada.<br>
                              2 - Registre algumas imagens de forma panorâmica para que se possa fazer uma avaliação do contexto geral.<br>
                              3 - Registre outras imagens se aproximando do local do problema.<br>
                              4 - Por fim registre uma imagem bem aproximada para que se possa realizar uma avaliação detalhada.<br>
                              5 - Ao final da execução do serviço, execute este mesmo procedimento de registro fotografico para o "antes e depois".
                        </div>
                     </div>
            </div>
         </div>
         <div class="row" style="margin-top:10px">
            <div class="col-sm-12 text-center">
                  <button id="bt_remover_foto" type="button" class="btn btn-danger hidden"><i class="fa fa-trash"></i> Remover foto</button>
            </div>
         </div>
      </div>
   </div>

<!---------------------------------------------------------------------->
<!---------------------------------------------------------------------->
   </div>
   <div id="mapa" class="tab-pane <?=$tab['mapa'];?>">
<!---------------------------------------------------------------------->
<!---------------------------------------------------------------------->
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
                  zoom: 15
               });

                  pin = new Microsoft.Maps.Pushpin(map.getCenter(), {
                  title: <?=($d['type']!=""?"'{$d['type']}:{$d['request']}'":"'Localização do evento'");?>,
                  //subTitle: <?=($d['coords_formattedaddress']!=""?"'".$d['coords_formattedaddress']."'":"'Joinville - Santa Catarina'");?>,
                  text: '',
                  color: defaultColor,
                  draggable: true
               });
               map.entities.push(pin);

      /*
               Microsoft.Maps.Events.addHandler(pin, 'mouseover', function (e) { e.target.setOptions({ color: hoverColor }); highlight('pushpinMouseover');});
               Microsoft.Maps.Events.addHandler(pin, 'mousedown', function (e) { e.target.setOptions({ color: mouseDownColor }); highlight('pushpinMousedown');});
               Microsoft.Maps.Events.addHandler(pin, 'mouseout',  function (e) { e.target.setOptions({ color: defaultColor }); highlight('pushpinMouseout');});

               Microsoft.Maps.Events.addHandler(pin, 'click',   function () { highlight('pushpinClick'); });
               Microsoft.Maps.Events.addHandler(pin, 'dblclick',function () { highlight('pushpinDblclick'); });
               Microsoft.Maps.Events.addHandler(pin, 'mouseup', function () { highlight('pushpinMouseup'); });

               Microsoft.Maps.Events.addHandler(pin, 'drag',    function () { highlight('pushpinDrag'); $("#coords").val(pin.getLocation().latitude+","+pin.getLocation().longitude);  });
      */
               Microsoft.Maps.Events.addHandler(pin,'drag',function(){    $("#coords").val(pin.getLocation().latitude+","+pin.getLocation().longitude);
                                                                      $("#coords_txt").val(pin.getLocation().latitude+","+pin.getLocation().longitude);});
               Microsoft.Maps.Events.addHandler(pin,'dragend',function(){map.setView({center: new Microsoft.Maps.Location(pin.getLocation().latitude,pin.getLocation().longitude)})});

          }

      </script>
      <script type='text/javascript' src='https://www.bing.com/api/maps/mapcontrol?callback=GetMap' async defer></script>



      <div class="row">
         <div class="col-md-8">
               <div id="myMap" style='position:relative;width:100%;height:400px;'></div>
         </div>
         <div class="col-md-4">
            <div class="row" style="margin-top:10px">
               <div class="col-md-12 text-center">
                  <button type="button" class="btn btn-info btn-block loading3" id="bt_localizar">Localizar georeferencia a partir do endereço</button>
               </div>
            </div>
            <div class="row" style="margin-top:10px">
               <div class="col-md-12">
                  <div class='form-group'>
                     <input type='text' class='form-control text-center' id='coords_txt' name='coords_txt' placeholder='Coordenadas geograficas' value='<?=$d['coords'];?>' >
                  </div>
               </div>
            </div>

            <div class="row" style="margin-top:10px">
               <div class="col-md-12">
                  <small>Endereço informado na solicitação:</small><br><?=implode("",$enderecoMapa);?>
               </div>
            </div>


            <div class="row hidden" style="margin-top:10px" id="alertageo">
               <div class="col-md-12 text-center">
                     <div class="alert alert-warning"><b>Atenção: </b>Sempre verifique se o sistema encontrou a localização correta.</div>
               </div>
            </div>
            <div class="row" style="margin-top:10px">
               <div class="col-md-12 text-center">
                     <a href='gsec/callcenter.php'><button type='button' class='btn btn-default loading'>Voltar</button></a>
                     <button type='button' class='btn btn-primary loading' onclick="$('#tabret').val('mapa');$('#bt_atualizar').click();">Atualizar coordenadas</button>
               </div>
            </div>
         </div>
      </div>
<!---------------------------------------------------------------------->
<!---------------------------------------------------------------------->
   </div>
   <div id="historico" class="tab-pane <?=$tab['historico'];?>">
   <!---------------------------------------------------------------------->
   <!---------------------------------------------------------------------->
   <?
         $sql = "SELECT U.name, C.acron as company_acron, L.date_added, L.info->'status' as status
                 FROM {$schema}gsec_logs L
                 JOIN {$schema}users U ON U.id = L.id_user
                 JOIN {$schema}company C ON C.id = U.id_company
                 WHERE L.table_origin = 'gsec_callcenter' AND L.id_origin = {$_GET['id']}
                 ORDER BY L.date_added ASC";
         $res = pg_query($sql)or die("SQL Error: ".__LINE__."<br>Query: {$sql}");
         $c=1;
         if(pg_num_rows($res))
         {
            echo "<table class='table table-striped table-condensed'>";
            echo "<thead><tr>";
            echo "<th>#</th>";
            echo "<th>Data</th>";
            echo "<th>Nome</th>";
            echo "<th>Setor</th>";
            echo "<th>Ação</th>";
            echo "<th>Dados</th>";
            echo "</tr></thead>";
            echo "<tbody>";
            while($hist = pg_fetch_assoc($res))
            {
               echo "<tr>";
                  echo "<td>".$c++."</td>";
                  echo "<td>".formataData($hist['date_added'],1)."</td>";
                  echo "<td>{$hist['name']}</td>";
                  echo "<td>{$hist['company_acron']}</td>";
                  echo "<td>".json_decode($hist['status'])."</td>";
                  echo "<td><i class='fa fa-search'></i></td>";
               echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
         }else{
            echo "<div class='alert alert-info'>Nenhuma informação de histórico registrado para este atendimento.</div>";
         }

   ?>
   <!---------------------------------------------------------------------->
   <!---------------------------------------------------------------------->
   </div>
</div>
</div>


                     </div>
                  </div>
               </div>


      </section>
</section>


<div class="modal fade"  id="modalFiltroTitularidade" z-index="-1" role="dialog" aria-labelledby="modalFiltroTitularidade" aria-hidden="true">
  <div class="modal-dialog" role="document">
   <div class="modal-content">
      <div class="modal-header">
       <h5 class="modal-title" id="exampleModalLabel">Filtros de pesquisa:</h5>
       <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-20px">
          <span aria-hidden="true">&times;</span>
       </button>
      </div>
      <form id="form_filtro_troca_titularidade" action="gsec/change_owner.php" method="post">
      <div class="modal-body">
         <select class="form-control" id="id_new_citizen" name="id_new_citizen">
         </select>
         <input type='hidden' name="id_callcenter_change_new_citizen" id="id_callcenter_change_new_citizen" value='<?=$_GET['id'];?>' />
      </div>


      <div class="modal-footer">
       <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
       <button type="button" class="btn btn-danger disabled" id="bt_submit_modal">Trocar Titularidade</button>
      </div>
      </form>
      <script>
      $('#id_new_citizen').change(function(){
         $('#bt_submit_modal').removeClass('disabled');
      })
      $("#bt_submit_modal").click(function(){
          $('#modalFiltroTitularidade').modal('hide');
          $('body').removeClass('modal-open');
          $('.modal-backdrop').remove();
          $("#form_filtro_troca_titularidade").submit();
      });
      </script>
   </div>
  </div>
</div>




<script>



//$('#id_new_citizen').select2({ dropdownParent: $('#modalFiltroTitularidade')});



   $("#bt_foto").click(function(){ $("#arqimg").click(); });

   var maxWidth  = 800;
   var maxHeight = 600;
   let imgInput  = document.getElementById('arqimg');

   imgInput.addEventListener('change', function(e) {
    if(e.target.files)
    {
      let imageFile = e.target.files[0]; //here we get the image file
      var reader    = new FileReader();
      reader.readAsDataURL(imageFile);

      reader.onloadend = function (e)
      {
         var myImage      = new Image();     // Creates image object
         myImage.src      = e.target.result; // Assigns converted image to image object
         var width        = myImage.width;
         var height       = myImage.height;

         if(width == 0 || height == 0){width = 800; height = 600;  }

         var shouldResize = (width > maxWidth) || (height > maxHeight);
         var newWidth;
         var newHeight;

         if(!shouldResize){ newWidth = width; newHeight = height; }

         if (width > height) {
             newHeight = height * (maxWidth / width);
             newWidth  = maxWidth;
         } else {
             newWidth  = width * (maxHeight / height);
             newHeight = maxHeight;
         }

         myImage.onload = function(ev)
         {
            var myCanvasThumb  = document.getElementById("canvasboxthumb"); // Creates a canvas object
            var myContextThumb = myCanvasThumb.getContext("2d"); // Creates a contect object

           var myCanvas  = document.getElementById("canvasbox"); // Creates a canvas object
           var myContext = myCanvas.getContext("2d"); // Creates a contect object

           myCanvas.width  = newWidth;
           myCanvas.height = newHeight;
           //myCanvas.width  = 400;
           //myCanvas.height = 400;
           //myCanvas.width = myImage.width; // Assigns image's width to canvas
           //myCanvas.height = myImage.height; // Assigns image's height to canvas
           myContext.drawImage(myImage,0,0,newWidth, newHeight); // Draws the image on canvas
           myContextThumb.drawImage(myImage,0,0,170,128); // Draws the image on canvas
           //myContext.drawImage(myImage,0,0,200,200); // Draws the image on canvas

           let imgData = myCanvas.toDataURL("image/jpeg",0.75); // Assigns image base64 string in jpeg format to a variable

           sendFile(imgData);
           //console.log(myCanvas);

         }
      }
   }
   });

   function sendFile(fileData) {

      var formData = new FormData();
      formData.append('img', fileData);
      formData.append('id', <?=$_GET['id'];?>);

      $.ajax({
         type: 'POST',
         url: 'gsec/fotos_upload.php',
         dataType: 'json',
         data: formData,
         contentType: false,
         processData: false,
         success: function (data){
             if (data.success) {
                $("#statusuploadimg").html('Foto enviada com sucesso.<br>'+data.status);
                $('#wrap').load("gsec/callcenter_FORM.php?id=<?=$_GET['id'];?>&tab=fotos");
             }else{
                $("#statusuploadimg").html('Houve um erro no envio da foto.<br>'+data.status);
             }
         },
         error: function (data) {
             $("#statusuploadimg").html('Houve um grave.<br>'+data.status);
             $("#bt_foto").removeClass("disabled");
             $("#bt_foto").html("<i class='fa fa-camera'></i><sup><i class='fa fa-plus'></i></sup> Inserir foto");
         }
      });


   }
</script>

<script>

$(document).ready( function () {

$('#id_new_citizen').select2({
 language: "pt-BR",
 minimumInputLength: 3,
 cache: true,
 allowClear: true,
 placeholder: "Pesquise pelo nome do cidadão ou da empresa.",
 ajax: {
    url: '../gsec/search_citizen.php',
    dataType: 'json',
    delay: 250,
    data: function (params) { return { searchTerm: params.term }; },
    processResults: function (data, params) {
      return {
          results: data.results
      };
    }
}
});

var imgsrc;

$(".imgThumb").click(function(){
      imgsrc  = $(this).attr("src");
      $("#viewphoto").html("<img src='"+imgsrc+"' class='img-responsive fullphoto'/>");
      $("#bt_remover_foto").removeClass("hidden");
});

$("#bt_remover_foto").click(function(){
   $("#wrap").load("gsec/fotos_remove.php?id=<?=$_GET['id']?>&arq="+imgsrc);
});


$("#status").change(function(){
    var label=$('#status :selected').parent().attr('label');
    if(label=="Aberto"){ $("#active").val("t").change(); }
    else{                $("#active").val("f").change(); }
});

   $("#bt_print").click(function(){
   	var vw = window.open('gsec/impressao_atendimento.php?id=<?=$_GET['id'];?>',
   									     'popup',
   								 	     'width=800, height=600, top=10, left=10, scrollbars=no,location=no,status=no');
   });



$("#bt_localizar").click(function(){

   (function() {

     var endereco = $( "#id_address option:selected" ).text();
     if($("#address_num").val() != ""){ endereco += ", "+$("#address_num").val(); }

     var url = "https://dev.virtualearth.net/REST/v1/Locations/BR/"+endereco+"%20Joinville?o=json&key=Ag2oAO30HR3VWnlUOEllUDh6Va6GBmboNrDqG1KZ5fJAt4105Zgnr1uQUqa6DhzX";

     $.getJSON(url,{format: "json"}).done(function(data){

        $("#bt_localizar").html("Localizar georeferencia a partir do endereço").removeClass("disabled");

         var name   = data.resourceSets[0].resources[0].name;
         var lat    = data.resourceSets[0].resources[0].point.coordinates[0];
         var long   = data.resourceSets[0].resources[0].point.coordinates[1];
         var cidade = data.resourceSets[0].resources[0].address.locality;
         var FormattedAddress = data.resourceSets[0].resources[0].address.formattedAddress;

         var tipo=$('#id_subject :selected').parent().attr('label');
         var solicitacao = $("#id_subject option:selected").text();
         if(solicitacao == "- - -"){ solicitacao = "Lolização da solicitação";}
         else                      { solicitacao = tipo+":"+solicitacao;      }

         if(cidade == "Joinville")
         {
               $("#coords").val(lat+","+long);
               $("#coords_txt").val(lat+","+long);
               $("#coords_formattedaddress").val(FormattedAddress);
               map.entities.remove(pin);
               map.setView({ center: new Microsoft.Maps.Location(lat,long)});

               pin = new Microsoft.Maps.Pushpin({"latitude": lat,"longitude": long,"altitude": 0,"altitudeReference": -1},
               {
                  title: solicitacao,
                  subTitle: FormattedAddress,
                  text: '',
                  color: defaultColor,
                  draggable: true
               });
               map.entities.push(pin);
               Microsoft.Maps.Events.addHandler(pin,'drag',function(){    $("#coords").val(pin.getLocation().latitude+","+pin.getLocation().longitude);
                                                                      $("#coords_txt").val(pin.getLocation().latitude+","+pin.getLocation().longitude);});
               Microsoft.Maps.Events.addHandler(pin,'dragend',function(){map.setView({center: new Microsoft.Maps.Location(pin.getLocation().latitude,pin.getLocation().longitude)})});
               $("#alertageo").fadeIn('slow').removeClass("hidden");
         }else {
              $("#bt_localizar").html("Nenhuma georefêrencia encontrada, arraste o PIN manualmente").removeClass("btn-info").addClass("disabled").addClass("btn-danger");
         }

     })
   })();

      //$.get(url, function(resultado){ $("#geocode").val(resultado); console.log(resultado.Location) });
});


    $('#tabela').DataTable({
      responsive: true,
      language: {
        processing:     "Pesquisando...",
        search:         "Pesquisar:",
        lengthMenu:     "_MENU_ &nbsp;Registros por página.",
        info:           "Mostrando _START_ a _END_ de um total de  _TOTAL_ registros.",
        infoEmpty:      "0 registros encontrado.",
        infoFiltered:   "(_MAX_ registros pesquisados)",
        infoPostFix:    "",
        loadingRecords: "Carregando registros...",
        zeroRecords:    "Nenhum registro encontrado com essa característica.",
        emptyTable:     "Nenhuma informação nesta tabela de dados.",
        paginate: {
            first:      "Primeiro",
            previous:   "Anterior",
            next:       "Próximo",
            last:       "Último"
        },
        aria: {
            sortAscending:  ": Ordem ascendente.",
            sortDescending: ": Ordem decrescente."
        }
      }
    });

});

$('#cpf').mask('000.000.000-00', {reverse: true});
$("#phone1").mask("(00) 00000-0000");
$("#phone2").mask("(00) 0 0000-0000");
$("#phone3").mask("(00) 0 0000-0000");


$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> Aguarde");});
$(".loading3").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> Aguarde, pesquisando localização").addClass("disabled");});
$(".loadingfoto").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> Enviando...").addClass("disabled");});
</script>
<?
function humanTiming($data)
{

    $time = strtotime($data);
    $time = time() - $time;
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'ano',
        2592000 => 'mes',
        604800 => 'semana',
        86400 => 'dia',
        3600 => 'hora',
        60 => 'min',
        1 => 'seg'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        if($text=="mes" && $numberOfUnits>1){ $ext = "es"; }else{ $ext = "s"; }
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?$ext:'');
    }

}
?>
