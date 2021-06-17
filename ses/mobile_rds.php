<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora = now();

  logger("Acesso","SES-PNCD - Visualização geral - Registro diário de serviço");

  $sql = "SELECT R.*, U.name, N.neighborhood,
         (SELECT count(*) FROM {$schema}ses_pncd_registro_diario_atividade WHERE id_ses_pncd_registro_diario = R.id) as qtd_atividades
          FROM {$schema}ses_pncd_registro_diario R
          JOIN {$schema}users U ON U.id = R.id_user
          JOIN {$schema}neighborhood N ON N.id = R.codigo_e_nome_localidade
          WHERE concluido = 'f' AND R.id_user = {$_SESSION['id']}";
  $res = pg_query($sql)or die("Error ".__LINE__."<br>Query: {$sql}");

?>
<style>
.link:hover{ cursor: pointer; }
.dataTables_filter { width: 50%; float: right; text-align: right; }
</style>
<section role="main">
<!--
   <header class="page-header">
    <h2>Registro diário de serviço</h2>
    <div style='position: absolute;top: 8px;right: 10px;'>
     <a href='auth/logout.php' ajax="false"><button type="button" class="btn btn-default">Sair</button></a>
   </div>
   </header>
-->
  <section class="panel">



  		<header class="panel-heading">
            <div class="panel-actions">
               <a href='ses/mobile_rds_FORM.php'>
                  <button type="button" class="btn btn-primary"><i class='fa fa-plus'></i> Nova atividade</button>
               </a>
            </div>
      </header>
  		<div class="panel-body" style="margin-top:0px">

        <div class="row">
          <div class="col-md-12">
              <?
                  if(pg_num_rows($res)){
                        echo "<table class='table table-striped table-hover' id='tabela'>";
                        echo "<thead><tr><th>#</th>
                                         <th>Nome</th>
                                         <th>Data</th>
                                         <th>Bairro</th>
                                         <th>Atividade</th>
                                         <th>Qtd. registros</th>
                              </thead>";
                        echo "<tbody>";
                        while($d = pg_fetch_assoc($res))
                        {
                            echo "<tr id='{$d['id']}' class='link'>";
                              echo "<td>{$d['id']}</td>";
                              echo "<td>{$d['name']}</td>";
                              echo "<td>".formataData($d['data_atividade'],1)."</td>";
                              echo "<td>{$d['neighborhood']}</td>";
                              echo "<td>{$d['atividade']}</td>";
                              echo "<td>{$d['qtd_atividades']}</td>";
                            echo "</tr>";

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

  </section><!--  <section class="panel box_shadow">-->
</section>

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


   $(".link").click(function(){
      $('#wrap').load("ses/mobile_rds_FORM.php?id="+$(this).attr("id"));
   })

   var table = $('#tabela').DataTable({
      minimumResultsForSearch: -1,
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
    "order": [[0,"desc"]]
    });

    $('.dataTables_filter input').click(function () { $(this).val('');});

});


</script>
