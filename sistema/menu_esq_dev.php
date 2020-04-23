<?
session_start();
session_start();
require_once("libs/php/funcoes.php");
require_once("libs/php/conn.php");
?>
<style>
.not-allowed {cursor: not-allowed;}
</style>
<aside id="sidebar-left" class="sidebar-left hidden-print">

  <div class="sidebar-header">
    <div class="sidebar-title">
      Menu do Sistema:
    </div>
    <div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
      <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
    </div>
  </div>

  <div class="nano">
    <div class="nano-content">
      <nav id="menu" class="nav-main" role="navigation">
        <ul class="nav nav-main">
          <li class="nav-active">
            <a href="index_sistema.php" ajax="false">
              <i class="fa fa-home" aria-hidden="true"></i><span>Home</span>
            </a>
          </li>


          <li class="nav-parent">
            <a><i class="fa fa-bank" aria-hidden="true"></i><span>Aplicações</span></a>
            <ul class="nav nav-children">



                <li class="nav-parent">
                    <a><span>Sistema ROTSS</span></a>
                    <ul class="nav nav-children">

                      <li class="nav-parent">
                          <a><span>Operação</span></a>
                          <ul class="nav nav-children">
                              <li><a href="oct/ocorrencias.php?rotss_nav_filtro_data_reset=true" menuautoclose="true">Ocorrências do dia</a></li>
                              <li><a href="oct/ocorrencias_todas.php" menuautoclose="true">Ocorrências Abertas</a></li>
                              <li><a href="oct/eventos_administrativos_INDEX.php" menuautoclose="true">Diário administrativo</a></li>

                              <? if($_SESSION['id']==1|| $_SESSION['id']==10){ ?>
                                  <li><a href="oct/dashboard_oc.php" menuautoclose="true">Dashboard - Ocorrências <sup><small>(DEV)</small></sup></a></li>
                                  <li><a href="oct/guarnicao_USERFORM.php" menuautoclose="true">Guarnição <sup><small>(DEV)</small></sup></a></li>
                              <? } ?>
                          </ul>
                      </li>
                      <li class="nav-parent">
                          <a><span>Gestão</span></a>
                          <ul class="nav nav-children">
                              <li><a href="oct/index.php" menuautoclose="true">Sistema</a></li>
                              <li><a href="oct/turnos_INDEX.php" menuautoclose="true">Turnos</a></li>
                          </ul>
                     </li>

                     <li class="nav-parent">
                         <a><span>Relatórios</span></a>
                         <ul class="nav nav-children">
                              <?
                              if(check_perm("3_15"))//ROTSS - Relatórios
                              {
                                  echo "<li><a href='oct/dashboard.php' menuautoclose='true'>Evolução mensal</a></li>";
                                  echo "<li><a href='oct/rel_export_rotss_csv.php' menuautoclose='true'>Exportação CSV</a></li>";
                              }else{
                                echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Evolução mensal</a></li>";
                                echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Exportação CSV</a></li>";
                              }
                              if($_SESSION['id_company']==8)
                              {
                                    echo "<li class='nav-parent'>
                                              <a><span>SAMU</span></a>
                                              <ul class='nav nav-children'>";
                                    if(check_perm("3_15"))
                                    {
                                              echo "<li><a href='oct/dashboard_SAMU.php' menuautoclose='true'>Evolução mensal</a></li>";
                                              echo "<li><a href='oct/rel_olostech_SAMU.php' menuautoclose='true'>Contagem de atendimentos</a></li>";
                                    }else {
                                              echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Evolução mensal</a></li>";
                                              echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Contagem de atendimentos</a></li>";
                                    }
                                    echo "</ul></li>";
                              }
                              ?>
                          </ul>
                      </li>
                        <li class="nav-parent">
                          <a><span>Configurações</span></a>
                          <ul class="nav nav-children">
                            <? if(check_perm("3_16","R")){ echo "<li><a href='oct/agenda_de_endereco_INDEX.php' menuautoclose='true'>Agenda de endereço</a></li>"; }else{ echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Agenda de endereço</a></li>"; }?>
                          </ul>
                        </li>
                    </ul>
              </li>



          

            </ul>
          </li>
<script>
function abre_monitoramento()
{
    	var params = ['height='+screen.height,'width='+(screen.width-10), 'fullscreen=yes'].join(',');
	    var newTab =window.open('','_blank',params);
      newTab.location = "../monitoramento/exec.php";
      newTab.focus();
}
function fullscreen() {
var isInFullScreen = (document.fullscreenElement && document.fullscreenElement !== null) ||
(document.webkitFullscreenElement && document.webkitFullscreenElement !== null) ||
(document.mozFullScreenElement && document.mozFullScreenElement !== null) ||
(document.msFullscreenElement && document.msFullscreenElement !== null);

var docElm = document.documentElement;
if (!isInFullScreen) {
if (docElm.requestFullscreen) {
    docElm.requestFullscreen();
} else if (docElm.mozRequestFullScreen) {
    docElm.mozRequestFullScreen();
} else if (docElm.webkitRequestFullScreen) {
    docElm.webkitRequestFullScreen();
} else if (docElm.msRequestFullscreen) {
    docElm.msRequestFullscreen();
}
$("#icofulls").removeClass('fa-expand').addClass('fa-compress');
$("#linkfulls").attr('title','Tela normal');
} else {
if (document.exitFullscreen) {
    document.exitFullscreen();
} else if (document.webkitExitFullscreen) {
    document.webkitExitFullscreen();
} else if (document.mozCancelFullScreen) {
    document.mozCancelFullScreen();
} else if (document.msExitFullscreen) {
    document.msExitFullscreen();
}
$("#icofulls").removeClass('fa-compress').addClass('fa-expand');
$("#linkfulls").attr('title','Tela cheia');
}
}
</script>

<!--

          <li class="nav-parent">
            <a><i class="fa fa-database" aria-hidden="true"></i><span>OpenData Mobilidade</span></a>
            <ul class="nav nav-children">
              <li><a href="sistema/conteudo0.php">Convênios <sup><small>(Em Desenvolvimento)</small></sup></a></li>
              <li><a href="sistema/conteudo0.php">Relatórios <sup><small>(Em Desenvolvimento)</small></sup></a></li>
              <li><a href="sistema/conteudo0.php">Configurações <sup><small>(Em Desenvolvimento)</small></sup></a></li>
              <li><a href="sistema/conteudo0.php">Auditoria <sup><small>(Em Desenvolvimento)</small></sup></a></li>
            </ul>
          </li>
-->
          <li class="nav-parent">
            <a><i class="fa fa-cogs" aria-hidden="true"></i><span>Configurações</span></a>
            <ul class="nav nav-children">
              <?
                  if(check_perm("1_1","R")){ echo "<li><a href='usuarios/index.php' menuautoclose='true'>Usuários</a></li>"; }else{ echo "<li><a href='#' menuautoclose='true'  class='not-allowed'><i class='fa fa-lock'></i> Usuários</a></li>"; }
                  if(check_perm("2_3"))    { echo "<li><a href='sistema/logviewer.php' menuautoclose='true'>Logs do sistema</a></li>";}else{echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Logs do sistema</a></li>"; }
                  if(check_perm("2_5"))    { echo "<li><a href='sistema/teste.php' menuautoclose='true'>Desenvolvimento</a></li>";}else{echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Desenvolvimento</a></li>"; }
              ?>
            </ul>
          </li>

<li class="" id="menu_logout">
  <a href="auth/logout.php" ajax="false">
    <i class="fa fa-power-off" aria-hidden="true"></i><span>Logout</span>
  </a>
</li>


        </ul>
      </nav>



    </div>

  </div>

</aside>
