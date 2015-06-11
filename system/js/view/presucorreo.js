function enviar() {
    var correo = $("#txtCorreo").val();
    var respuesta = '<h1>PRESUPUESTO DE CREDITO<br>PLAN DE PAGO</h1>';
    var mes = $("#cmbMeses").val();
    var esp1 = $("#txtMT1").val();var esp2 = $("#txtMT2").val();var esp3 = $("#txtMT3").val();var esp4 = $("#txtMT4").val();
    var montoT = unico*parseInt(mes);
    var unico = $("#txtMU").val();
    respuesta += "<table border=1><tr><td>CUOTA</td><td>PERIOCIDAD</td><td>DESCRIPCION</td><td>MONTO</td><td>TOTAL</td></tr>";

    if(esp1 != '' && esp1 != 0){
        respuesta += "<tr><td>1</td><td>MENSUAL</td><td> DESDE EL 1 DE " + MesTexto(parseInt($("#txtNominaPeriocidad1").val())) + " DE " + $("#txtAno1").val() + " HASTA EL 30 DE " + MesTexto(parseInt($("#txtNominaPeriocidad1").val())) + " DE " + $("#txtAno1").val() + "</td><td>" + $("#txtMT1").val() + "</td><td>" + $("#txtMT1").val() + "</td></tr>";
        montoT= montoT + $("#txtMT1").val();
    }
    if(esp2 != '' && esp2 != 0) {
        respuesta += "<tr><td>1</td><td>MENSUAL</td><td> DESDE EL 1 DE " + MesTexto(parseInt($("#txtNominaPeriocidad2").val())) + " DE " + $("#txtAno2").val() + " HASTA EL 30 DE " + MesTexto(parseInt($("#txtNominaPeriocidad2").val())) + " DE " + $("#txtAno2").val() + "</td><td>" + $("#txtMT2").val() + "</td><td>" + $("#txtMT2").val() + "</td></tr>";
        montoT= montoT + $("#txtMT2").val();
    }
    if(esp3 != '' && esp3 != 0) {
        respuesta += "<tr><td>1</td><td>MENSUAL</td><td> DESDE EL 1 DE " + MesTexto(parseInt($("#txtNominaPeriocidad3").val())) + " DE " + $("#txtAno3").val() + " HASTA EL 30 DE " + MesTexto(parseInt($("#txtNominaPeriocidad3").val())) + " DE " + $("#txtAno3").val() + "</td><td>" + $("#txtMT3").val() + "</td><td>" + $("#txtMT3").val() + "</td></tr>";
        montoT= montoT + $("#txtMT3").val();
    }
    if(esp4 != '' && esp4 != 0) {
        respuesta += "<tr><td>1</td><td>MENSUAL</td><td> DESDE EL 1 DE " + MesTexto(parseInt($("#txtNominaPeriocidad4").val())) + " DE " + $("#txtAno4").val() + " HASTA EL 30 DE " + MesTexto(parseInt($("#txtNominaPeriocidad4").val())) + " DE " + $("#txtAno4").val() + "</td><td>" + $("#txtMT4").val() + "</td><td>" + $("#txtMT4").val() + "</td></tr>";
        montoT= montoT + $("#txtMT4").val();
    }

    if(unico != '' && unico != 0) respuesta += "<tr><td>" + mes + "</td><td>MESUALES</td><td>DEL 1 AL 30 DE CADA MES</td><td>" + unico + "</td><td>" + (unico*parseInt(mes)) + "</td></tr>";

    respuesta += "</table><br><b>Monto Total Del Credito:"+montoT+" BS</b>";
    $.ajax({
        url : sUrlP + "EnviarCalculosCorreo",
        type : "POST",
        data : "correo="+correo+"&respuesta="+respuesta,
        success : function(resp) {
            alert("Correo Enviado!");
        }
    });
    return false;
}

function MesTexto(id){
    var mes = "";
    switch (id) {
        case 1:
            mes = "ENERO";
            break;
        case 2:
            mes = "FEBRERO";
            break;
        case 3:
            mes = "MARZO";
            break;
        case 4:
            mes = "ABRIL";
            break;
        case 5:
            mes = "MAYO";
            break;
        case 6:
            mes = "JUNIO";
            break;
        case 7:
            mes = "JULIO";
            break;
        case 8:
            mes = "AGOSTO";
            break;
        case 9:
            mes = "SEPTIEMBRE";
            break;
        case 10:
            mes = "OCTUBRE";
            break;
        case 11:
            mes = "NOVIEMBRE";
            break;
        case 12:
            mes = "DICIEMBRE";
            break;
        default:
            break;
    }
    return mes;
}

function Calcular_Total() {
    var abono_especial = 0;
    for(var i = 1 ; i <=4 ; i++){
        if ($("#txtMT"+i).val() == "") {
            $("#txtMT"+i).val(0)
        }
        abono_especial += parseInt($("#txtMT"+i).val());
    }
    //alert(abono_especial);
    /*if ($("#txtMT1").val() == "") {
     $("#txtMT1").val(0)
     }*/
    //A = parseInt($("#txtMT1").val());
    valor = parseInt($("#cmbMeses").val());
    cuotas = parseFloat($("#txtCuotas").val());
    monto_solicitado = parseInt($("#txtMonto").val());
    monto_credito = Math.round(valor * cuotas);
    //abono_especial = A;
    monto_final = parseInt(monto_credito) + parseInt(monto_solicitado) - parseInt(abono_especial);
    var resta = 0;
    var monto_n = 0;
    ct = Math.round(monto_final / valor);
    $('#txtMU').val(ct)
    //HABILITA CAJA DE CUOTA MENSUAL
    //$('#txtMU').attr("disabled", false);

    monto_n = ct * valor;
    $("#txtMTU").val(monto_n);
    resta = monto_final - monto_n;
    if (resta > 0) {
        agrega_cuota = parseInt(resta) + parseInt($("#txtMT1").val());
        $("#txtMT1").val(agrega_cuota);
    } else {
        if (resta < 0) {
            quitar_cuota = parseInt($("#txtMT1").val()) + parseInt(resta);
            $("#txtMT1").val(quitar_cuota);
        }
    }

}

function Formato(num, prefix) {
    num = Math.round(parseFloat(num) * Math.pow(10, 2)) / Math.pow(10, 2)
    prefix = prefix || '';
    num += '';
    var splitStr = num.split('.');
    var splitLeft = splitStr[0];
    var splitRight = splitStr.length > 1 ? '.' + splitStr[1] : '.00';
    splitRight = splitRight + '00';
    splitRight = splitRight.substr(0, 3);
    var regx = /(\d+)(\d{3})/;
    while (regx.test(splitLeft)) {
        splitLeft = splitLeft.replace(regx, '$1' + ',' + '$2');
    }
    return splitLeft + splitRight + '  ' + prefix;
}

function crea_combos(meses_c) {
    fecha_actual = new Date();
    dia = fecha_actual.getDate();
    mes = fecha_actual.getMonth() + 1;
    anio = fecha_actual.getFullYear();
    anio_f = anio;
    mes_f = mes;
    mes_f = mes_f + parseInt(meses_c);
    if (parseInt(mes_f) > 12 && mes_f <= 24) {
        anio_f = anio_f + 1;
        mes_f = mes_f - 12;
    }
    if (parseInt(mes_f) > 24) {
        anio_f = anio_f + 2;
        mes_f = mes_f - 24;
    }
    //alert(anio_f);

    for (var j = 1; j <= 4; j++) {
        $("#txtAno" + j).html('');
        $('#txtAno' + j).append(new Option('----------------------', 0, true, true));
        for (var i = parseInt(anio); i <= anio_f; i++) {
            $('#txtAno' + j).append(new Option(i, i, true, true));
        }
        $("#txtAno" + j + " option[value=0]").attr("selected", "selected");
    }

}

function valida_meses(item) {

    anioA = $("#txtAno" + item + " option:selected").val();
    meses_c = $("#cmbMeses").val();
    fecha_actual = new Date();
    dia = fecha_actual.getDate();
    mes = fecha_actual.getMonth() + 1;
    anio = fecha_actual.getFullYear();
    anio_f = anio;
    mes_f = mes;

    mes_f = mes_f + parseInt(meses_c);
    if (parseInt(mes_f) > 12 && parseInt(mes_f) < 24) {
        anio_f = anio_f + 1;
        mes_f = mes_f - 12;
    }
    if (parseInt(mes_f) >= 24) {
        anio_f = anio_f + 2;
        mes_f = mes_f - 24;
    }
    valor_mes = $("#txtNominaPeriocidad" + item + " option:selected").val();
    if (anioA == anio) {
        if (valor_mes <= mes) {
            $("#txtNominaPeriocidad" + item + " option[value=0]").attr("selected", "selected");
            $("#txtAno" + item + " option[value=0]").attr("selected", "selected");
        }
    }
    if (anioA == anio_f) {
        if (valor_mes > mes_f) {
            $("#txtNominaPeriocidad" + item + " option[value=0]").attr("selected", "selected");
            $("#txtAno" + item + " option[value=0]").attr("selected", "selected");
        }
    }
}

function Seleccion_Mes(item) {
    Calcular_Total(item);
}

function verificar_montos() {
    var calculo = $('#txtCalculo').val();
    var monto = calculo;
    $("#txtMT1").val(calculo);
    $("#txtMonto").val(calculo);
    $("#txtCuotas").val(0);
    $("#lista").show();
    $('#cmbMeses option[value=0]').attr("selected", true);
    $("#cmbMeses").change();

}

function soloNumeros(evt) {
    if ("which" in evt) {
        keynum = evt.keyCode;
    } else {
        keynum = evt.which;
    }
    if ((keynum > 47 && keynum < 58) || (keynum > 95 && keynum < 106) || (keynum == 13) || (keynum == 8) || (keynum == 9)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Calcular fin de descuento
 */

function Calcular_Fin_Descuento(cuotas, periodo, dia_inicio, mes_inicio, ano_inicio) {
    //alert(cuotas+'**'+periodo+'**'+dia_inicio+'**'+mes_inicio+'**'+ano_inicio);
    var dia_fin = 0;
    var mes_fin = 0;
    var ano_fin = 0;
    var base_mes = 0;
    var tiempo = 0;
    switch(periodo) {
        case '0':
            base_mes = 1 / 4;
            break;
        case '1':
            base_mes = 1 / 2;
            break;
        case '2':
            base_mes = 1 / 2;
            break;
        case '3':
            base_mes = 1 / 2;
            break;
        case '4':
            base_mes = 1;
            break;
        case '5':
            base_mes = 3;
            break;
        case '6':
            base_mes = 6;
            break;
        case '7':
            base_mes = 12;
            break;
    }

    tiempo = (parseInt(cuotas) - 1) * base_mes;
    tiempo_picado = String(tiempo).split('.');
    //alert(tiempo_picado[0]);
    var ano_t = parseInt(parseInt(tiempo_picado[0]) / 12);
    ano_t += parseInt(ano_inicio);
    mes_t = parseInt(tiempo_picado[0]) % 12;
    dia_t = parseInt(dia_inicio);
    //alert(ano_t+'**'+mes_t+'**'+dia_t);
    if (tiempo_picado[1] != null) {
        switch(parseInt(tiempo_picado[1])) {
            case 25:
                dia_t += 7;
                break;
            case 5:
                dia_t += 15;
                break;
            case 75:
                dia_t += 21;
                break;
        }
    }

    if (dia_t > 30) {
        mes_t += 1;
        diferencia = dia_t - 30;
        dia_t = diferencia;
    }

    var suma_meses = parseInt(mes_t) + parseInt(mes_inicio);
    if (suma_meses > 24) {
        ano_t += 2;
        mes_t = suma_meses - 24;
    } else {
        if (suma_meses > 12) {
            ano_t += 1;
            mes_t = suma_meses - 12;
        } else {
            mes_t = suma_meses;
        }
    }

    //alert(ano_t+'**'+mes_t+'**'+dia_t);
    return ano_t + '-' + mes_t + '-' + dia_t;

}
