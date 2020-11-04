<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora = now();


  logger("Acesso","Cidadão - Visualização detalhada");

  extract($_GET);
  if($id != "")
  {
      $acao = "Atualizar";
      $sql = "SELECT * FROM {$schema}sas_citizen WHERE id = '{$id}'";
      $res = pg_query($sql)or die("SQL error ".__LINE__);
      $d   = pg_fetch_assoc($res);
  }else {
      $acao = "Inserir";
  }
?>

<section role="main" class="content-body">

  <header class="page-header">
    <h2>Cadastro do cidadão</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>SAS-BEV</span></li>
        <li><a href="sas/cidadao.php">Cidadão</a></li>
        <li><span class='text-muted'>Visualização detalhada</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>

	<section class="panel box_shadow">
			<header class="panel-heading" style="height:70px">
        <div class="panel-actions" style="margin-top:5px">
          <a href="sas/cidadao.php"><button type="button" class="btn btn-default">Voltar</button></a>
        </div>
        <h2 class="panel-title">Título</h2>
        <p class="panel-subtitle">Subtítulo</p>
      </header>
      <div class="panel-body">
									Conteudo.
			</div>
      <div class="panel-footer">
			     rodapé
      </div>
  </section>
</section>


<script>
$('.select2').select2({
  language: {
        noResults: function() {
          return 'Nenhum resultado encontrado.';
        }
      }
});
$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> Aguarde");});
$(".loading2").click(function(){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i>");});
</script>
