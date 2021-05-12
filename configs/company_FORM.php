<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");

  if($_GET['id'] != "")
  {
    $acao = "atualizar";
    $sql  = "SELECT C.*
             FROM {$schema}company C
             WHERE
              	C.id = '{$_GET['id']}'";
    $res  = pg_query($sql)or die("Erro ".__LINE__);
    $d    = pg_fetch_assoc($res);

    logger("Acesso","Órgãos - visualizadetalhado", "Acesso aos dados: [".$_GET["id"]."] - ".$d['name']);

  }else {
    $acao = "inserir";
  }

  if($nav==""){ $nav_dados = "active"; }


?>
<section role="main" class="content-body">
    <header class="page-header">
    <h2>Órgãos e setores</h2>
        <div class="right-wrapper pull-right" style='margin-right:15px;'>
            <ol class="breadcrumbs">
                <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
                <li><span class='text-muted'>Configurações</span></li>
                <li><a href="config/company.php"><span>Órgãos</span></a></li>
                <li><span class='text-muted'>Visualização detalhada</span></li>
            </ol>
        </div>
    </header>

    <!-- start: page -->
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="tabs">
                <ul class="nav nav-tabs tabs-primary">
                    <li class="<?=$nav_dados;?>"><a href="#dados_cadastrais" data-toggle="tab" ajax='false'>Dados Cadastrais</a></li>
                    <? if($acao == "atualizar"){ ?>
                          <? if($_GET['e_setor']!="sim"){ ?>
                             <li class="<?=$nav_trab;?>"><a href="#setores" data-toggle="tab" ajax='false'>Setores</a></li>
                          <? } ?>
                         <li class="<?=$nav_acess;?>"><a href="#funcionarios" data-toggle="tab" ajax='false'>Funcionários</a></li>
                    <? } ?>
                </ul>


                <div class="tab-content">
                    <div id="dados_cadastrais" class="tab-pane <?=$nav_dados;?>">
                        <? require_once("company_dadoscadastrais_FORM.php");?>
                    </div>
                    <div id="setores" class="tab-pane">
                        <? require_once("company_setores.php");?>
                    </div>
                    <div id="funcionarios" class="tab-pane">
                        <? require_once("company_funcionarios.php");?>
                    </div>
                </div><!-- <div class="tab-content"> -->
            </div><!-- <div class="tabs"> -->
        </div><!-- <div class="col-md-12 col-lg-12"> -->
    </div><!-- <div class="row"> -->
    <!-- end: page -->
</section><!-- 	<section role="main" class="content-body"> -->
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


$(document).ready(function(){

    $(window).scrollTop(0);

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

    $('#tabela_dinamica2').DataTable({
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

   $('#tabela_dinamica3').DataTable({
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
