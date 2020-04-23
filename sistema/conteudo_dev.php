<?
  error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
  session_start();
  require_once("libs/php/funcoes.php");
  require_once("libs/php/sessao.php");
  $agora = now();

  $sql = "SELECT * FROM sepud.resume";
  $res = pg_query($conn_neogrid,$sql);
  while($d = pg_fetch_assoc($res))
  {
    $infos[$d['module']][$d['type']][$d['field']] = $d['int_value'];
  }


?>
<section role="main" class="content-body">

  <header class="page-header">
    <h2>Home</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>

  <!-- start: page -->
  <div class="row">

                      <div class="col-md-12">
                        <div class="row">
                                  <section class="panel">
                                    <header class="panel-heading">
                                      <h3 class="panel-title">Sistemas digitais</h3>
                                    </header>
                                    <div class='panel-body'>
                                      <div class="row">
                                        <div class="col-sm-6 col-sm-offset-3">
                                              <div class="alert alert-warning text-center">
                              										<strong>Área de treinamento.</strong><br>Área específica para testes e treinamento no sistema de ocorrências.<br>Qualquer alteração nesta área não reflete na área principal.
                              									</div>
                                        </div>
                                      </div>
                                    </div>
                                  </section>
                        </div>

</div>
