<?
session_start();
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");


$schema      = ($_SESSION['schema']!="" ?$_SESSION['schema'].".":"");
$dirdev      = ($_SESSION['origem']=="devops"?"dev/"           :"");
$id_oc       = $_POST["id_oc"];
$dir         = "uploads/".$dirdev.$id_oc;
$agora       = now();
$nome        = str_replace("-","",$agora['datatimesrv']);
$nome        = str_replace(":","",$nome);
$nome        = str_replace(" ","_",$nome);
$nome       .= ".jpg";
$arq         = "ROTSS_".$nome;
$ret['info'] = $_POST;

logger("Inserção","OCT - imagens", "Ocorrência n.".$id_oc);

if(!file_exists($dir)){  mkdir($dir, 0777, true); }

$img  = $_POST['img'];
$img  = str_replace('data:image/jpeg;base64,', '', $img);
$img  = str_replace(' ', '+', $img);
$data = base64_decode($img);

$success = file_put_contents($dir."/".$arq, $data);

if($success)
{
   $sql = "INSERT INTO ".$schema."oct_rel_events_images(id_events, image, path, timestamp)
   VALUES (".$id_oc.",'".$arq."','".$dir."','".$agora['datatimesrv']."')";
   pg_query($sql)or die(json_encode(array('success' => false, 'status' => "SQL error: ".$sql)));

   $ret['success']=true;
   $ret['status'] = $nome;
}else{
   $ret['success']=false;
   $ret['status'] = $nome;
}


echo json_encode($ret);
?>
