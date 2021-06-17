<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora = now();

  if($_GET['id']!="")
  {
      $acao = "atualizar";
      $sql  = "SELECT
                  	W.*
                  FROM {$schema}gsec_workteam W
                  WHERE W.id = '{$_GET['id']}'";
      $res  = pg_query($sql)or die("<div class='text-center text-danger'>Error: ".__LINE__."<br>SQL {$sql}</div>");
      $d    = pg_fetch_assoc($res);

      logger("Acesso","GSEC - EQUIPES", "Cadastro de EQUIPES - Visualização detalhada ID: {$_GET['id']}");
  }else{
      $acao = "inserir";
      logger("Acesso","GSEC - EQUIPES", "Cadastro de EQUIPES - Novo cadastro");
  }

if($_GET['nav']!=""){ $nav[$_GET['nav']] = "active"; }else{$nav['cadastro']="active";}

?>

<style>.link:hover{ cursor: pointer; }</style>
<section role="main" class="content-body">
      <header class="page-header">
       <h2>Cadastro do equipes de trabalho</h2>
       <div class="right-wrapper pull-right" style='margin-right:15px;'>
         <ol class="breadcrumbs">
           <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
           <li><a href='gsec/workteam.php'><span>Equipes de trabalho</span></a></li>
           <li><span >Cadastro</span></li>
         </ol>
       </div>
      </header>


      <section class="panel box_shadow">

               <header class="panel-heading " style="height:70px">
                  <div class="panel-actions" style="margin-top:5px">
                  <?
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
										<a href="#cadastro" data-toggle="tab" ajax="false"><i class="fa fa-user"></i> Dados Cadastrais</a>
									</li>
                           <? if($acao=="atualizar"){ ?>
                           <li class="<?=$nav['historico'];?>">
										<a href="#membros" data-toggle="tab" ajax="false"><i class="fa fa-file-o"></i> Membros</a>
									</li>
                           <? } ?>
								</ul>
								<div class="tab-content">
									<div id="cadastro" class="tab-pane <?=$nav['cadastro'];?>">
<!------------------------------------------------------------------------------------------------------------>
<!------------------------------------------------------------------------------------------------------------>
<form action="gsec/workteam_FORM_sql.php" method="post">


   <div class='row'>
      <div class='col-md-6'>
         <div class='row'>
            <div class='col-md-12'>
               <div class='form-group'>
                  <label class='control-label' for='name'>Nome da equipe:</label>
                  <input type='text' class='form-control' id='name' name='name' placeholder='Nome' value='<?=$d['name'];?>' required>
               </div>
            </div>
         </div>
         <div class='row'>
            <div class='col-md-2'>
               <div class='form-group'>
                     <label class='control-label' for='type'>Tipo:</label>
                     <select name="type" class="form-control select2">
                        <option value="Interna">Interna</option>
                        <option value="Terceira">Terceira</option>
                     </select>
               </div>
            </div>
         </div>
      </div>
      <div class='col-md-6'>
         <div class='form-group'>
            <label class='control-label' for='description'>Descrição:</label>
            <textarea class='form-control' id='description' name='description' rows='4'><?=$d['description'];?></textarea>
         </div>
      </div>
   </div>


<div class="row text-center" style="margin-top:20px">
   <div class="col-md-12">
         <input type='hidden' id='acao' name='acao' value='<?=$acao;?>' >
         <a href='gsec/workteam.php'><button type='button' class='btn btn-default loading'>Voltar</button></a>
         <input type="hidden" name="id_company" value="<?=$_SESSION['id_company'];?>" />
         <?
            if($acao=="atualizar"){
               if(check_perm("9_28","D")){ echo "<a href='gsec/workteam_FORM_sql.php?acao=remover&id={$d['id']}'><button type='button' class='btn btn-danger loading' onclick='this.disabled=true;'><i class='fa fa-trash'></i> Remover</button></a>"; }
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
<div id="membros" class="tab-pane <?=$nav['membros'];?>">
   <div class='row'>
      <div class='col-sm-12'>
         <form>
            <div class='col-md-6'>
               <div class='form-group'>
                  <label class='control-label' for='description'>Descrição:</label>
                  <textarea class='form-control' id='description' name='description' rows='4' placeholder="Observações"><?=$d['description'];?></textarea>
               </div>
            </div>
         </form>
      </div>
   </div>
<div class='row'>
   <div class='col-sm-12'>

   <?
         $sql = "SELECT * FROM {$schema}gsec_workteam_members WHERE id_workteam = '{$_GET['id']}'";
         $res = pg_query($sql)or die("SQL Error: ".__LINE__."<br>Query: {$sql}");
         $c=1;
         if(pg_num_rows($res))
         {
            echo "<table class='table table-striped table-condensed'>";
            echo "<thead><tr>";
            echo "<th width='10px'>#</th>";
            echo "<th>Nome</th>";
            echo "<th>tipo</th>";
            echo "<th>Descrição</th>";
            echo "</tr></thead>";
            echo "<tbody>";
            while($hist = pg_fetch_assoc($res))
            {
               echo "<tr>";
                  echo "<td>{$hist['id']}</td>";
                  echo "<td>{$hist['name']}</td>";
                  echo "<td>{$hist['type']}</td>";
                  echo "<td>{$hist['description']}</td>";
               echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
         }else{
            echo "<div class='alert alert-info'>Nenhum membro ou equipamento registrado para essa equipe.</div>";
         }

   ?>
   </div>
</div>
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
$(".select2").select2();
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
