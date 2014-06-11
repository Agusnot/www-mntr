<?
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$cons="Select AutoId from Informes.InformesCreados where Compania='$Compania[0]' Group By AutoId Order By AutoId Desc";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	if(!$Editar)$AutoId=$fila[0]+1;
	//echo $usuario[1];
	if($Guardar)
	{
		if(!$Editar)
		{
			$cons = "Insert into Informes.InformesCreados (AutoId,Compania,Modulo,Nombre,InstruccionSQL,Parametros,UsuarioCrea,FechaCrea)
					values ('$AutoId','$Compania[0]','$Modulo','$NombreInforme','$Instruccion','$Parametros','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]')";
		}
		else
		{
			$cons = "Update Informes.InformesCreados set Modulo = '$Modulo',Nombre = '$NombreInforme',
			InstruccionSQL = '$Instruccion',Parametros = '$Parametros', UsuadioModif = '$usuario[0]', FechaModif = '$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]'
			where AutoId = '$AutoId'";
		}
		$res = ExQuery($cons);
		?><script language="javascript">location.href="Informes.php?";</script><?
	}
?>
<script language="javascript">
	function Validar()
	{
		var b=0;
		if(document.FORMA.Modulo.value == ""){alert("Falta llenar el campo Modulo"); b=1;}
		else{if(document.FORMA.Nombre.value == ""){alert("Falta llenar le campo Nombre");b=1;}
			else{if(document.FORMA.Instruccion.value == ""){alert("Falta llenar le campo SQL");b=1;}}}
		
		if(document.FORMA.EModulo.value == "0"){alert("Asegurese de haber escogido un valor de la Lista para el campo Modulo");b=1;}
		
		if(b==1){ return false;}
	}
	function Mostrar()
	{
		document.getElementById('Busquedas').style.position='absolute';
		document.getElementById('Busquedas').style.top='50px';
		document.getElementById('Busquedas').style.right='10px';
		document.getElementById('Busquedas').style.display='';
	}
	function Ocultar()
	{
		document.getElementById('Busquedas').style.display='none';
	}
</script>
<?
	if($Editar)
	{
		$cons = "Select Modulo,Nombre,InstruccionSQL,Parametros from Informes.InformesCreados where AutoId='$AutoId' and Compania='$Compania[0]'";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		$Modulo = $fila[0]; $NombreInforme = $fila[1]; $Instruccion = $fila[2]; $Parametros = $fila[3];
	}
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onsubmit="return Validar()">
<input type="hidden" name="Editar" value="<? echo $Editar?>" />
<input type="hidden" name="AutoId" value="<? echo $AutoId?>" />
<input type="Hidden" name="EModulo" value="<? echo $EModulo?>" />
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5">
	<tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Modulo: </td>
        <td><input type="text" name="Modulo" value="<? echo $Modulo?>" size="40"
        onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?Tipo=Modulo&Modulo='+this.value+'&Objeto=Modulo'" 
		onkeyup="FORMA.EModulo.value=0;frames.Busquedas.location.href='Busquedas.php?Tipo=Modulo&Modulo='+this.value+'&Objeto=Modulo';" /></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Nombre: </td>
        <td><input type="text" name="NombreInforme" value="<? echo $NombreInforme?>" size="40" onfocus="Ocultar()" /></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">SQL:</td>
        <td><input type="text" name="Instruccion" value="<? echo $Instruccion?>" size="40" onfocus="Ocultar()" /></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Parametros:</td>
        <td><input type="text" name="Parametros" value="<? echo $Parametros?>" size="40" onfocus="Ocultar()" /></td>
    </tr>
</table>
<input type="submit" name="Guardar" value="Guardar" />
<input type="button" name="Cancelar" value="Cancelar" onclick="location.href = 'Informes.php'" />
</form>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php" frameborder="0" height="400"></iframe>
</body>