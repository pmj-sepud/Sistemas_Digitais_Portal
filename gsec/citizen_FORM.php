<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora = now();

  if($_GET['id']!="")
  {
      $acao = "atualizar";
      $sql  = "SELECT
                  	C.*,
                  	CO.name as company_name, CO.acron as company_acron,
                  	U.name  as user_added_name
                  FROM {$schema}gsec_citizen C
                  LEFT JOIN {$schema}company CO  ON CO.id = C.id_company
                  LEFT JOIN {$schema}users U     ON U.id  = C.id_user_added
                  WHERE C.id = '{$_GET['id']}'";
      $res  = pg_query($sql)or die("<div class='text-center text-danger'>Error: ".__LINE__."<br>SQL {$sql}</div>");
      $d    = pg_fetch_assoc($res);



      $sqlR = "SELECT
                C.id, C.status, C.date_added, C.coords,
                T.type, T.request,
                CO.name AS company_name, CO.acron as company_acron,
                S.name as street, C.address_num, C.address_complement, C.address_reference,
                N.neighborhood,
                CI.name as citizen, CI.rg, CI.cpf, CI.cnpj, CI.email, CI.phone1
                     FROM {$schema}gsec_callcenter C
                LEFT JOIN {$schema}gsec_citizen CI ON CI.id = C.id_citizen
                LEFT JOIN {$schema}streets S ON S.id = C.id_address
                LEFT JOIN {$schema}neighborhood N ON N.id = C.id_neighborhood
                LEFT JOIN {$schema}company CO ON CO.id = C.id_company
                LEFT JOIN {$schema}gsec_request_type T ON T.id = id_subject
                WHERE C.id_citizen = '{$d['id']}'
                ORDER BY C.date_added DESC";
      $rSol = pg_query($sqlR)or die("<div class='text-center'>SQL error ".__LINE__."<br>SQL: ".$sqlR."</div>");
      $qtdSolicitacoes = pg_num_rows($rSol);



      logger("Acesso","GSEC - CALLCENTER", "Cadastro de cidadão - Visualização detalhada ID: {$_GET['id']}, {$d['name']}");
  }else{
      $acao = "inserir";
      logger("Acesso","GSEC - CALLCENTER", "Cadastro de cidadão - Novo cadastro");
  }

?>

<style>.link:hover{ cursor: pointer; }</style>
<section role="main" class="content-body">
      <header class="page-header">
       <h2>Cadastro do cidadão</h2>
       <div class="right-wrapper pull-right" style='margin-right:15px;'>
         <ol class="breadcrumbs">
           <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
           <li><span class='gsec/callcenter_index.php'>Central de atendimentos</span></li>
           <li><span class='#'>Cidadão</span></li>
           <li><span class='#'>Cadastro</span></li>
         </ol>
       </div>
      </header>


      <section class="panel box_shadow">

               <header class="panel-heading " style="height:70px">
                  <div class="panel-actions" style="margin-top:5px">
                  <?
                     if($acao=="atualizar"){
                        if(check_perm("9_28","C")){
                           echo "<a href='gsec/callcenter_FORM.php?id_user_request={$_GET['id']}'><button type='button' class='btn btn-success'><i class='fa fa-laptop'></i><sup><i class='fa fa-plus'></i></sup> Novo atendimento</button></a>";
                        }else {
                           echo "<button type='button' class='btn btn-success disabled'><i class='fa fa-laptop'></i><sup><i class='fa fa-plus'></i></sup> Novo atendimento</button>";
                        }
                        //echo " <a href='gsec/citizen.php'><button type='button' class='btn btn-primary'><i class='fa fa-user-plus'></i> Novo cidadão</button></a>";
                     }
                  ?>
                  </div>
               </header>




               <div class="panel-body">

<div class="row">
						<div class="col-md-12">
							<div class="tabs">
								<ul class="nav nav-tabs">
									<li class="active">
										<a href="#cadastro" data-toggle="tab" ajax="false"><i class="fa fa-user"></i> Dados Cadastrais</a>
									</li>
                           <? if($acao=="atualizar"){ ?>
									<li>
										<a href="#solicitacoes" data-toggle="tab" ajax="false"><i class="fa fa-file-text-o"></i><?=($qtdSolicitacoes!=0?" <sup class='text-success'><b>({$qtdSolicitacoes})</b></sup>":"");?> Solicitações</a>
									</li>
                           <? } ?>
								</ul>
								<div class="tab-content">
									<div id="cadastro" class="tab-pane active">
<!------------------------------------------------------------------------------------------------------------>
<!------------------------------------------------------------------------------------------------------------>
<form action="gsec/citizen_FORM_sql.php" method="post">
<div class='row'>
   <div class='col-md-6'>
      <div class='form-group'>
         <label class='control-label' for='name'>Nome completo:</label>
         <input type='text' class='form-control' id='name' name='name' placeholder='Nome completo' value='<?=$d['name'];?>' required>
      </div>
   </div>

   <div class='col-md-3'>
         <div class='form-group'>
            <label class='control-label' for='phone1'>Telefone <b>CELULAR</b>:</label>
            <input type='text' class='form-control' id='phone1' name='phone1' placeholder='(DDD) Telefone celular' value='<?=$d['phone1'];?>'>
         </div>
      </div>

      <div class='col-md-3'>
            <div class='form-group'>
               <label class='control-label' for='phone1'>Telefone <b>FIXO</b>:</label>
               <input type='text' class='form-control' id='phone4' name='phone4' placeholder='(DDD) Telefone fixo' value='<?=$d['phone4'];?>'>
            </div>
         </div>
</div>

<div class='row'>

<div class='col-md-6'>
   <div class='form-group'>
      <label class='control-label' for='email'>E-mail:</label>
      <input type='text' class='form-control' id='email' name='email' placeholder='E-mail' value='<?=$d['email'];?>' >
   </div>
</div>

   <div class='col-md-3'>
      <div class='form-group'>
         <label class='control-label' for='phone2'>Telefone contato 1:</label>
         <input type='text' class='form-control' id='phone2' name='phone2' placeholder='Telefone de contato 2' value='<?=$d['phone2'];?>' >
      </div>
   </div>

   <div class='col-md-3'>
      <div class='form-group'>
         <label class='control-label' for='phone3'>Telefone contato 2:</label>
         <input type='text' class='form-control' id='phone3' name='phone3' placeholder='Telefone de contato 3' value='<?=$d['phone3'];?>' >
      </div>
   </div>
</div>

<div class='row'>
   <div class='col-md-4'>
      <div class='form-group'>
         <label class='control-label' for='cpf'>CPF:</label>
         <input type='text' class='form-control' id='cpf' name='cpf' placeholder='CPF' value='<?=$d['cpf'];?>' >
      </div>
   </div>

   <div class='col-md-4'>
      <div class='form-group'>
         <label class='control-label' for='rg'>RG:</label>
         <input type='text' class='form-control' id='rg' name='rg' placeholder='RG' value='<?=$d['rg'];?>' >
      </div>
   </div>

   <div class='col-md-4'>
      <div class='form-group'>
         <label class='control-label' for='cnpj'>CNPJ:</label>
         <input type='text' class='form-control' id='cnpj' name='cnpj' placeholder='CNPJ' value='<?=$d['cnpj'];?>' >
      </div>
   </div>
</div>

<div class='row'>
   <div class='col-md-4'>
      <div class='form-group'>
         <label class='control-label' for='id_residence_address'>Endereço Residêncial:</label>
         <select id="id_residence_address" name="id_residence_address" class="form-control select2" style="width: 100%; height:100%">
          <option value="">- - -</option>
          <?
             $sql = "SELECT * FROM ".$schema."streets ORDER BY name ASC";
             $res = pg_query($sql)or die();
             while($s = pg_fetch_assoc($res))
             {
              if($d["id_residence_address"] == $s["id"]){ $sel = "selected";}else{$sel="";}
              echo "<option value='".$s['id']."' ".$sel.">".$s['name']."</option>";
             }
          ?>
         </select>
      </div>
   </div>

   <div class='col-md-2'>
      <div class='form-group'>
         <label class='control-label' for='num_residence_address'>Num.:</label>
         <input type='number' class='form-control' id='num_residence_address' name='num_residence_address' placeholder='Número' value='<?=$d['num_residence_address'];?>' >
      </div>
   </div>

   <div class='col-md-4'>
      <div class='form-group'>
         <label class='control-label' for='complement_residence_address'>Complemento:</label>
         <input type='text' class='form-control' id='complement_residence_address' name='complement_residence_address' placeholder='Complemento' value='<?=$d['complement_residence_address'];?>' >
      </div>
   </div>


   <div class='col-md-2'>
      <div class='form-group'>
         <label class='control-label' for='id_neighborhood'>Bairro:</label>
         <select id="id_neighborhood" name="id_neighborhood" class="form-control select2" style="width: 100%; height:100%">
          <option value="">- - -</option>
          <?
             $sql = "SELECT * FROM ".$schema."neighborhood ORDER BY neighborhood ASC";
             $res = pg_query($sql)or die();
             while($s = pg_fetch_assoc($res))
             {
              if($d["id_neighborhood"] == $s["id"]){ $sel = "selected";}else{$sel="";}
              echo "<option value='".$s['id']."' ".$sel.">".$s['neighborhood']."</option>";
             }
          ?>
         </select>
      </div>
   </div>
</div>

<div class='row'>
   <div class='col-md-6'>
      <div class='form-group'>
         <label class='control-label' for='observations'>Observações:</label>
         <textarea class='form-control' id='observations' name='observations' rows='4' placeholder="Observações"><?=$d['observations'];?></textarea>
      </div>
   </div>
   <div class='col-md-6 small text-right text-muted'>
      <hr>
      <?
            if($acao=="atualizar"){
               echo "Inserido em ".formataData($d['date_added'],1);
               echo "<br>Por {$d['user_added_name']}<br>Setor: {$d['company_name']}";
            }
      ?>
   </div>
</div>

<div class="row text-center" style="margin-top:20px">
   <div class="col-md-12">
         <input type='hidden' id='acao' name='acao' value='<?=$acao;?>' >
         <a href='gsec/citizen.php'><button type='button' class='btn btn-default loading'>Voltar</button></a>
         <?
            if($acao=="atualizar"){
               if(check_perm("9_28","D")){ echo "<a href='gsec/citizen_FORM_sql.php?acao=remover&id={$d['id']}'><button type='button' class='btn btn-danger loading' onclick='this.disabled=true;'><i class='fa fa-trash'></i> Remover</button></a>"; }
               echo "<input type='hidden' id='id'         name='id'         value='{$d['id']}' >";
               echo "<input type='hidden' id='date_added' name='date_added' value='{$d['date_added']}'>";
               echo " <button type='submit' class='btn btn-primary loading'>Atualizar</button>";
            }else{
               echo " <button type='submit' class='btn btn-success loading'>Inserir</button>";
               echo "<input type='hidden' id='date_added' name='date_added' value='{$agora['datatimesrv']}'>";
            }
         ?>
   </div>
</div>

<? if($_SESSION['error']!=""){ ?>
<div class="row text-center" style="margin-top:20px">
   <div class="col-md-12">
      <?
         echo "<div class='alert alert-danger'>".$_SESSION['error']."</div>";
         unset($_SESSION['error']);
      ?>
   </div>
</div>
<? } ?>
</form>
<!------------------------------------------------------------------------------------------------------------>
<!------------------------------------------------------------------------------------------------------------>
									</div>
<? if($acao=="atualizar"){ ?>
									<div id="solicitacoes" class="tab-pane">
<!------------------------------------------------------------------------------------------------------------>
<!------------------------------------------------------------------------------------------------------------>
<?
      if(pg_num_rows($rSol))
      {
       echo "<div class='table-responsive'>";
       echo "<table class='table table-condensed table-hover' id='tabelainfos'>";
       echo "<thead><tr>
               <th class='text-muted'><small><i>#</small></i></th>
               <th class='text-muted'><small><i>Solicitação</i></small></th>
               <th class='text-muted'><small><i>Endereço</i></small></th>
               <th class='text-muted text-center'><small><i>Bairro</i></small></th>
               <th class='text-muted'><small><i>Status</i></small></th>
               <th class='text-muted'><small><i>Setor</i></small></th>
               <th class='text-muted'><small><i>Data</i></small></th>
               <th class='text-muted text-center'><small><i>Coords</i></small></th>
              </thead></tr>";
       echo "<tbody>";
       while($sol = pg_fetch_assoc($rSol))
       {
          unset($personaldata, $complemento, $numprotocolo);


            $aux = substr(str_replace("-","",$sol['date_added']),0,6);
            $numprotocolo = $aux.".".str_pad($sol['id'],4,"0",STR_PAD_LEFT);


                  if($sol['address_num']!= ""){ $sol['street']  .= ", ".$sol['address_num'];  }
            if($sol['address_complement']!=""){ $complemento[] = $sol['address_complement'];}
            if($sol['address_reference']!="") { $complemento[] = $sol['address_reference']; }
            if(isset($complemento)){ $complemento = "<br><small class='text-muted'><i>".implode(", ", $complemento)."</i></small>"; }

          echo "<tr class='link' id='{$sol['id']}'>";
               //echo "<td width='5px'><small class='text-muted'>{$sol['id']}</small></td>";
               echo "<td width='5px'><small class='text-muted'>{$numprotocolo}</small></td>";
               echo "<td><small class='text-muted'>{$sol['type']}</small><br><b>{$sol['request']}</b></td>";
               echo "<td>{$sol['street']}{$complemento}</td>";
               echo "<td class='text-center'>".($sol['neighborhood']!=""?$sol['neighborhood']:"<i class='fa fa-exclamation-triangle text-danger'></i>")."</td>";
               echo "<td>{$sol['status']}</td>";
               echo "<td>{$sol['company_acron']}</td>";
               echo "<td>".formataData($sol['date_added'],1)."<br><small class='text-muted'><i>".humanTiming($sol['date_added'])." atrás</i></small></td>";
               echo "<td class='text-center'>".($sol['coords']!=""?"<i class='fa fa-check text-success'></i>":"<i class='fa fa-exclamation-triangle text-danger'></i>")."</td>";
          echo "</tr>";
       }
       echo "</tbody>";
       echo "</table>";
       echo "</div>";
      }else{
          echo "<div class='alert alert-warning text-center'>Nenhum atendimento cadastrado no sistema.</div>";
      }
?>
<!------------------------------------------------------------------------------------------------------------>
<!------------------------------------------------------------------------------------------------------------>
									</div>
<? } ?>
								</div>
							</div>
						</div>
</div>



               </div>


<!--
               <div class="panel-footer"  style="margin-top:20px;height:60px;margin-bottom:10px;">
                  <div class="row">
                        <div class="col-md-12">

                            <a href='gsec/citizen.php'><button type='button' class='btn btn-default loading'>Voltar</button></a>

                       </div>
                  </div>
               </div>

-->


      </section>
</section>


<script>
$(".link").click(function(){
   $('#wrap').load("gsec/callcenter_FORM.php?id="+$(this).attr("id"));
})
$(document).ready( function () {
    $('#tabelainfos').DataTable({
      mark: true,
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
    },
    "order": [[ 0, "desc" ]]
    });
});



$('#cpf').mask('000.000.000-00', {reverse: true});
//$("#rg").mask('99.999.99-[9|S]');


$("#phone1").mask("(00) 0 0000-0000");
$("#phone4").mask("(00) 0000-0000");




$('.select2').select2();
$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> Aguarde");});
$(".loading3").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> Aguarde").addClass("disabled");});
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
