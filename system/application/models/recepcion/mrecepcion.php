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
        $usu = $this->session->userdata ( 'oidu' );
        $q = $this->db->query("SELECT t_usuario.oid as uoid,descripcion from t_usuario join _tr_usuarioubicacion on t_usuario.oid =_tr_usuarioubicacion.oidu where oidb=13");
        $con = $q->result();
        $arrUb = array();
        foreach($con as $ub){
            $arrUb[$ub->uoid] = $ub->descripcion;
        }
        $obj[11] = $arrUb;
        $query = 'SELECT t_recepcion_documento.oid as oidrec,envia,recibe,t_recepcion_documento.fecha as fec,hora,cedula,nombre,observacion,descripcion,seudonimo,t_recepcion_documento.tipo as tip  FROM t_recepcion_documento
                  left join t_usuario on t_usuario.oid = t_recepcion_documento.asignado where cedula!="" ';
        if($arr['estatus'] != 9) $query .= ' and t_recepcion_documento.estatus='.$arr['estatus'];
        if($arr['desde'] != '' &&  $arr['hasta'] != '') $query .= ' and t_recepcion_documento.fecha Between "'.$arr['desde'].'" and "'.$arr['hasta'].'"';
        if($arr['desde'] != '' &&  $arr['hasta'] == '') $query .= ' and t_recepcion_documento.fecha >= "'.$arr['desde'].'" ';
        if($arr['desde'] == '' &&  $arr['hasta'] != '') $query .= ' and t_recepcion_documento.fecha <= "'.$arr['hasta'].'" ';
        if($arr['tipo'] != 9 ) $query .= ' and t_recepcion_documento.tipo = '.$arr['tipo'];
        if($usu != 0 && $usu !=27 && $usu != 28) $query .= ' and asignado='.$usu;
        //return $query;
        $consulta = $this -> db ->  query ($query);
        $iCantidad = $consulta -> num_rows();
        $Conexion = $consulta -> result();

        $oCabezera[1] = array("titulo" => "oid", "oculto"=>TRUE);
        $oCabezera[2] = array("titulo" => "nivel", "oculto"=>TRUE);
        $oCabezera[3] = array("titulo" => "Enviado", "atributos" => "width:50px", "buscar" => 0);
        $oCabezera[4] = array("titulo" => "Recibido", "atributos" => "width:80px", "buscar" => 0);
        $oCabezera[5] = array("titulo" => "Tipo", "atributos" => "width:80px", "buscar" => 0);
        $oCabezera[6] = array("titulo" => "Cedula", "atributos" => "width:100px",'buscar'=>0);
        $oCabezera[7] = array("titulo" => "Cliente", "atributos" => "width:100px", "buscar" => 0);
        $oCabezera[8] = array("titulo" => "Fecha Rec.");
        $oCabezera[9] = array("titulo" => "Hora", "atributos" => "width:100px", "buscar" => 0);
        $oCabezera[10] = array("titulo" => "Observacion", "atributos" => "width:100px");
        $oCabezera[11] = array("titulo" => "Posesion", "atributos" => "width:100px");
        if($arr['estatus'] == 0 && ($usu == 0 || $usu == 27 || $usu == 28)){
            $oCabezera[11]['tipo'] = "combo";
            $oCabezera[12] = array("titulo" => "AC", "tipo" => "bimagen", "funcion" => 'aceptarEstatusDocu', "parametro" => "1,2,10", "ruta" => __IMG__ . "botones/aceptar1.png", "atributos" => "width:10px");
            $oCabezera[13] = array("titulo" => "AN", "tipo" => "bimagen", "funcion" => 'anularEstatusDocu', "parametro" => "1", "ruta" => __IMG__ . "botones/quitar.png", "atributos" => "width:10px");
        }

        if ($iCantidad > 0) {
            $i = 0;
            foreach ($Conexion as $row) {
                ++$i;
                $oFil[$i] = array("1" => $row -> oidrec,"2"=>$arr['estatus'] ,"3" => $row -> envia, "4" => $row -> recibe, "5"=>$row->tip,"6" => $row -> cedula, "7" => $row -> nombre,
                    "8" => $row -> fec, "9" => $row -> hora, "10" => $row -> observacion , "11" => $row -> descripcion);
                if($arr['estatus'] == 0 && ($usu == 0 || $usu == 27 || $usu == 28)){
                    $oFil[$i]["12"] = "";
                    $oFil[$i]["13"] = "";
                }
            }

            $oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Paginador" => 50, "Origen" => "json","msj" => TRUE,"Objetos"=>$obj);

        } else {
            $oTable = array("msj" => FALSE);
        }
        return json_encode($oTable);
    }

    function aceptarEstatusDocu($arr){
        $esta = $arr[1] + 1;
        $query = "update t_recepcion_documento set estatus=".$esta;
        if(isset($arr[2])) $query .= " , asignado=".$arr[2];
        $query .= " WHERE oid=".$arr[0];
        //return $query;
        $this -> db -> query($query);
        return "Se proceso con Exito";
    }

    function anularEstatusDocu($arr){
        $query = "update t_recepcion_documento set estatus=4 where oid=".$arr[0];
        //return $query;
        $this -> db -> query($query);
        return "Se proceso con Exito";
    }

    function guardar($datos){
        $documentos = $datos['docu2'];
        unset($datos['docu2']);
        unset($datos['docu']);
        $datos['usua'] = $this->session->userdata ( 'oidu' );
        $this -> db -> insert("t_recepcion_documento",$datos);
        $id = $this -> db -> insert_id();
        foreach($documentos as $doc){
            $query ="insert into t_it_recepcion_documentos(oidRec,docu,usua,tipo) values(".$id.",'".$doc."',".$datos['usua'].",".$datos['tipo'].")";
            $this -> db -> query($query);
        }
        return "Se registro Con Exito";
    }

}