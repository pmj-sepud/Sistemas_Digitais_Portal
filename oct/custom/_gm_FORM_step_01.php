<?
  session_start();
  require_once("../../libs/php/funcoes.php");
  require_once("../../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");


//print_r_pre($dados);


if(isset($_SESSION["id_company"]))
{
    $sql = "SELECT T.* FROM ".$schema."oct_event_type T
            JOIN ".$schema."oct_rel_event_type_company R ON R.id_event_type = T.id AND R.id_company = '".$_SESSION["id_company"]."'
            WHERE T.active = true
            ORDER BY T.name ASC";

    $res = pg_query($conn_neogrid,$sql)or die("Error: ".__LINE__);

          if(pg_num_rows($res))
          {
                  while($d = pg_fetch_assoc($res))
                  {
                      if($d['priority']=="t")
                      {
                        $oc_prioritarias[$d['name']] = $d;
                      }else {
                        $vet[$d['type']][] = $d;
                      }
                  }
          }else{
              $error = "Nenhum tipo de ocorrência associada a este orgão";
              $error = true;
          }
}else{
    $error = "Usuário não associado ao orgão em que trabalho";
    $error = true;
}

if(!$error)
{
echo "<div class='row'>
        <div class='col-sm-12 text-center'>";
        echo "<input type='hidden' name='tipo_oc' id='tipo_oc' value='{$dados['id_event_type']}' />";
              echo "<h5 class='text-left'>Ocorrências prioritárias:</h5>";
              if(isset($oc_prioritarias) && count($oc_prioritarias))
              {
                  foreach ($oc_prioritarias as $nome_oc => $infos) {
                      if($infos['name_acron']!=""){ $name_acron = $infos['name_acron']."<br>"; }else{ unset($name_acron); }
                      if($dados['id_event_type'] == $infos['id']){ $btnclass = "btn-success"; }else{ $btnclass = "btn-default text-muted";}
                      echo "<button type='button' class='btn btn-lg {$btnclass} bt_oc_prio btn-block' style='margin:2px' value='{$infos['id']}'>".$name_acron."<small>".$nome_oc."</small></button>";
                  }
              }else {
                echo "Nenhuma ocorrência prioritária configurada.";
              }
echo "</div>
</div>";


echo "<div class='row'>
          <div class='col-sm-12'>";
                echo "<h5>Outros tipos de ocorrências:</h5>";
                if(isset($vet) && count($vet))
                {
                        //echo "<p></p>";
                        echo "<select  class='form-control select2 input-lg' id='sel_oc_nao_prio'>";
                        echo "<option value=''></option>";
                        foreach($vet as $type => $d)
                        {
                          echo "<optgroup label='".$type."'>";
                            for($i = 0; $i < count($d); $i++)
                            {
                              if($d[$i]['name_acron'] != ""){ $acron = $d[$i]['name_acron']." - ";}else{$acron = "";}
                              if($dados['id_event_type'] == $d[$i]['id']){ $sel = "selected"; }else{ $sel = ""; }
                              echo "<option value='".$d[$i]['id']."' $sel>".$acron.$d[$i]['name']."</option>";
                            }
                          echo "</optgroup>";
                        }
                        echo "</select>";
                }
echo "</div>
  </div>";
}else {
echo "<div class='row'>
        <div class='col-sm-12'>";
        echo $error;
echo "</div>
</div>";
}
?>
<script>
$(".bt_oc_prio").click(function(){
    $(".bt_oc_prio").removeClass("btn-success").addClass("btn-default text-muted");
    $(this).addClass("btn-success").removeClass("text-muted");
    $('#sel_oc_nao_prio').val(null).trigger('change');
    $("#isok_01").removeClass("hidden");
    $("#tipo_oc").val($(this).val());
    $("#step_01").collapse('hide');
    $("#step_02").collapse('show');
});
$('.select2').select2({
  language: {
        noResults: function() {
          return 'Nenhum resultado encontrado.';
        }
      }
});

$('#sel_oc_nao_prio').on('select2:select', function (e) {
  $(".bt_oc_prio").removeClass("btn-success").addClass("btn-default text-muted");
  $("#isok_01").removeClass("hidden");
  $("#tipo_oc").val($(this).val());
  $("#step_01").collapse('hide');
  $("#step_02").collapse('show');
});
</script>
