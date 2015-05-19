function enviar(){
	var ced = $("#ced").val();
	strUrl_Proceso = sUrlP + "Enviar_Certificacion";
	if (ced != '') {
		$.ajax({
			url : strUrl_Proceso,
			type : 'POST',
			data : "ced="+ced,
			success : function(resp) {
				alert(resp);
				$("#ced").val('');
			},
			error : function(err) {
				alert("NO SE PUDO ENCONTRAR LA CEDULA O correo no exite;");
				alert(err['responseText']);
				/*$.each(err, function(k, v) {
					alert(k+":"+v);
				});*/
			}
		});
	}else{
		alert('Debe ingresar Cedula');
		return 0;
	}
}

function verificar(){
	var ced = $("#cced").val();
	var cod = $("#cod").val();
	var tipo = $("#tipo").val();
	strUrl_Proceso = sUrlP + "Verificar_Certificacion";
	if (ced != '' && cod != '') {
		$.ajax({
			url : strUrl_Proceso,
			type : 'POST',
			data : "ced="+ced+'&cod='+cod+"&tipo="+tipo,
			success : function(resp) {
				alert(resp);
				$("#cced").val('');
				$("#cod").val('');
			},
			error : function(err) {
				alert("NO SE PUDO ENCONTRAR LA CEDULA O correo no exite;");
				alert(err['responseText']);
				/*$.each(err, function(k, v) {
					alert(k+":"+v);
				});*/
			}
		});
	}else{
		alert('Debe ingresar cedula y codigo');
		return 0;
	}
}