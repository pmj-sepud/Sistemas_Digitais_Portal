<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");

  $sql = "SELECT * FROM ".$schema."oct_workshift WHERE id_company = '".$_SESSION['id_company']."' ORDER BY opened DESC";
  $rs  = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);

  $turno_aberto = false;
  while($tmp = pg_fetch_assoc($rs))
  {
      $dados[] = $tmp;
      if($tmp['status'] == "aberto"){ $turno_aberto = true; $id_turno = $tmp['id'];}
      $turnos[$tmp['status']][] = $tmp;
      $qtd_turno_por_datas[substr($tmp['opened'],0,10)]++;
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
    </div>
  </header>

<div class="col-md-12">
								<section class="panel box_shadow">
									<header class="panel-heading" style="height:50px">
                    <div class="panel-actions" style='margin-top:-10px;'>
                      <a href='oct/turno.php'>
                        <button type='button' class='btn btn-info'><i class="fa fa-file-text-o"></i> <sup><i class="fa fa-plus"></i></sup> Novo turno</button>
                      </a>
									  </div>
                  </header>
									<div class="panel-body">
										<div class="table-responsive">

                      <table class="table table-hover mb-none">
                        <thead>
                          <tr class='success'><td colspan='3'>Turno aberto:</td><td colspan='3' class='text-right'><b><?=(isset($turnos['aberto'])?count($turnos['aberto']):"0");?></b> turno(s)</td></tr>
                        </thead>
                        <tbody>
                            <?
                                unset($i, $d);
                                if(isset($turnos['aberto']) && count($turnos['aberto']))
                                {
                                    echo "<tr>
                                            <td width='10px'><small><i>Turno</i></small></td>
                                            <td width='10px'><small><i>Grupo</i></small></td>
                                            <td width='10px'><small><i>Abertura</i></small></td>
                                            <td width='10px'><small><i>Fechamento</i></small></td>
                                            <td><small><i>Observações</i></small></td>
                                            <td width='10px' class='text-center'><i class='fa fa-cogs'></i></td>
                                          </tr>";
                                    for($i = 0; $i<count($turnos['aberto']);$i++)
                                    {
                                      $d = $turnos['aberto'][$i];
                                      echo "<tr>";
                                      echo "<td><b>".$d['id']." </b></td>";
                                      echo "<td nowrap>".ucfirst($d['workshift_group'])."</td>";
                                      echo "<td nowrap>".formataData($d['opened'],1)."</td>";
                                      echo "<td nowrap>".formataData($d['closed'],1)."</td>";
                                      echo "<td>".$d['observation']."</td>";

                                      if($d['status'] == "aberto"){ $icon = "fa-cogs";}
                                      else                        { $icon = "fa-eye"; }

                                      echo "<td class='actions text-center'>
                                              <a href='oct/index.php?id_workshift=".$d['id']."' class='btn btn-default loading2'><i class='fa ".$icon."'></i></a>
                                            </td>";

                                      echo "</tr>";
                                  }
                                }else {
                                  echo "<tr><td colspan='6'><div class='alert alert-warning text-center'><i>Nenhum turno aberto.</i></div></td></tr>";
                                }
                            ?>
                        </tbody>
                      </table>

                      <table class="table table-hover mb-none">
                        <thead>
                          <tr class='warning'><td colspan='3'>Turno(s) fechado(s):</td><td colspan='3' class='text-right'><b><?=(isset($turnos['fechado'])?count($turnos['fechado']):"0");?></b> turno(s)</td></tr>
                        </thead>
                        <tbody>
                            <?
                                unset($i, $d);
                                if(isset($turnos['fechado']) && count($turnos['fechado']))
                                {
                                    echo "<tr>
                                            <td width='10px'><small><i>Turno</i></small></td>
                                            <td width='10px'><small><i>Grupo</i></small></td>
                                            <td width='10px'><small><i>Abertura</i></small></td>
                                            <td width='10px'><small><i>Fechamento</i></small></td>
                                            <td><small><i>Observações</i></small></th>
                                            <td width='10px' class='text-center'><i class='fa fa-cogs'></i></td>
                                          </tr>";
                                    for($i = 0; $i<count($turnos['fechado']);$i++)
                                    {
                                      $d = $turnos['fechado'][$i];
                                      if($qtd_turno_por_datas[substr($d['opened'],0,10)] > 1){ $bg_color="background:#FFFFD0";}else{ $bg_color = "";}
                                      echo "<tr>";
                                      echo "<td><b>".$d['id']."</b></td>";
                                      echo "<td nowrap>".ucfirst($d['workshift_group'])."</td>";
                                      echo "<td nowrap style='".$bg_color."'>".formataData($d['opened'],1)."</td>";
                                      echo "<td nowrap>".formataData($d['closed'],1)."</td>";
                                      echo "<td>".$d['observation']."</td>";

                                      if($d['status'] == "aberto"){ $icon = "fa-cogs";}
                                      else                        { $icon = "fa-eye"; }

                                      echo "<td class='actions text-center'>
                                              <a href='oct/index.php?id_workshift=".$d['id']."' class='btn btn-default loading2'><i class='fa ".$icon."'></i></a>
                                            </td>";

                                      echo "</tr>";
                                  }
                                  echo "<tr><td></td><td></td><td style='background:#FFFFD0'></td><td colspan='3' calss='text-muted'><i><small><b>Legenda: </b>Mais de um turno com mesma data inicial.</small></i></td></tr>";
                                }else {
                                  echo "<tr><td colspan='6'><div class='alert alert-warning text-center'><i>Nenhum turno fechado.</i></div></td></tr>";
                                }
                            ?>
                        </tbody>
                      </table>

                      <table class="table table-hover mb-none">
                        <thead>
                          <tr class='info'><td colspan='3'>Turno(s) inativo(s):</td><td colspan='3' class='text-right'><b><?=(isset($turnos['inativo'])?count($turnos['inativo']):"0");?></b> turno(s)</td></tr>
                        </thead>
                        <tbody>
                            <?
                                unset($i, $d);
                                if(isset($turnos['inativo']) && count($turnos['inativo']))
                                {
                                    echo "<tr>
                                            <td width='10px'><small><i>Turno</i></small></td>
                                            <td width='10px'><small><i>Grupo</i></small></td>
                                            <td width='10px'><small><i>Abertura</i></small></td>
                                            <td width='10px'><small><i>Fechamento</i></small></td>
                                            <td><small><i>Observações</i></small></td>
                                            <td width='10px' class='text-center'><i class='fa fa-cogs'></i></td>
                                          </tr>";
                                    for($i = 0; $i<count($turnos['inativo']);$i++)
                                    {
                                      $d = $turnos['inativo'][$i];
                                      if($qtd_turno_por_datas[substr($d['opened'],0,10)] > 1){ $bg_color="background:#FFFFD0";}else{ $bg_color = "";}
                                      echo "<tr>";
                                      echo "<td class='text-muted'>".$d['id']."</td>";
                                      echo "<td nowrap>".ucfirst($d['workshift_group'])."</td>";
                                      echo "<td nowrap style='".$bg_color."'>".formataData($d['opened'],1)."</td>";
                                      echo "<td nowrap>".formataData($d['closed'],1)."</td>";
                                      echo "<td>".$d['observation']."</td>";

                                      if($d['status'] == "aberto"){ $icon = "fa-cogs";}
                                      else                        { $icon = "fa-eye"; }

                                      echo "<td class='actions text-center'>
                                              <a href='oct/index.php?id_workshift=".$d['id']."' class='btn btn-default loading2'><i class='fa ".$icon."'></i></a>
                                            </td>";
                                      echo "</tr>";
                                  }
                                  echo "<tr><td></td><td></td><td style='background:#FFFFD0'></td><td colspan='3' calss='text-muted'><i><small><b>Legenda: </b>Mais de um turno com mesma data inicial.</small></i></td></tr>";
                                }else {
                                  echo "<tr><td colspan='6'><div class='alert alert-warning text-center'><i>Nenhum turno inativo.</i></div></td></tr>";
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
