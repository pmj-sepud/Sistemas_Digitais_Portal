<?
session_start();
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");
$schema      = ($_SESSION['schema']?$_SESSION['schema'].".":"");

$arq = str_replace("../gsec/","",$_GET['arq']);

if(unlink($arq))
{
   $sql = "DELETE FROM {$schema}gsec_files WHERE id_callcenter= '{$_GET['id']}' AND file_path = '{$_GET['arq']}'";
   pg_query($sql)or die("SQL Error ".__LINE__);
   header("Location: callcenter_FORM.php?id={$_GET['id']}&tab=fotos");
}
?>
