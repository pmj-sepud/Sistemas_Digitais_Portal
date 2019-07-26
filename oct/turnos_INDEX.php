<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  $sql = "SELECT * FROM sepud.oct_workshift WHERE id_company = '".$_SESSION['id_company']."' ORDER BY id DESC";
  $rs  = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);

  $turno_aberto = false;
  while($tmp = pg_fetch_assoc($rs))
  {
      $dados[] = $tmp;
      if($tmp['status'] == "aberto"){ $turno_aberto = true; $id_turno = $tmp['id'];}

  }

?>

<section role="main" class="content-body">
  <header class="page-header">
    <h2>Gestão do turno</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>Turnos</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>


<?

  if(!pg_num_rows($rs))
  {
    echo "<div class='col-md-12'>
    								<section class='panel'>
                    <header class='panel-heading'>
                    </header>
                      <div class='panel-body'>
                        <div class='alert alert-warning col-md-6 col-md-offset-3 text-center'><strong>Aviso: </strong> Nenhum turno cadastrado no sistema.</div>
                      </div>
                    </section>
          </div>";

  }else
  {
?>
<div class="col-md-12">
								<section class="panel box_shadow">
									<header class="panel-heading" style="height:50px">
                    <div class="panel-actions" style='margin-top:-10px;'>
                      <?
                        if(!$turno_aberto){
                      ?>
                      <a href='oct/turno.php?id=<?=$id_turno;?>'>
                        <button type='button' class='btn btn-primary'><i class="fa fa-plus"></i> Abrir novo turno de trabalho</button>
                      </a>
                    <? } ?>
                      <!--<a href="#" ic-get-from="sistema/logs.php" ic-target="#wrap" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary"><i class="fa fa-user-plus"></i> Novo usuário !</a>-->
									  </div>
                  </header>
									<div class="panel-body">
										<div class="table-responsive">
											<table class="table table-hover mb-none">
												<thead>

												</thead>
												<tbody>
<?
  $td_width = "140px";
  if(!$turno_aberto)
  {
    echo "<tr class='warning'><td colspan='6'>Turno(s) fechado(s):</td></tr>";
    echo "<tr>
    <th><i>Turno</i></th>
    <th><i>Período</i></th>
    <th width='".$td_width."'><i>Abertura</i></th>
    <th width='".$td_width."'><i>Fechamento</i></th>
    <th><i>Observações</i></th>
      <th class='text-center'><i class='fa fa-cogs'></i></th>
    </tr>";
  }
  for($i=0; $i<count($dados);$i++)
  {
    $d = $dados[$i];

    if($d['status'] == "aberto"){
      echo "<tr class='success'><td colspan='6'>Turno aberto:</td></tr>";
      echo "<tr>
      <th><i>Turno</i></th>
      <th><i>Período</i></th>
      <th width='".$td_width."'><i>Abertura</i></th>
      <th width='".$td_width."'><i>Fechamento</i></th>
      <th><i>Observações</i></th>
        <th class='text-center'><i class='fa fa-cogs'></i></th>
      </tr>";
    }


    echo "<tr>";
    echo "<td class='text-muted'>".$d['id']."</td>";
    echo "<td>".ucfirst($d['period'])."</td>";
    echo "<td>".formataData($d['opened'],1)."</td>";
    echo "<td>".formataData($d['closed'],1)."</td>";
    echo "<td>".$d['observation']."</td>";

    if($d['status'] == "aberto"){ $icon = "fa-cogs";}
    else                        { $icon = "fa-eye"; }

    echo "<td class='actions text-center'>
            <a href='oct/index.php?id_workshift=".$d['id']."' class='btn btn-xs btn-default loading2'><i class='fa ".$icon."'></i></a>
          </td>";

    if($d['status'] == "aberto"){
      echo "<tr class='warning'><td colspan='6'>Turno(s) fechado(s):</td></tr>";
      echo "<tr>
        <th><i>Turno</i></th>
        <th><i>Período</i></th>
        <th width='".$td_width."'><i>Abertura</i></th>
        <th width='".$td_width."'><i>Fechamento</i></th>
        <th><i>Observações</i></th>
        <th class='text-center'><i class='fa fa-cogs'></i></th>
      </tr>";
    }

    echo "</tr>";

  }

?>
								  </tbody>
											</table>
										</div>
									</div>
								</section>
							</div>

<? } ?>

<!-- Modal Warning -->
								<!--	<a class="mb-xs mt-xs mr-xs modal-basic btn btn-warning" href="#modalRemover" remover_id="4">Remover 1</a>
                  <a class="mb-xs mt-xs mr-xs modal-basic btn btn-warning" href="#modalRemover" remover_id="5">Remover 2</a>-->

									<div id="modalRemover" class="modal-block modal-header-color modal-block-warning mfp-hide">
										<section class="panel">
											<header class="panel-heading">
												<h2 class="panel-title">Atenção</h2>
											</header>
											<div class="panel-body">
												<div class="modal-wrapper">
													<div class="modal-icon">
														<i class="fa fa-warning"></i>
													</div>
													<div class="modal-text">
														<h4>Você tem certeza que deseja remover este cadastro?</h4>
														<p>Esta operação é permanente.</p>
													</div>
												</div>
											</div>
											<footer class="panel-footer">
												<div class="row">
													<div class="col-md-12 text-right">
                            <button class="btn btn-warning modal-confirm">Remover</button>
														<button class="btn btn-default modal-dismiss">Cancelar</button>
													</div>
												</div>
											</footer>
										</section>
									</div>
</section>
<script>
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
        url: "usuarios/sqls.php",
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
