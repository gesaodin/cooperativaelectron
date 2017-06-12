<h2>
    <span class="label label-success"><i class="fa fa-file"></i></span> &nbsp; Asociacion de Contrato
</h2>
<br/>

<br>
<div class="row fila-datos" id="fila-lista" style="display: none">
    <div class="col-md-12">

        <div class="panel panel-dark" data-collapsed="0">

            <!-- panel head -->
            <div class="panel-heading">
                <div class="panel-title">Ingrese Datos de la persona a pagar</div>
            </div>

            <!-- panel body -->
            <div class="panel-body" >
                <div class="col-md-12" id="persona">
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="unidad" value="">
<div class="row fila-datos" id="fila-agregar">
    <div class="col-md-12">
        <div class="panel panel-dark" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
Datos del Contrato
                </div>
            </div>

            <div class="panel-body">

                <form role="form" id="frmPag" class="form-horizontal form-groups-bordered" action="#" method="post" onsubmit="return guardar();">
                    <div class="form-group">
                        <label for="cedula" class="col-sm-2 control-label">N Factura</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="factura" name="factura" required="required" value="" />
                            <input type="hidden" class="form-control" id="contratos" name="contratos" required="required" value="" />
                        </div>
                        <label for="tipov" class="col-md-1 control-label">Monto Factura</label>
                        <div class="col-md-2">
                            <input type="text" class="form-control" id="MFactura" name="MFactura" required="required" value="" readonly="readonly" />
                        </div>
                        <label class="col-sm-1 control-label">Limite Factura</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="LMFactura" name="LMFactura" required="required" value="" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="monto" class="col-sm-1 control-label">Producto:</label>
                        <div class="col-sm-3">
                            <select class="form-control" id="producto" name="producto">
                                <option value="Telefono Android" selected="selected">Telefono Android</option>
                                <option value="Nevera 12 pies">Nevera 12 pies</option>
                                <option value="Nevera 14 pies">Nevera 14 pies</option>
                                <option value="Cocina 4 Hornillas">Cocina 4 Hornillas</option>
                                <option value="Cocina 6 Hornillas">Cocina 6 Hornillas</option>
                                <option value="Televisor Pantalla Plana 24">Televisor Pantalla Plana 24</option>
                                <option value="Televisor Pantalla Plana 30">Televisor Pantalla Plana 20</option>
                                <option value="Aire Acondicionado 12000 BTU">Aire Acondicionado 12000 BTU</option>
                                <option value="Aire Acondicionado 14000 BTU">Aire Acondicionado 14000 BTU</option>
                                <option value="Aire Acondicionado 16000 BTU">Aire Acondicionado 16000 BTU</option>
                                <option value="Aire Acondicionado 18000 BTU">Aire Acondicionado 18000 BTU</option>
                                <option value="Portatil I3">Portatil I3</option>
                            </select>                     </div>
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" class="btn btn-green btn-icon" id="btnGuardar">Guardar <i class="fa fa-save"></i></button>
                        </div>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>
