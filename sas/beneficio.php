<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora = now();

  logger("Acesso","SAS - BEV", "Benefícios - Visualização geral");

  if($_GET['filtro']!="ver_todos"){ $filtro_sql = " AND CO.id = {$_SESSION['id_company']}"; }

  $sql = "SELECT C.id, C.name, C.cpf, C.rg,
                 R.id as id_request, R.date as request_date, R.active_search,
                 CO.acron as company_acron
          FROM {$schema}sas_citizen C
          JOIN {$schema}sas_request R ON R.id_citizen = C.id AND R.status = 'Aberto'
          JOIN {$schema}company    CO ON CO.id = R.id_company {$filtro_sql}
          ORDER BY name ASC";
  $res = pg_query($sql)or die("<div class='text-center'>SQL error ".__LINE__."<br>SQL: ".$sql."</div>");
?>

</style>
<section role="main" class="content-body">
  <header class="page-header">
    <h2>Cidadãos aguardando benefícios</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>SAS-BEV</span></li>
        <li><span class='#'>Cidadãos aguardando benefícios</span></li>
      </ol>
    </div>
  </header>



								<section class="panel box_shadow">
  									<header class="panel-heading" style="height:70px">
                          <div class="panel-actions" style="margin-top:5px">


                              <?
                                  if(!isset($_GET['filtro'])){
                                      echo " <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modalFiltro'><i class='fa fa-print'></i> Imprimir recibos de entrega em lote</button>";
                                  }else{
                                      echo "<button type='button' class='btn btn-default disabled' disabled><i class='fa fa-print'></i> Imprimir recibos de entrega em lote</button>";
                                  }

                                  if(isset($_GET['filtro'])){
                                    echo " <a href='sas/beneficio.php'><button type='button' class='btn btn-primary'><i class='fa fa-search'></i> Visualizar meu órgão</button></a>";
                                 }else {
                                    echo " <a href='sas/beneficio.php?filtro=ver_todos'><button type='button' class='btn btn-primary'><i class='fa fa-search'></i> Visualizar todos</button></a>";
                                 }
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
                          echo "<table class='table table-condensed' id='tabela'>";

                          echo "<thead><tr>
                                  <th class='text-muted'><small><i>Nome</i></small></th>
                                  <th class='text-muted'><small><i>CPF</i></small></th>
                                  <th class='text-muted'><small><i>RG</i></small></th>
                                  <th class='text-muted'><small><i>Protocolo</i></small></th>
                                  <th class='text-muted'><small><i>Origem</i></small></th>
                                  <th class='text-muted'><small><i>Busca ativa?</i></small></th>
                                  <th class='text-muted'><small><i>Data solicitação</i></small></th>
                                  <th class='text-muted'><small><i>Decorrido</i></small></th>
                                  <th class='text-center'><small><i class='fa fa-cogs'></i></small></th>
                                </thead></tr>";

                          echo "<tbody>";
                          while($d = pg_fetch_assoc($res))
                          {

                                echo "<tr>";
                                echo "<td nowrap>{$d['name']}</td>";
                                echo "<td nowrap>{$d['cpf']}</td>";
                                echo "<td nowrap>{$d['rg']}</td>";
                                echo "<td nowrap><b>".str_replace("-","",substr($d['request_date'],0,-12)).".".$d['id_request']."</b></td>";
                                echo "<td nowrap>{$d['company_acron']}</td>";
                                echo "<td nowrap>".($d['active_search']=="t"?"<b>Sim</b>":"<small class='text-muted'>não</small>")."</td>";
                                echo "<td nowrap>".formataData($d['request_date'],1)."</td>";
                                echo "<td nowrap>".humanTiming($d['request_date'])."</td>";
                                echo "<td class='text-center' width='5px'>";
                                if(check_perm("7_21","CRUD") || check_perm("7_23","CRUD"))
                                {
                                  echo "<a href='sas/beneficio_FORM.php?id_citizen={$d['id']}&id_request={$d['id_request']}'><button class='btn btn-xs btn-default text-muted'><i class='fa fa-cogs'></i></button></a>";
                                }
                                echo "</td>";
                                echo "</tr>";
                          }
                          echo "</tbody>";
                          echo "</table>";
                          echo "</div>";
                        }else{
                            if(isset($_GET['filtro'])){
                              echo "<div class='alert alert-warning text-center'>Nenhum benefício aberto no sistema em nenhum órgão.</div>";
                            }else{
                              echo "<div class='alert alert-warning text-center'>Nenhum benefício aberto no sistema em seu órgão.</div>";
                            }

                        }

                    ?>
                  </div>
                </div>

                    </div>
                </section>
</section>

<div class="modal fade"  id="modalFiltro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Filtro de pesquisa para impressão de relatório:</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-20px">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form_filtro" action="../sas/rel_recibos_lote.php" method="post" target="_blank" rel="no_ajax">
      <div class="modal-body">

        <div class="row" style="margin-bottom:20px">
            <div class="form-group">
                <label class="col-md-3 control-label" for="data_inicial">Data inicial:</label>
                <div class="col-md-8">
                    <input type="date" name="data_inicial" id="data_inicial" class="form-control" required>
                </div>
            </div>
        </div>

        <div class="row" style="margin-bottom:10px">
            <div class="form-group">
                <label class="col-md-3 control-label" for="data_final">Data final:</label>
                <div class="col-md-8">
                    <input type="date" name="data_final" id="data_final" class="form-control" required>
                </div>
            </div>
        </div>

        <div class="row" style="margin-bottom:10px">
            <div class="form-group">
                <label class="col-md-3 control-label" for="data_final">Órgão:</label>
                <div class="col-md-8">
                    <?

                        if(!check_perm("7_25")){ $sql_perm = " AND id = '{$_SESSION['id_company']}'"; }
                        $sql = "SELECT id, name, acron FROM {$schema}company WHERE secretary = 'SAS' {$sql_perm} ORDER BY name ASC";
                        $res = pg_query($sql)or die("Error ".__LINE__);
                        while($c = pg_fetch_assoc($res))
                        {
                          $company[] = $c;
                        }
                    ?>
                    <select class="form-control select2" name="id_company">
                        <?
                          for($i=0;$i<count($company);$i++){
                            $sel = ($company[$i]['id'] == $_SESSION['id_company']?"selected":"");
                            echo "<option value='{$company[$i]['id']}' {$sel}>{$company[$i]['name']}</option>";
                          }
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="row" style="margin-bottom:10px">
            <div class="form-group">
                <label class="col-md-3 control-label" for="delivery_type_filtro">Tipo de retirada:</label>
                <div class="col-md-8">
                    <select class="form-control" name="delivery_type_filtro">
                        <option value="">Todos</option>
                        <option value="retirada_eqp">Retirada no equipamento</option>
                        <option value="entrega_dom">Entrega em domicílio</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row" style="margin-bottom:10px">
            <div class="form-group">
                <label class="col-md-3 control-label" for="sas_monitor_filtro">Família Acompanhada:</label>
                <div class="col-md-8">
                    <select class="form-control" name="sas_monitor_filtro">
                        <option value="">Todos</option>
                        <option value="t">Sim</option>
                        <option value="f">Não</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row" style="margin-bottom:10px">
            <div class="form-group">
                <label class="col-md-3 control-label" for="order_filtro">Ordenar por:</label>
                <div class="col-md-8">
                    <select class="form-control" name="order_filtro">
                        <option value="C.name">Nome</option>
                        <option value="N.neighborhood">Bairro</option>
                        <option value="S.name">Rua</option>
                    </select>
                </div>
            </div>
        </div>


        <div class="row" style="margin-bottom:10px">
          <div class="col-sm-12">
              <span class="text-muted"><i><b>Obs: </b>Selecione a data incial e final para impressão dos recibos de entrega de benefício.</i></span>
          </div>
        </div>

      </div>
      <div class="modal-footer">

        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary" id="bt_submit">Gerar relatório</button>
      </div>
      </form>
    </div>
  </div>
</div>


<script>
<?
function humanTiming($data)
{

    $time = strtotime($data);
    $time = time() - $time;
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'ano',
        2592000 => 'mês',
        604800 => 'semana',
        86400 => 'dia',
        3600 => 'hora',
        60 => 'minuto',
        1 => 'segundo'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        if($text=="mês" && $numberOfUnits>1){ $text="meses"; $ext = ""; }
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?$ext:'');
    }

}
?>
$(document).ready( function () {
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

$("#bt_print").click(function(){
	var vw = window.open('oct/rel_olostech_SAMU_print.php?filtro_data=<?=$filtro_data['data'];?>',
									     'popup',
								 	     'width=800, height=600, top=10, left=10, scrollbars=no,location=no,status=no');
});

$('.select2').select2({dropdownParent: $('#modalFiltro')});
$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> Aguarde");});
$(".loading2").click(function(){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i>");});
</script>
