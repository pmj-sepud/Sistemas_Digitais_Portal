<?php
//////////////////////////////////////////////////////////////////////////////
if(getenv("DB_HOST")==""){ setenvs(); }
function setenvs()
{
  $basedir = $_SERVER['DOCUMENT_ROOT'];
  $envfile = ".env";
  if(file_exists($basedir."/".$envfile))
  {
    $arq = file($basedir."/".$envfile);
    for($i=0;$i<count($arq);$i++)
    {
      putenv($arq[$i]);
    }
  }
}
function check_perm($perm, $tipo="")
{
    $offset = array("C"=>0, "R"=>1, "U"=>2, "D"=>3);
    if($tipo=="") //Permissão do tipo ação (1 byte)
    {
      if(strlen($_SESSION['permissoes'][$perm])==1) //Permissão do tipo acao (1 byte)
      {
        return ($_SESSION['permissoes'][$perm]?true:false);
      }else{
        return false;
      }
    }else{ //Permissão do tipo CRUD (4 bytes)
      return ($_SESSION['permissoes'][$perm][$offset[$tipo]]?true:false);
    }

}
//////////////////////////////////////////////////////////////////////////////
function codificar($dados,$funcao,$debug=0)
{
  //$cipher    = "aes-256-ccm";
  $cipher    = "aes-256-gcm";
  $key       = getenv("SECRET_KEY");
  if (in_array($cipher, openssl_get_cipher_methods()) && isset($key) && strlen($key) && isset($dados) && strlen($dados))
  {
    switch ($funcao) {
      case 'c':
          $iv         = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
          $ciphertext = openssl_encrypt($dados, $cipher, $key, $options=0, $iv, $tag);
          $retorno    = base64_encode(bin2hex($tag).".".bin2hex($iv).".".bin2hex($ciphertext));
          if($debug)
          {
            echo "<pre>Criptografia:<br>";
            if($key!=""){ echo "<br>Secret key ok !"; }
            echo "<br>TAG: ".bin2hex($tag)."<br>IV: ".bin2hex($iv)."<br>Cipher: ".bin2hex($ciphertext);
            echo "<br>Retorno: ".$retorno;
            echo "</pre>";

          }
          return $retorno;
        break;
      case 'd':
          $auxb64             = explode(".",base64_decode($dados));
          $original_plaintext = openssl_decrypt(hex2bin($auxb64[2]), $cipher, $key, $options=0, hex2bin($auxb64[1]), hex2bin($auxb64[0]));
          if($debug)
          {
            echo "<pre>Descriptografia:<br>";
            if($key!=""){ echo "<br>Secret key ok !"; }
            echo "<br>TAG: ".$auxb64[0]."<br>IV: ".$auxb64[1]."<br>Cipher: ".$auxb64[2];
            echo "<br>Retorno: ".$original_plaintext;
            echo "</pre>";
          }
          return $original_plaintext;
        break;
      default:
        return null;
        break;
    }
  }
  return null;
}

function logger($action, $module = "Null", $obs = "Null")
{
  if($module != "Null"){ $module = "'".$module."'"; }

  $id_user = ($_SESSION['id']!=""?$_SESSION['id']:"Null");

  $agora = now();
	$sql = "INSERT INTO sepud.logs(ip, id_user, module, action, timestamp, obs)
					VALUES ('".$_SERVER['REMOTE_ADDR']."', $id_user, ".$module.",'".$action."','".$agora['datatimesrv']."', '".$obs."')";
	pg_query($sql)or die("Erro ".__LINE__."<hr>".pg_last_error()."<hr>".$sql);
}

//////////////////////////////////////////////////////////////////////////////
function print_r_pre($arr){ echo "<pre>"; print_r($arr); echo "</pre>";}
//////////////////////////////////////////////////////////////////////////////
function array2utf8(&$arr)
	{
		foreach($arr as $chave => $valor)
		{
			  if(!mb_check_encoding($valor, 'UTF-8'))
			  {
				  $arr[$chave] = utf8_encode($valor);
			  }
		}
	}
//////////////////////////////////////////////////////////////////////////////
function now($ret = "todos"){

	$mkt                = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('y'));
	$data['dia']        = date('d',$mkt);
	$data['mes']        = date('m',$mkt);
	$data['ano']        = date('Y',$mkt);
	$data['hora']       = date('H',$mkt);
	$data['min']        = date('i',$mkt);
	$data['seg']        = date('s',$mkt);
	$data['data']       = $data['dia'].'/'.$data['mes'].'/'.$data['ano'];
	$data['datasrv']    = $data['ano'].'-'.$data['mes'].'-'.$data['dia'];
  $data['datatimesrv']= $data['ano'].'-'.$data['mes'].'-'.$data['dia']." ".$data['hora'].':'.$data['min'].':'.$data['seg'];
	$data['hm']         = $data['hora'].':'.$data['min'];
	$data['hms']        = $data['hora'].':'.$data['min'].':'.$data['seg'];
	$data['dthm']       = $data['data']." ".$data['hm'];
	$data['dthms']      = $data['data']." ".$data['hm'].":".$data['seg'];
	$data['mkt']        = $mkt;
	$data['ultimo_dia'] = date('t',$mkt);
  $data['dia_semana'] = date('N',$mkt);

	switch($data['mes'])
	{
		case '01': $data['mes_txt_c'] = "Jan"; $data['mes_txt'] = "Janeiro";   break;
		case '02': $data['mes_txt_c'] = "Fev"; $data['mes_txt'] = "Fevereiro"; break;
		case '03': $data['mes_txt_c'] = "Mar"; $data['mes_txt'] = "Março";     break;
		case '04': $data['mes_txt_c'] = "Abr"; $data['mes_txt'] = "Abril";     break;
		case '05': $data['mes_txt_c'] = "Mai"; $data['mes_txt'] = "Maio";      break;
		case '06': $data['mes_txt_c'] = "Jun"; $data['mes_txt'] = "Junho";     break;
		case '07': $data['mes_txt_c'] = "Jul"; $data['mes_txt'] = "Julho";     break;
		case '08': $data['mes_txt_c'] = "Ago"; $data['mes_txt'] = "Agosto";    break;
		case '09': $data['mes_txt_c'] = "Set"; $data['mes_txt'] = "Setembro";  break;
		case '10': $data['mes_txt_c'] = "Out"; $data['mes_txt'] = "Outubro";   break;
		case '11': $data['mes_txt_c'] = "Nov"; $data['mes_txt'] = "Novembro";  break;
		case '12': $data['mes_txt_c'] = "Dez"; $data['mes_txt'] = "Dezembro";  break;
	}

if($ret == "todos"){ return $data;      }
else{				 return $data[$ret];}

}
//////////////////////////////////////////////////////////////////////////////
function formataData($data,$modelo)
{
$tmp = explode(" ",$data);

if($data != "" && $modelo != 4){
	$aux = explode("-",$tmp[0]);
	if($modelo == 1){ $data =  $aux[2]."/".$aux[1]."/".$aux[0]; }
	if($modelo == 2){ $data =  $aux[2]."-".$aux[1]."-".$aux[0]; }
	if($modelo == 3){ $data =  $aux[2].$aux[1].$aux[0]; }
}
if($data != "" && $modelo == 4){
	 // 0  1   2
	// DD/MM/YYYY
	$aux = explode("/",$tmp[0]);
 	$data =  $aux[2]."-".$aux[1]."-".$aux[0];
}
if($aux[1] != ""){  return $data." ".$tmp[1]; }
			 else{	return $data; 			  }

}


function mkt2date($mkt){
	if($mkt != ""){
      $data['dia']        = date('d',$mkt);
      $data['mes']        = date('m',$mkt);
      $data['ano']        = date('Y',$mkt);
      $data['hora']       = date('H',$mkt);
      $data['min']        = date('i',$mkt);
      $data['seg']        = date('s',$mkt);
      $data['data']       = $data['dia'].'/'.$data['mes'].'/'.$data['ano'];
      $data['datasrv']    = $data['ano'].'-'.$data['mes'].'-'.$data['dia'];
      $data['datatimesrv']= $data['ano'].'-'.$data['mes'].'-'.$data['dia']." ".$data['hora'].':'.$data['min'].':'.$data['seg'];
      $data['hm']         = $data['hora'].':'.$data['min'];
      $data['hms']        = $data['hora'].':'.$data['min'].':'.$data['seg'];
      $data['dthm']       = $data['data']." ".$data['hm'];
      $data['dthms']      = $data['data']." ".$data['hm'].":".$data['seg'];
      $data['mkt']        = $mkt;
      $data['ultimo_dia'] = date('t',$mkt);
      $data['dia_semana'] = date('N',$mkt);
			switch($data['mes'])
			{
				case '01': $data['mes_txt_c'] = "Jan"; $data['mes_txt'] = "Janeiro";   break;
				case '02': $data['mes_txt_c'] = "Fev"; $data['mes_txt'] = "Fevereiro"; break;
				case '03': $data['mes_txt_c'] = "Mar"; $data['mes_txt'] = "Março";     break;
				case '04': $data['mes_txt_c'] = "Abr"; $data['mes_txt'] = "Abril";     break;
				case '05': $data['mes_txt_c'] = "Mai"; $data['mes_txt'] = "Maio";      break;
				case '06': $data['mes_txt_c'] = "Jun"; $data['mes_txt'] = "Junho";     break;
				case '07': $data['mes_txt_c'] = "Jul"; $data['mes_txt'] = "Julho";     break;
				case '08': $data['mes_txt_c'] = "Ago"; $data['mes_txt'] = "Agosto";   break;
				case '09': $data['mes_txt_c'] = "Set"; $data['mes_txt'] = "Setembro";  break;
				case '10': $data['mes_txt_c'] = "Out"; $data['mes_txt'] = "Outubro";   break;
				case '11': $data['mes_txt_c'] = "Nov"; $data['mes_txt'] = "Novembro";  break;
				case '12': $data['mes_txt_c'] = "Dez"; $data['mes_txt'] = "Dezembro";  break;
			}
			return $data;
	}else{
		return Null;
	}
}
function date2mkt($data){

		$dtAux = explode(" ",$data);
		if(count($dtAux) == 2){
			$dt   = explode("/",$dtAux[0]);
			$hora = explode(":",$dtAux[1]);
			return mktime($hora[0],$hora[1],$hora[2],$dt[1],$dt[0],$dt[2]);
		}else{
			$dt   = explode("/",$dtAux[0]);
			return mktime(0,0,0,$dt[1],$dt[0],$dt[2]);
		}
}
?>
