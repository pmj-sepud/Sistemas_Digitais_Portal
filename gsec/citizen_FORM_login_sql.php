<?
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");
$schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
$agora = now();

if($_POST['username'] != "" && $_POST['username'] != "Endereço de e-mail")
{
   unset($_POST['novoacesso']);
   $_POST['enable_login']   = ($_POST['status']!="Concedido"?'f':'t');
   if($_POST['password']    ==""){unset($_POST['password']);}
   else{ $_POST['password'] = md5($_POST['password']);  }
   cleanString();
   if($_POST['novoacesso']=="sim"){
           logger("Inserção","Acesso APP", "Cadastro e/ou liberação de acesso");
           $sql = makeSql("{$schema}gsec_citizen_login",$_POST,"ins","id_citizen");
   }else{
           logger("Atualização","Acesso APP", "Cadastro e/ou liberação de acesso");
           $sql = makeSql("{$schema}gsec_citizen_login",$_POST,"upd","id_citizen");
   }
   pg_query($sql)or die("Error ".__LINE__." - Query:{$sql}");
}

   header("Location: citizen_FORM.php?nav=login&id={$_POST['id_citizen']}");


function cleanString(){
   $utf8 = array('/[\']/u' => ' ');
   foreach ($_POST as $key => $value) {
      $_POST[$key] = preg_replace(array_keys($utf8), array_values($utf8), $value);
   }
}
?>
