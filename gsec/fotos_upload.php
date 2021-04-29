<?
session_start();
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");

$schema      = ($_SESSION['schema']?$_SESSION['schema'].".":"");
$agora       = now();
$dirbase     = "../gsec/uploads/";
$dir         = $dirbase.$_POST['id'];
$ret['info'] = $_POST;

$nome  = str_replace("-","",$agora['datatimesrv']);
$nome  = str_replace(":","",$nome);
$nome  = str_replace(" ","_",$nome);
$nome .= ".jpg";
$arq   = $dir."/".$nome;

if(!file_exists($dir)){  mkdir($dir, 0777, true); }

$img  = $_POST['img'];
$img  = str_replace('data:image/jpeg;base64,', '', $img);
$img  = str_replace(' ', '+', $img);
$data = base64_decode($img);

$success = file_put_contents($arq, $data);

if($success)
{
   $sql = "INSERT INTO {$schema}gsec_files(id_callcenter, file_path) VALUES('{$_POST['id']}', '{$arq}')";
   pg_query($sql)or die(json_encode(array('success' => false, 'status' => "SQL error: ".$sql)));

   $ret['success']=true;
   $ret['status'] = $nome;
}else{
   $ret['success']=false;
   $ret['status'] = $nome;
}



echo json_encode($ret);
?>
