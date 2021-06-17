<?php
session_start();
require_once("../libs/php/configs.php");
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");

extract($_POST);
$_SESSION['auth'] = "false";

if($modulo=="devops"){
	$schema_session  =  trim(getenv('SCHEMA_DEV'));
	$schema 				= (getenv('SCHEMA_DEV')?trim(getenv('SCHEMA_DEV')).".":"");
}else{
	$schema_session = trim(getenv('SCHEMA'));
	$schema = (getenv('SCHEMA')?trim(getenv('SCHEMA')).".":"");
}

if((isset($username) && trim($username) != "") && (isset($password) && trim($password) != "")){
	$res = pg_prepare($conn_neogrid, "qry1", "SELECT U.id, U.name, U.area, U.job, U.active, U.in_activation, U.phone, U.cpf, U.date_of_birth, U.registration,
																									 C.name as company_name, C.acron as company_acron, C.id as id_company, C.id_father as id_company_father,
																									 P.value as permissoes
																						FROM {$schema}users U
																						JOIN {$schema}company C ON C.id = U.id_company
																						LEFT JOIN {$schema}users_rel_perm_user P ON P.id_user = U.id
																						WHERE U.email = $1 AND U.password = md5($2)");
	$res = pg_execute($conn_neogrid, "qry1", array($username,$password));
	if(pg_num_rows($res)==1)
	{
		$d = pg_fetch_assoc($res);
		if($d['active'] == 't')
		{
			if($d['in_activation'] == 'f')
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
									if($cc['name']=="oct_form" && $_SESSION['id']==1)
									{
										$_SESSION['company_configs'][$cc['name']]=$cc['value'];
									}else {
										$_SESSION['company_configs'][$cc['name']]=$cc['value'];
									}

								}
			}else{
				$_SESSION=$d;
				//$_SESSION['change_pass']=true;
				header("Location: ../change_pass.php");
				exit();
			}

		}
		if($d['active'] == 'f')				{ $_SESSION['error'] = "Este usuário não esta ativo no sistema.";}
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
		}elseif($modulo=="PNCD"){
			if(check_perm("8_26"))
			{
				header("Location: ../index_pncd.php?modulo=".$modulo);
			}else{
				$_SESSION["error"] = "Sem permissão de acesso para módulo de operação em campo do PNCD - Combate a Dengue.<br>Em caso de dúvida contate o administrador do sistema.";
				header("Location: ../index.php");
			}
		}else{

			header("Location: ../index_sistema.php?modulo=".$modulo);


		}
}else{
		header("Location: ../index.php");
}

?>
