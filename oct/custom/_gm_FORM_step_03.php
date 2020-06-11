<?
  session_start();
  require_once("../../libs/php/funcoes.php");
  require_once("../../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");

?>


    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                  <label class="control-label">Descrição detalhada:</label>
                  <textarea name="description" id="description" class="form-control changefield" rows="10"><?=$dados['description'];?></textarea>
            </div>
        </div>
    </div>


    <div class="row">
          <div class="col-sm-8">
            <div class="form-group">
            <label class="control-label">Solicitante/Reclamante:</label>
                <input type="text" name="requester" class="form-control changefield input-lg" value="<?=$dados['requester'];?>">
           </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
            <label class="control-label">Telefone:</label>
                <input type="text" name="requester_phone" class="form-control changefield input-lg" value="<?=$dados['requester_phone'];?>">
           </div>
          </div>
     </div>

     <div class="row">
       <div class="col-sm-8">
         <div class="form-group">
         <label class="control-label">Origem da solicitação:</label>
             <select name="requester_origin" class="form-control changefield select2">
                <option value=''></option>
                <option value='Carta' <?=($dados['requester_origin']=="Carta"?"selected":"");?>>Carta</option>
                <option value='Central 153' <?=($dados['requester_origin']=="Central 153"?"selected":"");?>>Central 153</option>
                <option value='Comunicado' <?=($dados['requester_origin']=="Comunicado"?"selected":"");?>>Comunicado</option>
                <option value='E-mail' <?=($dados['requester_origin']=="E-mail"?"selected":"");?>>E-mail</option>
                <option value='Indicação' <?=($dados['requester_origin']=="Indicação"?"selected":"");?>>Indicação</option>
                <option value='Memorando' <?=($dados['requester_origin']=="Memorando"?"selected":"");?>>Memorando</option>
                <option value='Ocorrência' <?=($dados['requester_origin']=="Ocorrência"?"selected":"");?>>Ocorrência</option>
                <option value='Ofício' <?=($dados['requester_origin']=="Ofício"?"selected":"");?>>Ofício</option>
                <option value='Ouvidoria' <?=($dados['requester_origin']=="Ouvidoria"?"selected":"");?>>Ouvidoria</option>
                <option value='Pessoalmente' <?=($dados['requester_origin']=="Pessoalmente"?"selected":"");?>>Pessoalmente</option>
                <option value='Protocolo' <?=($dados['requester_origin']=="Protocolo"?"selected":"");?>>Protocolo</option>
                <option value='Requerimento' <?=($dados['requester_origin']=="Requerimento"?"selected":"");?>>Requerimento</option>
                <option value='SEI' <?=($dados['requester_origin']=="SEI"?"selected":"");?>>SEI</option>
                <option value='Telefone' <?=($dados['requester_origin']=="Telefone"?"selected":"");?>>Telefone</option>
             </select>
        </div>
       </div>
       <div class="col-sm-4">
         <div class="form-group">
         <label class="control-label">Protocolo:</label>
             <input type="text" name="requester_protocol" class="form-control changefield input-lg" value="<?=$dados['requester_protocol'];?>">
        </div>
       </div>
    </div>
<script>

  $("#description").keyup(function(){
    if($(this).val().length >= 5)
    {
      $("#isok_03").removeClass("hidden");
    }else {
      $("#isok_03").addClass("hidden");
    }
  })
</script>
