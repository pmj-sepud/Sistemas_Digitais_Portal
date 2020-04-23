<?php
session_start();
require_once("../libs/php/configs.php");
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");

extract($_POST);
$_SESSION['auth'] = "false";

if($modulo=="devops")
{
	$schema_session = getenv('SCHEMA_DEV');
	$schema = (getenv('SCHEMA_DEV')?getenv('SCHEMA_DEV').".":"");
}else{
	$schema_session = getenv('SCHEMA');
	$schema = (getenv('SCHEMA')?getenv('SCHEMA').".":"");
}

if((isset($username) && trim($username) != "") && (isset($password) && trim($password) != "")){
	$res = pg_prepare($conn_neogrid, "qry1", "SELECT U.id, U.name, U.area, U.job, U.active, U.in_ativaction, U.phone, U.cpf, U.date_of_birth, C.name as company_name, C.acron as company_acron, C.id as id_company, P.value as permissoes FROM ".$schema."users U JOIN ".$schema."company C ON C.id = U.id_company LEFT JOIN ".$schema."users_rel_perm_user P ON P.id_user = U.id WHERE U.email = $1 AND U.password = md5($2)");
	$res = pg_execute($conn_neogrid, "qry1", array($username,$password));
	if(pg_num_rows($res)==1)
	{
		$d = pg_fetch_assoc($res);
		if($d['active'] == 't' && $d['in_ativaction'] == 'f')
		{
			if($d['permissoes']!="")
			{
				$d['permissoes'] = (array) json_decode(codificar($d['permissoes'],'d'));
			}

			$_SESSION 				  = $d;
			$_SESSION['auth']   = "true";
			$_SESSION['origem'] = $_POST['modulo'];
			$_SESSION['schema'] = $schema_session;
			logger("Login",$_POST['modulo'],"URL ORIGEM: ".$_SERVER['HTTP_REFERER']);

			$sql = "SELECT * FROM ".$schema."company_configs WHERE id_company = '".$d['id_company']."'";
			$res = pg_query($sql)or die("SQL Error: ".$sql);
			while($cc = pg_fetch_assoc($res))
			{
				$_SESSION['company_configs'][$cc['name']]=$cc['value'];
			}

		}
		if($d['active'] == 'f')				{ $_SESSION['error'] = "Este usuário não esta ativo no sistema.";}
		if($d['in_ativaction'] == 't'){ $_SESSION['error'] = "Aguardando liberação de acesso.";}
	}else{ $_SESSION['error'] = "E-mail ou senha podem estar errados.";}
}else{   $_SESSION['error'] = "Usuário ou senha não podem estar em branco.";}



if($_SESSION['auth']=="true"){
		if($modulo == "SERP"){
			if(check_perm("4_9"))
			{
					header("Location: ../index_erg.php?modulo=".$modulo);
			}else
			{
					$_SESSION["error"] = "Sem permissão de acesso para módulo de operação do SERP.<br>Em caso de dúvida contate o administrador do sistema.";
					header("Location: ../index.php");
			}
		}else{

			header("Location: ../index_sistema.php?modulo=".$modulo);

/*
			if($modulo!="devops")
			{
					header("Location: ../index_sistema.php?modulo=".$modulo);
			}else{
				if($_SESSION['id']==1)
				{
						header("Location: ../index_sistema.php?modulo=".$modulo);
				}else {
						$_SESSION["error"] = "Sem permissão de acesso para módulo de treinamento e testes de sistema.<br>Aguarde, em fase de implantação.<br>Em caso de dúvida contate o administrador do sistema.";
						header("Location: ../index.php");
				}
			}
*/
		}
}else{
		header("Location: ../index.php");
}

?>
