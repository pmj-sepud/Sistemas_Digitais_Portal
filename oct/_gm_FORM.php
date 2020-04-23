<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");

?>

<section role="main" class="content-body">
    <header class="page-header">
      <div class="right-wrapper pull-right" style='margin-right:15px;'>
        <ol class="breadcrumbs">
          <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
          <li><span class='text-muted'>Aplicações</span></li>
          <li><a href='oct/<?=$retorno_origem;?>?filtro_data=<?=$_GET['filtro_data'];?>'>Ocorrências - MOBILE - GM</a></li>
          <li><span class='text-muted'><?=$txt_bread;?></span></li>
        </ol>
      </div>
    </header>
    <section class="panel">

      <header class="panel-heading" style="height:50px">
              <div class='row' style="margin-top:-8px">
                  <div class='col-sm-12 text-right'>
                      Nova ocorrência.
                  </div>
                </div>
      </header>
      <div class="panel-body">

                <div class='row'>
                  <div class="col-md-12">
                            <div class="tabs tabs-vertical tabs-right tabs-primary">



                              <div class="tab-content">
                                <!--============================================================================-->
                                <div id="form1" class="tab-pane active">

                                                <?

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
                                                        $error = "Nenhuma ocorrência associada a este orgão";
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
                                                                  echo "<h4 class='text-left'>Ocorrências prioritárias:</h4>";
                                                                  if(isset($oc_prioritarias) && count($oc_prioritarias))
                                                                  {

                                                                      foreach ($oc_prioritarias as $nome_oc => $infos) {
                                                                          if($infos['name_acron']!=""){ $name_acron = $infos['name_acron']."<br>"; }else{ unset($name_acron); }
                                                                          echo "<button class='btn btn-lg btn-default bt_oc_prio text-muted' style='margin:2px'>".$name_acron."<small>".$nome_oc."</small></button>";
                                                                      }
                                                                  }else {
                                                                    echo "Nenhuma ocorrência prioritária configurada.";
                                                                  }
                                                  echo "</div>
                                                    </div>";


                                                    echo "<div class='row'>
                                                              <div class='col-sm-12'>";
                                                                    echo "<h4>Demais ocorrências:</h4>";
                                                                    if(isset($vet) && count($vet))
                                                                    {
                                                                            echo "<p></p>";
                                                                            echo "<select  class='form-control select2' id='sel_oc_nao_prio'>";
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
                                </div>
                                <!--============================================================================-->
                                <!--============================================================================-->
                                <div id="form2" class="tab-pane">
                                  <p>Endereço</p>
                                </div>
                                <!--============================================================================-->
                                <!--============================================================================-->
                                <div id="form3" class="tab-pane">
                                  <p>Informações gerais</p>
                                </div>
                              </div>
                              <!--============================================================================-->
                              <ul class="nav nav-tabs col-sm-3 col-xs-5">
                                <li class="active">
                                  <a href="#form1" data-toggle="tab" ajax="false">Tipo</a>
                                </li>
                                <li>
                                  <a href="#form2" data-toggle="tab" ajax="false">Endereço</a>
                                </li>
                                <li>
                                  <a href="#form3" data-toggle="tab" ajax="false">Informações</a>
                                </li>
                                <li>
                                  <a href="#form4" data-toggle="tab" ajax="false">Providências</a>
                                </li>
                                <li>
                                  <a href="#form5" data-toggle="tab" ajax="false">Envolvidos</a>
                                </li>
                                <li>
                                  <a href="#form6" data-toggle="tab" ajax="false">Veículos</a>
                                </li>
                              </ul>
                              <!--============================================================================-->

                            </div>
                  </div>

                </div>
      </div>


      <footer class="panel-footer">
      </footer>

    </section>
</section>
</form>



<script>
$(".bt_oc_prio").click(function(){
    $(".bt_oc_prio").removeClass("btn-success").addClass("btn-default text-muted");
    $(this).addClass("btn-success").removeClass("text-muted");
    $('#sel_oc_nao_prio').val(null).trigger('change');
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
});
</script>
