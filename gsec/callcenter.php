<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora = now();

  logger("Acesso","GSEC - CALLCENTER", "Callcenter - Visualização geral");

  $filtroativo = ($_GET['filtro_ativo']=="f"?"f":"t");

  $sql = "SELECT
  		      (SELECT count(*) FROM {$schema}gsec_files F WHERE F.id_callcenter = C.id) AS qtd_foto,
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
            WHERE C.id_company = '{$_SESSION['id_company']}'
              AND C.active =  '{$filtroativo}'
            ORDER BY C.date_added DESC";

  $res = pg_query($sql)or die("<div class='text-center'>SQL error ".__LINE__."<br>SQL: ".$sql."</div>");
?>

<style>.link:hover{ cursor: pointer; }</style>

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
                                 if($filtroativo=="f"){ echo "<h4 style='margin-top:0px'><small class='text-muted'><sup>Visualizando</sup></small><br><b>Atendimentos fechados</b></h4>";    }
                                                  else{ echo "<h4 style='margin-top:0px'><small class='text-muted'><sup>Visualizando</sup></small><br><b>Atendimentos abertos</b></h4>";   }
                              ?>
                               <div class="panel-actions" style="margin-top:5px">
                                 <?
                                          //echo "<a href='gsec/callcenter_FORM.php'><button type='button' class='btn btn-success'><i class='fa fa-laptop'></i><sup><i class='fa fa-plus'></i></sup> Novo atendimento</button></a>";
                                          if($filtroativo=="f"){  echo " <a href='gsec/callcenter.php'><button type='button' class='btn btn-info'><i class='fa fa-search'></i> Abertos</button></a>"; }
                                                           else{  echo " <a href='gsec/callcenter.php?filtro_ativo=f'><button type='button' class='btn btn-warning'><i class='fa fa-search'></i> Fechados</button></a>"; }
                                          echo " <a href='gsec/citizen.php'><button type='button' class='btn btn-primary'><i class='fa fa-user'></i><sup><i class='fa fa-search'></i></sup> Cidadão</button></a>";
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
                                                    <th class='text-muted'><small><i>Solicitação</i></small></th>
                                                    <th class='text-muted'><small><i>Endereço</i></small></th>
                                                    <th class='text-muted text-center'><small><i>Bairro</i></small></th>
                                                    <th class='text-muted'><small><i>Status</i></small></th>
                                                    <!--<th class='text-muted'><small><i>Setor</i></small></th>-->
                                                    <th class='text-muted'><small><i>Data</i></small></th>
                                                    <th class='text-muted text-center'><small><i>Coords</i></small></th>
                                                    <th class='text-muted text-center'><i class='fa fa-camera'></i></th>
                                                  </thead></tr>";
                                            echo "<tbody>";
                                            while($d = pg_fetch_assoc($res))
                                            {
                                               unset($personaldata, $complemento, $numprotocolo);


                                                $aux = substr(str_replace("-","",$d['date_added']),0,6);
                                                $numprotocolo = $aux."<br><b>".str_pad($d['id'],4,"0",STR_PAD_LEFT)."</b>";

                                                 if($d['rg'] !=""){ $personaldata[]="<sup class='text-muted'>RG:</sup>".str_replace(".","",str_replace("-","",$d['rg'])); }
                                                if($d['cpf'] !=""){ $personaldata[]="<sup class='text-muted'>CPF:</sup>".str_replace(".","",str_replace("-","",$d['cpf'])); }
                                                if($d['cnpj'] !=""){ $personaldata[]="<sup class='text-muted'>CNPJ:</sup>".str_replace(".","",str_replace("/","",$d['cnpj'])); }
                                                if(isset($personaldata)){ $personaldata =  "{$d['citizen']}<br><small class='text-muted'><i>".implode(", ", $personaldata)."</i></small>"; }
                                                                   else{ $personaldata = $d['citizen']; }

                                                      if($d['address_num']!= ""){ $d['street']  .= ", ".$d['address_num'];  }
                                                if($d['address_complement']!=""){ $complemento[] = $d['address_complement'];}
                                                if($d['address_reference']!="") { $complemento[] = $d['address_reference']; }
                                                if(isset($complemento)){ $complemento = "<br><small class='text-muted'><i>".implode(", ", $complemento)."</i></small>"; }

                                               echo "<tr class='link' id='{$d['id']}'>";
                                                   //echo "<td width='5px'><small class='text-muted'>{$d['id']}</small></td>";
                                                   echo "<td class='text-center' width='5px'><small class='text-muted'>{$numprotocolo}</small></td>";
                                                   echo "<td>{$personaldata}</td>";
                                                   echo "<td><small class='text-muted'>{$d['type']}</small><br><b>{$d['request']}</b></td>";
                                                   echo "<td>{$d['street']}{$complemento}</td>";
                                                   echo "<td nowrap class='text-center'>".($d['neighborhood']!=""?$d['neighborhood']:"<i class='fa fa-exclamation-triangle text-danger'></i>")."</td>";
                                                   echo "<td nowrap>{$d['status']}</td>";
                                                   //echo "<td>{$d['company_acron']}</td>";
                                                   echo "<td nowrap>".substr(formataData($d['date_added'],1),0,-3)."<br><small class='text-muted'><i>".humanTiming($d['date_added'])." atrás</i></small></td>";
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
