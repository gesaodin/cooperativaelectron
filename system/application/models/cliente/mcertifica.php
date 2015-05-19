<?php
class MCertifica extends Model {

	var $color;
	public function __construct() {
		parent::Model();
	}
	
	function Enviar($ced){
		$msj = '';
		$sConsulta = "SELECT * FROM t_personas WHERE documento_id='" . $ced . "' LIMIT 1";
		$rsC = $this -> db -> query($sConsulta);
		if($rsC -> num_rows() == 0){
			$msj = 'El cliente no esta registrado';
		}else{
			$resp = $rsC -> result();
			foreach ($resp as $fila) {
				$correo = $fila -> correo;
				$nombre = $fila -> primer_nombre.' '.$fila -> primer_apellido;
			}
			if($correo == ''){
				$msj = 'El Cliente no posee correo electronico registrado.'; 
			}else{
				$sConsulta1 = "SELECT * FROM t_certificados WHERE cedula='" . $ced . "' LIMIT 1";
				$rsC1 = $this -> db -> query($sConsulta1);
				$cod = '';
				if($rsC1 -> num_rows() == 0){
					$cod = rand(1, 99999);
					$this -> db -> query('INSERT INTO t_certificados (cedula, codigo,correo) VALUES ('.$ced.','.$cod.',"'.$correo.'");');
				}else{
					$Conexion = $rsC1 -> result();
					foreach ($Conexion as $row) {
						$cod = $row -> codigo;
					}
					$this -> db -> query('UPDATE t_certificados SET correo ="'.$correo.'" WHERE cedula = '.$ced.';');
				}
				$msj = $this -> correo($correo,$cod,$nombre,$ced);	
			}
		}
		return $msj;
	}
	
	function correo($email,$cod,$nombre,$ced){
	    //error_reporting(E_STRICT);
	    require_once('system/application/libraries/PHPMail/class.phpmailer.php');
	    $mail = new PHPMailer();
	    $body                ='<body style="margin: 10px;">Prueba de Envio<br /></body>';//file_get_contents('');
	    //$body                = preg_replace('/[\]/','',$body);        
	    $mail->IsSMTP(); // telling the class to use SMTP
	    
	    $mail->SMTPDebug  = 1;
	    $mail->Host          = "smtp.gmail.com";
	    $mail->SMTPSecure = "tls";     
	    $mail->SMTPAuth      = true;                  // enable SMTP authentication
	    $mail->SMTPKeepAlive = true;                  // SMTP connection will not close after each email sent
	    
	    $mail->Port          = 587;    
	    $mail->Username      = "soporteelectron465@gmail.com"; // SMTP account username
	    $mail->Password      = "soporte8759";        // SMTP account password
	    $mail->SetFrom('soporteelectron465@gmail.com', 'Departamento de Ventas');
	    $mail->AddReplyTo('soporteelectron465@gmail.com', 'Despartamento de Ventas');
	    $mail->Subject = 'Grupo Electron (Codigo de Certificado)';    
	    
	    $cuerpo = '<table style="font-family: Trebuchet MS; font-size: 13px;text-align: justify;" width="0"><tr><td rowspan="2"  width=180><img src="' . __IMG__ . 'logoN.jpg" width=200></td>
	            </tr><tr><td colspan="3" >Apreciado/a:  <br>' . $nombre . '.<br> ' . $ced . '</td></tr>
	            <tr><td colspan="4">Recibe un caluroso saludo de parte de Grupo Electrón, mediante la presente te notificamos que ha sido enviado el 
	            c&oacute;digo de verificación para certificar su registro en nuestro sistema.<br><br>
	            <h2><center><font color="#0070A3">CODIGO:'.$cod.'</font></center></h2><br><br>Debe facilitar este código a nuestros(as) analistas de ventas en la oficina correspondiente a su registro o a través del número telef&oacute;nico: 0274-9358009<br>
	            </td>
	          </tr><tr><td colspan="4">Si tienes alguna pregunta o si necesitas alguna asistencia con respecto a esta comunicación, tienes a tu disposición a nuestro equipo de atenci&oacute;n al cliente a través del número </td></tr>
	          <tr><td colspan="4"><hr></hr><small>Muchas gracias por ser parte de la comunidad Electr&oacute;n 465.
Por favor, no responda este e-mail ya que ha sido enviado automáticamente. Usted ha recibido esta comunicación por tener una relación con Electrón 465. Esta comunicación forma parte básica de nuestro programa de atención al cliente. Si no desea seguir recibiendo este tipo de comunicaciones, le rogamos cancele por escrito su afiliación al mismo. 
Electrón 465 se compromete firmemente a respetar su privacidad. No compartimos su información con ningún tercero sin su consentimiento.</small>
	          </td></tr></table>';
	
	    $mail->AltBody    = "Texto Alternativo"; // optional, comment out and test
	    $mail->MsgHTML($cuerpo);
      $address = $email;
	    $mail->AddAddress($address, $nombre);
	    if(!$mail->Send()) {
	      return "Error al enviar: " . $mail->ErrorInfo;
	    } else {
	      return "Mensaje enviado a:  " .  $address . "!";
	    }
	}

	function Verifica($arr){
		$query = "SELECT * From t_certificados where cedula='".$arr['ced']."' and codigo=".$arr['cod'];
		//return $query;
		$rs = $this -> db -> query($query);
		if($rs -> num_rows() == 0){
			return "No fue posible realizar la verificacion. Compruebe los datos suminstrados";
		}else{
			$query2 = "UPDATE t_certificados SET verificado=1,tipo='".$arr['tipo']."' WHERE cedula='".$arr['ced']."' and codigo=".$arr['cod'];
			$this -> db -> query($query2);
			return "Se realizo la verificacion con exito";
		}
		
	}
}
?>
