<?
    session_start();
    require_once("../libs/php/funcoes.php");
    require_once("../libs/php/conn.php");

    extract($_POST);

    if($acao=="inserir")
    {
      if($registration == ""){ $registration = "Null"; }else{ $registration = "'".$registration."'";}
      if($senha != "nova_senha" && trim($senha) != "" && ($senha == $senha_repete)){ $senhasql1 =   "MD5('".$senha."')"; }else{ $senhasql1 = "Null"; }
      if(trim($email) == ""){ $email = "Null"; }else{ $email = "'".$email."'";}

      $sql =  "INSERT INTO sepud.users(
                      name,
                      email,
                      password,
                      area,
                      job,
                      active,
                      id_company,
                      observation,
                      phone,
                      in_ativaction,
                      nickname,
                      registration)
              VALUES (
                     '".$name."',
                     ".$email.",
                     ".$senhasql1.",
                    '".$area."',
                    '".$job."',
                    't',
                    '".$id_company."',
                    '".$observation."',
                    '".$phone."',
                    'f',
                    '".$nickname."',
                    ".$registration.") RETURNING id";
       $res = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
       $aux = pg_fetch_assoc($res);

       logger("Inserção","Usuário", "Inseriu o usuário: [".$aux["id"]."] - ".$name);

       header("Location: FORM.php?id=".$aux['id']);
       exit();
    }


    if($acao=="atualizar")
    {
        if($senha != "nova_senha" && $senha != "" && ($senha == $senha_repete)){ $senhasql =   "password    = MD5('".$senha."'),"; }
        if($registration == ""){ $registration = "Null"; }else{ $registration = "'".$registration."'";}
        if($email == ""){ $email = "Null"; }else{ $email = "'".$email."'";}

        $sql =  "UPDATE sepud.users SET
                        name         = '".$name."',
                        email        = ".$email.",
                        ".$senhasql."
                        area         = '".$area."',
                        job          = '".$job."',
                        id_company   = '".$id_company."',
                        phone        = '".$phone."',
                        observation  = '".$observation."',
                        active       = '".$active."',
                        nickname     = '".$nickname."',
                        registration = ".$registration."
                  WHERE id = '".$id."'";
      pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
      logger("Atualização","Usuário","Atualizou dados do usuário: [".$id."] - ".$name);
      header("Location: FORM.php?id=".$id);
      exit();
  }

    extract($_GET);

    if($acao == "remover" && $id != "")
    {
      $sql = "UPDATE sepud.users SET active = false WHERE id = '".$id."'";
      pg_query($sql)or die("Erro ".__LINE__);
      logger("Baixa","Usuário","Baixou o usuário: [".$id."]");
      header("Location: index.php");
      exit();
    }

    if($id != ""){ header("Location: FORM.php?id=".$id);      }
             else{ header("Location: FORM_novo_usuario.php"); }
    exit();
?>
