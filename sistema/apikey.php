<?
session_start();
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");
$schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");


logger("Acesso","Gerador API Key Zeladoria");
?>
<section role="main" class="content-body">
  <header class="page-header">
    <h2>Gerador de chave da API de Zeladoria</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span>Gerador de chaves</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>

  <!-- start: page -->
  <div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
      <section class="panel">
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12">
               <div class="row">
                    <div class='col-sm-6 text-center'>
                       <?
                        echo "Seu IP é <b>{$_SERVER['REMOTE_ADDR']}</b>";
                       ?>
                    </div>
               </div>
              <div class="row" style="margin-top:30px">
                 <div class='col-sm-6'>
                       <form method="post" action="sistema/apikey.php">
                          <div class="form-group">
                             <label class="col-md-2 control-label" for="name">Nome:</label>
                             <div class="col-md-10">
                                <input type="text" class="form-control" id="Name" name="Name" placeholder='Nome completo'>
                             </div>
                          </div>
                          <div class="form-group">
                             <label class="col-md-2 control-label" for="ip">End. IP:</label>
                             <div class="col-md-10">
                                <input type="text" class="form-control" id="authip" name="authip" placeholder='Endereço IP da estação ou do gateway'>
                             </div>
                          </div>
                          <div class="form-group text-center">
                             <button type="submit" class='btn btn-primary'>Gerar chave</button>
                          </div>
                       </form>
                  </div>
                  <div class='col-sm-6'>
                     <? if($_POST['Name']!="" && $_POST['authip']!=""){

                           echo "Nome: ".$_POST['Name'];
                           echo ", Endereço IP: ".$_POST['authip'];
                           $key = codificar(json_encode($_POST),'c');
                           echo "<div class='alert alert-warning' style='word-break:break-all;'><b>Chave gerada:</b><br>{$key}</div>";

                     }else{
                        echo "<div class='alert alert-info text-center'>Gerador de chave de acesso para API RESTFULL do sistema de Zeladoria Urbana da cidade de Joinville.</div>";
                     }
                     ?>
                  </div>
               </div>




            </div>
          </div>
        </div>
    </section>
  </div>
</div>
</section>
