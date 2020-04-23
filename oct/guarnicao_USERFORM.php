<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");

  $agora = now();
//logger("Acesso - Atualização","OCT - Guarnição", "Guarnição ID:".$_GET['id_garrison']);
  $sql = "SELECT id FROM ".$schema."oct_workshift WHERE status = 'aberto' AND id_company = '".$_SESSION['id_company']."'";
  $res = pg_query($sql)or die("SQL error ".__LINE__);
  if(pg_num_rows($res))
  {
         $turno_aberto = true;
         $aux          = pg_fetch_assoc($res);
         $id_workshift = $aux['id'];
  }else{ $turno_aberto = false; }
?>
<style>

</style>
<section role="main" class="content-body">
  <header class="page-header">
    <h2>Guarnição</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><a href="oct/index.php">Sistema</a></li>
        <li><span class='text-muted'>Guarnição</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>


  <div class="col-md-12">
        <section class="panel box_shadow">
              <header class="panel-heading" style="height:70px">
              <div class="panel-actions" style='margin-top:-12px'>

              </div>
              </header>
              <div class="panel-body">
                  <div class='row'>
                      <div class='col-sm-6'>
                          <?
                            echo "<table class='table table-condensed'>
                                  <thead>
                                    <tr><th>Informações:</th></tr>
                                  </thead>";
                            echo "<tbody>";
                            echo "<tr><td>".$_SESSION['name']."</td></tr>";
                            echo "<tr><td>".$_SESSION['company_acron']." - ".$_SESSION['company_name']."</td></tr>";

                            if($turno_aberto)
                            {
                                      $sql = "SELECT
                                              		W.id as id_workshift, W.status as status_workshit,
                                              	  G.id as id_garrison, G.opened, G.name,
                                                	P.type,
                                                	V.initial_km, V.obs as vehicle_obs, V.id as id_rel_garrison_vehicle,
                                                	F.nickname, F.plate, F.model, F.brand, F.id as id_vehicle
                                              FROM ".$schema."oct_garrison G
                                                   JOIN ".$schema."oct_rel_garrison_persona P ON P.id_garrison = G.id AND P.id_user = '".$_SESSION['id']."'
                                              LEFT JOIN ".$schema."oct_rel_garrison_vehicle V ON V.id = P.id_rel_garrison_vehicle
                                              LEFT JOIN ".$schema."oct_fleet                F ON F.id = V.id_fleet
                                              		 JOIN ".$schema."oct_workshift 					 W ON W.id = G.id_workshift AND status = 'aberto'
                                              WHERE G.closed is null";
                                      $res = pg_query($sql)or die("SQL Error: ".__LINE__);
                                      if(pg_num_rows($res))
                                      {
                                        $dados = pg_fetch_assoc($res);
                                        echo "<tr><th>Guarnição: ".strtoupper($dados['name'])."</th></tr>";
                                        echo "<tr><td>Abertura: ".formataData($dados['opened'],1)."</td></tr>";

                                        if($dados['id_vehicle']!="")
                                        {
                                            echo "<tr><td>";
                                              echo $dados['nickname']." - ".$dados['brand']." ".$dados['model']." - Placa: ".$dados['plate'];
                                            echo "</td></tr>";
                                            echo "<tr><td>Posição: ";
                                              echo $dados['type'];
                                            echo "</td></tr>";

                                            echo "<tr><td class='text-center'>";
                                                echo "<a href='oct/guarnicao_USERFORM.php?acao=encerrar_guarnicao&id_garrison=".$dados['id_garrison']."&id_rel_garrison_vehicle=".$dados['id_rel_garrison_vehicle']."'><button class='btn btn-warning'>Encerrar guarnição</button></a> ";
                                            echo "</td></tr>";
                                        }else{
                                          echo "<tr><td>Observação: Sem veículo, guarnição a pé.</td></tr>";
                                          echo "<tr><td class='text-center'>";
                                              echo "<a href='oct/guarnicao_USERFORM_sql.php?acao=encerrar_guarnicao_a_pe&id_garrison=".$dados['id_garrison']."'><button class='btn btn-warning'>Encerrar guarnição</button></a> ";
                                          echo "</td></tr>";
                                        }


                                      }else {
                                        echo "<tr><th>Guarnição:</th></tr>";
                                        echo "<tr><td><div class='alert alert-warning text-center'>Você não esta associado a nenhuma guarnição ativa.</div></td></tr>";
                                        echo "<tr><td class='text-center'>";
                                            echo "<a href='oct/guarnicao_USERFORM.php?acao=existente'><button class='btn btn-success'>Entrar numa guarnição existente</button></a> ";
                                            echo "<a href='oct/guarnicao_USERFORM.php?acao=nova'><button class='btn btn-primary'>Criar uma nova guarnição</button></a>";
                                        echo "</td></tr>";
                                      }
                                }else {
                                  echo "<tr><th>Guarnição:</th></tr>";
                                  echo "<tr><td><div class='alert alert-warning text-center'>Nenhum turno de trabalho aberto.</div></td></tr>";
                                }

                            echo "</tbody>";
                            echo "</table>";
                          ?>
                      </div>

                      <div class='col-sm-6'>
                          <?
                              if($_GET['acao']=="encerrar_guarnicao")
                              {
                                $guarnicao    = guarnicoes($_GET['id_garrison'],'','dados');
                                $qtd_veiculos = 0;
                                echo "<form action='oct/guarnicao_USERFORM_sql.php' method='POST'>";
                                echo "<table class='table'>
                                      <thead><tr><th>#</th>
                                             <th>Apelido</th>
                                             <th>Km inicial</th>
                                             <th>Km final</th>
                                      </thead><tbody>";
                                    foreach ($guarnicao['veiculos'] as $id_rel_garrison_vehicle => $vals){
                                        $qtd_veiculos++;
                                        echo "<tr>";
                                            echo "<td><small class='text-muted'>".$vals['id']."</small></td>";
                                            echo "<td>".$vals['nickname']."</td>";
                                            echo "<td>".number_format($vals['initial_km'],0,'','.')."</td>";
                                            echo "<td><input name='".$vals['id']."_kmfinal' type='number' class='form-control campo_km' value=''></td>";
                                        echo "</tr>";
                                    }
                                echo "</tbody></table>";
                                echo "<input type='hidden' name='qtd_veiculos' value='".$qtd_veiculos."'>";
                                echo "<input type='hidden' name='id_garrison' value='".$_GET['id_garrison']."'>";
                                echo "<input type='hidden' name='acao' value='".$_GET['acao']."'>";
                                echo "<div class='text-center'>
                                          <a href='oct/guarnicao_USERFORM.php'><button type='button' class='btn btn-default'>Voltar</button></a>
                                          <button type='submit' class='btn btn-warning'>Encerrar guarnição</button>
                                      </div>";
                                echo "</form>";
                              }


                              if($_GET['acao']=='existente')
                              {
                                echo "<h4>Guarnição<sup><small class='text-muted'>(ões)</small></sup> aberta<sup><small class='text-muted'>(s)</small></sup>:</h4>";

                                $sql = "SELECT *
                                        FROM
                                        	".$schema."oct_garrison G
                                        WHERE
                                        	  G.closed IS NULL
                                        AND G.id_workshift = '".$id_workshift."'";
                                $res = pg_query($sql)or die("SQL Error ".__LINE__);
                                if(pg_num_rows($res))
                                {

                                    echo "<table class='table'><tbody>";
                                    while($d = pg_fetch_assoc($res))
                                    {
                                          $guarnicao = guarnicoes($d['id'],'');

                                          echo "<tr>";
                                              echo "<td><b>".strtoupper($guarnicao['name'])."</b></td>";
                                              echo "<td class='text-right'>".$guarnicao['abertura']."</td>";
                                          echo "</tr>";
                                          echo "<tr>";
                                              echo "<td colspan='2'>".$guarnicao['info']."</td>";
                                          echo "</tr>";
                                          echo "<tr>";
                                              echo "<td colspan='2'><small class='text-muted'><i>Observações:</i></small><br>".$guarnicao['obs']."</td>";
                                          echo "</tr>";
                                          echo "<tr><td colspan='2'></td></tr>";
                                    }
                                    echo "</tbody></table>";
                                }else {
                                  echo "<div class='alert alert-default text-center'>Nenhuma guarnição aberta.</div>";
                                }
                              }

                              if($_GET['acao']=='nova')
                              {
                                  echo "<form action='oct/guarnicao_USERFORM_sql.php' method='post'>";
                                  echo "<h4>Nova guarnição:</h4>";
                                  echo "<div class='row'>
                                          <div class='col-sm-12'>";
                                                $sql = "SELECT * FROM ".$schema."oct_fleet WHERE id_company = '".$_SESSION['id_company']."' ORDER BY brand DESC, model ASC, nickname ASC";
                                                $res = pg_query($sql)or die("Erro ".__LINE__);
                                                while($d = pg_fetch_assoc($res)){


                                                  $sqlRelVeic = "SELECT
                                                                  	G.closed, G.name,
                                                                  	R.final_km
                                                                  FROM
                                                                  	".$schema."oct_rel_garrison_vehicle R
                                                                  LEFT JOIN ".$schema."oct_garrison G ON G.id = R.id_garrison
                                                                  WHERE
                                                                  	R.id_fleet = '".$d['id']."'
                                                                  ORDER BY R.id DESC
                                                                  LIMIT 1";
                                                  $resRelVeic = pg_query($sqlRelVeic)or die("SQL Error ".__LINE__);
                                                  $aux        = pg_fetch_assoc($resRelVeic);

                                                  $d['guarnicao'] = $aux;
                                                  $autos[$d['type']][] = $d;

                                                }
                                                echo "<div class='form-group'>
                                                <label class='control-label'>Associar veículo:</label>
                                                <select id='id_fleet' name='id_fleet' class='form-control select2'>";
                                                      echo "<option value=''></option>";
                                                      foreach ($autos as $tipo => $auto)
                                                      {
                                                          echo "<optgroup label='".$tipo."'>";
                                                          for($i=0;$i<count($auto);$i++)
                                                          {
                                                              if($auto[$i]['guarnicao']['name']!="" && $auto[$i]['guarnicao']['closed']=="")
                                                              {
                                                                  $guarnicao_em_uso .= "<option disabled value='".$auto[$i]["id"]."'>".$auto[$i]["nickname"]." [".$auto[$i]["plate"]."] - ".$auto[$i]["brand"]." ".$auto[$i]["model"]."</option>";
                                                              }else{

                                                                   if($auto[$i]['guarnicao']['final_km']!=""){
                                                                      echo "<option rel='".$auto[$i]['guarnicao']['final_km']."' value='".$auto[$i]["id"]."'>".$auto[$i]["nickname"]." [".$auto[$i]["plate"]."] - ".$auto[$i]["brand"]." ".$auto[$i]["model"];
                                                                      echo " [Km final: ".number_format($auto[$i]['guarnicao']['final_km'],0,'','.')."]";
                                                                      echo "</option>";
                                                                   }else {
                                                                     echo "<option value='".$auto[$i]["id"]."'>".$auto[$i]["nickname"]." [".$auto[$i]["plate"]."] - ".$auto[$i]["brand"]." ".$auto[$i]["model"];
                                                                     echo "</option>";
                                                                   }

                                                              }
                                                          }
                                                          echo "</optgroup>";
                                                      }
                                                      if($guarnicao_em_uso!="")
                                                      {
                                                        echo "<optgroup label='Veículo(s) em uso'>";
                                                        echo $guarnicao_em_uso;
                                                        echo "</optgroup>";
                                                      }

                                                echo "</select></div>";
                                  echo "  </div>
                                        </div>";

                                  echo "<div class='row'>";
                                      echo "<div class='col-sm-6'>";
                                            echo "<div class='form-group'>
                                                    <label class='control-label'>KM inicial:</label>
                                                    <input name='initial_km' id='initial_km' type='number' class='form-control campo_km' onClick='$(this).val(\"\");'>
                                                  </div>";
                                      echo "</div>";
                                          echo "<div class='col-sm-6'>";
                                        echo "<div class='form-group'>
                                                <label class='control-label'>Posição no veículo:</label>
                                                <select id='type' name='type' class='form-control select2'>
                                                  <option value='Motorista'>Motorista</option>
                                                  <option value='Passageiro'>Passageiro</option>
                                                </select>
                                              </div>";
                                  echo "  </div>
                                        </div>";

                                  echo "<div class='row'>
                                          <div class='col-sm-12'>
                                              <div class='form-group'>
                                                   <label class='control-label'>Observações:</label>
                                                   <textarea id='obs' name='obs' class='form-control' rows='3'></textarea>
                                             </div>
                                          </div>
                                        </div>";

                              echo "<div class='row'>
                                      <div class='col-sm-12 text-center' style='margin-top:10px'>
                                            <button type='submit' class='btn btn-primary'>Criar guarnição</button>
                                      </div>
                                    </div>";

                              echo "<input type='hidden' name='acao' value='nova_guarnicao'>";
                              echo "<input type='hidden' name='id_user' value='".$_SESSION['id']."'>";
                              echo "<input type='hidden' name='id_workshift' value='".$id_workshift."'>";
                              echo "</form>";
                              }


                              if($dados['id_vehicle']!="" && $_GET['acao']=="")
                              {
                                  //print_r_pre($dados);
                                  $sql = "SELECT count(*) as qtd, status, active FROM ".$schema."oct_events WHERE id_garrison = '".$dados['id_garrison']."' GROUP BY status, active";
                                  $res = pg_query($sql)or die("SQL error ".__LINE__);
                                  while($d = pg_fetch_assoc($res))
                                  {
                                    $d['active'] = ($d['active']=="t"?"Ativa":"Terminada");
                                    $stats[$d['active']][$d['status']]=$d['qtd'];
                                  }


                                  //print_r_pre($stats);
                              }
                          ?>
                      </div>
                  </div>
              </div>
        </section>
  </div>

</section>


<script>
$("#id_fleet").change(function(){
  var kmfinal      = $("option:selected", this).attr("rel");
  $("#initial_km").val(kmfinal);
})
$('.select2').select2({
  language: {
        noResults: function() {
          return 'Nenhum resultado encontrado.';
        }
      }
});
$(document).ready(function(){ $(this).scrollTop(0);});
$(".campo_km").mask('000000');
$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> Aguarde");});
$(".loading2").click(function(){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i>");});
</script>
<?
function guarnicoes($id_garrison, $id_workshift, $modelo = "resumido")
{
        $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
       if($id_garrison != "")//Buscando uma guarnição especifica
       {
              $sql = "SELECT
                        F.nickname, F.plate, F.model, F.brand,
                        G.*
                      FROM
                        ".$schema."oct_garrison G
                      LEFT JOIN ".$schema."oct_fleet F ON F.id = G.id_fleet
                      WHERE
                        G.id = '".$id_garrison."'";
              $res = pg_query($sql)or die("SQL error ".__LINE__);
              while($aux = pg_fetch_assoc($res))
              {
                  $guarnicao_empenhada = $aux;
              }


              if($guarnicao_empenhada['name']!="") //Guarnições no modelo novo//
              {
                     //Busca os veículos
                      $sql = "SELECT
                                F.nickname,
                                F.brand,
                                F.model,
                                F.plate,
                                V.*
                              FROM
                                ".$schema."oct_rel_garrison_vehicle V
                                JOIN ".$schema."oct_fleet F ON F.ID = V.id_fleet
                              WHERE
                                V.id_garrison = '".$id_garrison."'";

                      $res = pg_query($sql)or die("SQL error ".__LINE__);
                      while($aux = pg_fetch_assoc($res)){ $guarnicao_empenhada['veiculos'][$aux['id']] = $aux;  }
             }
                     //Buscando as pessoas integrantes da guarnição//
                     $sql = "SELECT
                               U.NAME,
                               U.nickname,
                               U.registration,
                               P.*
                             FROM
                               ".$schema."oct_rel_garrison_persona P
                               JOIN ".$schema."users U ON U.ID = P.id_user
                             WHERE
                               P.id_garrison = '".$id_garrison."'
                             ORDER BY U.nickname ASC";

                     $res = pg_query($sql)or die("SQL error ".__LINE__);
                     while($aux = pg_fetch_assoc($res))
                     {
                             if($guarnicao_empenhada['name']!="") //Guarnições no modelo novo//
                             {
                                   if($aux['id_rel_garrison_vehicle']!="")
                                   {
                                     $guarnicao_empenhada['veiculos'][$aux['id_rel_garrison_vehicle']]['pessoas'][] = $aux;
                                   }else{
                                     $guarnicao_empenhada['pessoas_a_pe'][] = $aux;
                                   }
                             }else{
                                     $guarnicao_empenhada['pessoas'][] = $aux;
                             }
                     }


              if($modelo == "resumido")
              {
                    if($guarnicao_empenhada['name']!="")//Modelo novo de guarnição//
                    {
                        if(isset($guarnicao_empenhada['veiculos']))
                        {

                              foreach($guarnicao_empenhada['veiculos'] as $id_rel_garrison_vehicle => $d)
                              {
                                   if(isset($info)){$info .= "<br>";}
                                   unset($pessoas, $motorista);
                                   $motorista = false;
                                   for($i=0;isset($d['pessoas']) && $i<count($d['pessoas']);$i++)
                                   {
                                     $pessoas[] = ($d['pessoas'][$i]['nickname'] != ""?$d['pessoas'][$i]['nickname']:$d['pessoas'][$i]['name']);
                                     if($d['pessoas'][$i]['type']=="Motorista"){ $motorista = true; }
                                   }

                                   //$info .= "<a href='oct/guarnicao_USERFORM_sql.php?acao=existente&id_fleet=".$d['id']."&id_garrison=".$guarnicao_empenhada['id']."'><button class='btn btn-sm btn-info'><i class='fa fa-user'></i><sup><i class='fa fa-plus'></i></sup></button></a> ".$d['nickname'].": ";

                                   $info .= "<div class='btn-group'>
												<button type='button' class='mb-xs mt-xs mr-xs btn btn-info dropdown-toggle' data-toggle='dropdown'><i class='fa fa-user'></i><sup><i class='fa fa-plus'></i></sup> <span class='caret'></span></button>
												<ul class='dropdown-menu' role='menu'>
                          <li style='margin-left:10px'>Posição no veículo:</li>";
                          if(!$motorista)
                          {
                            $info .= "<li><a href='oct/guarnicao_USERFORM_sql.php?id_user=".$_SESSION['id']."&posicao=Motorista&acao=inserir_user_guarnicao_existente&id_rel_garrison_vehicle=".$d['id']."&id_garrison=".$guarnicao_empenhada['id']."'><i class='text-muted fa fa-angle-double-right'></i> Motorista</a></li>";
                          }
										      $info .= "<li><a href='oct/guarnicao_USERFORM_sql.php?id_user=".$_SESSION['id']."&posicao=Passageiro&acao=inserir_user_guarnicao_existente&id_rel_garrison_vehicle=".$d['id']."&id_garrison=".$guarnicao_empenhada['id']."'><i class='text-muted fa fa-angle-double-right'></i> Passageiro</a></li>
												</ul>
											</div>";


                                    $info .= "<b><i>".$d['nickname']."</i></b> - ";
                                   if(isset($pessoas) && count($pessoas)){ $info .= implode(", ",$pessoas); }
                                   else                                  { $info .= "<span class='text-danger'><i>Nenhum integrante</i></span>";}
                              }
                        }
                        //Verificando se há pessoas a pé na guarnição//
                        if(isset($guarnicao_empenhada['pessoas_a_pe']) && count($guarnicao_empenhada['pessoas_a_pe']))
                        {
                          if(isset($info)){$info .= " | ";}
                          $info .= "A PÉ: ";
                          unset($pessoas);
                          for($i=0;isset($guarnicao_empenhada['pessoas_a_pe']) && $i<count($guarnicao_empenhada['pessoas_a_pe']);$i++)
                          {
                            $pessoas[] = $guarnicao_empenhada['pessoas_a_pe'][$i]['nickname'];
                          }
                          $info .= implode(", ",$pessoas);
                        }

                        return array("name" => $guarnicao_empenhada['name'], "info" => $info, "abertura" => formataData($guarnicao_empenhada['opened'],1), "obs" => $guarnicao_empenhada['observation']);

                   }else{ //Guarnição no modelo antigo//
                         $info = $guarnicao_empenhada['nickname'].": ";
                         for($i=0;isset($guarnicao_empenhada['pessoas']) && $i<count($guarnicao_empenhada['pessoas']);$i++)
                         {
                           $pessoas[] = $guarnicao_empenhada['pessoas'][$i]['nickname'];
                         }
                         $info .= implode(", ",$pessoas);
                         return array("name" => number_format($id_garrison,0,'','.'), "info" => $info);
                   }
              }

              if($modelo=="dados")
              {
                return $guarnicao_empenhada;
              }
        }
}
?>
