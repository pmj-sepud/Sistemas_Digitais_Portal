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
          	     <a><i class="fa fa-align-left" aria-hidden="true"></i><span>Convênios</span></a>
          			 <ul class="nav nav-children">
                      <? if(check_perm("5_7")){ ?>
                                  <li class="nav-parent">
                                    <a>Radares</a>
                    							  <ul class="nav nav-children">
                        								  <li><a style="cursor:pointer" href="radar/index.php" menuautoclose="true">Equipamentos</a></li>
                    				        </ul>
                                  </li>
                          <? }else{ ?>
                                  <li><a style="cursor:not-allowed" href="" menuautoclose="true"><i class='fa fa-lock'></i> Radares</a></li>
                          <? } ?>

                      <? if(check_perm("5_6")){ ?>
                              <li class="nav-parent">
                  						    <a>WAZE</a>
                  							  <ul class="nav nav-children">
                                        <li><a href="waze/index.php"           menuautoclose="true">Dashboard</a></li>
                                        <li><a href="waze/evolucao_diaria.php" menuautoclose="true">Evolução Diária</a></li>
                                        <li><a href="waze/mapa.php" menuautoclose="true">Mapa</a></li>
                  				        </ul>
                  						</li>
                    <? }else{ ?>
                            <li><a style="cursor:not-allowed" href="" menuautoclose="true"><i class='fa fa-lock'></i> Waze</a></li>
                    <? } ?>

                      <? if(check_perm("5_8")){ ?>
                      <li class="nav-parent">
          						    <a>TOMTOM</a>
          							  <ul class="nav nav-children">
                                <li><a href="tomtom/trafficflow.php" menuautoclose="true">Fluxo de tráfego</a></li>
                                <li><a href="tomtom/jams.php" menuautoclose="true">Congestionamento</a></li>
          				        </ul>
          						</li>
                    <? }else{ ?>
                            <li><a style="cursor:not-allowed" href="#" menuautoclose="true"><i class='fa fa-lock'></i> TOMTOM</a></li>
                    <? } ?>

          				</ul>
          	   </li>

<? /* ?>
          <li class="nav-parent">
            <a><i class="fa fa-bus" aria-hidden="true"></i><span>Radares</span></a>
            <ul class="nav nav-children">
              <li><a href="#" ic-get-from="radar/index.php"      ic-target="#wrap">Equipamentos</a></li>
              <!--<li><a href="#" ic-get-from="radar/dashboard.php"  ic-target="#wrap">Dashboard</a></li>-->
              <!--<li><a href="#" ic-get-from="#"                    ic-target="#wrap">Mapa</a></li>-->

            </ul>
          </li>

          <li class="nav-parent">
            <a><i class="fa fa-road" aria-hidden="true"></i><span>Waze</span></a>
            <ul class="nav nav-children">
              <li><a href="#" ic-get-from="waze/index.php"      ic-target="#wrap">Dashboard</a></li>
              <li><a href="#" ic-get-from="waze/mapa.php"       ic-target="#wrap">Mapa</a></li>

            </ul>
          </li>

<? */ ?>

          <li class="nav-parent">
            <a><i class="fa fa-bank" aria-hidden="true"></i><span>Aplicações</span></a>
            <ul class="nav nav-children">

                <li class="nav-parent">
                     <a><span>GSEC</span></a>
                     <ul class="nav nav-children">
                              <?
                                 if(check_perm("9_28","CRUD")){ echo "<li><a href='gsec/callcenter.php' menuautoclose='true'>Atendimento</a></li>"; }
                                                         else{ echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Atendimentos</a></li>"; }
                              ?>
                              <li class="nav-parent">
                                   <a><span>Relatórios</span></a>
                                   <ul class="nav nav-children">
                                      <?
                                        if(check_perm("9_30")){ echo "<li><a href='gsec/dashboard.php' menuautoclose='true'>Dashboard</a></li>"; }
                                                          else{ echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Dashboard</a></li>"; }
                                       if($_SESSION['id']==1){ echo "<li><a href='gsec/dashboard_geral.php' menuautoclose='true'>Dashboard - Todos</a></li>"; }
                                      ?>
                                   </ul>
                              </li>

                        <? if($_SESSION['id']==1){ ?>
<!--
                              <li><a href="#" menuautoclose="true">Ordens de Serviço</a></li>
                              <li><a href="#" menuautoclose="true">Contratos</a></li>
                              <li><a href="#" menuautoclose="true">Inventário de estoque</a></li>
                              <li><a href="#" menuautoclose="true">Orçamento</a></li>
-->
                        <? }else{ ?>
<!--
                           <li><a href="#" menuautoclose="true" class="not-allowed" ><i class='fa fa-lock'></i> Atendimentos</a></li>
                           <li><a href="#" menuautoclose="true" class="not-allowed" ><i class='fa fa-lock'></i> Ordens de Serviço</a></li>
                           <li><a href="#" menuautoclose="true" class="not-allowed" ><i class='fa fa-lock'></i> Contratos</a></li>
                           <li><a href="#" menuautoclose="true" class="not-allowed" ><i class='fa fa-lock'></i> Inventário de estoque</a></li>
                           <li><a href="#" menuautoclose="true" class="not-allowed" ><i class='fa fa-lock'></i> Orçamento</a></li>
-->
                        <? } ?>
                     </ul>
                </li>



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
                                  <li><a href="oct/guarnicao_USERFORM.php" menuautoclose="true">Guarnição <sup>dev <i class='fa fa-cogs'></i></sup></a></li>
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

                              if($_SESSION['id']==1){
                                 echo "<li><a href='oct/dashboard_oc.php' menuautoclose='true'>Dashboard - Ocorrências <sup><small>(DEV)</small></sup></a></li>";
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
                            <? if(check_perm("3_16")){ echo "<li><a href='oct/agenda_de_endereco_INDEX.php' menuautoclose='true'>Agenda de endereço</a></li>"; }else{ echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Agenda de endereço</a></li>"; }?>
                            <? if(check_perm("3_19","R")){ echo "<li><a href='oct/frota_INDEX.php' menuautoclose='true'>Frota de veículos</a></li>"; }else{ echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Frota de veículos</a></li>"; }?>
                            <? if($_SESSION['id']==1){ echo "<li><a href='oct/tipos_ocorrencias_INDEX.php' menuautoclose='true'>Tipos de ocorrências <sup>dev <i class='fa fa-cogs'></i></sup></a></li>"; }else{ echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Tipos de ocorrências <sup>dev <i class='fa fa-cogs'></i></sup></a></li>"; }?>

                          </ul>
                        </li>
                    </ul>
              </li>



          <li class="nav-parent">
            <a><span>Monitoramento</span></a>
            <ul class="nav nav-children">
                <?
                    if(check_perm("6_17"))    { echo "<li><a href='monitoramento/index.php' menuautoclose='true'>Configurações</a></li>"; }else{ echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Configurações</a></li>"; }
                    if(check_perm("6_18"))    { echo "<li><a href='#' menuautoclose='true' onClick='abre_monitoramento();'>Executar</a></li>"; }else{ echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Executar</a></li>"; }
                ?>
            </ul>
          </li>

          <li class="nav-parent">
            <a><span>SEPROT - SERP</span></a>
            <ul class="nav nav-children">
                <?
                    if(check_perm("4_10"))    { echo "<li><a href='erg/index.php' menuautoclose='true'>Dashboard</a></li>"; }else{ echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Dashboard</a></li>"; }
                    if(check_perm("4_11"))    { echo "<li><a href='erg/rel_autuados.php' menuautoclose='true'>Autuações</a></li>"; }else{ echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Autuações</a></li>"; }
                    if(check_perm("4_12","R")){ echo "<li><a href='erg/vagas.php' menuautoclose='true'>Vagas</a></li>"; }else{ echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Vagas</a></li>"; }
                ?>
            </ul>
          </li>

          <li class="nav-parent">
            <a><span>SAS - Benefícios Eventuais</span></a><!-- Sec. de Asst. Social - Beneficios Eventuais -->
            <ul class="nav nav-children">
                <?
                    if(check_perm("7_21","R") || check_perm("7_23","R")){ echo "<li><a href='sas/cidadao.php' menuautoclose='true'>1 - Cidadãos cadastrados</a></li>"; }else{ echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> 1 - Cidadãos cadastrados</a></li>"; }
                    if(check_perm("7_21","R") || check_perm("7_23","R")){ echo "<li><a href='sas/beneficio.php' menuautoclose='true'>2 - Benefício em aberto</a></li>"; }else{ echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> 2 - Benefício</a></li>"; }
                ?>

                <li class="nav-parent">
                  <a><span>Relatórios</span></a>
                    <ul class="nav nav-children">
                      <?
                          if(check_perm("7_21","R") || check_perm("7_23","R")){ echo "<li><a href='sas/dashboard.php' menuautoclose='true'>Dashboard</a></li>"; }else{ echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Dashboard</a></li>"; }
                          if(check_perm("7_21","R") || check_perm("7_23","R")){ echo "<li><a href='sas/dashboard_eqp.php' menuautoclose='true'>Equipamento</a></li>"; }else{ echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Equipamento</a></li>"; }
                          if(check_perm("7_21","R") || check_perm("7_23","R")){ echo "<li><a href='sas/rel_atividades_diaria.php' menuautoclose='true'>Atividades diária</a></li>"; }else{ echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Atividades diária</a></li>"; }
                      ?>
                    </ul>
                </li>

                <li class="nav-parent">
                  <a><span>Configurações</span></a>
                    <ul class="nav nav-children">
                      <?
                          if(check_perm("7_22"))    { echo "<li><a href='sas/variaveis.php' menuautoclose='true'>Variáveis de classificação</a></li>"; }else{ echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Variáveis de classificação</a></li>"; }
                      ?>
                    </ul>
                </li>

            </ul>
          </li>


          <li class="nav-parent">
              <a><span>SES - PNCD</span></a>
              <ul class="nav nav-children">
                  <?
                      if(check_perm("8_26")){ echo "<li><a href='ses/rds.php' menuautoclose='true'>Registro diário de serviços</a></li>"; }else{ echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Registro diário de serviços</span></a></li>"; }

                  ?>

                  <li class="nav-parent">
                    <a><span>Armadilhas</span></a>
                      <ul class="nav nav-children">
                          <?   if(check_perm("8_26")){ echo "<li><a href='ses/armadilhas_dashboard.php' menuautoclose='true'>Dashboard</a></li>"; }else{ echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Dashboard</span></a></li>"; } ?>
                          <?   if(check_perm("8_26")){ echo "<li><a href='ses/armadilhas.php' menuautoclose='true'>Cadastro</a></li>"; }else{ echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Cadastro</span></a></li>"; } ?>
                      </ul>
                  </li>


                  <li class="nav-parent">
                    <a><span>Pontos Estratégicos</span></a>
                      <ul class="nav nav-children">
                          <?   //if(check_perm("8_26")){ echo "<li><a href='ses/pontosestrategicos_dashboard.php' menuautoclose='true'>Dashboard</a></li>"; }else{ echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Dashboard</span></a></li>"; } ?>
                          <?   if(check_perm("8_26")){ echo "<li><a href='ses/pontosestrategicos.php' menuautoclose='true'>Cadastro</a></li>"; }else{ echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Cadastro</span></a></li>"; } ?>
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
                  if(check_perm("2_20","R")){ echo "<li><a href='configs/company.php' menuautoclose='true'>Órgãos</a></li>";}else{echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Órgãos</a></li>"; }
                  if(check_perm("2_3"))    { echo "<li><a href='sistema/logviewer.php' menuautoclose='true'>Logs do sistema</a></li>";}else{echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Logs do sistema</a></li>"; }
                  if(check_perm("2_5"))    { echo "<li><a href='sistema/teste.php' menuautoclose='true'>Desenvolvimento</a></li>";}else{echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Desenvolvimento</a></li>"; }
                  if(check_perm("2_5"))    { echo "<li><a href='sistema/fotos.php' menuautoclose='true'>Upload fotos</a></li>";}else{echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Upload fotos</a></li>"; }
                  if(check_perm("2_27"))    { echo "<li><a href='sistema/backups.php' menuautoclose='true'>Backups</a></li>";}else{echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Backups</a></li>"; }


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
