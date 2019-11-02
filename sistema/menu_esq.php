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
                                        <li><a href="waze/index.php" menuautoclose="true">Dashboard</a></li>
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
                                <li><a href="tomtom/index.php" menuautoclose="true">Dashboard</a></li>
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
                    <a><span>Sistema ROTSS</span></a>
                    <ul class="nav nav-children">

                      <li class="nav-parent">
                          <a><span>Operação</span></a>
                          <ul class="nav nav-children">
                              <li><a href="oct/ocorrencias.php" menuautoclose="true">Ocorrências</a></li>
                              <li><a href="oct/eventos_administrativos_INDEX.php" menuautoclose="true">Diário administrativo</a></li>

                              <? if($_SESSION['id']==1){ ?>
                                  <li><a href="oct/guarnicao_USERFORM.php" menuautoclose="true">Guarnição</a></li>
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
                              }else{
                                echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Evolução mensal</a></li>";
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

          <li class="nav-parent">
            <a><span>SERP</span></a>
            <ul class="nav nav-children">
                <?
                    if(check_perm("4_10"))    { echo "<li><a href='erg/index.php' menuautoclose='true'>Dashboard</a></li>"; }else{ echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Dashboard</a></li>"; }
                    if(check_perm("4_11"))    { echo "<li><a href='erg/rel_autuados.php' menuautoclose='true'>Autuações</a></li>"; }else{ echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Autuações</a></li>"; }
                    if(check_perm("4_12","R")){ echo "<li><a href='erg/vagas.php' menuautoclose='true'>Vagas</a></li>"; }else{ echo "<li><a href='#' menuautoclose='true' class='not-allowed'><i class='fa fa-lock'></i> Vagas</a></li>"; }
                ?>
            </ul>
          </li>

            </ul>
          </li>


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
