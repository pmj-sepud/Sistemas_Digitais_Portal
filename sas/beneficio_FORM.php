<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora  = now();
  if(isset($_GET['tab'])){ $tabac[$_GET['tab']]="active"; }else{$tabac['tab0']="active";}

  extract($_GET);
  if($id_request != "")
  {
    $acao = "Atualizar";

    $sql = "SELECT CI.*,
                   U.name as name_user_register,
                   C.name as company_name, C.acron as company_acron,
                   R.*
            FROM {$schema}sas_citizen CI
            JOIN {$schema}users       U ON U.id = CI.id_user_register
            JOIN {$schema}company     C ON C.id = U.id_company
            JOIN {$schema}sas_request R ON R.id_citizen = CI.id AND R.id = {$id_request}
            WHERE CI.id = '{$id_citizen}'";
    $res = pg_query($sql)or die("<div class='text-center'>SQL error ".__LINE__."<br>Query: {$sql}</div>");
    $d   = pg_fetch_assoc($res);

    $requerente = array("name"          =>$d['name'],
                        "birth"         =>$d['birth'],
                        "rg"            => $d['rg'],
                        "cpf"           => $d['cpf'],
                        "company_name"  =>$d['company_name'],
                        "company_acron" => $d['company_acron']);

    $demandsel      = json_decode($d['demand']);
    $demandstatus   = json_decode($d['demand_status']);
    $varssel        = json_decode($d['vars']);

    logger("Acesso","SAS - BEV", "Benefícios - Visualização detalhada");
  }else{
      $demandstatus = array("-","-","-");
      $sql = "SELECT CI.*,
                     C.name as company_name, C.acron as company_acron
              FROM {$schema}sas_citizen CI
              JOIN {$schema}company C ON C.id = CI.id_company_register
              WHERE CI.id = '{$id_citizen}'";
      $res = pg_query($sql)or die("SQL error ".__LINE__."<br>Query: ".$sql);
      $requerente = pg_fetch_assoc($res);
      $acao = "Inserir";
      logger("Inserção","SAS - BEV", "Benefícios");
  }



?>
<section role="main" class="content-body">
  <header class="page-header">
    <h2>Cadastro de benefício</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>SAS-BEV</span></li>
        <li><a href="sas/cidadao.php">Cidadão</a></li>
<? if($acao=="Atualizar"){ ?>
        <li><span class='text-muted'>Manutenção de benefício</span></li>
<? }else{ ?>
    <li><span class='text-muted'>Cadastro de um novo benefício</span></li>
<? }?>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>

  <div class="row">
						<div class="col-md-12">
							<div class="tabs tabs-primary">
								<ul class="nav nav-tabs">

                  <li class="<?=$tabac['tab0'];?>">
										<a href="#tab0" data-toggle="tab" ajax="false"><i class="fa fa-check-square-o"></i> Avaliação</a>
									</li>
<? if($acao=="Atualizar"){

          if($d['active_search']=="t")
          {
?>
                  <li class="<?=$tabac['tab1'];?>">
										<a href="#tab1" data-toggle="tab" ajax="false"><i class="fa fa-search"></i> Busca ativa</a>
									</li>
<?        } ?>

<?
  if((isset($demandsel)  && in_array("alimentacao",$demandsel)) && ($demandstatus[0]=="Aberto" || $demandstatus[0]=="Fechado"))
  {
?>
                  <li class="<?=$tabac['tab2'];?>">
										<a href="#tab2" data-toggle="tab" ajax="false"><i class="fa fa-cart-plus"></i> Entrega</a>
									</li>
<? }

} ?>
								</ul>

								<div class="tab-content box_shadow">


									<div id="tab0" class="tab-pane <?=$tabac['tab0'];?>" style="margin-right:10px">
                      <? require_once('beneficio_FORM_tab0.php');?>
									</div>

<? if($acao=="Atualizar"){

        if($d['active_search']=="t")
        {
?>
                  <div id="tab1" class="tab-pane <?=$tabac['tab1'];?>" style="margin-right:10px">
                    <? require_once('beneficio_FORM_tab1.php');?>
									</div>
<?      }  ?>

<?
  if(isset($demandsel)  && in_array("alimentacao",$demandsel)?"checked":"")
  {
?>
                  <div id="tab2" class="tab-pane <?=$tabac['tab2'];?>" style="margin-right:10px">
                    <? require_once('beneficio_FORM_tab2.php');?>
									</div>
<?
 }
} ?>
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
$(".loading").click(function(){  $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> Aguarde");});
$(".loading2").click(function(){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i>");});
</script>
