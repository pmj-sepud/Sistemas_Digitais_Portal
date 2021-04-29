<?
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");
$schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
logger("Acesso","Backups");

  $dir     = '/var/www/html/backups/diario';

if(file_exists($dir))
{

  $diario  = scandir($dir,1);
  $dir     = '/var/www/html/backups/horario';
  $horario = scandir($dir);

  $infoA    = file("../backups/info");
  $infoB     = explode(" ",$infoA[0]);
  $info     = "<b>ATENÇÃO</b> - Informações do servidor de banco de dados e unidade de backup:<br>";
  $info    .= "Espaço total: ".$infoB[8]." Utilizado: ".$infoB[11]." (".$infoB[15]."), Espaço livre: ".$infoB[13];

}else
{
  $info     = "<b>ATENÇÃO</b> - Unidade remota de backup não mapeada neste servidor.<br>";
}
  function human_filesize($bytes, $decimals = 2) {
      $sz = 'BKMGTP';
      $ext = array("<small> Bytes</small>", "<small> Kb</small>", "<small> Mb</small>", "<small> Gb</small>", "<small> Tb</small>", "<small> Pb</small>");
      $factor = floor((strlen($bytes) - 1) / 3);
      return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$ext[$factor];
  }

?>
<section role="main" class="content-body">
  <header class="page-header">
    <h2>Controle das peças de backups</h2>

    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>Configurações</span></li>
        <li><span class='text-muted'>Backup</span></li>
      </ol>
    </div>
  </header>

  <!-- start: page -->


  					<div class="row">
  						<div class="col-md-12 col-lg-12">

<div class="alert alert-warning">
  <?=$info;?>
</div>

  							<div class="tabs">
  								<ul class="nav nav-tabs tabs-primary">
  									<li class="active">
  										<a href="#diario" data-toggle="tab" ajax='false'>Diário</a>
  									</li>
                    <li class="">
  										<a href="#horario" data-toggle="tab" ajax='false'>Horário</a>
  									</li>
                  </ul>


  								<div class="tab-content">
                    <!----------------------------->
                    <div id="diario" class="tab-pane active">
                      <div class="table-responsive">
                            <table class="table table-hover mb-none" id="tabela_dinamica">
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Arquivo</th>
                                  <th>Data</th>
                                  <th>Tamanho</th>
                                  <th>Download</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?
                                    $totalarqs = 1;
                                    for($i=0;isset($diario) && $i<count($diario);$i++)
                                    {
                                      if($diario[$i]!="." && $diario[$i]!="..")
                                      {
                                        echo "<tr>";
                                          echo "<td>".$totalarqs++."</td>";
                                          echo "<td>".$diario[$i]."</td>";
                                          echo "<td>".date("d/m/Y H:i", filectime("../backups/diario/".$diario[$i]))."</td>";
                                          echo "<td>".human_filesize(filesize("../backups/diario/".$diario[$i]))."</td>";
                                          echo "<td><a href='../backups/diario/{$diario[$i]}' ajax='false'><i class='fa fa-cloud-download'></i></a></td>";
                                        echo "</tr>";
                                      }
                                    }
                                ?>
                              </tbody>
                          </table>
                      </div>
                    </div>
                    <!----------------------------->
                    <!----------------------------->
                    <div id="horario" class="tab-pane">
                      <div class="table-responsive">
                            <table class="table table-hover mb-none">
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Arquivo</th>
                                  <th>Data</th>
                                  <th>Tamanho</th>
                                  <th>Download</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?
                                $totalarqs = 1;
                                for($c=0;isset($horario) && $c<count($horario);$c++)
                                {
                                  if($horario[$c]!="." && $horario[$c]!="..")
                                  {
                                    echo "<tr>";
                                      echo "<td>".$totalarqs++."</td>";
                                      echo "<td>".$horario[$c]."</td>";
                                      echo "<td>".date("d/m/Y H:i", filectime("../backups/horario/".$horario[$c]))."</td>";
                                      echo "<td>".human_filesize(filesize("../backups/horario/".$horario[$c]))."</td>";
                                      echo "<td><a href='../backups/horario/{$horario[$c]}' ajax='false'><i class='fa fa-cloud-download'></i></a></td>";
                                    echo "</tr>";
                                  }
                                }
                                ?>
                              </tbody>
                          </table>
                      </div>
                    </div>
                    <!----------------------------->


                  </div>
                </div>
              </div>
            </div>




  <script>
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
