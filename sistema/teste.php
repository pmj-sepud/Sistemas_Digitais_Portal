<?
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");
$schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
$agora  = now();

?>

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

      </div>
  </div>

  <div class="row">
        <div class="col-md-12">


<?
  print_r_pre($agora);
  echo "<pre>";
    if(check_perm("7_23", "C")){ echo "C - OK<br>"; }else{ echo "C - Sem permissão<br>"; }
    if(check_perm("7_23", "R")){ echo "R - OK<br>"; }else{ echo "R - Sem permissão<br>"; }
    if(check_perm("7_23", "U")){ echo "U - OK<br>"; }else{ echo "U - Sem permissão<br>"; }
    if(check_perm("7_23", "D")){ echo "D - OK<br>"; }else{ echo "D - Sem permissão<br>"; }
    if(check_perm("7_23", "CRUD")){ echo "Geral - OK"; }else{ echo "Geral - Sem permissão"; }
  echo "</pre>";




    $table = "ses_pe";
    $sql   = "SELECT * FROM {$schema}{$table} LIMIT 1";
    $res   = pg_query($sql)or die("Error ".__LINE__);

    $i = pg_num_fields($res);
    echo "<pre>";
    for ($j = 0; $j < $i; $j++) {

        $field = pg_field_name($res, $j);

          echo "<code>&lt;div class='form-group'&gt;
            &lt;label class='col-md-2 control-label' for='{$field}'&gt;{$field}&lt;/label&gt;
            &lt;div class='col-md-10'&gt;
              &lt;input type='text' class='form-control' id='{$field}' name='{$field}' placeholder='' value='&lt;&#63;&#61;&#36;d['{$field}'];&#63;&gt' &gt;
            &lt;/div&gt;
          &lt;/div&gt;</code><br><br>";
    }
    echo "</pre>";
  /*
  <div class="form-group">
    <label class="col-md-2 control-label" for="name">Apelido</label>
    <div class="col-md-10">
      <input type="text" class="form-control" id="nickname" name="nickname" placeholder='Nome de guerra' value="<?=$d['nickname'];?>">
    </div>
  </div>
  */
?>

        </div>
  </div>

</section>
<script>
</script>
