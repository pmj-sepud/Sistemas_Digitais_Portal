<?
	session_start();
	require("libs/php/funcoes.php");
	require("libs/php/conn.php");
	if($_SESSION['id']==""){ header("Location: index.php");}

	if($_POST['id']!="")
	{
		if($_POST['pass00']!="" && $_POST['pass01'] !="" && $_POST['matricula']!="")
		{
			if($_POST['matricula']!=$_SESSION['registration']){
					$error = "<div class='text-danger text-center'>AVISO:  <b> Matrícula não conferem.</b></div>";
			}else{
				if($_POST['pass00'] == $_POST['pass01'])
				{
						$error = "<div class='text-success text-center'><b>Senha alterada com sucesso.</b></div>";
						$alterada = true;

						$res = pg_prepare($conn_neogrid, "qry2", "UPDATE sepud.users SET password = md5($2), in_activation = ($3) WHERE id = $1");
						$res = pg_execute($conn_neogrid, "qry2", array($_POST['id'],$_POST['pass00'],'f'));

				}else {
					$error = "<div class='text-danger text-centr'><b> As senhas digitadas devem ser iguais.</b></div>";
				}
			}
		}else {
			$error = "<div class='text-danger text-center'><b> Campos não podem ficar em branco.</b></div>";
		}
	}

?>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<!DOCTYPE html>
<html>
<head>
	<title>Sistemas Digitais</title>
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />

   <!--Made with love by Mutiullah Samim -->

	<!--Bootsrap 4 CDN-->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!--Fontawesome CDN-->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

	<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
	<!--Custom styles-->
	<style>
  /* Made with love by Mutiullah Samim*/

html,body{
/*background-image: url('http://getwallpapers.com/wallpaper/full/a/5/d/544750.jpg');*/
/*background-image: url("assets/images/PanoramicaJoinvilleCentro.jpg");*/
background-image: url("assets/images/joinville_rev0.jpg");

background-size: cover;
background-repeat: no-repeat;
height: 100%;
font-family: 'Lato', sans-serif;
}



  </style>
</head>
<body>
<div class="container">
<div class="row">
	<div class="col-md-6 offset-md-3">

<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
								<div class="card">
								  <div class="card-header">
								    Este é seu primeiro acesso, altere a sua senha:
								  </div>
								  <div class="card-body">

											<? if(!$alterada){ ?>
																	<form class="form-signin" autocomplete="off" method="post">
																		<input autocomplete="off" name="hidden" type="text" style="display:none;">
																	<p>&nbsp;</p>
																	<p><h1 class="h5 mb-3 font-weight-normal text-center"><small>Seja bem-vindo(a)</small><br><?=$_SESSION['name'];?></h1></p>
																	<p>&nbsp;</p>

																	<label for="matricula">Confirme sua matricula PMJ <sup>(apenas os números)</sup></label>
																	<input type="matricula00" id="matricula" name="matricula" class="form-control" placeholder="Matrícula" required autofocus value="" autocomplete="off">

																	<label for="pass00">Nova senhas:</label>
																	<input type="password" id="pass00" name="pass00" class="form-control" placeholder="Nova senha" required value="" autocomplete="new-password">

																	<label for="pass01">Repita Nova senha:</label>
																	<input type="password" id="pass01" name="pass01" class="form-control" placeholder="Repita nova senha" required value="" autocomplete="new-password">
																	<br><br>

																	<input type="hidden" name="id"           value="<?=$_SESSION['id'];?>" 					 />

																	<button class='btn btn-primary text-center' type='submit'>Salvar</button>

																	</form>
												<? }else{
 															echo $error."<br>"; $error="";
															echo "<a href='index_sistema.php'><button class='btn  btn-default text-center' type='button'>Voltar para o sistema</button></a>";
												}?>
										</div>
								</div>
</div>
</div>
</div>
</body>
</html>
<script>

</script>
