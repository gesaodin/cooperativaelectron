<?php
/**
 *  @author Judelvis Rivas
 *  @package cooperativa.system.application.model.cliente
 *  @version 1.0.0
 */

class MRecepcion extends Model {

    public function __construct() {
        parent::Model();
    }

    public function __destruct() {

    }

    public function listar($arr){


        $query = 'SELECT t_recepcion_documento.oid as oidrec,envia,recibe,t_recepcion_documento.fecha as fec,hora,cedula,nombre,observacion,descripcion,seudonimo  FROM t_recepcion_documento
                  left join t_usuario on t_usuario.oid = t_recepcion_documento.asignado where cedula!="" ';
        if($arr['estatus'] != 9) $query .= ' and t_recepcion_documento.estatus='.$arr['estatus'];
        if($arr['desde'] != '' &&  $arr['hasta'] != '') $query .= ' and t_recepcion_documento.fecha Between "'.$arr['desde'].'" and "'.$arr['hasta'].'"';
        if($arr['desde'] != '' &&  $arr['hasta'] == '') $query .= ' and t_recepcion_documento.fecha >= "'.$arr['desde'].'" ';
        if($arr['desde'] == '' &&  $arr['hasta'] != '') $query .= ' and t_recepcion_documento.fecha <= "'.$arr['hasta'].'" ';
        //return $query;
        $consulta = $this -> db ->  query ($query);
        $iCantidad = $consulta -> num_rows();
        $Conexion = $consulta -> result();

        $oCabezera[1] = array("titulo" => "oid", "oculto"=>TRUE);
        $oCabezera[2] = array("titulo" => "Enviado", "atributos" => "width:50px", "buscar" => 0);
        $oCabezera[3] = array("titulo" => "Recibido", "atributos" => "width:80px", "buscar" => 0);
        $oCabezera[4] = array("titulo" => "Cedula", "atributos" => "width:100px",'buscar'=>0);
        $oCabezera[5] = array("titulo" => "Cliente", "atributos" => "width:100px", "buscar" => 0);
        $oCabezera[6] = array("titulo" => "Fecha Rec.");
        $oCabezera[7] = array("titulo" => "Hora", "atributos" => "width:100px", "buscar" => 0);
        $oCabezera[8] = array("titulo" => "Observacion", "atributos" => "width:100px");
        $oCabezera[9] = array("titulo" => "Posesion", "atributos" => "width:100px");

        if ($iCantidad > 0) {
            $i = 0;
            foreach ($Conexion as $row) {
                ++$i;
                $oFil[$i] = array("1" => $row -> oidrec, "2" => $row -> envia, "3" => $row -> recibe, "4" => $row -> cedula, "5" => $row -> nombre, "6" => $row -> fec, "7" => $row -> hora, "8" => $row -> observacion , "9" => $row -> seudonimo);

            }

            $oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Paginador" => 50, "Origen" => "json","msj" => TRUE);

        } else {
            $oTable = array("msj" => FALSE);
        }
        return json_encode($oTable);
    }

    function guardar($datos){
        $documentos = $datos['docu2'];
        unset($datos['docu2']);
        unset($datos['docu']);
        $datos['usua'] = $this->session->userdata ( 'oidu' );
        $this -> db -> insert("t_recepcion_documento",$datos);
        $id = $this -> db -> insert_id();
        foreach($documentos as $doc){
            $query ="insert into t_it_recepcion_documentos(oidRec,docu,usua) values(".$id.",'".$doc."',".$datos['usua'].")";
            $this -> db -> query($query);
        }
        return "Se registro Con Exito";
    }

}