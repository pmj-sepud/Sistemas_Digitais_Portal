<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");

  if($_GET['id'] != "")
  {
    $acao = "atualizar";
    $sql  = "SELECT C.name AS name_company,
            			  C.acron AS acron_company,
            			  C.workshift_groups_repetition,
            			  C.workshift_groups,
                    C.workshift_subgroups_repetition,
            			  C.workshift_subgroups,
			              U.*
             FROM ".$schema."users U
	           JOIN ".$schema."company C ON C.id = U.id_company
             WHERE
              	U.id = '".$_GET['id']."'";
    $res  = pg_query($sql)or die("Erro ".__LINE__);
    $d    = pg_fetch_assoc($res);

    $sql = "SELECT
                R.value
              FROM
                ".$schema."users_rel_perm_user R
              WHERE
                R.id_user = '".$_GET['id']."'";
     $res = pg_query($sql)or die("SQL Error ".__LINE__);

     if(pg_num_rows($res))
     {
       $p               = pg_fetch_assoc($res);
       $userperms_resum = (array) json_decode(codificar($p['value'],'d'));
     }

     logger("Acesso","Perfil de usuário", "Acesso aos dados: [".$_GET["id"]."] - ".$d['name']);

     if($_GET['nav']!=""){ $nav[$_GET['nav']] = "active"; }else{$nav['dados']="active";}
  }
?>
<style>
.link:hover{ cursor: pointer; }
.dataTables_filter { width: 50%; float: right; text-align: right; }
.dataTables_wrapper .dt-buttons { float:right; }
</style>
				<section role="main" class="content-body">
					<header class="page-header">
						<h2>Perfil do Usuário</h2>

						<div class="right-wrapper pull-right" style='margin-right:15px;'>
							<ol class="breadcrumbs">
								<li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
				        <li><span class='text-muted'>Configurações</span></li>
				        <li><a href="usuarios/index.php"><span>Usuários</span></a></li>
								<li><span class='text-muted'>Perfil do usuário</span></li>
							</ol>
						</div>
					</header>

					<!-- start: page -->

					<div class="row">
						<div class="col-md-12 col-lg-12">

							<div class="tabs">
								<ul class="nav nav-tabs tabs-primary">
									<li class="<?=$nav['dados'];?>">
										<a href="#dados" data-toggle="tab" ajax='false'>Dados</a>
									</li>
                  <li class="<?=$nav['trabalho'];?>">
										<a href="#trabalho" data-toggle="tab" ajax='false'>Trabalho</a>
									</li>
                  <li class="<?=$nav['acesso'];?>">
										<a href="#acesso" data-toggle="tab" ajax='false'>Acesso</a>
									</li>
                  <li class="<?=$nav['permissoes'];?>">
										<a href="#permissoes" data-toggle="tab" ajax='false'>Permissões</a>
									</li>
                  <li class="<?=$nav['logs'];?>">
										<a href="#logs" data-toggle="tab" ajax='false'>Log</a>
									</li>
                </ul>


								<div class="tab-content">
<!--------------------------------------------->
<!--------------------------------------------->
                                    <? require_once("../usuarios/FORM_tab_dados.php");?>

                                    <? require_once("../usuarios/FORM_tab_trabalho.php");?>

                                    <? require_once("../usuarios/FORM_tab_acesso.php");?>

                                    <? require_once("../usuarios/FORM_tab_permissoes.php"); ?>

                                    <? require_once("../usuarios/FORM_tab_logs.php");?>
<!--------------------------------------------->
<!--------------------------------------------->

						</div>

					<!-- end: page -->
				</section>
<script>

$(".crud").click(function(){

    var str     = $(this).attr('id').split("_");
    var perm    = str[0];
    var str_bin = "";

    if($("#"+perm+"_c").is(":checked")){str_bin += "1";}else{str_bin += "0";}
    if($("#"+perm+"_r").is(":checked")){str_bin += "1";}else{str_bin += "0";}
    if($("#"+perm+"_u").is(":checked")){str_bin += "1";}else{str_bin += "0";}
    if($("#"+perm+"_d").is(":checked")){str_bin += "1";}else{str_bin += "0";}

    //var hex = parseInt(str_bin, 2).toString(16);
    //$("#"+perm).val(hex.toUpperCase());

    $("#"+perm).val(str_bin);

});


$(document).ready( function () {
   var table = $('#tabela').DataTable({
      mark: true,
      responsive: true,
      stateSave: true,
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
                },
    "order": [[0,"asc"]],
    dom: 'Bflrtip',
    buttons: [
         { extend: 'copyHtml5', text: 'Copiar'},
         'excelHtml5',
         'csvHtml5',
         { extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'A3'}
     ]
    });

    $('.dataTables_filter input').click(function () { $(this).val('');});

});



$(".campo_hora").mask('00:00');
$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde.</small>"); });
$("#userform").on("submit", function(){
  if($("#email").val()        == "Endereço de e-mail"){ $("#email").val('');}
  if($("#senha").val()        == "nova_senha")        { $("#senha").val('');}
  if($("#senha_repete").val() == "nova_senha")        { $("#senha_repete").val('');}
});
$('.select2').select2({
  language: {
        noResults: function() {
          return 'Nenhum resultado encontrado.';
        }
      }
});
</script>
