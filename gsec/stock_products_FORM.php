<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora = now();

  if($_GET['id']!="")
  {
      $acao = "atualizar";
      $sql  = "SELECT P.*, T.name as product_type,
                     C.name as company_name, C.acron AS company_acron,
                     CF.name as company_father_name, CF.acron AS company_father_acron
               FROM sepud.gsec_stock_product P
               JOIN sepud.gsec_stock_product_type T ON T.id = P.id_product_type
               LEFT JOIN sepud.company C ON C.id = P.id_company
               LEFT JOIN sepud.company CF ON CF.id = P.id_company_father
               WHERE P.id = '{$_GET['id']}'
               ORDER BY P.name ASC";
      $res  = pg_query($sql)or die("<div class='text-center text-danger'>Error: ".__LINE__."<br>SQL {$sql}</div>");
      $d    = pg_fetch_assoc($res);

      logger("Acesso","GSEC - ESTOQUE", "Cadastro de produto - Visualização detalhada ID: {$_GET['id']}");
  }else{
      $acao = "inserir";
      logger("Acesso","GSEC - ESTOQUE", "Cadastro de produto - Novo cadastro");
  }

if($_GET['nav']!=""){ $nav[$_GET['nav']] = "active"; }else{$nav['cadastro']="active";}

?>

<style>.link:hover{ cursor: pointer; }</style>
<section role="main" class="content-body">
      <header class="page-header">
       <h2>Cadastro de produtos</h2>
       <div class="right-wrapper pull-right" style='margin-right:15px;'>
         <ol class="breadcrumbs">
           <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
           <li><a href='gsec/workteam.php'><span>Cadastro de produtos</span></a></li>
           <li><span >Cadastro</span></li>
         </ol>
       </div>
      </header>


      <section class="panel box_shadow">

               <header class="panel-heading " style="height:70px">
                  <div class="panel-actions" style="margin-top:5px">
                  <?
                  echo "<a href='gsec/stock_products_FORM.php'><button type='button' class='btn btn-success'><i class='fa fa-clipboard'></i><sup><i class='fa fa-plus'></i></sup> Novo produto</button></a>";
                  /*
                     if($acao=="atualizar"){
                        if(check_perm("9_28","C")){
                           echo "<a href='gsec/callcenter_FORM.php?id_user_request={$_GET['id']}'><button type='button' class='btn btn-success'><i class='fa fa-laptop'></i><sup><i class='fa fa-plus'></i></sup> Novo atendimento</button></a>";
                        }else {
                           echo "<button type='button' class='btn btn-success disabled'><i class='fa fa-laptop'></i><sup><i class='fa fa-plus'></i></sup> Novo atendimento</button>";
                        }
                     }
                  */
                  ?>
                  </div>
               </header>




               <div class="panel-body">

<div class="row">
						<div class="col-md-12">
							<div class="tabs">
								<ul class="nav nav-tabs">
									<li class="<?=$nav['cadastro'];?>">
										<a href="#cadastro" data-toggle="tab" ajax="false"><i class="fa fa-clipboard"></i> Dados Cadastrais</a>
									</li>

                           <? /* if($acao=="atualizar"){ ?>
                           <li class="<?=$nav['historico'];?>">
										<a href="#historico" data-toggle="tab" ajax="false"><i class="fa fa-file-o"></i> Histórico</a>
									</li>
                           <? } */ ?>
								</ul>
								<div class="tab-content">
									<div id="cadastro" class="tab-pane <?=$nav['cadastro'];?>">
<!------------------------------------------------------------------------------------------------------------>
<!------------------------------------------------------------------------------------------------------------>
<form action="gsec/stock_products_FORM_sql.php" method="post">
<div class='row'>
   <div class='col-md-6'>
      <div class='form-group'>
         <label class='control-label' for='name'>Produto:</label>
         <input type='text' class='form-control' id='name' name='name' placeholder='Nome' value='<?=$d['name'];?>' required>
      </div>
   </div>
   <div class='col-md-3'>
      <div class='form-group'>
         <label class='control-label' for='unit'>Unidade:</label>
         <select class='form-control select2' id='unit' name='unit'>
            <option value="Unid" <?=($d['unit']=="Unid"?"selected":"");?>>Unidade</option>
            <option value="M"    <?=($d['unit']=="M"?"selected":"");?>>Metro</option>
            <option value="M2"   <?=($d['unit']=="M2"?"selected":"");?>>Metro2</option>
            <option value="M3"   <?=($d['unit']=="M3"?"selected":"");?>>Metro3</option>
            <option value="Kg"   <?=($d['unit']=="Kg"?"selected":"");?>>Quilo</option>
            <option value="L"    <?=($d['unit']=="L"?"selected":"");?>>Litro</option>
         </select>
      </div>
   </div>

   <div class='col-md-3'>
      <div class='form-group'>
         <label class='control-label' for='name'>Categoria:</label>
         <?
            $sql = "SELECT * FROM {$schema}gsec_stock_product_type ORDER BY name ASC";
            $res = pg_query($sql)or die("SQL Error ".__LINE__);
         ?>
         <select class="form-control select2" id='id_product_type' name="id_product_type">
            <?
               while($p = pg_fetch_assoc($res)){
                  if($p['id']==$d['id_product_type']){$sel="selected";}else{$sel="";}
                  echo "<option value='{$p['id']}' {$sel}>{$p['name']}</option>";
               }
            ?>
         </select>
      </div>
   </div>
</div>

<div class='row'>
   <div class='col-md-6'>
      <div class='form-group'>
         <label class='control-label' for='description'>Descrição:</label>
         <textarea class='form-control' id='description' name='description' rows='4' placeholder="Descrição detalhada do produto ou equipamento"><?=$d['description'];?></textarea>
      </div>
   </div>
   <div class='col-md-6'>
      <div class='row'>
         <div class='col-md-12'>
            <div class='form-group'>
               <label class='control-label' for='name'>Setor:</label>
               <?
                  $sql = "SELECT id, name, acron, id_father FROM ".$schema."company WHERE active = 't' ORDER BY name ASC";
                  $res = pg_query($sql)or die();
                  while($comp = pg_fetch_assoc($res))
                  {
                      if($comp['id_father']!=""){
                        $orgao_filhos[$comp['id_father']]['filhos'][]=$comp;
                      }else{
                        $orgao[$comp['id']]=$comp;
                      }
                  }

                  foreach ($orgao_filhos as $id_pai => $filhos) { $orgao[$id_pai]['filhos'] = $filhos['filhos'];}
                  foreach ($orgao as $id_orgao => $orgao_dados){

                    if($acao=="inserir"){$d['id_company_father']=$_SESSION['id_company_father'];$d['id_company']=$_SESSION['id_company'];}
                    if($id_orgao==$d['id_company_father']){$selfather="selected";}else{$selfather="";}
                    $optfather .= "<option value='{$id_orgao}' {$selfather}>{$orgao_dados['name']}</option>";

                    $opt .= "<optgroup label='".$orgao_dados['name']."'>";
                        for($i=0;$i<count($orgao_dados['filhos']);$i++)
                        {
                         if($orgao_dados['filhos'][$i]['id'] == $d['id_company']){ $sel = "selected"; }else{ $sel = "";}
                         $opt .= "<option value='{$orgao_dados['filhos'][$i]['id']}' {$sel}>{$orgao_dados['filhos'][$i]['name']}</option>";
                        }
                    $opt .= "</option>";
                  }
               ?>
               <select class="form-control select2" id='id_company' name="id_company">
                  <option value=''>- - -</option>
                  <?=$opt;?>
               </select>
            </div>
         </div>
      </div>
      <div class='row'>
         <div class='col-md-12'>
            <div class='form-group'>
               <label class='control-label' for='name'>Órgão:</label>
               <select class="form-control select2" id='id_company_father' name="id_company_father">
                  <option value=''>- - -</option>
                  <?=$optfather;?>
               </select>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="row text-center" style="margin-top:20px">
   <div class="col-md-12">
         <input type='hidden' id='acao' name='acao' value='<?=$acao;?>' >
         <a href='gsec/stock_products.php'><button type='button' class='btn btn-default loading'>Voltar</button></a>
         <?
            if($acao=="atualizar"){
               //if(check_perm("9_28","D")){ echo "<a href='gsec/citizen_FORM_sql.php?acao=remover&id={$d['id']}'><button type='button' class='btn btn-danger loading' onclick='this.disabled=true;'><i class='fa fa-trash'></i> Remover</button></a>"; }
               echo "<input type='hidden' id='id'         name='id'         value='{$d['id']}' >";
               echo " <button type='submit' class='btn btn-primary loading'>Atualizar</button>";
            }else{
               echo " <button type='submit' class='btn btn-success loading'>Inserir</button>";
            }
         ?>
   </div>
</div>

<? if($_SESSION['error']!=""){ ?>
<div class="row text-center" style="margin-top:20px">
   <div class="col-md-12">
      <?
         echo "<div class='alert alert-danger'>".$_SESSION['error']."</div>";
         unset($_SESSION['error']);
      ?>
   </div>
</div>
<? } ?>
</form>
<!------------------------------------------------------------------------------------------------------------>
<!------------------------------------------------------------------------------------------------------------>
									</div>
<? if($acao=="atualizar"){ ?>

<!------------------------------------------------------------------------------------------------------------>
<!------------------------------------------------------------------------------------------------------------>

<!------------------------------------------------------------------------------------------------------------>
<!------------------------------------------------------------------------------------------------------------>
<div id="historico" class="tab-pane <?=$nav['historico'];?>">
   <?
         $sql = "SELECT U.name, C.acron as company_acron, L.date_added, L.info->'status' as status
                 FROM {$schema}gsec_logs L
                 JOIN {$schema}users U ON U.id = L.id_user
                 JOIN {$schema}company C ON C.id = U.id_company
                 WHERE L.table_origin = 'gsec_workteam' AND L.id_origin = {$_GET['id']}
                 ORDER BY L.date_added ASC";
         //$res = pg_query($sql)or die("SQL Error: ".__LINE__."<br>Query: {$sql}");
         $c=1;
         if(pg_num_rows($res))
         {
            echo "<table class='table table-striped table-condensed'>";
            echo "<thead><tr>";
            echo "<th>#</th>";
            echo "<th>Data</th>";
            echo "<th>Nome</th>";
            echo "<th>Setor</th>";
            echo "<th>Ação</th>";
            echo "<th>Dados</th>";
            echo "</tr></thead>";
            echo "<tbody>";
            while($hist = pg_fetch_assoc($res))
            {
               echo "<tr>";
                  echo "<td>".$c++."</td>";
                  echo "<td>".formataData($hist['date_added'],1)."</td>";
                  echo "<td>{$hist['name']}</td>";
                  echo "<td>{$hist['company_acron']}</td>";
                  echo "<td>".json_decode($hist['status'])."</td>";
                  echo "<td><i class='fa fa-search'></i></td>";
               echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
         }else{
            echo "<div class='alert alert-info'>Nenhuma informação de histórico registrado para este atendimento.</div>";
         }

   ?>
</div>
<!------------------------------------------------------------------------------------------------------------>
<!------------------------------------------------------------------------------------------------------------>
<? } ?>
								</div>
							</div>
						</div>
</div>



               </div>



      </section>
</section>


<script>
$(".link").click(function(){
   $('#wrap').load("gsec/callcenter_FORM.php?id="+$(this).attr("id"));
})
$(document).ready( function () {

});


$('.select2').select2({
   language: "pt-BR"
});
$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> Aguarde");});
$(".loading3").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> Aguarde").addClass("disabled");});
</script>
<?
function humanTiming($data)
{

    $time = strtotime($data);
    $time = time() - $time;
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'ano',
        2592000 => 'mes',
        604800 => 'semana',
        86400 => 'dia',
        3600 => 'hora',
        60 => 'min',
        1 => 'seg'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        if($text=="mes" && $numberOfUnits>1){ $ext = "es"; }else{ $ext = "s"; }
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?$ext:'');
    }

}
?>
