$(function() {
	Crear();
	$("#fecha").datepicker({
		showOn : "button",
		buttonImage : sImg + "calendar.gif",
		buttonImageOnly : true
	});
	
});
function Crear() {
	strUrl_Proceso = sUrlP + "GV_Fpresuesto";
	$.ajax({
		url : strUrl_Proceso,
		dataType : "json",
		success : function(oBj) {//alert(oBj);
			vis = new GVista(oBj, 'formulario', 'Factura Presupuesto', 'botones');
			vis.SetNombre("frmPresup");
			vis.SetCeldas(3);
			vis.SetBotones(1);
			vis.Generar();
				$("#cedula").val($("#txtCed").val());
				$("#precio").val($("#txtMon").val());
				$("#cant").val(1);
				$("#empresa > option[value='" + $("#txtEmp").val() +  "']").attr("selected","selected");
				consulta_cliente();

		}
	});
}

function generar() {
	var empresa = $("#empresa option:selected").val();
	if (empresa == '') {
		alert('Debe ingresar una empresa');
		return 0;
	}
	strUrl_Proceso = sUrlP + "Generar_Codigo_Semillero/" + empresa;
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		success : function(codigo) {
			$("#factura").val(codigo);
		}
	});
	return true;
}

function guardar() {
	var empresa = $("#empresa option:selected").val();
	var factura = $("#factura").val();
	var cedula = $("#cedula").val();
	var nombre = $("#nombre").val();
	var fecha = $("#fecha").val();
	var direccion = $("#direc").val();
	var telf = $("#telf").val();
	var cert = $("#txtCert").val();
	
	var total = 0;
	var i = 0;
	var l_pro = new Array();
	$("#productos option").each(function() {
		var texto_picado = $(this).text().split('|');
		var monto = texto_picado[0] * texto_picado[2];
		total += monto;
		l_pro[i] = $(this).text();
		i++;
	});
	if(i == 0){
		alert("Debe ingresar al menos u producto");
		return false;
	}	
	strUrl_Proceso = sUrlP + "Guarda_Fpresupuesto";
	$.ajax({
		url : strUrl_Proceso,
		data : 'empresa=' + empresa + '&factura=' + factura + '&cedula=' + cedula + '&nombre=' + nombre + '&fecha=' + fecha + '&direccion=' + direccion + '&telf=' + telf + '&total=' + total + '&productos=' + l_pro  + '&cert=' + cert,
		type : 'POST',
		success : function(msj) {
			$("#msj_alertas").html("<center>"  + msj + "<br><br><a href='" + sUrlP + "Generar_Contratos_View/"  + cert + "/"  +  factura + "'><font style='font-size:14px;'>Continuar Ir a Generar Contratos</font></a></center>");
			
			$("#msj_alertas").dialog('open');
			
			limpiar();
			
		}
	});
	return false;
	

}

function Ir(){
	window.location.href = sUrlP + 'Generar_Contratos_View';
}

function limpiar(){
	$("#factura").val('');
	$("#cedula").val('');
	$("#nombre").val(4);
	$("#fecha").val('');
	$("#telf").val('');
	$("#direc").val('');
	$("#des").val('');
	$("#cant").val('');
	$("#precio").val('');
	$("#productos").html('');
	$("#empresa > option[value='']").attr("selected","selected");
}

function consulta_cliente() {
	strUrl_Proceso = sUrlP + "DataSource_Cliente";
	var id = $("#cedula").val();
	if (id == '') {
		alert('Debe ingresar una cedula');
		return 0;
	}
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : 'id=' + id,
		dataType : 'json',
		success : function(json) {
			cedula = json["documento_id"];
			celular = json["celular"];
			pnom = json["primer_nombre"];
			snom = json["segundo_nombre"];
			pape = json["primer_apellido"];
			sape = json["segundo_apellido"];
			telf = json["telefono"];
			nombre = pape + ' ' + sape + ' ' + pnom + ' ' + snom;
			$('#nombre').val(nombre);
			$('#telf').val(telf);

		}
	});
	return true;
}

function consulta_factura() {
	strUrl_Proceso = sUrlP + "Consulta_Fpresupuesto";
	var factura = $("#factura").val();
	if (factura == '') {
		alert('Debe ingresar una factura');
		return 0;
	}

	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : 'factura=' + factura,
		dataType : 'json',
		success : function(json) {
			$('#nombre').val(json['nombre']);
			$('#cedula').val(json['nombre']);
			$('#telf').val(json['telf']);
			$('#fecha').val(json['fecha']);
			$('#direc').val(json['direccion']);
			$('#telf').val(json['telf']);
		}
	});
	return true;
}

function agregar() {
	var des = $("#des").val();
	var cant = $("#cant").val();
	var precio = $("#precio").val();
	if (des == '' || cant == '' || precio == '') {
		alert('Debe ingresar todos los campos...');
		return false;
	}
	var cadena = cant + '|' + des + '|' + precio;
	$("#productos").append(new Option(cadena, cadena));
	//$("#des").val('');
	//$("#cant").val('');
	//$("#precio").val('');
	$("#btnAgregar").attr("disabled",true);
}

function borrar() {
	$("#productos option:selected").remove();
	$("#btnAgregar").attr("disabled",false);
}
