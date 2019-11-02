<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  $agora = now();
//logger("Acesso - Atualização","OCT - Guarnição", "Guarnição ID:".$_GET['id_garrison']);
  $sql = "SELECT id FROM sepud.oct_workshift WHERE status = 'aberto' AND id_company = '".$_SESSION['id_company']."'";
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
                                                	V.initial_km, V.obs as vehicle_obs,
                                                	F.nickname, F.plate, F.model, F.brand, F.id as id_vehicle
                                              FROM sepud.oct_garrison G
                                                   JOIN sepud.oct_rel_garrison_persona P ON P.id_garrison = G.id AND P.id_user = '".$_SESSION['id']."'
                                              LEFT JOIN sepud.oct_rel_garrison_vehicle V ON V.id = P.id_rel_garrison_vehicle
                                              LEFT JOIN sepud.oct_fleet                F ON F.id = V.id_fleet
                                              		 JOIN sepud.oct_workshift 					 W ON W.id = G.id_workshift AND status = 'aberto'
                                              WHERE G.closed is null";
                                      $res = pg_query($sql)or die("SQL Error: ".__LINE__);
                                      if(pg_num_rows($res))
                                      {
                                        $dados = pg_fetch_assoc($res);
                                        echo "<tr><th>Guarnição: ".strtoupper($dados['name'])."</th></tr>";
                                        echo "<tr><td>";
                                          echo $dados['nickname']." - ".$dados['brand']." ".$dados['model']." - Placa: ".$dados['plate'];
                                        echo "</td></tr>";
                                        echo "<tr><td>Posição: ";
                                          echo $dados['type'];
                                        echo "</td></tr>";

                                        echo "<tr><td class='text-center'>";
                                            echo "<button class='btn btn-warning'>Encerrar guarnição</button> ";
                                        echo "</td></tr>";
                                      }else {
                                        echo "<tr><th>Guarnição:</th></tr>";
                                        echo "<tr><td><div class='alert alert-warning text-center'>Você não esta associado a nenhuma guarnição ativa.</div></td></tr>";
                                        echo "<tr><td class='text-center'>";
                                            echo "<a href='oct/guarnicao_USERFORM.php?acao=existente'><button class='btn btn-success'>Entrar numa guarnição existente</button></a> ";
                                            echo "<button class='btn btn-primary'>Criar uma nova guarnição</button>";
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
                              if($_GET['acao']=='existente')
                              {
                                echo "<h4>Guarnição<sup><small class='text-muted'>(ões)</small></sup> aberta<sup><small class='text-muted'>(s)</small></sup>:</h4>";

                                $sql = "SELECT *
                                        FROM
                                        	sepud.oct_garrison G
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
                                }
                              }
                          ?>
                      </div>
                  </div>
              </div>
        </section>
  </div>

</section>


<script>
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

       if($id_garrison != "")//Buscando uma guarnição especifica
       {
              $sql = "SELECT
                        F.nickname, F.plate, F.model, F.brand,
                        G.*
                      FROM
                        sepud.oct_garrison G
                      LEFT JOIN sepud.oct_fleet F ON F.id = G.id_fleet
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
                                sepud.oct_rel_garrison_vehicle V
                                JOIN sepud.oct_fleet F ON F.ID = V.id_fleet
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
                               sepud.oct_rel_garrison_persona P
                               JOIN sepud.users U ON U.ID = P.id_user
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
                                   if(isset($info)){$info .= " | ";}
                                   $info .= "<a href='oct/guarnicao_USERFORM_sql.php?acao=existente&id_fleet=".$d['id']."&id_garrison=".$guarnicao_empenhada['id']."'><button class='btn btn-sm btn-info'><i class='fa fa-user'></i><sup><i class='fa fa-plus'></i></sup></button></a> ".$d['nickname'].": ";
                                   unset($pessoas);
                                   for($i=0;isset($d['pessoas']) && $i<count($d['pessoas']);$i++)
                                   {
                                     $pessoas[] = ($d['pessoas'][$i]['nickname'] != ""?$d['pessoas'][$i]['nickname']:$d['pessoas'][$i]['name']);
                                   }
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
        }
}
?>
