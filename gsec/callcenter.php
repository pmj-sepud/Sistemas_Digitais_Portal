<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora = now();

  logger("Acesso","GSEC - CALLCENTER", "Callcenter - Visualização geral");

     if($_GET['callbackFilterReset']=="true"){unset($_SESSION['callbackFilters']);}

     if($_GET['filtro_ativo']!="")
     {
        $_SESSION['callbackFilters']['filtro_ativo'] = $_GET['filtro_ativo'];
        $filtroativo  = $_GET['filtro_ativo'];
     }else{
        if(isset($_SESSION['callbackFilters']['filtro_ativo'])){ $filtroativo = $_SESSION['callbackFilters']['filtro_ativo']; }
                                                           else{ $filtroativo = 't'; }
     }

     if(isset($_POST) && count($_POST))
     {
         if($_POST['id_company']=='todos')
         {
            $id_company   = "SELECT id FROM {$schema}company WHERE id_father = '{$_SESSION['id_company_father']}'";
            $name_company = $_POST['name_company'];
         }else{
            $id_company   = $_POST['id_company'];
            $name_company = $_POST['name_company'];
         }
         $_SESSION['callbackFilters']['id_company'] = $id_company;
         $_SESSION['callbackFilters']['name_company'] = $name_company;
     }else{
        $id_company   = ($_SESSION['callbackFilters']['id_company']  !="" ?$_SESSION['callbackFilters']['id_company']  :$_SESSION['id_company']);
        $name_company = ($_SESSION['callbackFilters']['name_company']!="" ?$_SESSION['callbackFilters']['name_company']:$_SESSION['company_name']);
     }

  $sql = "SELECT
  		      (SELECT count(*) FROM {$schema}gsec_files F WHERE F.id_callcenter = C.id) AS qtd_foto,
            C.id, C.status, C.date_added, C.date_closed, C.coords, C.origin_type,
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
            WHERE C.id_company in ({$id_company})
              AND C.active =  '{$filtroativo}'
            ORDER BY C.date_added DESC";

  $res = pg_query($sql)or die("<div class='text-center'>SQL error ".__LINE__."<br>SQL: ".$sql."</div>");
?>

<style>
.link:hover{ cursor: pointer; }
.dataTables_filter { width: 50%; float: right; text-align: right; }
.dataTables_wrapper .dt-buttons { float:right; }
</style>

<section role="main" class="content-body">
  <header class="page-header">
    <h2>Central de atendimentos</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='#'>Central de atendimentos</span></li>
      </ol>
    </div>
  </header>



								<section class="panel box_shadow">
  									<header class="panel-heading" style="height:70px">
                              <?
                                 if($filtroativo=="f"){ echo "<h4 style='margin-top:0px'><b>{$name_company}</b><br><small class='text-warning'><i>Atendimentos fechados</i></small></h4>";    }
                                                  else{ echo "<h4 style='margin-top:0px'><b>{$name_company}</b><br><small class='text-success'><i>Atendimentos abertos</i></small></h4>";   }
                              ?>
                               <div class="panel-actions" style="margin-top:5px">
                                 <?
                                          //echo "<a href='gsec/callcenter_FORM.php'><button type='button' class='btn btn-success'><i class='fa fa-laptop'></i><sup><i class='fa fa-plus'></i></sup> Novo atendimento</button></a>";
                                          echo " <a href='gsec/citizen.php'><button type='button' class='btn btn-primary'><i class='fa fa-user'></i><sup><i class='fa fa-search'></i></sup> Cidadão</button></a>";
                                          if($filtroativo=="f"){  echo " <a href='gsec/callcenter.php?filtro_ativo=t'><button type='button' class='btn btn-success'><i class='fa fa-folder-open-o'></i> Visualizar abertos</button></a>"; }
                                                           else{  echo " <a href='gsec/callcenter.php?filtro_ativo=f'><button type='button' class='btn btn-warning'><i class='fa fa-folder-o'></i> Visualizar fechados</button></a>"; }
                                          echo " <button type='button' class='btn btn-info' data-toggle='modal' data-target='#modalFiltro'><i class='fa fa-search'></i></button>";
                                 ?>
                               </div>
                           </header>

  									<div class="panel-body">
                              <div class="row">
                                 <div class="col-md-12">
                                    <?
                                          if(pg_num_rows($res))
                                          {
                                            echo "<div class='table-responsive'>";
                                            echo "<table class='table table-condensed table-hover' id='tabelainfos'>";
                                            echo "<thead><tr>
                                                    <th class='text-muted'><small><i>#</small></i></th>
                                                    <th class='text-muted'><small><i>Requisitante</i></small></th>
                                                    <th class='text-muted'><small><i>Tipo</i></small></th>
                                                    <th class='text-muted'><small><i>Solicitação</i></small></th>
                                                    <th class='text-muted'><small><i>Endereço</i></small></th>
                                                    <th class='text-muted text-center'><small><i>Bairro</i></small></th>
                                                    <th class='text-muted'><small><i>Status</i></small></th>
                                                    <!--<th class='text-muted'><small><i>Setor</i></small></th>-->";


                                                    if($filtroativo=='f') //Fechados
                                                    {
                                                      echo "<th class='text-muted'><small><i>Abertura</i></small></th>";
                                                      echo "<th class='text-muted'><small><i>Fechamento</i></small></th>";
                                                    }else{
                                                      echo "<th class='text-muted'><small><i>Abertura</i></small></th>";
                                                    }



                                                    echo "<th class='text-muted text-center'><small><i>Coords</i></small></th>
                                                    <th class='text-muted text-center'><i class='fa fa-camera'></i></th>
                                                  </thead></tr>";
                                            echo "<tbody>";
                                            while($d = pg_fetch_assoc($res))
                                            {
                                               unset($personaldata, $complemento, $numprotocolo);


                                                $aux = substr(str_replace("-","",$d['date_added']),0,6);
                                                $numprotocolo = $aux."<br><b>".str_pad($d['id'],4,"0",STR_PAD_LEFT)."</b>";

                                                 if($d['rg'] !="") { $personaldata[]="<sup class='text-muted'>RG:</sup>".str_replace(".","",str_replace("-","",$d['rg'])); }
                                                if($d['cpf'] !="") { $personaldata[]="<sup class='text-muted'>CPF:</sup>".str_replace(".","",str_replace("-","",$d['cpf'])); }
                                                if($d['cnpj'] !=""){ $personaldata[]="<sup class='text-muted'>CNPJ:</sup>".str_replace(".","",str_replace("/","",$d['cnpj'])); }
                                                if(isset($personaldata)){ $personaldata =  "{$d['citizen']}<br>\n<small class='text-muted'><i>".implode(", ", $personaldata)."</i></small>"; }
                                                                   else{ $personaldata = $d['citizen']; }

                                                      if($d['address_num']!= ""){ $d['street']  .= ", ".$d['address_num'];  }
                                                if($d['address_complement']!=""){ $complemento[] = $d['address_complement'];}
                                                if($d['address_reference']!="") { $complemento[] = $d['address_reference']; }
                                                if(isset($complemento)){ $complemento = "<br>\n<small class='text-muted'><i>".implode(", ", $complemento)."</i></small>"; }

                                               echo "<tr class='link' id='{$d['id']}'>";
                                                   //echo "<td width='5px'><small class='text-muted'>{$d['id']}</small></td>";
                                                   echo "<td class='text-center' width='5px'><small class='text-muted'>{$numprotocolo}</small></td>";
                                                   echo "<td>{$personaldata}</td>";
                                                   echo "<td nowrap><small class='text-muted'>Tipo:</small><br><b>{$d['origin_type']}</b></td>";
                                                   echo "<td><small class='text-muted'>{$d['type']}</small><br><b>{$d['request']}</b></td>";
                                                   echo "<td>{$d['street']}{$complemento}</td>";
                                                   echo "<td nowrap class='text-center'>".($d['neighborhood']!=""?$d['neighborhood']:"<i class='fa fa-exclamation-triangle text-danger'></i>")."</td>";
                                                   echo "<td nowrap>{$d['status']}</td>";
                                                   //echo "<td>{$d['company_acron']}</td>";
                                                   echo "<td nowrap>".substr(formataData($d['date_added'],1),0,-3)."<br><small class='text-muted'><i>".humanTiming($d['date_added'])." atrás</i></small></td>";

                                                   if($filtroativo=='f') //Fechados
                                                   {
                                                      echo "<td nowrap>".substr(formataData($d['date_closed'],1),0,-3)."<br><small class='text-muted'><i>".humanTiming($d['date_closed'])." atrás</i></small></td>";
                                                   }
                                                   echo "<td class='text-center'>".($d['coords']!=""?"<i class='fa fa-check text-success'></i>":"<i class='fa fa-exclamation-triangle text-danger'></i>")."</td>";
                                                   echo "<td class='text-center'>".($d['qtd_foto']==0?"<span class='text-muted'>-</span>":"<span class='badge bg-info'>{$d['qtd_foto']}</span>")."</td>";
                                                   //echo "<td class='text-center'>".($d['qtd_foto']==0?"<span class='text-muted'>-</span>":"{$d['qtd_foto']}")."</td>";
                                               echo "</tr>";
                                            }
                                            echo "</tbody>";
                                            echo "</table>";
                                            echo "</div>";
                                          }else{
                                              echo "<div class='alert alert-warning text-center'>Nenhum atendimento cadastrado no sistema.</div>";
                                          }
                                    ?>
                                 </div>
                              </div>
                           </div>
                        </section>
</section>
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
      <form id="form_filtro" action="gsec/callcenter.php" method="post">
      <div class="modal-body">
         <select class="form-control select2" id="id_company" name="id_company">
            <?
               if(check_perm("9_31")){
                echo "<option value='todos'>TODOS OS ATENDIMENTOS</option>";
                echo "<optgroup label='Setores'>";
                 $sql = "SELECT id, name, acron, id_father
                         FROM {$schema}company
                         WHERE active = 't' AND id_father = '{$_SESSION['id_company_father']}'
                         ORDER BY name ASC";
                 $res = pg_query($sql)or die();
                 while($setores = pg_fetch_assoc($res)){
                    if($setores['id']==$_SESSION['id_company']){ $sel = "selected"; }else{ $sel=""; }
                    echo "<option value='{$setores['id']}' {$sel}>{$setores['name']}</option>";
                 }
                 echo "</optgroup>";
              }else{
                 echo "<option value='{$_SESSION['id_company']}'>{$_SESSION['company_name']}</option>";
              }
            ?>
         </select>
         <input type="hidden" name="name_company" id="name_company" value='' />
      </div>
      <div class="modal-footer">
       <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
       <button type="button" class="btn btn-primary" id="bt_submit">Filtrar</button>
      </div>
      </form>
   </div>
  </div>
</div>
<script>

$("#bt_submit").click(function(){
    $('#modalFiltro').modal('hide');
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
    $("#name_company").val($("#id_company option:selected").text());
    $("#form_filtro").submit();
});

$(".link").click(function(){
   $('#wrap').load("gsec/callcenter_FORM.php?id="+$(this).attr("id"));
})

$(document).ready( function () {
   var table = $('#tabelainfos').DataTable({
      mark: true,
      responsive: true,
      stateSave: true,
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
    "order": [[0,"desc"]],
    dom: 'Bflrtip',
    buttons: [
         { extend: 'copyHtml5', text: 'Copiar'},
         'excelHtml5',
         'csvHtml5',
         { extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'A3'}
     ]
    });

    $('.dataTables_filter input').click(function () { $(this).val('');});

});




$('.select2').select2({ dropdownParent: $('#modalFiltro')});
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
