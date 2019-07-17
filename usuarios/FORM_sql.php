<?
    session_start();
    require_once("../libs/php/funcoes.php");
    require_once("../libs/php/conn.php");

    extract($_POST);

    //echo "<pre>";
    //print_r($_POST);
    //echo  "</pre>";
    //exit();

/*
    [name] => Daniel Alberti
    [nickname] =>
    [phone] => 47 996972456
    [registration] =>
    [id_company] => 3
    [area] => Seprot
    [job] => Agente de Transito
    [observation] =>
    [workshift_group_time_init] => 06:30
    [workshift_group_time_finish] => 22:30
    [workshift_subgroup] => Turno
    [workshift_subgroup_time_init] => 08:00
    [workshift_subgroup_time_finish] => 13:00
    [workshift_group] => Alfa
    [email] => daniel.alberti@joinville.sc.gov.br
    [senha] =>
    [senha_repete] =>
    [active] => t
    [acao] => atualizar
    [id] => 69
)
*/

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

        $workshift_group_time_init      = ($workshift_group_time_init      != "" ? "'".$workshift_group_time_init.":00'"     :"Null");
        $workshift_group_time_finish    = ($workshift_group_time_finish    != "" ? "'".$workshift_group_time_finish.":00'"   :"Null");
        $workshift_group                = ($workshift_group                != "" ? "'".$workshift_group."'"                  :"Null");
        $workshift_subgroup_time_init   = ($workshift_subgroup_time_init   != "" ? "'".$workshift_subgroup_time_init.":00'"  :"Null");
        $workshift_subgroup_time_finish = ($workshift_subgroup_time_finish != "" ? "'".$workshift_subgroup_time_finish.":00'":"Null");
        $workshift_subgroup             = ($workshift_subgroup             != "" ? "'".$workshift_subgroup."'"               :"Null");


        $sql =  "UPDATE sepud.users SET
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
