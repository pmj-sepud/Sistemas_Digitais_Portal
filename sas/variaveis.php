<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora = now();

  logger("Acesso","SAS - BEV", "Variáveis de classificação");

  $sql = "SELECT * FROM {$schema}sas_vars ORDER BY description ASC";
  $res = pg_query($sql)or die("<div class='text-center'>SQL error ".__LINE__."<br>SQL: ".$sql."</div>");
  while($d = pg_fetch_assoc($res))
  {
    $dados[$d['subgroup']][]=$d;
  }

?>

</style>
<section role="main" class="content-body">
  <header class="page-header">
    <h2>Variáveis de classificação</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>SAS-BEV</span></li>
        <li><span class='#'>Variáveis de classificação</span></li>
      </ol>
    </div>
  </header>



								<section class="panel box_shadow">
  									<header class="panel-heading hidden-xs" style="height:70px">
                      <div class="panel-actions" style="margin-top:5px">
                        <?
                              if(check_perm("7_21","C"))
                              {
                                  echo "<a href='sas/variaveis_FORM.php'><button type='button' class='btn btn-info'><i class='fa fa-plus'></i> Nova variável</button></a>";
                              }
                          ?>
                      </div>
                    </header>

                    <header class="panel-heading visible-xs" style="height:100px">
                      <div class="text-center">
                      <?
                            if(check_perm("7_21","C"))
                            {
                                echo "<a href='sas/variaveis_FORM.php'><button type='button' class='btn btn-info'><i class='fa fa-plus'></i> Nova variável</button></a>";
                            }
                        ?>
                      </div>
                    </header>
  									<div class="panel-body">
                      <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                    <?

                        if(isset($dados) && count($dados))
                        {
                          echo "<div class='table-responsive'>";
                          echo "<table class='table table-condensed' id='tabela'>";

                          echo "<thead><tr>
                                  <th class='text-muted'><small><i>#</small></i></th>
                                  <th class='text-muted'><small><i>Variável</i></small></th>
                                  <th class='text-muted'><small><i>Grupo</i></small></th>
                                  <th class='text-muted'><small><i>Status</i></small></th>
                                  <th class='text-center'><small><i class='fa fa-cogs'></i></small></th>
                                </thead></tr>";

                          echo "<tbody>";
                          foreach ($dados as $subgrupo => $vars)
                          {
                            /*
                              echo "<tr><th colspan='4' class='info'>{$grupo}</th></tr>";
                              echo "<tr>
                                      <td class='text-muted'><small><i>#</small></i></td>
                                      <td class='text-muted'><small><i>Variável</i></small></td>
                                      <td class='text-muted'><small><i>Grupo</i></small></td>
                                      <td class='text-center'><small><i class='fa fa-cogs'></i></small></td>
                                    </tr>";
                            */
                            for($c=0; $c<count($vars);$c++)
                            {
                                echo "<tr>";
                                echo "<td><small class='text-muted'>{$vars[$c]['id']}</small></td>";
                                echo "<td nowrap>{$vars[$c]['description']}</td>";
                                echo "<td nowrap>{$subgrupo}</td>";
                                echo "<td nowrap>".($vars[$c]['status']=='t'?"Ativo":"Inativo")."</td>";
                                echo "<td class='text-center'>";
                                if(check_perm("7_21","U"))
                                {
                                  echo "<a href='sas/variaveis_FORM.php?id={$vars[$c]['id']}'><button class='btn btn-xs btn-default text-muted'><i class='fa fa-cogs'></i></button></a>";
                                }
                                echo "</td>";
                                echo "</tr>";
                            }
                          }
                          echo "</tbody>";
                          echo "</table>";
                          echo "</div>";
                        }else{

                            echo "<div class='alert alert-warning text-center'>Nenhuma variável cadastrada no sistema.</div>";

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
$(".loading2").click(function(){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i>");});
</script>
