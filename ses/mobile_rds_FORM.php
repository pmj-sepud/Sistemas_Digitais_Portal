<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora = now();

  if($_GET['tab']!=""){$tabs[$_GET['tab']]="active"; }else{ $tabs['tab0']="active";}

  if($_GET['id']!="")
  {
    $acao = "Atualizar";
    logger("Acesso","SES-PNCD - Atualização - Registro diário de serviço");
    //--------------------------------------------------------------------//
    $sql = "SELECT * FROM {$schema}ses_pncd_registro_diario R
            WHERE id = '{$_GET['id']}'";
    $res = pg_query($sql)or die("Error ".__LINE__."<br>Query: {$sql}");
    $d   = pg_fetch_assoc($res);
    //--------------------------------------------------------------------//
    $sql = "SELECT A.*, S.name as street_name
            FROM {$schema}ses_pncd_registro_diario_atividade A
            LEFT JOIN {$schema}streets S ON S.id = A.id_logradouro
            WHERE id_ses_pncd_registro_diario = '{$_GET['id']}'";
    $res = pg_query($sql)or die("Error ".__LINE__."<br>Query: {$sql}");
    if(pg_num_rows($res)){ while($x = pg_fetch_assoc($res)){$a[]=$x;} }
  }else{
    $acao = "Inserir";
    logger("Acesso","SES-PNCD - Inserção - Registro diário de serviço");
  }

?>


<section role="main">
   <header class="page-header" style="top:0px;left:0px">
    <h2>Registro diário de serviço</h2>
    <div style='position: absolute;top: 8px;right: 10px;'>
      <a href='auth/logout.php' ajax="false"><button type="button" class="btn btn-default">Sair</button></a>
   </div>
   </header>

  <section class="panel box_shadow" style="margin-top:50px">


  		<div class="panel-body">


        <div class="row">
          <div class="col-md-12">

            <div class="tabs tabs-primary">
                      <ul class="nav nav-tabs">
                        <li class="<?=$tabs['tab0'];?>">
                          <a href="#tab0" data-toggle="tab" ajax="false"><i class="fa fa-check-square-o"></i> Informações</a>
                        </li>
                        <? if($acao=="Atualizar"){?>
                            <li class="<?=$tabs['tab1'];?>">
                              <a href="#tab1" data-toggle="tab" ajax="false"><i class="fa fa-search"></i> Atividades</a>
                            </li>
                        <? } ?>
                      </ul>

                      <div class="tab-content box_shadow">

                              <div id="tab0" class="tab-pane <?=$tabs['tab0'];?>" style="margin-right:10px">
                                  <? require_once('mobile_rds_FORM_tab0.php');?>
                              </div>


                              <div id="tab1" class="tab-pane <?=$tabs['tab1'];?>" style="margin-right:10px">
                                  <? require_once('mobile_rds_FORM_tab1.php');?>
                              </div>


                      </div>
            </div>

          </div>
        </div>



  </section><!--  <section class="panel box_shadow">-->
</section>


<script>
$('.select2').select2();
$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> Aguarde");});
$(".loading2").click(function(){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i>");});

</script>
