<?
  session_start();
?>
<header class="header hidden-print">
  <div class="logo-container">
    <a href="index_sistema.php" class="logo">
      <img src="assets/images/logo.png" height="35" alt="SISTEMAS DIGITAIS" />
    </a>

    <div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
      <i id="menu_bt_top" class="fa fa-bars" aria-label="Toggle sidebar"></i>
    </div>
  </div>
  <div class="header-right hidden-print hidden-xs">
    <? if($_SESSION['origem'] == "devops"){ echo "<span class='text-danger'><b><i>Área de treinamento</i></b></span>"; } ?>
<!----------------------------------------------------->
<?
if($_SESSION['tem_foto'] == "sim")
{
  $imgsrc = "usuarios/foto_ver.php?id=".$_SESSION['userid'];
}else
{
  $imgsrc = "assets/images/icon-user-default.png";
}

?>

    <span class="separator"></span>

    <div id="userbox" class="userbox">
      <a href="#" data-toggle="dropdown">
        <figure class="profile-picture">
          <img src="<?=$imgsrc;?>" alt="<?=$_SESSION['nome'];?>" class="img-circle" data-lock-picture="<?=$imgsrc;?>" />
        </figure>
        <div class="profile-info" data-lock-name="<?=$_SESSION['nome'];?>" data-lock-email="<?=$_SESSION['email'];?>">
          <span class="name"><?=$_SESSION['name'];?></span>
          <span class="role"><?=$_SESSION['job']."-".$_SESSION['company_acron'];?></span>
        </div>

        <i class="fa custom-caret"></i>
      </a>

      <div class="dropdown-menu">
        <ul class="list-unstyled">
          <li class="divider"></li>
          <li>
            <a role="menuitem" tabindex="-1" href='usuarios/FORM_change_pass.php'><i class="fa fa-user"></i> Trocar senha</a>
          </li>
          <li>
            <a role="menuitem" tabindex="-1" href="#" data-lock-screen="true"><i class="fa fa-lock"></i> Bloquear tela</a>
          </li>
          <li>
            <a role="menuitem" tabindex="-1" href="auth/logout.php" ajax="false"><i class="fa fa-power-off"></i> Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</header>
