<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  $id = $_GET['id'];
  $agora = now();
  if($id != "")
  {
      $sql   = "SELECT
                  P.id            AS parking_id,
                  P.name          AS parking_code,
                	PT.type         AS parking_type,
                	PT.time         AS parking_time,
                  PT.time_warning AS parking_time_warning,
                	U.NAME,
                	U.id_company,
                	C.NAME          AS company_name,
                	C.acron         AS company_acron,
                	SP.*
                FROM
                	     sepud.eri_schedule_parking SP
                	JOIN sepud.users                 U ON U.ID = SP.id_user
                	JOIN sepud.company               C ON C.ID = U.id_company
                	JOIN sepud.eri_parking           P ON P.id = SP.id_parking
                	JOIN sepud.eri_parking_type     PT ON PT.id = P.id_parking_type
                  WHERE
                  	SP.id = '".$id."'";
      $res   = pg_query($conn_neogrid,$sql)or die("Error ".__LINE__);
      $dados = pg_fetch_assoc($res);


      $acao  = "atualizar";

      $dt = formataData($dados['timestamp'],1); $data = $dt;  $aux  = explode(" ",$data);
      $agora['data'] = $aux[0];
      $agora['hms']  = $aux[1];

      $txt_bread = "Registro n.".$id;
      $dados['status']        = "Registro ativo";


      if($dados['notified'] == "t"){ $dados['status'] = "Notificado"; }
      if($dados['closed'] == "t")  { $dados['status'] = "Baixado";    }

      $diff = floor((strtotime($agora['datatimesrv']) - strtotime($dados['timestamp']))/60);

      $classalert = "alert-success"; $class = "text-success";
      if($dados['closed']!="t" && $dados['notified']!="t")
      {
        if($diff >= 0                      && $diff < $dados['parking_time_warning']){ $classalert = "alert-success"; $class = "text-success"; $txtstatus="No prazo"; }
        if($diff >= $dados['parking_time_warning'] && $diff < $dados['parking_time']){ $classalert = "alert-warning"; $class = "text-warning"; $txtstatus="Próximo do fim do prazo";}
        if($diff >= $dados['parking_time']                                          ){ $classalert = "alert-danger";  $class = "text-danger";  $txtstatus="Expirado"; $status = "notificar";}
      }else{
        if($dados['closed']  =="t"){ $classalert = "alert-primary"; $class = "text-primary"; $diff = floor((strtotime($dados['closed_timestamp'])   - strtotime($dados['timestamp']))/60); $txtstatus="";}
        if($dados['notified']=="t"){ $classalert = "alert-dark";    $class = "text-dark";    $diff = floor((strtotime($dados['notified_timestamp']) - strtotime($dados['timestamp']))/60); $txtstatus="";}
      }


      //Histórico da placa
      $sqlP = "SELECT
                   P.id   as parking_id,
                   P.name as parking_code,
                  PT.type as parking_type,
                  PT.time as parking_time,
                  SP.*
                FROM
                  sepud.eri_schedule_parking SP
                JOIN sepud.eri_parking P       ON P.id = SP.id_parking
                JOIN sepud.eri_parking_type PT ON PT.id = P.id_parking_type
                WHERE licence_plate = '".$dados['licence_plate']."' AND timestamp >= '".$agora['datasrv']." 00:00:00'
                ORDER BY id DESC";
      $res = pg_query($sqlP)or die("Erro ".__LINE__);
      while($hist = pg_fetch_assoc($res))
      {

        $aux = explode(" ",$hist['timestamp']);         $hist['timestamp']          = substr(end($aux),0,5);
        $aux = explode(" ",$hist['closed_timestamp']);  $hist['closed_timestamp']   = substr(end($aux),0,5);
        $aux = explode(" ",$hist['notified_timestamp']);$hist['notified_timestamp'] = substr(end($aux),0,5);
        $aux = explode(" ",$hist['winch_timestamp']);   $hist['winch_timestamp']    = substr(end($aux),0,5);

        if($hist['parking_id'] == $dados['parking_id'] && $hist['id'] != $dados['id']){
          $class = "danger";
          //$status = "notificar";
          $status_hist = "notificar";
        }else{ $class=""; }

          $hist_linhas .= "<tr class='".$class."'>";
            $hist_linhas .= "<td>".$hist['parking_code']."</td>";
            //$hist_linhas .= "<td>".$hist['parking_type']." ";
            $hist_linhas .= "<td><sup>".$hist['parking_time']."min</sup></td>";
            $hist_linhas .= "<td>".$hist['timestamp']."</td>";
            $hist_linhas .= "<td>".$hist['closed_timestamp']."</td>";
            $hist_linhas .= "<td>".$hist['notified_timestamp']."</td>";
            $hist_linhas .= "<td>".$hist['winch_timestamp']."</td>";
          $hist_linhas .= "</tr>";

      }
      if($status_hist == "notificar")
      {
          $hist_linhas_foot = "<tr><td class='danger'>&nbsp;</td><td colspan='6' class='text-danger'>Veículo irregular, já ocupou esta vaga.</td></tr>";
      }

      $data_inicial = date2mkt(formataData($dados['timestamp'],1));
      $tempo_passado = $agora['mkt']-$data_inicial;

      if($tempo_passado <= 60) //Primeiro minuto
      {
        $status = $status_hist = "notificar";
        $hist_linhas_foot = "<tr><td class='warning'>&nbsp;</td><td colspan='6' class='text-danger'>Primeiro minuto, notificação liberada em caso de irregularidade.</td></tr>";
      }

      logger("Acesso","SERP - Registro",$txt_bread.", Placa do veículo: ".$dados['licence_plate']);

  }else{

      $acao                   = "inserir";
      $dados['status']        = "Novo registro";
      $dados['company_acron'] = $_SESSION['company_acron'];
      $dados['company_name']  = $_SESSION['company_name'];
      $dados['name']          = $_SESSION['name'];
      $txt_bread              = "Novo registro";
      $classalert             = "alert-success";
      $class                  = "text-success";
      logger("Acesso","SERP - Registro","Novo registro");

  }
?>
<style>
  .error{
    border: 2px solid red;
    animation: border-pulsate 2s infinite;
  }
  @keyframes border-pulsate {
    0%   { border-color: rgba(255, 0, 0, 1); }
    50%  { border-color: rgba(255, 0, 0, .2); }
    100% { border-color: rgba(255, 0, 0, 1); }
}
.pl_merc{
  border-width:0px;
  border:none;
  border-bottom: 1px solid lightgrey;
  font-size:26px;
  font-weight: bold;
  text-align: center;
  padding-bottom: 2px;

}
</style>
<form id="form_eri" action="erg/app_FORM_sql.php" method="post">
<section role="main" class="content-body">
    <header class="page-header" style="top:0px;left:0px">
      <?
          if($acao == "inserir")
          {
              echo "<h2>Novo registro</h2>";
          }else{
              echo "<h2>Registro n° ".str_pad($_GET['id'],3,"0",STR_PAD_LEFT)."</h2>";
          }
      ?>
      <?
        if($acao!="inserir"){
      ?>
      <div  style='position: absolute;top: 8px;right: 70px;'>
          <a href='erg/app_FORM.php'><button type="button" class="btn btn-success">Novo</button></a>
      </div>
    <? } ?>
      <div  style='position: absolute;top: 8px;right: 10px;'>
          <a href='auth/logout.php' ajax="false"><button type="button" class="btn btn-default">Sair</button></a>
      </div>
    </header>

    <section class="panel box_shadow">

      <div class="panel-heading text-center">
        <!--<h3 class='<?=$class;?>' style="margin-top:-10px"><strong><i><?=$dados['status'];?><br><small><?=$txtstatus;?></small></i></strong></h3>-->
        <h3 class='<?=$class;?>'  style=""><i><?=$dados['status'];?></i></h3>
      </div>


      <div class="panel-body box_shadow">

        <div class="row">
            <div class="col-sm-12">

                    <div class="row">
                        <div class="col-sm-12">

<? if($acao=="inserir"){ ?>
                            <div class="form-group">
                            <label class="control-label">Vaga:</label>

                            <div class="input-group mb-md">
      														<input id="pesquisa" type="number" pattern="\d*" class="form-control input-lg text-center" placeholder="Nº da vaga">
      														<span class="input-group-btn">
      															<button id="pesquisa_btn" class="btn btn-success btn-lg" type="button"><i class="fa fa-search"></i></button>
      														</span>
                            </div>


                            <!--  <input type="number" pattern="\d*" class="form-control input-lg text-center" id="pesquisa" placeholder="Nº da vaga">-->

                                <div id="vagas" class="text-center">
                                        <? if($_GET['erro']=="fora_do_horario"){
                                              echo "<h4 class='text-danger'><i><b>Não registrado</b><br>Fora do horário de operação.";
                                           }else {
                                             echo "<h4 class='text-warning'><i>Digite um número de vaga.";
                                           }
                                        ?>
                                        </i><span style='color:white'><br>.<br>.</span></h4>
                                </div>

                           </div>
<? }else{

echo "<div class='alert ".$classalert." text-center'>";
  echo "<h4>Vaga nº <b>".$dados['parking_code']."</b>";
  echo "<br><small style='color:black'>".$dados['parking_type']."<br>".$dados['parking_time']." min</small>";
  echo "<br>".$txtstatus;
  echo "</h4>";
echo "</div>";

} ?>

                        </div>
                      </div>

<? if($acao=="inserir"){ ?>
                    <div class="row">
                      <div class="col-xs-6">
                          <div class="form-group">
                              <label class="control-label" id='label_letters'>Letras:</label>
                              <input placeholder="XXX" type="text" id="license_plate_letters" name="license_plate_letters"  maxlength="3" class="form-control input-lg text-center" value="<?=substr($dados['licence_plate'],0,3);?>" />
                         </div>
                       </div>
                       <div class="col-xs-6" id='div_placa'>
                           <div class="form-group">
                               <label class="control-label" id='label_numbers'>Números:</label>
                               <input style="width:100%" placeholder="9999" type="number" pattern="\d*" id="license_plate_numbers" name="license_plate_numbers"           maxlength="4" size="4" class="form-control input-lg text-center" value="<?=substr($dados['licence_plate'],3);?>" />

                                  <input placeholder="AAA" type="text"   id="plate_m_part0" name="plate_m_part0" class="pl_merc" value="" onclick="this.value=''" style="margin-left: -105px; width:85px; display:none; margin-top:20px"/>
                                  <input placeholder="0"   type="tel"    id="plate_m_part1" name="plate_m_part1" class="pl_merc" value="" onclick="this.value=''" style="width:25px; display:none;"/>
                                  <input placeholder="A"   type="text"   id="plate_m_part2" name="plate_m_part2" class="pl_merc" value="" onclick="this.value=''" style="width:30px; display:none;"/>
                                  <input placeholder="00"  type="tel"    id="plate_m_part3" name="plate_m_part3" class="pl_merc" value="" onclick="this.value=''" style="width:35px;display:none;"/>

                          </div>
                        </div>
                  </div>
                  <div class='row'>
                    <div class="col-xs-12 text-center">
                        <? if($acao=="inserir"){ ?>
                          <button type="button" class="btn bt-sm btn-default bt_placas" id="bt_mercosul" style="margin-top:22px"><span class="text-muted">Modelo Mercosul</span></button>
                          <button type="button" class="btn bt-sm btn-default bt_placas" id="bt_placa_padrao" style="margin-top:22px;display:none"><span class="text-muted">Modelo antigo</span></button>
                        <? } ?>
                     </div>
                  </div>
<? }else{
    echo "<div class='row'>
            <div class='col-xs-12 text-center'>";
    echo    "<h2><span class='text-muted'>Placa: </span><b>".$dados['licence_plate']."</b></h2>";
    echo "</div></div>";
}

?>

<?           if($acao != "inserir")
            {
?>
                    <div class="row">
                            <div class="col-xs-6 text-center">
                                <h3><? echo "<small><sup>Entrada:</sup></small><br><b>".$agora['hms']."</b><br><small class='text-muted'>".$agora['data']."</small>"; ?></h3>
                                <h3><small><sup>Tempo decorrido:</sup></small><br><b class='<?=$class;?>'><?=$diff;?> min</b></h3>
                            </div>


                          <div class="col-xs-6 text-center" style="margin-top:25px">
                          <?  if($dados['notified'] != "t" && $dados['closed'] != "t")
                             {

                                        if($status != "notificar" && $status_hist != "notificar")
                                        {
                                          echo "<button type='button' class='btn btn-lg btn-block btn-default disabled' role='button' style='margin-bottom:5px'><span class='text-muted'>Notificar</span></button>";
                                        }else{
                                          echo "<a href='erg/app_FORM_sql.php?id=".$id."&acao=notificar'><button type='button' class='btn btn-lg btn-block  btn-dark  loading' role='button' style='margin-bottom:5px'>Notificar</button></a>";
                                        }
                              echo "<button type='button' class='btn btn-lg btn-block btn-default disabled' role='button' style='margin-bottom:5px'><span class='text-muted'>Guinchar</span></button>";
                              echo "<a href='erg/app_FORM_sql.php?id=".$id."&acao=baixar'><button type='button' class='btn btn-lg btn-block btn-primary loading' role='button'>Baixar</button></a>";

                            }else{

                              echo "<button type='button' class='btn btn-lg btn-block btn-default disabled' role='button' style='margin-bottom:5px'><span class='text-muted'>Notificar</span></button>";
                                if($dados['notified'] == "t" && $dados['winch_timestamp'] == "" && $dados['closed'] != "t")
                                {
                                  echo "<a href='erg/app_FORM_sql.php?id=".$id."&acao=guinchar'><button type='button' class='btn btn-lg btn-block  btn-dark  loading' role='button' style='margin-bottom:5px'>Guinchar</button></a>";
                                }else {
                                  echo "<button type='button' class='btn btn-lg btn-block btn-default disabled' role='button' style='margin-bottom:5px'><span class='text-muted'>Guinchar</span></button>";
                                }

                              if($dados['closed'] == "t")
                              {
                                echo "<button type='button' class='btn btn-lg btn-block btn-default disabled' role='button'><span class='text-muted'>Baixar</span></button>";
                              }else{
                                echo "<a href='erg/app_FORM_sql.php?id=".$id."&acao=baixar'><button type='button' class='btn btn-lg btn-block btn-primary' role='button'>Baixar</button></a>";
                              }

                            }



                              echo "<button id='bt_imprimir' type='button' class='btn btn-lg btn-block btn-info' role='button' style='margin-top:5px'>Imprimir Recibo</button></a>";
                            ?>
                          </div>

                    </div>
<?           } ?>


            </div>
          </div>


          <div class="row">
            <div class="col-xs-12">

              <?
                  if($acao=="atualizar")
                  {

                                echo "<h5>Histórico da placa:</h5>";
                                echo "<div id='historico_placa'>";

                                echo "<table class='table table-condensed'>";
                                echo "<thead><tr><th>Vaga</th><th>Tipo</th><th>E</th><th>B</th><th>N</th><th>G</th>
                                      </tr></thead><tbody>";
                                echo $hist_linhas;
                                echo "</tbody>
                                <tfoot>
                                <tr><td colspan='6' style='background:#EEEEEE'><i><b>Legenda:</b></i></td></tr>
                                    ".$hist_linhas_foot."
                                    <tr><td class='text-center'><b>E</b></td><td colspan='5'>Entrada</td></tr>
                                    <tr><td class='text-center'><b>B</b></td><td colspan='5'>Baixa</td></tr>
                                    <tr><td class='text-center'><b>N</b></td><td colspan='5'>Notificado</td></tr>
                                    <tr><td class='text-center'><b>G</b></td><td colspan='5'>Guinchado</td></tr>
                                </tfoot>
                                </table>";
                                echo "</div>";
                        }

                    ?>

            </div>

    </div>

    <footer class="panel-footer text-center" style="margin-top:20px">

          <input type="hidden" id="fullplate" name="fullplate" value="">
          <input type="hidden" id="data" name="data" value="<?=$agora['data'];?>">
          <input type="hidden" id="hora" name="hora" value="<?=$agora['hms'];?>">


          <input type="hidden" name="status"  value="<?=$dados['status'];?>" >
          <input type="hidden" name="id_user" value="<?=$_SESSION['id']?>">
          <input type="hidden" name="acao"    value="<?=$acao;?>">
          <input type="hidden" name="id"      value="<?=$id;?>">
          <a href="erg/app_index.php"><button type='button' class="btn btn-lg btn-default loading" role="button">Voltar</button></a>
      <?
          if($acao == "inserir")
          {
            echo "<button id='bt_inserir_oc' type='button' class='btn btn-lg btn-success' style='margin-left:5px'>Registrar</button>";
          }else{
            echo "<a href='erg/app_FORM.php'><button type='button' class='btn btn-lg btn-success'>Novo</button></a>";
          }
      ?>
      <h5 class="text-center"><span class="text-muted"></span><strong><?=$_SESSION['name']?></strong><br><small><?=$_SESSION['company_acron'];?> - <?=$_SESSION['company_name'];?></small></h5>
    </footer>

</section>


</section>
</form>
<script>

var tipo_placa_selecionada = "padrao";


$("form :input").attr("autocomplete", "off");

$(document).ready(function() {
    $('#pesquisa').focus();
    $('#pesquisa').click();
    document.body.scrollTop = 0;            // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
});


$("#bt_imprimir").click(function(){
    var win = window.open('erg/app_IMPRIMIR.php?id=<?=$id;?>', '_blank', 'location=no,toolbar=no,menubar=no,scrollbars=yes,resizable=yes');
    win.print();
});

$("#bt_inserir_oc").click(function(){

  var placa;
  var enviar_form = false;


  $("#pesquisa").removeClass("error");
  $("#license_plate_letters").removeClass("error");
  $("#license_plate_numbers").removeClass("error");
  $("#license_plate_numbers_mercosul").removeClass("error");
  $("#plate_m_part0").removeClass("error");
  $("#plate_m_part1").removeClass("error");
  $("#plate_m_part2").removeClass("error");
  $("#plate_m_part3").removeClass("error");

  if(jQuery.type($("#id_parking").val()) === "undefined"){ enviar_form = false; $("#pesquisa").addClass("error"); }


  if(tipo_placa_selecionada == "padrao")
  {
          if($("#license_plate_letters").val().length == 3 &&
             $("#license_plate_numbers").val().length == 4){
                enviar_form = true;
                placa = $("#license_plate_letters").val()+$("#license_plate_numbers").val();
          }else{
            if($("#license_plate_letters").val().length != 3){ $("#license_plate_letters").addClass("error"); }
            if($("#license_plate_numbers").val().length != 4){ $("#license_plate_numbers").addClass("error"); }
          }
  }else{ //Placa do mercosul
          if($("#plate_m_part0").val().length == 3 &&
             $("#plate_m_part1").val().length == 1 &&
             $("#plate_m_part2").val().length == 1 &&
             $("#plate_m_part3").val().length == 2){

            enviar_form = true;
            placa = $("#plate_m_part0").val()+$("#plate_m_part1").val()+$("#plate_m_part2").val()+$("#plate_m_part3").val();

          }else{
            if($("#plate_m_part0").val().length != 3){  $("#plate_m_part0").addClass("error"); }
            if($("#plate_m_part1").val().length != 1){  $("#plate_m_part1").addClass("error"); }
            if($("#plate_m_part2").val().length != 1){  $("#plate_m_part2").addClass("error"); }
            if($("#plate_m_part3").val().length != 2){  $("#plate_m_part3").addClass("error"); }
          }
  }



  if(enviar_form == true)
  {
      if(placa == $("#placa_anterior").val())
      {
        enviar_form = false;
        $("#license_plate_numbers_mercosul").addClass("error");
        $(".pl_merc").addClass("error");
                 $("#license_plate_numbers").addClass("error");
                 $("#license_plate_letters").addClass("error");
                              $("#ret_pesq").addClass("error");
      }
  }

  if(enviar_form)
  {
      $("#fullplate").val(placa);
      $(this).attr('disabled', 'disabled');
      $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde</small>");
      $("#form_eri").submit();
  }
});

             $("#pesquisa").mask('0000');
         //$("#pesquisa_txt").mask('0000');
$('#license_plate_numbers').mask('0000');
$('#license_plate_letters').mask('SSS');


$('#plate_m_part0').mask('SSS');
$('#plate_m_part1').mask('0');
$('#plate_m_part2').mask('S');
$('#plate_m_part3').mask('00');

$('#plate_m_part0').keyup(function () {
  var letrasM = $(this).val().toUpperCase();
  $(this).val(letrasM);
  if(letrasM.length == 3){ $('#plate_m_part1').focus();}
});
$('#plate_m_part1').keyup(function () {
  var letrasM = $(this).val();
  if(letrasM.length == 1){ $('#plate_m_part2').focus();}
});
$('#plate_m_part2').keyup(function () {
  var letrasM = $(this).val().toUpperCase();
  $(this).val(letrasM);
  if(letrasM.length == 1){ $('#plate_m_part3').focus();}
});


$("#pesquisa").click(function(){
    $(this).val('');
    $("#vagas").html("<h4 class='text-warning'><i>Digite um número de vaga.</i><span style='color:white'><br>.<br>.</span></h4>");
});
$("#pesquisa_btn").click(function(){
    var query = $("#pesquisa").val();
    $("#vagas").load("erg/app_pesquisa_vaga.php?origem=pesquisa_vaga_form&query="+query);

    document.body.scrollTop = 275;            // For Safari
    document.documentElement.scrollTop = 275; // For Chrome, Firefox, IE and Opera

    $('#license_plate_letters').focus();
    $('#license_plate_letters').click();
});

/*
$("#pesquisa").keyup(function(){
    var query = $(this).val();
    $("#vagas").load("erg/app_pesquisa_vaga.php?origem=pesquisa_vaga_form&query="+query);
});
*/
$('#license_plate_letters').keyup(function () {
  var letras = $(this).val();
  var letrasM = $(this).val().toUpperCase();
  $(this).val(letrasM);
  if(letras.length == 3){ $('#license_plate_numbers').focus();}
});

$('#license_plate_numbers').keyup(function(){
  var num = $(this).val();
  if(num.length == 4){
    $('#license_plate_numbers').blur();
    document.body.scrollTop = 400;            // For Safari
    document.documentElement.scrollTop = 400; // For Chrome, Firefox, IE and Opera
  }
});

//$('#license_plate_numbers_mercosul').keyup(function(){$(this).val($(this).val().toUpperCase());});
//$('#license_plate_numbers_mercosul').mask('0S00');


//$('#bt_mercosul').click(function(){
$('.bt_placas').click(function(){

           $('#license_plate_numbers').val('');
  $('#license_plate_numbers_mercosul').val('');

             $('#license_plate_numbers').toggle();
                    $('#label_mercosul').toggle();
                     $('#label_numbers').toggle();
                     $('#label_letters').toggle();
             $('#license_plate_letters').toggle();
    $('#license_plate_numbers_mercosul').toggle();

    $('#plate_m_part0').toggle();
    $('#plate_m_part1').toggle();
    $('#plate_m_part2').toggle();
    $('#plate_m_part3').toggle();

    $('#bt_mercosul').toggle();
    $('#bt_placa_padrao').toggle();

    if(tipo_placa_selecionada=="padrao"){ tipo_placa_selecionada = "mercosul"; $("#plate_m_part0").focus();$('#plate_m_part0').click(); }
                                    else{ tipo_placa_selecionada = "padrao";   $("#license_plate_letters").focus();$('#license_plate_letters').click(); }


    /*

    if($('#license_plate_numbers_mercosul').is(":visible"))
    {
      $('#license_plate_numbers_mercosul').focus();
      $('#div_placa').removeClass('col-xs-6').addClass('col-xs-12 text-center');
    }else{
      $('#license_plate_numbers').focus();
      $('#div_placa').removeClass('col-xs-12').addClass('col-xs-6');
    }
    */

});

$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i>"); });

</script>
