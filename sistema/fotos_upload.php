<?
session_start();

$dir    = "../oct/uploads/dev/";
$uploadfile = $dir . basename($_FILES['uplimg']['name']);

$ret['info'] = $_POST;


$img = $_POST['img'];
$img = str_replace('data:image/jpeg;base64,', '', $img);
$img = str_replace(' ', '+', $img);

$data = base64_decode($img);
$file = $dir.'AAAA.jpg';

$success = file_put_contents($file, $data);
if($success)
{
   $ret['success']=true;
   $ret['status'] = $file;
}else{
   $ret['success']=false;
   $ret['status'] = $file;
}



echo json_encode($ret);
?>
