<?
    session_start();
    require_once("../libs/php/funcoes.php");
    require_once("../libs/php/conn.php");
    $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");


   if($_POST['acao']=="atualizar_dados")
   {
      unset($_POST['acao']);
      logger("Atualização","Usuário","Atualizou dados do usuário: [{$_POST['id']}] - {$_POST['name']}");
      $sql = makeSql("{$schema}users",$_POST,"upd","id");
      pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
      header("Location: FORM.php?nav=dados&id={$_POST['id']}");
      exit();
   }

   if($_POST['acao'] == "atualizar_permissoes")
   {
      logger("Permissões","Usuário", "Atualizou as permissões de ID: {$_POST['id']}");
      $id = $_POST['id'];
      unset($_POST['acao'],$_POST['id']);
      $arr = codificar(json_encode($_POST),'c');
      $sql = "INSERT INTO ".$schema."users_rel_perm_user (id_user, value) VALUES ('{$id}', '{$arr}')
              ON CONFLICT (id_user) DO UPDATE SET value = excluded.value";
      pg_query($sql)or die("SQL Error ".__LINE__."<br>Query: {$sql}");
      header("Location: FORM.php?nav=permissoes&id={$id}");
      exit();
   }

   if($_POST['acao'] == "atualizar_acesso")
   {
      unset($_POST['acao']);
      if($_POST['senha'] != "nova_senha" && $_POST['senha'] != "" && ($_POST['senha'] == $_POST['senha_repete']) && $_POST['email']!=""){
         $_POST['password'] = md5($_POST['senha']);
         unset($_POST['senha'], $_POST['senha_repete']);
         $sql = makeSql("{$schema}users",$_POST,"upd","id");
         $_SESSION['error'] = "Senha alterada e dados atualizados com sucesso.";
      }else{
         $_SESSION['error'] = "Dados atualizados com sucesso (A senha não foi alterada).";
         unset($_POST['senha'], $_POST['senha_repete']);
         $sql = makeSql("{$schema}users",$_POST,"upd","id");
      }
      logger("Acesso","Usuário", "Atualizou as informações de acesso do ID: {$_POST['id']}");
      pg_query($sql)or die("Query error ".__LINE__);
      header("Location: FORM.php?nav=acesso&id={$_POST['id']}");
      exit();
   }

   if($_POST['acao'] == "atualizar_trabalho")
   {
      unset($_POST['acao']);
      logger("Trabalho","Usuário", "Atualizou as informações do turno de trabalho do ID: {$_POST['id']}");
      $sql = makeSql("{$schema}users",$_POST,"upd","id");
      pg_query($sql)or die("Query error ".__LINE__);
      header("Location: FORM.php?nav=trabalho&id={$_POST['id']}");
      exit();
   }

   if($_POST['acao']=="inserir")
   {
      unset($_POST['acao']);
      $sql = makeSql("{$schema}users",$_POST,"ins","id");
      $res = pg_query($sql)or die("<div class='text-center text-danger'>Error: ".__LINE__."<br>SQL {$sql}</div>");
      $aux = pg_fetch_assoc($res);
      logger("Inserção","Usuário", "Inseriu o usuário: [".$aux["id"]."] - ".$name);
      header("Location: FORM.php?id={$aux['id']}");
      exit();
   }

   if($_GET['acao'] == "remover" && $_GET['id'] != "")
   {
     $sql = "UPDATE {$schema}users SET active = false WHERE id = '{$_GET['id']}'";
     pg_query($sql)or die("Erro ".__LINE__);
     logger("Baixa","Usuário","Baixou o usuário: [{$_GET['id']}]");
     $_SESSION['error']="Esta conta de usuário foi baixada no sistema. Usuário não poderá mais efetuar o login.";
     header("Location: FORM.php?nav=acesso&id={$_GET['id']}");
     exit();
   }








    if($acao=="inseriraa")
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


    if($acao=="atualizaraa")
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




/*
    if($id != ""){ header("Location: FORM.php?id=".$id);      }
             else{ header("Location: FORM_novo_usuario.php"); }

    exit();
*/
?>
