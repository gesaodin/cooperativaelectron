<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 * @author Judelvis Rivas
 * @since Version 1.0
 *
 */
class CTriangulo extends Model
{

    function __construct()
    {
        parent::Model();
        $this->load->database();
    }

    function guardar($datos)
    {
        $sql= $this->db->query("insert into t_triangulo (estatus) values(0)");
        if ($sql) {
            $id = $this->db->insert_id();
            $sql2= $this->db->query("insert into t_triangulo_a (id_g,cedula,factura) values({$id},'{$datos['ced1']}','{$datos['fact1']}')");
            $sql3= $this->db->query("insert into t_triangulo_a (id_g,cedula,factura) values({$id},'{$datos['ced2']}','{$datos['fact2']}')");
            $sql4= $this->db->query("insert into t_triangulo_a (id_g,cedula,factura) values({$id},'{$datos['ced3']}','{$datos['fact3']}')");
            return 'Se registro con exito';
        }
    }

    function factura($fact = null)
    {
        $datos = array("filas" => 0, "datos" => "No se encontro factura", "parametro" => $fact);
        $contrato = $this->db->query("select contrato_id, cantidad,nomina_procedencia,t_personas.documento_id as ced,
                      primer_nombre,primer_apellido,banco_1,cuenta_1,banco_2,cuenta_2
                      from t_clientes_creditos 
                      join t_personas on t_personas.documento_id = t_clientes_creditos.documento_id
                      where numero_factura ='" . $fact . "' ");


        if ($contrato->num_rows() > 0) {

            foreach ($contrato->result() as $con){
                $nomi = $con->nomina_procedencia;
                $qnomi = "select * FROM t_nominas WHERE nombre='{$nomi}' and triangulo=1";
                $rsnomi = $this->db->query($qnomi);
                if($rsnomi->num_rows() == 0) return array("filas" => 0, "datos" => "La nomina asociada a este cliente no se encuentra activa para la modalidad triangulo", "parametro" => $fact);
            }

            $ver1 = "select * from t_triangulo_a 
                          join t_triangulo on t_triangulo.id = t_triangulo_a.id_g
                          where factura='{$fact}' and estatus = 0";
            $rs1 = $this->db->query($ver1);

            if($rs1->num_rows() > 0) return array("filas" => 0, "datos" => "La factura se encuentra asociada a otro grupo de pago", "parametro" => $fact);
            else{
                $datos = array("filas" => $contrato->num_rows(), "datos" => $contrato->result());
            }

        }
        return $datos;
    }

    public function ficha($tipo){
        $est = $tipo;
        //if($tipo == 1) $est = 0;
        $query = "select * FROM t_sumi where estatus={$est} ";
        $dat = $this->db->query($query);
        $datos=array("filas"=>0,"datos"=>"No se encontraron registros");
        if($dat->num_rows()>0){
            $rs = $dat->result();
            $html="<table border='1' class='table table-bordered'><thead><tr><td>Factura</td><td>M.factura</td><td>M.Limite</td><td>M.Pagado</td><td>Estatus</td>
                <td>D.Entrega</td><td>Accion</td>
                </tr></thead><tbody>";
            foreach($rs as $fact){

                $cont = explode('|',$fact->contratos);
                $mntPag = 0;
                foreach ($cont as $con){
                    $q2 = $this->db->query("SELECT * FROM t_lista_cobros_u where contrato_id='{$con}'");
                    foreach ($q2->result() as $mmt){
                        $mntPag += $mmt->monto;
                    }
                }

                $estatus = $est;
                $boton = '';
                $clase = '';
                switch ($est){
                    case 0:
                        $estatus = "Pendiente de pagos";
                        if($fact->monto_limite <= $mntPag) {
                            $boton ="<button onclick='entregar(\"".$fact->factura."\")' class='btn btn-info' >Entregar</button>";
                            $estatus = "Pendiente de entrega";
                            $clase = "class='bg-warning'";
                        }
                        else $boton = "Pagos pendientes";
                        break;
                    case 1:
                        $estatus = "Entregado";
                        $boton ="<button onclick='detalle(\"".$fact->factura."\")' class='btn btn-info fa fa-print' >Entregado</button>";
                        break;
                    default : break;
                }
                $porc = ($mntPag*100)/$fact->monto_factura;
                $html.="
                <tr ".$clase.">
                <td>".$fact->factura."</td><td>".$fact->monto_factura."</td>
                <td>".$fact->monto_limite."</td><td>".$mntPag."</td><td>".$estatus."|".number_format($porc,2)."%</td>
                <td>".$fact->observacion."</td>
                <td>".$boton."</td>
                </tr>
                ";
            }
            $html .= "</tbody></table>";
            $datos=array("filas"=>$dat->num_rows(),"datos"=>$html);
        }
        return $datos;
    }

    function guardarEntrega($dat){
        if($this -> db -> query("UPDATE t_sumi set observacion='".$dat['obs']."',estatus=1 WHERE factura='".$dat['factura']."'")) return 'Se Registro Entrega con Exito';
        else return "Error al insertar.";
    }


    function reporte($tipo){
        $query="SELECT *,monto_limite as monto_pagado FROM t_sumi WHERE estatus=".$tipo;
        $dat = $this->db->query($query);
        $datos=array("filas"=>0,"datos"=>"No se encontraron datos para el dia seleccionado");
        if($dat->num_rows()>0){
            $datos=array("filas"=>$dat->num_rows(),"datos"=>$dat->result());
        }
        return $datos;

    }
	
	function guardarBien($datos)
    {
       
        if($this -> db -> insert("t_sumi_bien", $datos)) return 'Se Registro con Exito';
        else return "Error al insertar.";
        

    }
	
	function cmbBien(){
		$datos = $this -> db ->query("SELECT * FROM t_sumi_bien");
		$rs = $datos->result();
		$cmb='';
		foreach ($rs as $row) {
			$cmb .= '<option value="'.$row->nombre.'">'.$row->nombre.'</option>';
		}
		return $cmb;
	}


}
