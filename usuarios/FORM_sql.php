<?
    session_start();
    require_once("../libs/php/funcoes.php");
    require_once("../libs/php/conn.php");
    $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");

    extract($_POST);


    if($acao == "permissoes")
    {
      unset($_POST['acao'],$_POST['id']);
      $arr = codificar(json_encode($_POST),'c');
      $sql = "INSERT INTO ".$schema."users_rel_perm_user (id_user, value) VALUES ('".$id."', '".$arr."')
              ON CONFLICT (id_user) DO UPDATE SET value = excluded.value";
      pg_query($sql)or die("SQL Error ".__LINE__."<br>Query: ".$sql);
      logger("Permissões","Usuário", "Atualizou as permissões de ID: ".$id);
      header("Location: FORM.php?nav=permissoes&id=".$id);
      exit();
    }

    if($acao=="inserir")
    {
      if($registration == ""){ $registration = "Null"; }else{ $registration = "'".$registration."'";}
      if($senha != "nova_senha" && trim($senha) != "" && ($senha == $senha_repete)){ $senhasql1 =   "MD5('".$senha."')"; }else{ $senhasql1 = "Null"; }
      if(trim($email) == ""){ $email = "Null"; }else{ $email = "'".$email."'";}
      if($initial_workshift_position == ""){ $initial_workshift_position = "Null"; }else{ $initial_workshift_position = "'".$initial_workshift_position."'";}

      $sql =  "INSERT INTO ".$schema."users(
                      name,
                      email,
                      password,
                      area,
                      job,
                      active,
                      id_company,
                      observation,
                      phone,
                      in_activation,
                      nickname,
                      registration,
                      initial_workshift_position,
                      work_status)
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
                    't',
                    '".$nickname."',
                    ".$registration.",
                    ".$initial_workshift_position.",
                    '".$work_status."') RETURNING id";
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
        if($initial_workshift_position == ""){ $initial_workshift_position = "Null"; }else{ $initial_workshift_position = "'".$initial_workshift_position."'";}

        $workshift_group_time_init      = ($workshift_group_time_init      != "" ? "'".$workshift_group_time_init.":00'"     :"Null");
        $workshift_group_time_finish    = ($workshift_group_time_finish    != "" ? "'".$workshift_group_time_finish.":00'"   :"Null");
        $workshift_group                = ($workshift_group                != "" ? "'".$workshift_group."'"                  :"Null");
        $workshift_subgroup_time_init   = ($workshift_subgroup_time_init   != "" ? "'".$workshift_subgroup_time_init.":00'"  :"Null");
        $workshift_subgroup_time_finish = ($workshift_subgroup_time_finish != "" ? "'".$workshift_subgroup_time_finish.":00'":"Null");
        $workshift_subgroup             = ($workshift_subgroup             != "" ? "'".$workshift_subgroup."'"               :"Null");


        $sql =  "UPDATE ".$schema."users SET
                        workshift_group_time_init      = ".$workshift_group_time_init.",
                        workshift_group_time_finish    =  ".$workshift_group_time_finish.",
                        workshift_group                = ".$workshift_group.",
                        workshift_subgroup_time_init   = ".$workshift_subgroup_time_init.",
                        workshift_subgroup_time_finish = ".$workshift_subgroup_time_finish.",
                        workshift_subgroup             = ".$workshift_subgroup.",
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
                        registration = ".$registration.",
                        initial_workshift_position = ".$initial_workshift_position.",
                        work_status  = '".$work_status."',
                        in_activation = '".$in_activation."'
                  WHERE id = '".$id."'";
      pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
      logger("Atualização","Usuário","Atualizou dados do usuário: [".$id."] - ".$name);
      header("Location: FORM.php?id=".$id);
      exit();
  }

    extract($_GET);

    if($acao == "remover" && $id != "")
    {
      $sql = "UPDATE ".$schema."users SET active = false WHERE id = '".$id."'";
      pg_query($sql)or die("Erro ".__LINE__);
      logger("Baixa","Usuário","Baixou o usuário: [".$id."]");
      header("Location: index.php");
      exit();
    }

    if($id != ""){ header("Location: FORM.php?id=".$id);      }
             else{ header("Location: FORM_novo_usuario.php"); }
    exit();
?>
