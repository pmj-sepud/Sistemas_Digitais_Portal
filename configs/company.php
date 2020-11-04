<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  logger("Acesso","Usuários");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");

?>

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

  if($_GET['filtro_status']!="inativos"){ $filtro[] = " U.active = 't'";                             }
  else{                                   $filtro[] = " U.active = 'f'";                             }
  if($_GET['filtro_orgaos']!= "todos") {  $filtro[] = " id_company = '".$_SESSION['id_company']."'"; }

  $sql = "SELECT
          	C.*
          FROM
          	   ".$schema."company C";
  $rs  = pg_query($conn_neogrid,$sql);

  $traducao["agente"]      = "Agente de campo";
  $traducao["central"]     = "Central de atendimento";
  $traducao["coordenacao"] = "Coordenação";
  $traducao["gerencia"]    = "Gerência";
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

                      <!--<a href="#" ic-get-from="sistema/logs.php" ic-target="#wrap" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary"><i class="fa fa-user-plus"></i> Novo usuário !</a>-->
									  </div>
                  </header>
									<div class="panel-body">
                    <?

                      //  print_r_pre($d);
                        /*
                        [id] => 1
                            [name] => Secretaria de Planejamento Urbano e Desenvolvimento Sustentável
                            [acron] => SEPUD
                            [workshift_groups_repetition] =>
                            [workshift_groups] =>
                            [workshift_subgroups_repetition] =>
                            [workshift_subgroups] =>
                            [workshift_rel_config] =>
                        */

                    ?>
										<div class="table-responsive">
											<table class="table table-hover mb-none" id="tabela_dinamica">
												<thead>
													<tr>
														<th>#</th>
														<th>Órgão/Entidade</th>
														<th>Apelido</th>
                            <th class='text-center'>Ativo</th>
                            <th class='text-center'>É externo</th>
                            <th class='text-center'><i class='fa fa-cogs'></i></th>
													</tr>
												</thead>
												<tbody>
<?
  while($d = pg_fetch_assoc($rs))
  {
    //array2utf8($d);
    echo "<tr id='".$d['id']."'>";
    echo "<td class='text-muted'>".$d['id']."</td>";
    echo "<td nowrap>".$d['name']."</td>";
    echo "<td>".$d['acron']."</td>";

    echo "<td class='text-center'>";
      echo ($d['active'] == 't'?"<i class='fa fa-check-square-o'></i>":"<i class='fa fa-square-o'></i>");
    echo "</td>";

    echo "<td class='text-center'>";
      echo ($d['is_external'] == 't'?"<i class='fa fa-check-square-o'></i>":"<i class='fa fa-square-o'></i>");
    echo "</td>";


    echo "<td class='actions text-center' nowrap>";
      if(check_perm("2_20","U")){ echo "<a href='configs/company_FORM.php?id=".$d['id']."'><i class='fa fa-pencil'></i></a>"; }else{ echo " <span class='text-muted'><i class='fa fa-lock'></i></span>";}
    echo "</td>";

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
