<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora = now();

  logger("Visualização geral","SES - PNCD", "Pontos estratégicos");

  $sql = "SELECT S.name as logradouro, P.*
          FROM {$schema}ses_pe P
          LEFT JOIN {$schema}streets S ON S.id = P.id_street";
  $res = pg_query($sql)or die("<div class='text-center'>SQL error ".__LINE__."<br>SQL: ".$sql."</div>");
?>


<section role="main" class="content-body">
  <header class="page-header">
    <h2>Cadastro dos pontos estratégicos</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>SES-PNCD</span></li>
        <li><span class='#'>Cadastro dos pontos estratégicos</span></li>
      </ol>
    </div>
  </header>



								<section class="panel box_shadow">
  									<header class="panel-heading hidden-xs" style="height:70px">
                      <div class="panel-actions" style="margin-top:5px">
                        <?
                              if(check_perm("8_26","C"))
                              {
                                  echo "<a href='ses/pontosestrategicos_FORM.php'><button type='button' class='btn btn-primary'><i class='fa fa-plus'></i> Novo cadastro</button></a>";
                              }
                          ?>
                      </div>
                    </header>

                    <header class="panel-heading visible-xs" style="height:100px">
                      <div class="text-center">
                        <?
                            if(check_perm("7_21","C"))
                            {
                                echo "<a href='sas/cidadao_FORM.php'><button type='button' class='btn btn-info'><i class='fa fa-plus'></i> Novo cadastro</button></a>";
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
                                  <th class='text-muted'><small><i>#</small></i></th>
                                  <th class='text-muted'><small><i>Agente</i></small></th>
                                  <th class='text-muted'><small><i>Logradouro</i></small></th>
                                  <th class='text-muted'><small><i>Singel</i></small></th>
                                  <th class='text-muted'><small><i>Bairro</i></small></th>
                                  <th class='text-muted'><small><i>Estabelecimento/Referência</i></small></th>

                                  <th class='text-center'><small><i class='fa fa-cogs'></i></small></th>
                                </thead></tr>";

                          echo "<tbody>";
                          while($d = pg_fetch_assoc($res))
                          {

                                echo "<tr>";
                                echo "<td width='5px'><small class='text-muted'>{$d['id']}</small></td>";
                                echo "<td>{$d['agente']}</td>";
                                echo "<td>{$d['endereco']}, {$d['num']}</td>";
                                echo "<td>{$d['logradouro']}</td>";
                                echo "<td>{$d['bairro']}</td>";
                                echo "<td>{$d['estabelecimento']}</td>";

                                echo "<td class='text-center' width='5px'>";
                                if(check_perm("7_21","U"))
                                {
                                  echo "<a href='ses/pontosestrategicos_FORM.php?id={$d['id']}'><button class='btn btn-xs btn-default text-muted'><i class='fa fa-cogs'></i></button></a>";
                                }
                                echo "</td>";
                                echo "</tr>";
                          }
                          echo "</tbody>";
                          echo "</table>";
                          echo "</div>";
                        }else{

                            echo "<div class='alert alert-warning text-center'>Nenhum cidadão cadastrado no sistema.</div>";

                        }

                    ?>
                  </div>
                </div>

                    </div>
                </section>
</section>


<script>

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
