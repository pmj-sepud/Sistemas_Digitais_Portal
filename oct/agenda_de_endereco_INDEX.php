<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  $agora = now();

/*
  if(isset($_POST['filtro_data'])){ $filtro_data = mkt2date(date2mkt($_POST['filtro_data'])); }
                              else{ $filtro_data = now(); }
*/
  if(isset($_GET['filtro_active']) && $_GET['filtro_active'] == "f" ){  $filtro_sql = " WHERE A.active = 'f'"; $filtro = true; }

  logger("Acesso","Agenda de Endereço");

  $meses[1]['curto'] = "Jan";
  $meses[2]['curto'] = "Fev";
  $meses[3]['curto'] = "Mar";
  $meses[4]['curto'] = "Abr";
  $meses[5]['curto'] = "Mai";
  $meses[6]['curto'] = "Jun";
  $meses[7]['curto'] = "Jul";
  $meses[8]['curto'] = "Ago";
  $meses[9]['curto'] = "Set";
  $meses[10]['curto'] = "Out";
  $meses[11]['curto'] = "Nov";
  $meses[12]['curto'] = "Dez";


  $meses[1]['longo'] = "Janeiro";
  $meses[2]['longo'] = "Fevereiro";
  $meses[3]['longo'] = "Março";
  $meses[4]['longo'] = "Abril";
  $meses[5]['longo'] = "Maio";
  $meses[6]['longo'] = "Junho";
  $meses[7]['longo'] = "Julho";
  $meses[8]['longo'] = "Agosto";
  $meses[9]['longo'] = "Setembro";
  $meses[10]['longo'] = "Outubro";
  $meses[11]['longo'] = "Novembro";
  $meses[12]['longo'] = "Dezembro";


?>

</style>
<section role="main" class="content-body">
  <header class="page-header">
    <h2>Agenda de Endereços</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><a href="oct/index.php">Sistema</a></li>
        <li><span class='text-muted'>Agenda de Endereço</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>



								<section class="panel box_shadow">
  									<header class="panel-heading hidden-xs" style="height:70px">
                      <div class="panel-actions" style="margin-top:5px">
                        <? if($filtro){ ?>
                        <a href="oct/agenda_de_endereco_INDEX.php"><button type="button" class="btn btn-primary loading"><i class='fa fa-search'></i> Visualizar ativos</button></a>
                        <? }else{ ?>
                        <a href="oct/agenda_de_endereco_INDEX.php?filtro_active=f"><button type="button" class="btn btn-primary loading"><i class='fa fa-search'></i> Visualizar inativos</button></a>
                        <? }
                          if(check_perm("3_16","C"))
                          {
                              echo "<a href='oct/agenda_de_endereco_FORM.php'><button type='button' class='btn btn-info'><i class='fa fa-map-marker'></i> <sup><i class='fa fa-plus'></i></sup> Novo registro</button></a>";
                          }
                        ?>


                      </div>
                    </header>

                    <header class="panel-heading visible-xs" style="height:100px">
                      <div class="text-center">
                        <? if($filtro){ ?>
                        <a href="oct/agenda_de_endereco_INDEX.php"><button type="button" class="btn btn-primary loading"><i class='fa fa-search'></i> Visualizar ativos</button></a>
                        <? }else{ ?>
                        <a href="oct/agenda_de_endereco_INDEX.php?filtro_active=f"><button type="button" class="btn btn-primary loading"><i class='fa fa-search'></i> Visualizar inativos</button></a>
                        <? }
                            if(check_perm("3_16","C"))
                            {
                                echo "<a href='oct/agenda_de_endereco_FORM.php'><button type='button' class='btn btn-info'><i class='fa fa-map-marker'></i> <sup><i class='fa fa-plus'></i></sup> Novo registro</button></a>";
                            }
                        ?>
                        <!--<br><a href="oct/agenda_de_endereco_FORM.php"><button type="button" class="btn btn-info"><i class='fa fa-map-marker'></i> <sup><i class='fa fa-plus'></i></sup> Novo registro</button></a>-->
                      </div>
                    </header>
  									<div class="panel-body">
                    <?
                        $sql = "SELECT S.name as street_name, A.* FROM sepud.oct_addressbook A
                                LEFT JOIN sepud.streets S ON S.id = A.id_street
                                ".$filtro_sql."
                                ORDER BY A.name ASC";
                        $res = pg_query($sql)or die("SQL error ".__LINE__);
                        if(pg_num_rows($res))
                        {
                          echo "<div class='table-responsive'>";
                          echo "<table class='table table-condensed' id='tabela'>";
                          echo "<thead><tr>
                                  <th><small><i class='text-muted'>#</small></i></td>
                                  <th><small><i>Local</i></small></td>
                                  <th><small><i>Tipo</i></small></th>
                                  <th><small><i>Endereço</i></small></th>
                                  <th><small><i>Número</i></small></th>
                                  <th><small><i>Bairro</i></small></th>
                                  <th><small><i>Zona</i></small></th>
                                  <th class='text-center'><small><i class='fa fa-cogs'></i></small></th>
                                </tr></thead>";
                          echo "<tbody>";
                          while($d=pg_fetch_assoc($res))
                          {
                            echo "<tr>";
                            echo "<td><small class='text-muted'>".$d['id']."</small></td>";
                            echo "<td nowrap>".$d['name']."</td>";
                            echo "<td nowrap>".ucwords($d['type'])."</td>";
                            echo "<td nowrap>".$d['street_name']."</td>";
                            echo "<td>".$d['num_ref']."</td>";
                            echo "<td nowrap>".$d['neighborhood']."</td>";
                            echo "<td nowrap>".$d['zone']."</td>";
                            echo "<td class='text-center'>";
                            if(check_perm("3_16","U"))
                            {
                              echo "<a href='oct/agenda_de_endereco_FORM.php?id=".$d['id']."'><button class='btn btn-xs btn-default text-muted'><i class='fa fa-cogs'></i></button></a>";
                            }
                            echo "</td>";
                            echo "</tr>";
                          }
                          echo "</tbody>";
                          echo "</table>";
                          echo "</div>";
                        }else{
                          if($filtro)
                          {
                            echo "<div class='alert alert-warning text-center'>Nenhum registro inativo.</div>";
                          }else{
                            echo "<div class='alert alert-warning text-center'>Não há cadastro na agenda de endereço.</div>";
                          }
                        }

                    ?>


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
      <form id="form_filtro" action="oct/rel_olostech_SAMU.php" method="post">
      <div class="modal-body">
        <div class="row" style="margin-bottom:10px">
            <div class="form-group">
                <label class="col-md-3 control-label" for="filtro_data">Perído:</label>
                <div class="col-md-8">
                  <select id="filtro_data" name="filtro_data" class="form-control">

                     <?
                      for($a = 2019; $a <= $agora['ano']; $a++)
                      {
                          echo "<optgroup label='".$a."'>";

                            if($a == $agora['ano']){ $mes_ate = date('n'); }
                            else                   { $mes_ate = 12;        }

                            for($m = 1; $m <= $mes_ate; $m++)
                            {
                                if($a == $agora['ano'] && $m == $mes_ate){ $sel = "selected"; }

                                echo  "<option value='01/".$m."/".$a." 00:00:00' ".$sel.">".$meses[$m]['longo']."/".$a."</option>";
                            }
                          echo "</optgroup>";

                      }
                     ?>
                  </select>
                </div>
            </div>
        </div>
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


$("#bt_submit").click(function(){
    $('#modalFiltro').modal('hide');
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
    $("#form_filtro").submit();
});
$('.select2').select2();
$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> Aguarde");});
$(".loading2").click(function(){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i>");});
</script>
