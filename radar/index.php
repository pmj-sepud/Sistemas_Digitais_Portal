<?
  session_start();

  require("../libs/php/funcoes.php");
  require("../libs/php/conn.php");

  $agora = now();



  if($_GET['filtro']=="dia"){
    $ontem = date('Y-m-d',strtotime("-1 days"));
    $filtro_sql = " F.date = '".$ontem."' ";
    $txt_filtro = "Referência: ".formataData($ontem,1);
    logger("Acesso","Radares","Filtro: último dia");
  }else{
    $filtro_sql = " F.date >= '".$agora['ano']."-".$agora['mes']."-01' ";
    $txt_filtro = "Referência: ".$agora['mes_txt_c']."/".$agora['ano'];
    logger("Acesso","Radares");
  }

      $sql  = "SELECT E.id, E.equipment, E.address,
              			 max(F.date) as last_file_imported, sum(total) as contador_veiculos
              FROM radars.radars_flows F, radars.radars_equipments E
              WHERE $filtro_sql
              AND F.equipament_id = E.id
              GROUP BY E.id, E.equipment, E.address
              ORDER BY E.id ASC";

  $res  = pg_query($conn_neogrid,$sql);
  while($d = pg_fetch_assoc($res)){

      $eqps[$d['equipment']] = $d;

    /*  $sqlImport  = "SELECT max(pubdate) as data_import FROM radars.equipment_files WHERE equipment = '".$d['equipment']."'";
      $resImport  = pg_query($conn_neogrid,$sqlImport);
      $infoImport = pg_fetch_assoc($resImport);
      $eqps[$d['equipment']]['last_file_imported'] = $infoImport['data_import'];
    */
  }

?>

<section role="main" class="content-body">
  <header class="page-header">
    <h2>Equipamentos</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>Radares</span></li>
        <li><span class='text-muted'>Equipamentos</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>


<div class="col-md-12">
								<section class="panel">
									<header class="panel-heading">
                    <div class="panel-actions" style='margin-top:-12px'>
<!--
                      <a href="#" ic-get-from="#" ic-target="#wrap">
                        <button type="button" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary"><i class="fa fa-user-plus"></i> Novo equipamento</button>
                      </a>
-->
                      <? if($_GET['filtro']=="dia"){   ?>
                                            <a href="radar/index.php">
                                              <button type="button" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary"><i class="fa fa-search"></i> Acumulado mensal</button>
                                            </a>
                      <? }else{ ?>
                                            <a href="radar/index.php?filtro=dia">
                                              <button type="button" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary"><i class="fa fa-search"></i> Último dia</button>
                                            </a>
                      <? } ?>
                      <!--<a href="#" ic-get-from="sistema/logs.php" ic-target="#wrap" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary"><i class="fa fa-user-plus"></i> Novo usuário !</a>-->
									  </div>
                  </header>
									<div class="panel-body">
										<div class="table-responsive">
											<table class="table table-hover mb-none" id="tabela_dinamica">
												<thead>
													<tr>
														<th>#</th>
														<th>Equipamento</th>
                            <th>Endereço</th>
                            <th class='text-center'>Última atualização</th>
                            <th class='text-center'>Contagem de tráfego</th>
                            <th class='text-center'><i class='fa fa-cogs'></i></th>
													</tr>
												</thead>
												<tbody>
<?
/*

    [FS627JOI] => Array
        (
            [equipment] => FS627JOI
            [address] => R. Nacar, 260
            [id] => 584
            [contador_veiculos] => 361254
            [file_data_import] => 2018-11-25
        )

*/
if(isset($eqps))
{
  foreach($eqps as $eqp => $info)
  {


    $datetime1 = date_create($info['last_file_imported']);
    $datetime2 = date_create($agora['datasrv']);
    $interval  = date_diff($datetime1, $datetime2);

    $classtd = "";
    if($interval->format('%a') >= 2){$classtd = "warning";}
    if($interval->format('%a') >= 5){$classtd = "danger";}

    echo "<tr id='".$info['id']."'>";
    echo "<td class='text-muted'>".$info['id']."</td>";
    echo "<td class=''>".$info['equipment']."</td>";
    echo "<td nowrap class=''>".$info['address']."</td>";
    echo "<td nowrap class='text-center ".$classtd."'>".formataData($info['last_file_imported'],1)." <sup>(".$interval->format('%R%a dias').")</sup></td>";
    echo "<td class='text-center'>".number_format($info['contador_veiculos'],0,'','.')."</td>";

    echo "<td class='actions text-center'>
            <a style='cursor:pointer' href='radar/detalhado.php?id=".$info['id']."'>
            <i class='fa fa-eye'></i>
            </a>
            <!--<a href='#modalRemover' class='delete-row modal-basic' remover_id='".$d['id']."'><i class='fa fa-trash-o'></i></a>-->
          </td>";

    echo "</tr>";
  }
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


(function( $ ) {

	'use strict';

  /*
  $('.modal-basic').click(function() {
    var id_remover = $(this).attr("remover_id");



    $('.modal-basic').magnificPopup({
      type: 'inline',
  		fixedContentPos: false,
  		fixedBgPos: true,
  		overflowY: 'auto',
  		closeBtnInside: true,
  		preloader: false,
  		midClick: true,
  		removalDelay: 300,
  		mainClass: 'my-mfp-slide-bottom',
  		modal: true,
      callbacks: {
        beforeClose: function()
        {
            $.ajax({
              method: "POST",
              url: "usuarios/sqls.php",
              data: { id: id_remover, acao: "remover" }
            }).done(function( msg ) {
                $("#"+id_remover).fadeOut("slow");
              });

        }
      }
  	});
  });
*/
$(".modal-basic").click(function(){
    var ID = $(this).attr('remover_id');
    $('.modal-confirm').attr('remover_id',ID);
});

	$('.modal-basic').magnificPopup({
    type: 'inline',
		fixedContentPos: false,
		fixedBgPos: true,
		overflowY: 'auto',
		closeBtnInside: true,
		preloader: false,
		midClick: true,
		removalDelay: 300,
		mainClass: 'my-mfp-slide-bottom',
		modal: true
	});

  	$(document).on('click', '.modal-dismiss', function (e) {
  		e.preventDefault();
  		$.magnificPopup.close();
      $('.modal-confirm').removeAttr('remover_id');
  	});


  	$(document).on('click', '.modal-confirm', function (e) {
      var remover_id = $(this).attr('remover_id');
      var stack_bottomright = {"dir1": "up", "dir2": "left", "firstpos1": 15, "firstpos2": 15};
      e.preventDefault();
  		$.magnificPopup.close();

      $.ajax({
        method: "POST",
        //url: "usuarios/sqls.php",
        data: { id: remover_id, acao: "remover" }
      }).done(function( msg ) {
          //alert("REMOVIDO !!!! ID: "+remover_id);
          $("#"+remover_id).fadeOut("slow");

          var notice = new PNotify({
                title: 'Sucesso',
                text:  'Registro #'+remover_id+' removido.',
                type:  'success',
                addclass: 'stack-bottomright',
                stack: stack_bottomright,
                hide: true,
                delay: 1000,
                closer: true
              });

        });
  	});

  }).apply( this, [ jQuery ]);
</script>
