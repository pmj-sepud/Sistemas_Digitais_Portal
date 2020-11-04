<?
  session_start();
  //error_reporting(0);
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora = now();
  if(isset($_GET['tab'])){ $tabac[$_GET['tab']]="active"; }else{$tabac['tab0']="active";}





  extract($_GET);
  if($id != "")
  {
    $acao = "Atualizar";
    $sql = "SELECT CI.*,
                   U.name as name_user_register,
                   C.name as company_user_register
            FROM {$schema}sas_citizen CI
            LEFT JOIN {$schema}users   U ON U.id = CI.id_user_register
            LEFT JOIN {$schema}company C ON C.id = U.id_company
            WHERE CI.id = '{$id}'";
    $res = pg_query($sql)or die("SQL error ".__LINE__);
    $d   = pg_fetch_assoc($res);

    $sql = "SELECT * FROM {$schema}sas_request WHERE id_citizen = '{$d['id']}' ORDER BY id DESC";
    $res = pg_query($sql)or die("SQL error ".__LINE__."<br>Query: ".$sql);

    while($aux = pg_fetch_assoc($res))
    {
      if($aux['status']=="Aberto")
      {
        $beneficio_aberto[] = $aux;
      }else{
        $beneficio_fechado[] = $aux;
      }

    }
    $r   = pg_fetch_assoc($res);
    logger("Acesso","SAS - BEV", "Cidadão - Visualização detalhada de ID: ".$id);
  }else {
      $acao = "Inserir";
      logger("Inserção","SAS - BEV", "Cidadão");
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
        <li><span class='text-muted'><?=($acao=="Atualizar"?"Visualização detalhada":"Novo cadastro");?></span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>

  <div class="row">
						<div class="col-md-12">
							<div class="tabs tabs-primary">
								<ul class="nav nav-tabs">
									<li class="<?=$tabac['tab0'];?>">
										<a href="#tab0" data-toggle="tab" ajax="false"><i class="fa fa-user"></i> Cadastro</a>
									</li>

<? if($acao=="Atualizar"){ ?>
<? /* ?>
                  <li class="<?=$tabac['tab1'];?>">
										<a href="#tab1" data-toggle="tab" ajax="false"><i class="fa fa-check-square-o"></i> Avaliação</a>
									</li>
                  <li class="<?=$tabac['tab2'];?>">
										<a href="#tab2" data-toggle="tab" ajax="false"><i class="fa fa-search"></i> Busca ativa</a>
									</li>
<? if(check_perm("7_23","R")){ ?>
                  <li class="<?=$tabac['tab3'];?>">
										<a href="#tab3" data-toggle="tab" ajax="false"><i class="fa fa-cart-plus"></i> Entrega</a>
									</li>
<? } */ ?>
                  <li class="<?=$tabac['tab4'];?>">
										<a href="#tab4" data-toggle="tab" ajax="false"><i class="fa fa-dedent"></i> Histórico de benefícios e atendimentos</a>
									</li>
<? } ?>
								</ul>

								<div class="tab-content box_shadow">
									<div id="tab0" class="tab-pane <?=$tabac['tab0'];?>" style="margin-right:10px">
                      <? require_once('cidadao_FORM_tab0.php');?>
									</div>
    <? /* ?>
									<div id="tab1" class="tab-pane <?=$tabac['tab1'];?>" style="margin-right:10px">
                      <? require_once('cidadao_FORM_tab1.php');?>
									</div>
                  <div id="tab2" class="tab-pane <?=$tabac['tab2'];?>" style="margin-right:10px">
                      <? require_once('cidadao_FORM_tab2.php');?>
									</div>
                  <div id="tab3" class="tab-pane <?=$tabac['tab3'];?>" style="margin-right:10px">
                      <? require_once('cidadao_FORM_tab3.php');?>
									</div>
    <? } */ ?>
                  <div id="tab4" class="tab-pane <?=$tabac['tab4'];?>" style="margin-right:10px">
                      <? require_once('cidadao_FORM_tab4.php');?>
									</div>
								</div>
							</div>
						</div>
  </div>


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
