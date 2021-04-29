<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  logger("Acesso","Usuários");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");

?>
<?
if(check_perm("2_20","U")){
?>
   <style>.link:hover{ cursor: pointer; }</style>
   <script>
      $(".link").click(function(){ $('#wrap').load("configs/company_FORM.php?id="+$(this).attr("id"));});
   </script>
<? }else{ ?>
   <style>.link:hover{ cursor: not-allowed; }</style>
<? } ?>


<section role="main" class="content-body">
  <header class="page-header">
    <h2>Listagem de usuários do sistema</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>Configurações</span></li>
        <li><span class='text-muted'>Usuários</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>


<?
  if($_GET['filtro_status']=="inativos"){ $fitrosql = "C.active = 'f'";}
                                    else{ $fitrosql = "C.active = 't'";}

   $sql = "SELECT C.* FROM {$schema}company C WHERE {$fitrosql} AND id_father is null ORDER BY name ASC";
   $rs  = pg_query($conn_neogrid,$sql);

?>
<div class="col-md-12">
								<section class="panel">
									<header class="panel-heading">
                    <div class="panel-actions" style='margin-top:-12px'>

                      <? if(check_perm("2_20","C")){ ?>
                      <a href="configs/company_FORM.php">
                        <button type="button" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary loading"><i class="fa fa-user-plus"></i> Novo órgão</button>
                      </a>
                     <? } ?>

                     <?
                      echo "<a href='configs/company.php?filtro_status=".($_GET['filtro_status']!=""?"":"inativos")."'>
                           <button type='button' class='mb-xs mt-xs mr-xs btn btn-xs btn-".($_GET['filtro_status']!=""?"success":"danger")." loading'><i class='fa fa-".($_GET['filtro_status']!=""?"level-up":"level-down")."'></i> Ver ".($_GET['filtro_status']!=""?"ativos":"inativos")."</button>
                           </a>";
                     ?>

									  </div>
                  </header>
									<div class="panel-body">
										<div class="table-responsive">
											<table class="table table-hover mb-none" id="tabela_dinamica">
												<thead>
													<tr>
														<th>#</th>
														<th>Órgão/Entidade</th>
														<th>Apelido</th>
                                          <th class='text-center'>Ativo</th>
                                          <th class='text-center'>É externo</th>
                                          <!--<th class='text-center'><i class='fa fa-cogs'></i></th>-->
													</tr>
												</thead>
												<tbody>
<?
  while($d = pg_fetch_assoc($rs))
  {
    //array2utf8($d);
    echo "<tr class='link' id='".$d['id']."'>";
    echo "<td class='text-muted'>".$d['id']."</td>";
    echo "<td nowrap>".$d['name']."</td>";
    echo "<td>".$d['acron']."</td>";

    echo "<td class='text-center'>";
      echo ($d['active'] == 't'?"<i class='fa fa-check-square-o'></i>":"<i class='fa fa-square-o'></i>");
    echo "</td>";

    echo "<td class='text-center'>";
      echo ($d['is_external'] == 't'?"<i class='fa fa-check-square-o'></i>":"<i class='fa fa-square-o'></i>");
    echo "</td>";

/*
    echo "<td class='actions text-center' nowrap>";
      if(check_perm("2_20","U")){ echo "<a href='configs/company_FORM.php?id=".$d['id']."'><i class='fa fa-pencil'></i></a>"; }else{ echo " <span class='text-muted'><i class='fa fa-lock'></i></span>";}
    echo "</td>";
*/
    echo "</tr>";
  }

?>
								  </tbody>
											</table>
										</div>
									</div>
								</section>
							</div>



<script>
//$(".loading").click(function(event){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde</small>");});
$(document).ready( function () {
    $('#tabela_dinamica').DataTable({
      mark: true,
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
