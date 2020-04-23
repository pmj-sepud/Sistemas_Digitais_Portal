<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  logger("Acesso","Usuários");

  $data_format = date('d/m/Y',strtotime("-1 days"));

  $mon[] = array("arq" => "waze_stats_00.php", "desc" => "Evolução diária dos congestionamentos e tempos de atrasos acumulados");
  $mon[] = array("arq" => "waze_stats_01.php", "desc" => "Evolução dos congestionamentos e tempos de atrasos acumulados no período da manhã");
  $mon[] = array("arq" => "waze_stats_02.php", "desc" => "Evolução dos congestionamentos e tempos de atrasos acumulados no período da tarde");
  $mon[] = array("arq" => "waze_stats_03.php", "desc" => "Vias mais congestionadas no momento atual");
  $mon[] = array("arq" => "waze_stats_04.php", "desc" => "Mapa do Waze em tempo real");
  $mon[] = array("arq" => "waze_stats_05.php", "desc" => "Alertas da plataforma WAZE no momento atual");
  $mon[] = array("arq" => "waze_stats_06.php", "desc" => "Evolução diária dos congestionamentos e tempos de atrasos acumulados na DATA DE ONTEM (".$data_format.")");
?>

<section role="main" class="content-body">
      <header class="page-header">
        <h2>Sistema de monitoramento de recursos</h2>
        <div class="right-wrapper pull-right" style='margin-right:15px;'>
          <ol class="breadcrumbs">
            <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
            <li><span class='text-muted'>Configurações</span></li>
            <li><span class='text-muted'>Monitoramento</span></li>
          </ol>
          <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
        </div>
      </header>
      <div class="col-md-12">

        <section class="panel">
          <header class="panel-heading">
            <div class="panel-actions" style='margin-top:-12px'>
            </div>
          </header>
          <div class="panel-body">
                <div class="table-responsive">
                  <table class="table table-hover mb-none" id="tabela_dinamica">
                    <thead>
                      <tr>
                          <th>#</th>
                          <th>Arquivo</th>
                          <th>Descrição</th>
                          <th>Ação</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?
                            for($i=0;$i<count($mon);$i++){
                              $d = $mon[$i];
                              echo "<tr>";
                                echo "<td class='text-muted'><small>".$i."</small></td>";
                                echo "<td>".$d['arq']."</td>";
                                echo "<td>".$d['desc']."</td>";
                                echo "<td class='actions'>
                                  <a href='monitoramento/exec.php?arq=".$d['arq']."&acao=vis' target='_blank' ajax='false'><i class='fa fa-eye'></i></a>
                                  <a href='#'><i class='fa fa-cogs'></i></a>
                                </td>";
                              echo "</tr>";
                            }
                        ?>
                    </tbody>
                  </table>
                </div>
          </div>
        </section>

      </div>
</section>

<script>
function abre_monitoramento()
{
    	var params = ['height='+screen.height,'width='+(screen.width-10), 'fullscreen=yes'].join(',');
	    var newTab =window.open('','_blank',params);
      newTab.location = "../monitoramento/exec.php";
      newTab.focus();
}
//$(".loading").click(function(event){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde</small>");});
$(document).ready( function () {
    $('#tabela_dinamica').DataTable({
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
</script>
