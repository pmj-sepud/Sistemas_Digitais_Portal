<?
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");
$schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");

if(isset($_GET["searchTerm"]))
{
   $sql = "SELECT id, name as text FROM {$schema}streets WHERE name ilike '%{$_GET['searchTerm']}%' ORDER BY name ASC";
   $res = pg_query($sql)or die();
   while($d = pg_fetch_assoc($res)){ $return[] = $d; }
}else{
   $return[] = array("text"=>"Digite algo para iniciar a pesquisa.");
}
echo json_encode(array("results" => $return));
?>
