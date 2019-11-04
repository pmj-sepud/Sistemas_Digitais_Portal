<?
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");

exit();
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
                $sql = "SELECT U.id, U.name, P.value as permissoes FROM sepud.users U
                        LEFT JOIN sepud.users_rel_perm_user P ON P.id_user = U.id
                        WHERE U.id_company = 3";
                $res = pg_query($sql)or die("SQL Error: ".__LINE__."<br>"."Query: ".$sql);
                while($d = pg_fetch_assoc($res))
                {
                  echo "<br>".$d['id']." - ".$d['name'];
                  if($d['permissoes']!="")
                  {
                              unset($userperms);
                              $userperms = (array) json_decode(codificar($d['permissoes'],'d'));
                              if($userperms['4_9']!="1")
                              {
                                  echo " - Incluindo permissão";
                                  $userperms["4_9"] = 1;
                              }else{
                                echo " - Usuário já possui permissão";
                              }
                  }else{
                        echo " - Incluindo NOVA permissão";
                        $userperms = array("4_9" => 1);
                      //echo "<h4>Usuário sem permissões, criando nova:</h4>";
                      //print_r_pre($userperms);
                  }
                  print_r_pre($userperms);
                  $arr = codificar(json_encode($userperms),'c');
                  echo "<hr>".$arr."<hr>";
                  $sql = "INSERT INTO sepud.users_rel_perm_user (id_user, value) VALUES ('".$d['id']."', '".$arr."')
                          ON CONFLICT (id_user) DO UPDATE SET value = excluded.value";
                  pg_query($sql)or die("SQL Error ".__LINE__."<br>Query: ".$sql);
                }

            ?>

        </div>
  </div>

</section>
<script>
</script>
