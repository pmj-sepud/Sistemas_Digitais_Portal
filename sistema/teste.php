<?
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");


?>
<style>
.select2-selection__rendered {
line-height: 32px !important;
}

.select2-selection {
height: 34px !important;
}
</style>
<section role="main" class="content-body">

  <header class="page-header">
    <h2>Página para testes de scripts</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span>Configurações</span></li>
        <li><span>Desenvolvimento</span></li>
      </ol>
    </div>
  </header>

  <div class="row">
        <div class="col-md-12">
            <?
                  $agora = now();
                  print_r_pre($agora);
            ?>

        </div>
  </div>

</section>
<script>
</script>
