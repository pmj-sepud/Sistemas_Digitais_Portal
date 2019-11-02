<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  $id         = $_GET['id'];
  $agora      = now();

  logger("Acesso","OCT - Providencias", "Ocorrência n.".$_GET['id']);

  if($_GET['id_providence'])
  {
    $acao  = "atualizar";
    $sql   = "SELECT * FROM sepud.oct_rel_events_providence WHERE id = '".$_GET['id_providence']."'";
    $res   = pg_query($sql)or die("SQL Error ".__LINE__);
    $dados = pg_fetch_assoc($res);
  }else{

    $acao       = "inserir";
  }

  $margin_upd = "15px";

?>
<form id="form_providencias" name="form_providencias" action="oct/FORM_providencias_sql.php" method="post">
<section role="main" class="content-body">
    <header class="page-header">
      <?="<h2>Ocorrência n° ".str_pad($_GET['id'],3,"0",STR_PAD_LEFT)."</h2>";?>
      <div class="right-wrapper pull-right" style='margin-right:15px;'>
        <ol class="breadcrumbs">
          <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
          <li><span class='text-muted'>Aplicações</span></li>
          <li><a href='oct/index.php'>Ocorrências de trânsito</a></li>
          <li><a href='oct/FORM.php?id=<?=$_GET['id']?>'>Ocorrência n.<?=$_GET['id'];?></a></li>
          <li><span class='text-muted'>Providências</span></li>
        </ol>
      </div>
    </header>

    <section class="panel">
      <header class="panel-heading">
        <h4><span class="text-muted"><i class="fa fa-cogs"></i> Providências</h4>
      </header>
      <div class="panel-body">

        <div class="row">
          <div class="col-sm-6">
            <!-- ========================================================= -->
          <div class="row">

               <div class="col-sm-12">
                 <div class="form-group">
                 <label class="control-label">Providência:</label>
                 <select id="id_providence_type" name="id_providence_type" class="form-control">
                    <?
                          $sql = "SELECT * FROM sepud.oct_providence ORDER BY area, providence ASC";
                          $res = pg_query($sql)or die("Erro ".__LINE__);
                          while($p = pg_fetch_assoc($res))
                          {
                            $provs[$p['area']][] = $p;
                          }

                          foreach ($provs as $area => $prov)
                          {
                            echo "<optgroup label='$area'>";
                              for($i=0;$i<count($prov);$i++)
                              {

                                if(isset($dados['id_providence']) && $dados['id_providence']==$prov[$i]["id"]){ $sel = "selected"; }
                                else {
                                  if($prov[$i]["id"] == 26){ $sel = "selected"; }else{ $sel = ""; }
                                }

                                echo "<option value='".$prov[$i]["id"]."' $sel>".$prov[$i]["providence"]."</option>";
                              }
                            echo "</optgroup>";
                          }
                    ?>
                 </select>

                </div>
              </div>


           </div>


           <div class="row">
             <div class="col-sm-12">
               <div class="form-group">
                 <label class="control-label">Responsável:</label>
                 <select class="form-control select2" name="id_user_resp">
                    <option value="">- - -</option>
                    <?
                        $sql = "SELECT id, nickname, name, registration FROM sepud.users U WHERE id_company = '".$_SESSION['id_company']."' AND active = 't' ORDER BY name ASC";
                        $res = pg_query($sql)or die("<option value=''>Erro: ".$sql."</option>");
                        while($u = pg_fetch_assoc($res))
                        {
                          if($dados['id_user_resp']==$u['id']){ $sel = "selected"; }else{$sel="";}
                          echo "<option value='".$u['id']."' ".$sel.">".$u['nickname']." - ".$u['name'].", Matricula: ".$u['registration']."</option>";
                        }
                    ?>
                 </select>
               </div>
            </div>
         </div>


<div class="row">
  <div class="col-sm-12">
    <?
     $turno_aberto['id'] = $_GET['id_workshift'];
     if($turno_aberto['id']!="")
     {
       $sql = "SELECT
                 F.nickname, F.plate, F.model, F.brand,
                 G.*
               FROM
                 sepud.oct_garrison G
               LEFT JOIN sepud.oct_fleet F ON F.id = G.id_fleet
               WHERE
                 G.id_workshift = '".$turno_aberto['id']."' AND G.closed is null AND G.name is not null";
       $res = pg_query($sql)or die("SQL error ".__LINE__);
       while($aux = pg_fetch_assoc($res)){
         if($aux['name']!=""){ //Nome da guarnição, novo modelo de gestão//
           $vetor_nova_guarnicao[$aux['id']] = $aux;
         }
       }
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
                 V.id_garrison IN (SELECT G.ID FROM sepud.oct_garrison G WHERE G.id_workshift = '".$turno_aberto['id']."' AND G.closed is null AND G.name is not null)";

       $res = pg_query($sql)or die("SQL error ".__LINE__);
       while($aux = pg_fetch_assoc($res)){
         $vetor_nova_guarnicao[$aux['id_garrison']]['veiculos'][$aux['id']] = $aux;
       }

       $sql = "SELECT
                 U.NAME,
                 U.nickname,
                 U.registration,
                 P.*
               FROM
                 sepud.oct_rel_garrison_persona
                 P JOIN sepud.users U ON U.ID = P.id_user
               WHERE
                 P.id_garrison IN (SELECT G.ID FROM sepud.oct_garrison G WHERE G.id_workshift = '".$turno_aberto['id']."' AND G.closed is null AND G.name is not null)
               ORDER BY U.nickname ASC";

       $res = pg_query($sql)or die("SQL error ".__LINE__);
       while($aux = pg_fetch_assoc($res)){

         if($aux['id_rel_garrison_vehicle']!="")
         {
           $vetor_nova_guarnicao[$aux['id_garrison']]['veiculos'][$aux['id_rel_garrison_vehicle']]['pessoas'][] = $aux;
         }else {
           $vetor_nova_guarnicao[$aux['id_garrison']]['pessoas_a_pe'][] = $aux;
         }
       }
       echo "<div class='form-group'>
             <label class='control-label'>Guarnição</label>
             <select class='form-control select2' name='id_garrison'>
               <option value=''>- - -</option>";
               if($dados['closed_garrison']!="")
               {
                   if($dados['name_garrison']=="")//Guarnição modelo antigo//
                   {
                     echo "<optgroup label='Guarnição empenhada (baixada):'>
                           <option value='".$dados['id_garrison']."' selected>".$dados['fleet_nickname']." - ".$dados['plate'].": ".$dados['nickname_garrison']."</option>
                           </optgroup>";
                   }else{
                     /*

                     SELECT G.name, P.* FROM sepud.oct_rel_garrison_persona P, sepud.oct_garrison G WHERE id_garrison = '1974' AND P.id_garrison = G.id;
                     */
                     $sql = "SELECT F.nickname as fleet_nickname, G.name, R.* FROM sepud.oct_rel_garrison_vehicle R, sepud.oct_garrison G, sepud.oct_fleet F WHERE R.id_fleet = F.id AND id_garrison = '".$dados['id_garrison']."' AND R.id_garrison = G.id";
                     $res = pg_query($sql)or die("Erro ".__LINE__);
                     while($auxg = pg_fetch_assoc($res)){
                         $guarnicao_nova_baixada[$auxg['id_garrison']]['name']=$auxg['name'];
                         $guarnicao_nova_baixada[$auxg['id_garrison']]['veiculos'][$auxg['id']] = $auxg;
                     }
                     $sql = "SELECT G.name, P.*, U.nickname FROM sepud.oct_rel_garrison_persona P, sepud.oct_garrison G, sepud.users U WHERE id_garrison = '".$dados['id_garrison']."' AND P.id_garrison = G.id AND P.id_user = U.id";
                     $res = pg_query($sql)or die("Erro ".__LINE__);
                     while($auxg = pg_fetch_assoc($res)){
                         $guarnicao_nova_baixada[$auxg['id_garrison']]['veiculos'][$auxg['id_rel_garrison_vehicle']]['pessoas'][] = $auxg;
                     }
                     unset($str);
                     foreach ($guarnicao_nova_baixada as $id_garrison_baixada => $info_veic) {
                     echo "<optgroup label='Guarnição ".strtoupper($info_veic['name'])." (baixada):'>";
                       foreach ($info_veic['veiculos'] as $id_rel_gar_veic => $values)
                       {
                         if(isset($str)){ $str .= " | ";}
                         $str .= $values['fleet_nickname'].": ";
                         unset($straux);
                         for($i=0;$i<count($values['pessoas']);$i++)
                         {
                           $straux[] = $values['pessoas'][$i]['nickname'];
                         }
                         $str .= implode(", ",$straux);
                       }
                       echo "<option value='".$id_garrison_baixada."' selected>".$str."</option>";
                     echo "</optgroup>";
                     }
                   }
               }
             foreach ($vetor_nova_guarnicao as $id_garrison => $info)
             {
               echo "<optgroup label='Guarnição ".strtoupper($info['name'])."'>";
               unset($str_veic, $str_pess);
               foreach ($info['veiculos'] as $id_fleet => $info_veic)
               {
                 if(isset($str_veic)){ $str_veic .= " | "; }
                 $str_veic .= $info_veic['nickname'].": ";
                 unset($str_pess);
                 for($i=0;$i<count($info_veic['pessoas']);$i++)
                 {
                   $str_pess[] = $info_veic['pessoas'][$i]['nickname'];
                 }
                 $str_veic .= implode(", ",$str_pess);
               }
               if(count($info['pessoas_a_pe'])){
                 unset($str_pess);
                 for($i=0;$i<count($info['pessoas_a_pe']);$i++)
                 {
                   $str_pess[] = $info['pessoas_a_pe'][$i]['nickname'];
                 }
                 if(isset($str_veic)){ $str_veic .= " | ";}
                 $str_veic .= "A PÉ: ".implode(", ",$str_pess);
               }
               if($dados['id_garrison']==$id_garrison){ $sel = "selected";}else{$sel="";}
               echo "<option value='".$id_garrison."' ".$sel.">".strtoupper($info['name'])." - ".$str_veic."</option>";
               echo "</optgroup>";
             }

             $sql = "SELECT
                        U.name as user_name, U.registration, U.nickname as user_nickname,
                        G.*,
                        F.plate,
                        F.TYPE,
                        F.model,
                        F.brand,
                        F.nickname
                      FROM
                        sepud.oct_garrison G
                      JOIN sepud.oct_fleet F ON F.id = G.id_fleet
                      JOIN sepud.oct_rel_garrison_persona GP ON GP.id_garrison = G.id AND GP.type = 'Motorista'
                      JOIN sepud.users U ON U.id = GP.id_user
                      WHERE
                          G.id_workshift = '".$turno_aberto['id']."'
                      AND G.closed is null";
             $res = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);;
             if(pg_num_rows($res))
             {
               echo "<optgroup label='Guarnições ativas - Modelo antigo:'>";
               while($dg = pg_fetch_assoc($res))
               {
                 if($dados['id_garrison']==$dg['id']){ $sel = "selected";}else{$sel="";}
                 echo "<option value='".$dg['id']."' ".$sel.">".$dg['nickname']." - ".$dg['plate'].": ".$dg['user_nickname']."</option>";
               }
               echo "</optgroup>";
             }

       echo "</select></div>";


     }

    ?>
  </div>
</div>


           <div class="row">
             <div class="col-sm-6">

                 <div class="form-group">
                     <label class="control-label">Veículo:</label>
                      <select id="id_vehicle" name="id_vehicle" class="form-control">
                        <option value="">- - -</option>
                          <?
                              $sql = "SELECT
                                        	VE.*,
                                        	(SELECT COUNT ( * ) FROM sepud.oct_victim WHERE id_vehicle = VE.ID) AS qtd_vitimas
                                        FROM
                                        	sepud.oct_vehicles VE
                                        WHERE
                                        	VE.id_events = '".$id."'
                                        ORDER BY VE.id ASC";
                              $res = pg_query($sql) or die("Erro ".__LINE__);
                              while($d = pg_fetch_assoc($res))
                              {
                                if($dados['id_vehicle']==$d['id']){ $sel = "selected"; }else{ $sel = "";}
                                if($d['licence_plate'] != ""){ $placa =  " (Placa: ".$d['licence_plate'].")"; }else{ $placa = "";}
                                echo "<option value='".$d['id']."' ".$sel.">".$d['description'].$placa."</option>";
                              }
                          ?>
                      </select>
                 </div>

               </div>


             <div class="col-sm-6">
               <?


               ?>
               <div class="form-group">
                   <label class="control-label">Envolvido:</label>
                    <select id="id_victim" name="id_victim" class="form-control">
                      <option value="">- - -</option>
                      <?
                            $sql = "SELECT
                                       VI.id as vitima_id,
                                       VI.description as vitima_desc,
                                       VE.id as veiculo_id,
                                       VE.description as veiculo_desc,
                                       *
                                     FROM
                                       sepud.oct_victim VI
                                     LEFT JOIN sepud.oct_vehicles VE ON VE.ID = VI.id_vehicle
                                     WHERE
                                       VI.id_events = '".$id."'";
                            $res = pg_query($sql) or die("Erro ".__LINE__);

                            while($d = pg_fetch_assoc($res))
                            {
                              if($dados['id_victim']==$d['vitima_id']){ $sel = "selected"; }else{ $sel = "";}
                              echo "<option value='".$d['vitima_id']."' ".$sel.">".$d['name']."</option>";
                            }
                      ?>
                    </select>
               </div>
             </div>

           </div>



           <div class="row">
             <div class="col-sm-6">

                 <div class="form-group">
                     <label class="control-label">Hospital:</label>
                      <select id="id_hospital" name="id_hospital" class="form-control">
                        <option value="">- - -</option>
                          <?
                              $sql = "SELECT
                                          H.*
                                        FROM
                                          sepud.hospital H
                                        ORDER BY H.name ASC";
                              $res = pg_query($sql) or die("Erro ".__LINE__);
                              while($d = pg_fetch_assoc($res))
                              {
                                if($dados['id_hospital']==$d['id']){ $sel = "selected"; }else{ $sel = ""; }
                                echo "<option value='".$d['id']."' ".$sel.">".$d['name']."</option>";
                              }
                          ?>
                      </select>
                 </div>

               </div>


             <div class="col-sm-6">
               <div class="form-group">
                   <label class="control-label">Orgão:</label>
                    <select id="id_company" name="id_company" class="form-control">
                      <option value="">- - -</option>
                      <?
                          $sql = "SELECT
                                  	*
                                  FROM
                                  	sepud.company
                                  ORDER BY
                                  	name ASC";

                          $res = pg_query($sql) or die("Erro ".__LINE__);
                          while($d = pg_fetch_assoc($res))
                          {
                            if($dados['id_company_requested']==$d['id']){ $sel = "selected"; }else{ $sel = ""; }
                            echo "<option value='".$d['id']."' ".$sel.">".$d['acron']." - ".$d['name']."</option>";
                          }
                      ?>
                    </select>
               </div>
             </div>

           </div>


           <div class="row">


               <div class="col-sm-6">
                 <div class="form-group">
                     <label class="control-label">Data:</label>
                     <input onclick="$(this).val('');" type="text" id="data" name="data" value="<?=($dados['opened_date']!=""?substr(formataData($dados['opened_date'],1),0,10):$agora['data']);?>" class="form-control campo_data"/>
                  </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Hora:</label>
                    <input onclick="$(this).val('');" type="text" id="hora" name="hora" value="<?=($dados['opened_date']!=""?substr(formataData($dados['opened_date'],1),11,5):$agora['hm']);?>" class="form-control campo_hora"/>
                 </div>
             </div>


          </div>

            <!-- ========================================================= -->
          </div><!--<div class="col-sm-8"> FORM PRINCIPAL-->
          <div class="col-sm-6">
            <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                    <label class="control-label">Observações:</label>
                        <textarea name="description" placeholder="Descrição detalhada providência tomada." rows="7" class="form-control"><?=$dados['observation'];?></textarea>
                   </div>
                 </div>
            </div>

            <div class="row">
                  <div class="col-sm-12 text-center" style="margin-top:28px">
                      <input type="hidden" name="id"                             value="<?=$id;?>">
                      <input type="hidden" name="id_providence"                  value="<?=$_GET['id_providence'];?>">
                      <input type="hidden" name="id_workshift"                   value="<?=$_GET['id_workshift'];?>">
                    <!--  <input type="hidden" name="victim_sel"                     value="<?=$victim_sel;?>">-->
                      <input type="hidden" name="acao"                           value="<?=$acao?>">
                      <input type="hidden" name="retorno_acao" id="retorno_acao" value="">
                      <a class="btn btn-default loading" href='oct/FORM.php?id=<?=$_GET['id']?>'>Voltar</a>

                      <? if($acao == "inserir"){ ?>
                      <button id='bt_inserir_voltar'    type='submit' class="btn btn-primary loading" role="button">Inserir e voltar</button>
                      <button id='bt_inserir_continuar' type='button' class="btn btn-primary loading" role="button">Inserir e continuar</button>
                      <? }else{ ?>
                        <a href="oct/FORM_providencias.php?id_workshift=<?=$_GET['id_workshift'];?>&id=<?=$id;?>"  class="mb-xs mt-xs mr-xs btn btn-default"><i class="fa fa-user"></i> Nova providencia</a><br>
                        <button id='bt_atualizar_voltar'    type='submit' class="btn btn-primary loading" role="button">Atualizar e voltar</button>
                        <button id='bt_atualizar_continuar' type='button' class="btn btn-primary loading" role="button">Atualizar e continuar</button>
                      <? } ?>
                  </div>
            </div>


          </div><!--<div class="col-sm-4"> FORM LATERAL-->
        </div><!--<div class="row">-->


          <div class="row">
            <div class="col-sm-12" style="margin-top:<?=$margin_upd;?>">
              <hr>
            </div>
          </div>

        <div class="row">
          <div class="col-sm-12" style="margin-top:15px">

            <!-- ========================================================= -->

                                            <?
                                              $sql = "SELECT
                                                             U.name,
                                                             C.acron, C.name as company,
                                                             VE.description as vehicle, VE.color as vehicle_color, VE.licence_plate,
                                                             VI.name as victim_name, VI.age as victim_age,
                                                             H.name as hospital,
                                                             CO.acron as company_acron, CO.name as company_name,
                                                             PR.area, PR.providence,
                                                             P.*
                                                      FROM sepud.oct_rel_events_providence P
                                                      JOIN sepud.users U ON U.id = P.id_owner
                                                      JOIN sepud.company C ON C.id = U.id_company
                                                      LEFT JOIN sepud.oct_vehicles VE ON VE.id = P.id_vehicle
                                                      LEFT JOIN sepud.oct_victim   VI ON VI.id = P.id_victim
                                                      LEFT JOIN sepud.hospital      H ON  H.id = P.id_hospital
                                                      LEFT JOIN sepud.company      CO ON CO.id = P.id_company_requested
                                                      JOIN sepud.oct_providence    PR ON PR.id = P.id_providence
                                                      WHERE P.id_event = '".$id."'
                                                      ORDER BY P.opened_date DESC";
                                                $res = pg_query($sql)or die("Erro ".__LINE__."<hr><pre>".$sql."</pre>");


                                              if(pg_num_rows($res))
                                              {
                                                  while($p = pg_fetch_assoc($res))
                                                  {

                                                    echo "<table class='table table-condensed'>";
                                                      echo "<tr bgcolor='#dbe9ff'>";
                                                        echo "<td>ID: ".$p['id']." - ".$p['area']." - ";
                                                        echo "<b>".$p['providence']."</b></td>";
                                                        echo "<td  width='150px' align='center'>".formataData($p['opened_date'],1)."</td>";
                                                        //echo "<td  width='150px' align='center'>".formataData($p['closed_date'],1)."</td>";
                                                      echo "<td colspan='3' class='text-center'>Ações</td></tr>";
                                                      echo "<tr>";
                                                        echo "<td colspan='3'>";


                                                                  echo "<table class='table'>";
                                                                  echo "<tr><td width='50'><span style='color:#CCCCCC'>Observações:</span></td><td colspan='3'>";
                                                                  if($p['observation'] != ""){ echo $p['observation']; }else{ echo "<span style='color:#CCCCCC'>Nenhuma anotação de observação para essa providência.</span>";}
                                                                  echo "</td></tr>";

                                                                    if(isset($p['vehicle']) || isset($p['victim_name']) || isset($p['hospital']) || isset($p['company_name']))
                                                                    {
                                                                      //echo "<hr><span style='color:#CCCCCC'>Envolvidos: </span>";

                                                                      echo "<tr>";
                                                                      if(isset($p['vehicle'])){      echo "<td width='50'><span style='color:#CCCCCC'>Veículo:</span></td><td>".$p['vehicle'].", ".$p['vehicle_color']." - ".$p['licence_plate']."</td>"; }
                                                                      if(isset($p['victim_name'])){  echo "<td width='50'><span style='color:#CCCCCC'>Vítima:</span></td><td>".$p['victim_name'];
                                                                                                     if(isset($p['victim_age'])){ echo ", idade: ".$p['victim_age']." ano(s)"; }
                                                                                                     echo  "</td>"; }


                                                                      echo "</tr><tr>";

                                                                      if(isset($p['hospital'])){     echo "<td width='50'><span style='color:#CCCCCC'>Hospital:</span></td><td>".$p['hospital']."</td>"; }
                                                                      if(isset($p['company_name'])){ echo "<td width='50'><span style='color:#CCCCCC'>Orgão:</span></td><td>".$p['company_name']."</td>";}

                                                                      echo "</tr>";

                                                                    }
                                                                    echo "</table>";

                                                            echo "<td class='text-center' width='50px'><a href='oct/FORM_providencias_sql.php?id_workshift=".$_GET['id_workshift']."&id=".$id."&id_providence=".$p['id']."&acao=remover'><button type='button' class='mb-xs mt-xs mr-xs btn btn-xs btn-danger'><i class='fa fa-trash'></i></button></a></td>";
                                                            echo "<td class='text-center' width='50px'><a href='oct/FORM_providencias.php?id_workshift=".$_GET['id_workshift']."&id=".$id."&id_providence=".$p['id']."'><button type='button' class='mb-xs mt-xs mr-xs btn btn-xs btn-primary'><i class='fa fa-cogs'></i></button></a></td>";
                                                            
                                                        echo "</td>";
                                                      echo "</tr>";

                                                      echo "<tr bgcolor='#eeeeee'>";
                                                        echo "<td colspan='5' align='right'>".$p['name']."<br><small>".$p['acron']." - ".$p["company"]."</small></td>";
                                                      echo "</tr>";



                                                    echo "</table>";
                                                  }
                                              }else{
                                                    echo "<div class='alert alert-warning text-center'>Nenhuma providência cadastrada para esta ocorrência.</div>";
                                              }



                                            ?>

            <!-- ========================================================= -->

          </div>
        </div>



    </div>
    <footer class="panel-footer">
    </footer>
  </section>
</section>
</form>
<script>
$(".campo_hora").mask('00:00');
$(".campo_data").mask('00/00/0000');
$('.select2').select2();
$(".loading").click(function(){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde</small>");});
$(".loading2").click(function(){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i>");});

$("#bt_inserir_continuar").click(function(){
    $("#retorno_acao").val("continuar");
    $("#form_providencias").submit();
});
$("#bt_atualizar_continuar").click(function(){
    $("#retorno_acao").val("continuar");
    $("#form_providencias").submit();
});
</script>
