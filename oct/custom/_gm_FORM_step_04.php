<?
  session_start();
  require_once("../../libs/php/funcoes.php");
  require_once("../../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");

  $dt = formataData($dados['date'],1);
  $data = $dt;
  $aux  = explode(" ",$data);
  $data = $aux[0];
  $hora = $aux[1];
?>


    <div class="row">
          <div class="col-sm-4">

            <div class="form-group">
                <label class="control-label">Status:</label>
                <select  class='form-control select2 input-lg' id='initial_status' name="initial_status">
<optgroup label="Em atividade">
                      <option value="Aberta"               <?=($dados['status']=="Aberta"?"selected":"")?>>Aberta</option>
                      <option value="Em deslocamento"      <?=($dados['status']=="Em deslocamento"?"selected":"")?>>Em deslocamento</option>
                      <option value="Em atendimento"       <?=($dados['status']=="Em atendimento"?"selected":"")?>>Em atendimento</option>
                      <option value="Encaminhamento"       <?=($dados['status']=="Encaminhamento"?"selected":"")?>>Encaminhamento</option>
                      <option value="Ocorrência terminada" <?=($dados['status']=="Ocorrência Terminada"?"selected":"")?>>Ocorrência Terminada</option>
</optgroup>
<optgroup label="Agendamento">
                      <option value="Inativa" <?=($dados['status']=="Oc. Inativa (Futura)"?"selected":"")?>>Oc. Inativa (Futura)</option>
<optgroup label="Cancelamento">
                      <option value="Ocorrência cancelada - Evadido/Não localizado" <?=($dados['status']=="Ocorrência cancelada - Evadido/Não localizado"?"selected":"")?>>Evadido/Não localizado</option>
                      <option value="Ocorrência cancelada - trote" <?=($dados['status']=="Ocorrência cancelada - trote"?"selected":"")?>>Trote</option>
                      <option value="Ocorrência cancelada - Central de atendimento" <?=($dados['status']=="Ocorrência cancelada - Central de atendimento"?"selected":"")?>>Cancelada pela central de atendimento</option>
                      <option value="Ocorrência cancelada - Sem recurso" <?=($dados['status']=="Ocorrência cancelada - Sem recurso"?"selected":"")?>>Sem recurso</option>
</optgroup>
                </select>
              </div>

          </div>
          <div class="col-sm-4">
            <div class="form-group">
            <label class="control-label">Data:</label>
                <input onclick="$(this).val('');" type="text" name="data" class="form-control changefield campo_data input-lg" value="<?=($acao=="inserir"?$agora['data']:$data);?>">
           </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
            <label class="control-label">Hora:</label>
                <input onclick="$(this).val('');" type="time" name="hora" class="form-control changefield campo_hora input-lg" value="<?=($acao=="inserir"?$agora['hm']:$hora);?>">
           </div>
          </div>
    </div>
<script>

</script>
