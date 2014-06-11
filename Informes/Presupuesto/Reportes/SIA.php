<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
<style>
	a{color:black;text-decoration:none;}
	a:hover{color:blue;text-decoration:underline;}
</style>

<font face="tahoma" style="font-variant:small-caps" style="font-size:11px">
<strong><?echo strtoupper($Compania[0])?></strong><br>
<?echo $Compania[1]?><br>S     I     A
<br><br>

<table bordercolor="#e5e5e5" cellspacing="0" border="1" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#e5e5e5"><td><a href="SIACatalogoCuentas.php?DatNameSID=<? echo $DatNameSID?>&MesIni=<?echo $MesIni?>&MesFin=<?echo $MesFin?>&Anio=<?echo $Anio?>&Encabezados=<?echo $Encabezados?>&NoDigitos=<?echo $NoDigitos?>">Cat&aacute;logo de Cuentas</a></td></tr>
<tr><td>Resumen de Caja Menor</a></td></tr>
<tr bgcolor="#e5e5e5"><td>Relaci&oacute;n de Gastos de Caja</td></tr>
<tr><td><a href="SIACuentasBancarias.php?DatNameSID=<? echo $DatNameSID?>&MesIni=<?echo $MesIni?>&MesFin=<?echo $MesFin?>&Anio=<?echo $Anio?>&Encabezados=<?echo $Encabezados?>&NoDigitos=<?echo $NoDigitos?>"><font color='red'>Cuentas Bancarias</td></tr></font>

<tr bgcolor="#e5e5e5"><td><a href="SIAMovBancos.php?DatNameSID=<? echo $DatNameSID?>&MesIni=<?echo $MesIni?>&MesFin=<?echo $MesFin?>&Anio=<?echo $Anio?>&Encabezados=<?echo $Encabezados?>&NoDigitos=<?echo $NoDigitos?>">Movimiento de Bancos General</td></tr>
<tr bgcolor="#e5e5e5"><td><a href="SIAMovBancosDetalle.php?DatNameSID=<? echo $DatNameSID?>&MesIni=<?echo $MesIni?>&MesFin=<?echo $MesFin?>&Anio=<?echo $Anio?>&Encabezados=<?echo $Encabezados?>&NoDigitos=<?echo $NoDigitos?>">Movimiento de Bancos Detallado</td></tr>


<tr><td>P&oacute;lizas de Aseguramiento</td></tr>
<tr><td bgcolor="#e5e5e5"><a href="SIAPPyEAdquisiciones.php?DatNameSID=<? echo $DatNameSID?>&MesIni=<?echo $MesIni?>&MesFin=<?echo $MesFin?>&Anio=<?echo $Anio?>&Encabezados=<?echo $Encabezados?>&NoDigitos=<?echo $NoDigitos?>">Propiedad, Planta y Equipo - Adquisiciones y Bajas</td></tr>
<tr><td><a href="SIAPPyEInventarios.php?DatNameSID=<? echo $DatNameSID?>&MesIni=<?echo $MesIni?>&MesFin=<?echo $MesFin?>&Anio=<?echo $Anio?>&Encabezados=<?echo $Encabezados?>&NoDigitos=<?echo $NoDigitos?>">Propiedad, Planta y Equipo - Inventarios</td></tr>
<tr bgcolor="#e5e5e5"><td><a href="SIAEjecIngresos.php?DatNameSID=<? echo $DatNameSID?>&MesIni=<?echo $MesIni?>&MesFin=<?echo $MesFin?>&Anio=<?echo $Anio?>&Encabezados=<?echo $Encabezados?>&NoDigitos=<?echo $NoDigitos?>">Ejecuci&oacute;n Presupuestal de Ingresos</td></tr>

<tr><td><a href="SIARelacIngresos.php?DatNameSID=<? echo $DatNameSID?>&MesIni=<?echo $MesIni?>&MesFin=<?echo $MesFin?>&Anio=<?echo $Anio?>&Encabezados=<?echo $Encabezados?>&NoDigitos=<?echo $NoDigitos?>">Relacion de Ingresos</td></tr>
<tr bgcolor="#e5e5e5"><td><a href="SIAEjecGastos.php?DatNameSID=<? echo $DatNameSID?>&MesIni=<?echo $MesIni?>&MesFin=<?echo $MesFin?>&Anio=<?echo $Anio?>&Encabezados=<?echo $Encabezados?>&NoDigitos=<?echo $NoDigitos?>">Ejecuci&oacute;n Presupuestal de Gastos</td></tr>

<tr><td><a href="SIACompromisos.php?DatNameSID=<? echo $DatNameSID?>&MesIni=<?echo $MesIni?>&MesFin=<?echo $MesFin?>&Anio=<?echo $Anio?>&Encabezados=<?echo $Encabezados?>&NoDigitos=<?echo $NoDigitos?>">Relaci&oacute;n de Compromisos</td></tr>
<tr bgcolor="#e5e5e5"><td><a href="SIARelacPagos.php?DatNameSID=<? echo $DatNameSID?>&MesIni=<?echo $MesIni?>&MesFin=<?echo $MesFin?>&Anio=<?echo $Anio?>&Encabezados=<?echo $Encabezados?>&NoDigitos=<?echo $NoDigitos?>">Relaci&oacute;n de Pagos</td></tr>

<tr><td><a href="SIARelacPagosSinPres.php?DatNameSID=<? echo $DatNameSID?>&MesIni=<?echo $MesIni?>&MesFin=<?echo $MesFin?>&Anio=<?echo $Anio?>&Encabezados=<?echo $Encabezados?>&NoDigitos=<?echo $NoDigitos?>">Relaci&oacute;n de Pagos Sin Afectaci&oacute;n presupuestal</td></tr>
<tr bgcolor="#e5e5e5"><td><a href="SIAModIngresos.php?DatNameSID=<? echo $DatNameSID?>&MesIni=<?echo $MesIni?>&MesFin=<?echo $MesFin?>&Anio=<?echo $Anio?>&Encabezados=<?echo $Encabezados?>&NoDigitos=<?echo $NoDigitos?>">Modificacion al presupuesto de Ingresos</td></tr>

<tr><td><a href="SIAModEgresos.php?DatNameSID=<? echo $DatNameSID?>&MesIni=<?echo $MesIni?>&MesFin=<?echo $MesFin?>&Anio=<?echo $Anio?>&Encabezados=<?echo $Encabezados?>&NoDigitos=<?echo $NoDigitos?>">Modificaciones al Presupuesto de Egresos</td></tr>
<tr bgcolor="#e5e5e5"><td>Ejecuci&oacute;n PAC de la Vigencia</td></tr>

<tr><td><a href="SIAEjecReserva.php?DatNameSID=<? echo $DatNameSID?>&Vigencia=Anteriores&ClaseVigencia=Reserva&MesIni=<?echo $MesIni?>&MesFin=<?echo $MesFin?>&Anio=<?echo $Anio?>&Encabezados=<?echo $Encabezados?>&NoDigitos=<?echo $NoDigitos?>">Ejecuci&oacute;n Reserva Presupuestal</a></td></tr>

<tr bgcolor="#e5e5e5"><td><a href="SIAEjecCxP.php?DatNameSID=<? echo $DatNameSID?>&Vigencia=Anteriores&ClaseVigencia=CxP&MesIni=<?echo $MesIni?>&MesFin=<?echo $MesFin?>&Anio=<?echo $Anio?>&Encabezados=<?echo $Encabezados?>&NoDigitos=<?echo $NoDigitos?>">Ejecuci&oacute;n de Cuentas x Pagar</a></td></tr>

<tr><td><a href="SIARelacPagosCxP.php?DatNameSID=<? echo $DatNameSID?>&MesIni=<?echo $MesIni?>&MesFin=<?echo $MesFin?>&Anio=<?echo $Anio?>&Encabezados=<?echo $Encabezados?>&NoDigitos=<?echo $NoDigitos?>">Relaci&oacute;n de Pagos de Cuentas x Pagar</td></tr>

<tr bgcolor="#e5e5e5"><td><a href="SIATrasladosBancarios.php?DatNameSID=<? echo $DatNameSID?>&MesIni=<?echo $MesIni?>&MesFin=<?echo $MesFin?>&Anio=<?echo $Anio?>&Encabezados=<?echo $Encabezados?>&NoDigitos=<?echo $NoDigitos?>">Traslado de Fondos Bancarios</a></td></tr>


</table>