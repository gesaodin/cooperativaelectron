$(function() {
	$("#fecha").datepicker({
		showOn : "button",
		buttonImage : sImg + "calendar.gif",
		buttonImageOnly : true
	});

	$.datepicker.regional['es'] = {
		closeText : 'Cerrar',
		prevText : '&#x3c;Ant',
		nextText : 'Sig&#x3e;',
		currentText : 'Hoy',
		monthNames : ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
		monthNamesShort : ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
		dayNames : ['Domingo', 'Lunes', 'Martes', 'Mi&eacute;rcoles', 'Jueves', 'Viernes', 'S&aacute;bado'],
		dayNamesShort : ['Dom', 'Lun', 'Mar', 'Mi&eacute;', 'Juv', 'Vie', 'S&aacute;b'],
		dayNamesMin : ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'S&aacute;'],
		weekHeader : 'Sm',
		dateFormat : 'dd/mm/yy',
		firstDay : 1,
		isRTL : false,
		showMonthAfterYear : false,
		yearSuffix : ''
	};
	$.datepicker.setDefaults($.datepicker.regional['es']);
	$("#fecha").datepicker("option", "dateFormat", "yy-mm-dd");

	$.ajax({
		url : sUrlP + "maximo_acuse",
		type : 'POST',
		success : function(html) {
			$("#txtCodigo").val(html);
		}
	});
	listar();
});

function Guardar_Recibo() {
	strUrl_Proceso = sUrlP + "GuardaReciboAdmin";
	var acuse = $("#txtCodigo").val();
	var monto = $("#txtMonto").val();
	var nombre = $("#txtNombre").val();
	var tipo = $("#txtTipo option:selected").val();
	var ced = $("#txtCedula").val();

	var concepto = $("#txtConcepto").val();
	var banco = $("#txtBanco option:selected").text();
	var chequera = $("#txtChequera").val();
	var cheque = $("#txtCheque").val();
	var fecha = $("#fecha").val();
	//alert(concepto);
	if (ced == '' || monto == '' || nombre == '' || fecha == '' || tipo == '' || concepto == '' || banco == '' || chequera == '' || cheque == '') {
		alert("Debe Ingresar Todos los Datos");
		return 0;
	}

	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : 'acuse=' + acuse + '&cedula=' + ced + '&monto=' + monto + '&nombre=' + nombre + '&fecha=' + fecha + '&tipo=' + tipo + '&cheque=' + cheque + '&banco=' + banco + '&concepto=' + concepto + '&chequera=' + chequera,
		//dataType: 'json',
		success : function(html) {
			$("#msj_alertas").html(html);
			$("#msj_alertas").dialog('open');
		}
	});
	limpiar();
}

function limpiar(){
	
	$("#txtMonto").val('');
	$("#txtNombre").val('');
	$("#txtTipo option:selected").val('');
	$("#txtCedula").val('');
	$("#txtConcepto").val('');
	$("#txtChequera").val('');
	$("#txtCheque").val('');
	$("#fecha").val('');
	$.ajax({
		url : sUrlP + "maximo_acuse",
		type : 'POST',
		success : function(html) {
			$("#txtCodigo").val(html);
		}
	});
	listar();
}

function listar(){
	
	strUrl_Proceso = sUrlP + "Listar_Acuse";
	$.ajax({
		url : strUrl_Proceso,
		dataType : "json",
		success : function(oEsq) {
			Grid3 = new TGrid(oEsq,'acuse','acuse');
			Grid3.SetName("Lista");			
			Grid3.SetNumeracion(true);
			Grid3.Generar();
		}
	});
}
