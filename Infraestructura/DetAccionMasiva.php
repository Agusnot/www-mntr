<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.Relacion.value != "No Encontrados")
		{
			if(document.FORMA.IDRA.value=="" && document.FORMA.CC.value==""){alert("Debe seleccionar un Responsable Actual o un Centro de Costos");return false;}	
		}
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()" action="ListaElementos.php">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="Tipo" value="<? echo $Tipo?>" />
<input type="hidden" name="Numero" value="<? echo $Numero?>" />
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD?>" />
<table border="1" width="730px" bordercolor="#e5e5e5" style="font-family:<? echo $Estilo[8]?>;font-size:12;font-style:<? echo $Estilo[10]?>">
<tr>
    <td width="15%"  bgcolor="#e5e5e5">Responsable Actual</td>
    <td colspan="3"><input type="Text" name="Responsable" style="width:600px"  onFocus="parent.Mostrar();
    if(CC.value==''){parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=NuevoMovimiento&ObjId=IDRA&ObjetoNombre=Responsable&Tipo=Nombre&Nombre='+this.value;}
    else{ parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=NuevoMovimiento&ObjId=IDRA&ObjTercero=Responsable&Tercero='+this.value+'&Tipo=TerceroxCC&CC='+CC.value+'&Anio=<? echo $ND[year]?>';}" 
    onKeyUp="xLetra(this);IDRA.value='';
    if(CC.value==''){parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=NuevoMovimiento&ObjId=IDRA&ObjetoNombre=Responsable&Tipo=Nombre&Nombre='+this.value;}
    else{ parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=NuevoMovimiento&ObjId=IDRA&ObjTercero=Responsable&Tercero='+this.value+'&Tipo=TerceroxCC&CC='+CC.value+'&Anio=<? echo $ND[year]?>';}"
    onKeyDown="xLetra(this)"/>
    <input type="hidden" name="IDRA" /> </td>
</tr>
<tr><td bgcolor="#e5e5e5">Centro de Costos Actual</td>
    <td><input type="text" name="CC" style="width:100%;text-align:right;" 
onFocus="parent.Mostrar();
if(IDRA.value==''){parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=NuevoMovimiento&Centro='+this.value+'&Tipo=CCG&Anio=<? echo $ND[year]?>';}
else{parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=NuevoMovimiento&ObjetoCC=CC&Tipo=CCxTercero&CC='+this.value+'&Anio=<? echo $ND[year]?>&Cedula='+IDRA.value;};"
onkeyup="SubUb.value='';if(IDRA.value==''){parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=NuevoMovimiento&Centro='+this.value+'&Tipo=CCG&Anio=<? echo $ND[year]?>';}
else{parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=NuevoMovimiento&Tipo=CCxTercero&CC='+this.value+'&Anio=<? echo $ND[year]?>&Cedula='+IDRA.value;};
xNumero(this);" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" /></td>
	<td bgcolor="#e5e5e5">SubUbicacion</td>
    <td>
    <input type="text" name="SubUb" onKeyDown="xLetra(this)" title="SubUbicacion" 
        onfocus="parent.Mostrar();
        parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=NuevoMovimiento&Tipo=SubUbicacionxCC&SubUbicacion='+this.value+'&CC='+CC.value+'&ObjUbicacion=SubUb&Anio=<? echo $ND[year]?>';"
        onkeyup="xLetra(this);
        parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=NuevoMovimiento&Tipo=SubUbicacionxCC&SubUbicacion='+this.value+'&CC='+CC.value+'&ObjUbicacion=SubUb&Anio=<? echo $ND[year]?>';" />
    </td>
</tr>
<tr>
	<td  bgcolor="#e5e5e5">Grupo</td>
    <td><select name="Grupo" onFocus="parent.Ocultar()"><option></option>
	<?
    $cons = "Select Grupo From Infraestructura.GruposdeElementos Where Compania='$Compania[0]' and Clase='Devolutivos' order by Grupo";
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		echo "<option value='$fila[0]'>$fila[0]</option>";	
	}
	?>
	</select></td>
    <td  bgcolor="#e5e5e5">Impacto</td>
    <td><select name="Impacto" onFocus="parent.Ocultar()"><option></option>
	<?
    $cons = "Select Nombre from Central.Impactos";
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		echo "<option value='$fila[0]'>$fila[0]</option>";	
	}	
	?>
	</select></td>
</tr>
<tr>
	<td  bgcolor="#e5e5e5">Estado</td>
    <td><select name="Estado" onFocus="parent.Ocultar()"><option></option>
	<?
    $cons = "Select Nombre from Central.Estados";
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		echo "<option value='$fila[0]'>$fila[0]</option>";	
	}
	?>
	</select></td>
    <td  bgcolor="#e5e5e5">Relacion de</td>
    <td><select name="Relacion" onFocus="parent.Ocultar()">
    	<option value="">Todos</option>
        <option value="Encontrados">Encontrados</option>
        <option value="No Encontrados">No Encontrados</option>
    </select></td>
</tr>
</table>
<input type="submit" name="Buscar" value="Buscar" onClick="parent.Ocultar()" />
<?
if($Existe){ ?><input type="button" name="Volver" value="Volver" 
onclick="location.href='ListaAccionMasiva.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Tipo=<? echo $Tipo?>';
		parent.document.FORMA.Guardar.disabled=false;" /><? }
?>
</form>
</body>