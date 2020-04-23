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



            $schema     = getenv('SCHEMA');
            $schema_dev = getenv('SCHEMA_DEV');
            $waze_url   = getenv('WAZE_URL');

            //print_r_pre($schema);
            //print_r_pre($schema_dev);
            print_r_pre($waze_url);
            $waze_url = base64_decode($waze_url);
            print_r_pre($waze_url);
            //print_r_pre($_SESSION);

            echo "<br>- Buscando dados: ";
            $json  = file_get_contents($waze_url);
            $d = json_decode(json_encode(json_decode($json)), True); //Conversão Obj to array
            echo  "ok.<br>";
            print_r_pre($d);

            ?>


        </div>
  </div>

</section>
<script>
</script>
<?

function makeSql($table, $fieldvals, $type, $returning="")
{
    switch ($type) {
      case 'ins':
              foreach ($fieldvals as $key => $val)
              {
                $keys[] = $key;
                $vals[] = ($val!=""?"'".$val."'":"Null");
              }

              $sql = "INSERT INTO ".$table." (".implode(", ", $keys).") VALUES (".implode(", ",$vals).") ".($returning!=""?"RETURNING ".$returning:"");
      break;
      case 'upd':
              foreach ($fieldvals as $key => $val)
              {
                if($val!="")
                {
                  $upds[] = $key."='".$val."'";
                }else {
                  $upds[] = $key."=Null";
                }
              }
              if($returning != "")
              {
                $sql = "UPDATE ".$table." SET ".implode(", ",$upds)." WHERE ".$returning;
              }
      break;

      default:
        break;
    }
  return $sql;
}

?>
