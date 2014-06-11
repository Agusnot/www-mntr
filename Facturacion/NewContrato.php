<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
<body background="/Imgs/Fondo.jpg">
<script language="javascript">
	function Validar()
	{
		alert("Debe establecer tipos de descuento, si no aplica descuento configure como NA");return false;
	}
</script>
<form name="FORMA" onSubmit="return Validar()">
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5">
<tr><td>No. Contrato</td><td><input type="text" name="NoContrato" style="width:90px;"></td></tr>
<tr><td>Poliza</td><td><input type="text" name="NoContrato" style="width:90px;"></td></tr>
<tr><td>Nombre</td><td><input type="text" name="NoContrato" style="width:290px;"></td></tr>
<tr><td>Plan Servicios</td><td><select name="PlanServicios"><option>Evento</option></select>
<tr><td>Plan Medicamentos</td><td><select name="PlanMedicamentos"><option>Farmaprecios</option><option>PLM</option></select></td></tr>
<tr><td>Codificacion</td><td><select name="PlanMedicamentos"><option>CUPS</option><option>SOAT</option><option>ISS</option></select></td></tr>
<tr><td>Modo Facturacion</td><td><select name="PlanMedicamentos"><option>Colectiva x Liquidaciones</option><option>Individual</option><option>Paquete Trimestral</option><option>Solo Consulta Externa</option></select></td></tr>
<tr><td>Mensaje Factura</td><td><textarea style="width:290px;height:40px;"></textarea></td></tr>
<tr><td>Tipo Descuento</td><td><select name="PlanServicios"><option></option></select></td></tr>
<tr><td>Porc Descto</td><td><input type="text" name="NoContrato" style="width:90px;"></td></tr>
</table>
<input type="submit" name="Guardar" value="Guardar">
<input type="button" value="Cancelar" onClick="location.href='ContratosxEntidad.php?DatNameSID=<? echo $DatNameSID?>'">
</form>
</body>