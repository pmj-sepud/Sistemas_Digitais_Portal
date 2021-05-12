<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  logger("Acesso","Usuários");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");

if(check_perm("1_1","U")){

?>
<style>.link:hover{ cursor: pointer; }</style>
<script>
   $(".link").click(function(){ $('#wrap').load("usuarios/FORM.php?id="+$(this).attr("id"));});
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

  if($_GET['filtro_status']!="inativos"){ $filtro[] = " U.active = 't'";                             }
  else{                                   $filtro[] = " U.active = 'f'";                             }
  if($_GET['filtro_orgaos']!= "todos") {  $filtro[] = " id_company = '".$_SESSION['id_company']."'"; }

  $sql = "SELECT
          	C.name as ccompany_name, C.acron as ccompany_acron,
          	U.*
          FROM
          	   ".$schema."users   U
          JOIN ".$schema."company C ON C.id = U.id_company
          WHERE  ".implode(" AND ", $filtro)."
          ORDER BY C.acron, U.name ASC";
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

                      <? if(check_perm("1_1","C")){ ?>
                      <a href="usuarios/FORM_novo_usuario.php">
                        <button type="button" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary loading"><i class="fa fa-user-plus"></i> Novo usuário</button>
                      </a>
                     <? } ?>
                      <?
                        echo "<a href='usuarios/index.php?filtro_orgaos=".$_GET['filtro_orgaos']."&filtro_status=".($_GET['filtro_status']!=""?"":"inativos")."'>
                              <button type='button' class='mb-xs mt-xs mr-xs btn btn-xs btn-danger loading'><i class='fa fa-users'></i> Ver ".($_GET['filtro_status']!=""?"ativos":"inativos")."</button>
                              </a>";


                        if(check_perm("1_2")){
                              echo  "<a href='usuarios/index.php?filtro_orgaos=".($_GET['filtro_orgaos']!=""?"":"todos")."&filtro_status=".$_GET['filtro_status']."'>
                                     <button type='button' class='mb-xs mt-xs mr-xs btn btn-xs btn-success loading'><i class='fa fa-users'></i> Ver ".($_GET['filtro_orgaos']!=""?"meu órgão":"todos os órgãos")."</button>
                                     </a>";
                        }

                    ?>
                      <!--<a href="#" ic-get-from="sistema/logs.php" ic-target="#wrap" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary"><i class="fa fa-user-plus"></i> Novo usuário !</a>-->
									  </div>
                  </header>
									<div class="panel-body">
										<div class="table-responsive">
											<table class="table table-hover mb-none" id="tabela_dinamica">
												<thead>
													<tr>
														<th>#</th>
														<th>Nome</th>
														<th>Nome de guerra</th>
                            <th>Cargo</th>
                            <th>Horario</th>
                            <th>Turno</th>
                            <th>Sub-turno</th>
                            <th>Inicia como</th>
                            <th class='text-center'>Setor</th>
                            <!--<th class='text-center'>Setor</th>
                            <th class='text-center'>Função</th>-->
                            <th class='text-center'>Ativo</th>
                            <!--<th class='text-center'>Em ativação</th>-->
                            <th class='text-center'>Último acesso</th>
                            <th class='text-center'><i class='fa fa-cogs'></i></th>
													</tr>
												</thead>
												<tbody>
<?
  while($d = pg_fetch_assoc($rs))
  {
    //array2utf8($d);
    echo "<tr id='".$d['id']."' class='link'>";
    echo "<td class='text-muted'>".$d['id']."</td>";
    echo "<td nowrap>".$d['name']."</td>";
    echo "<td>".$d['nickname']."</td>";
    echo "<td>".$d['job']."</td>";
    echo "<td nowrap>".($d['workshift_group_time_init']!="" && $d['workshift_group_time_finish'] != "" ? substr($d['workshift_group_time_init'],0,5)." às ".substr($d['workshift_group_time_finish'],0,5):"<span class='text-muted'>- - -</span>")."</td>";
    echo "<td>".$d['workshift_group']."</td>";
    echo "<td>".$d['workshift_subgroup']."</td>";
    echo "<td>".$traducao[$d['initial_workshift_position']]."</td>";
    echo "<td class='text-center'>".$d['ccompany_acron']."</td>";
    //echo "<td class='text-center'>".$d['area']."</td>";
    //echo "<td class='text-center'>".$d['job']."</td>";

    echo "<td class='text-center'>";
      echo ($d['active'] == 't'?"<i class='fa fa-check-square-o'></i>":"<i class='fa fa-square-o'></i>");
    echo "</td>";
/*
    echo "<td class='text-center ".($d['in_ativaction'] == 't'?"warning":"")."'>";
      echo ($d['in_ativaction'] == 't'?"<i class='fa fa-check-square-o'></i>":"<i class='fa fa-square-o'></i>");
    echo "</td>";
*/
    echo "<td class='text-center'>".formataData($d['ultimo_acesso'],1)."</td>";

    echo "<td class='actions text-center' nowrap>";
   //   if(check_perm("1_1","U")){ echo "<a href='usuarios/FORM.php?id=".$d['id']."'><i class='fa fa-pencil'></i></a>"; }else{ echo " <span class='text-muted'><i class='fa fa-lock'></i></span>";}
   //   if(check_perm("1_1","D")){ echo "<a href='usuarios/FORM_sql.php?acao=remover&id=".$d['id']."' class='delete-row'><i class='fa fa-trash-o'></i></a>"; }else{ echo " <span class='text-muted'><i class='fa fa-lock'></i></span>";}
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
