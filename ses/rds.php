<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora = now();

  logger("Acesso","SES-PNCD - Visualização geral - Registro diário de serviço");

  $sql = "SELECT R.*, U.name, N.neighborhood
          FROM {$schema}ses_pncd_registro_diario R
          JOIN {$schema}users U ON U.id = R.id_user
          JOIN {$schema}neighborhood N ON N.id = R.codigo_e_nome_localidade
          --WHERE concluido = 'f'";
  $res = pg_query($sql)or die("Error ".__LINE__."<br>Query: {$sql}");

?>


<section role="main" class="content-body">
  <header class="page-header">
    <h2>Registro diário de serviço</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>SES-PNCD</span></li>
        <li><span class='#'>Registro diário de serviço</span></li>
      </ol>
    </div>
  </header>

  <section class="panel box_shadow">
  		<header class="panel-heading" style="height:70px">
            <div class="panel-actions" style="margin-top:5px">
                <a href='ses/rds_FORM.php'>
                    <button type="button" class="btn btn-primary"><i class='fa fa-plus'></i> Nova atividade</button>
                </a>
            </div>
      </header>

  		<div class="panel-body">

        <div class="row">
          <div class="col-md-12">
              <?
                  if(pg_num_rows($res)){
                        echo "<table class='table table-striped' id='tabela'>";
                        echo "<thead><tr><th>#</th>
                                         <th>Nome</th>
                                         <th>Data</th>
                                         <th>Bairro</th>
                                         <th>Atividade</th>
                                         <th>Status</th>
                                         <th class='text-center'><small><i class='fa fa-cogs'></i></small></th>
                              </thead>";
                        echo "<tbody>";
                        while($d = pg_fetch_assoc($res))
                        {
                            echo "<tr>";
                              echo "<td>{$d['id']}</td>";
                              echo "<td>{$d['name']}</td>";
                              echo "<td>".formataData($d['data_atividade'],1)."</td>";
                              echo "<td>{$d['neighborhood']}</td>";
                              echo "<td>{$d['atividade']}</td>";
                              echo "<td>".($d['concluido']=='t'?"Concluído":"Aberto")."</td>";
                              echo "<td class='text-center'>
                                      <a href='ses/rds_FORM.php?id={$d['id']}'>
                                        <button class='btn btn-xs btn-default text-muted'><i class='fa fa-cogs'></i></button>
                                      </a>
                                    </td>";
                            echo "</tr>";
                          /*
                          [id] => 1
[municipio] => Joinville
[codigo_e_nome_localidade] => ADHEMAR GARCIA
[categoria_localidade] => bairro
[zona] => urbana
[tipo] => 1
[concluido] => f
[data_atividade] => 2020-07-31
[ciclo_ano] =>
[atividade] => 1
[id_user] => 1
                          */
                        }
                        echo "</tbody>";
                        echo "</table>";
                  }else{
                    echo "<div class='alert alert-warning text-center'>
										      <strong>AVISO</strong> Nenhum registro de atividade aberto.
									        </div>";
                  }
              ?>
          </div>
        </div>

      </div>
  </section><!--  <section class="panel box_shadow">-->
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
      <form id="form_filtro" action="#" method="post" target="_blank" rel="no_ajax">
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

$('.select2').select2({dropdownParent: $('#modalFiltro')});
$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> Aguarde");});
$(".loading2").click(function(){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i>");});
</script>
