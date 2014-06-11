<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
	if($Ejecutar)
	{
		$cons = "Select FechaAdquisicion,Grupo,Impacto,Nombre,Caracteristicas,Modelo,Serie,Estado,Marca,CostoInicial,Depreciar,Activo,DepreciarEn,
		DepreciarDurante,Documentacion,Observaciones,Clase,Consumo From InfraEstructura.CodElementos Where Compania='$Compania[0]' and AutoId=$AutoId
		and Codigo='$Codigo' and Clase='$Clase'";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		
		$cons1 = "Select Responsable,CentroCostos,FechaIni From InfraEstructura.Ubicaciones Where Compania='$Compania[0]' and AutoId=$AutoId";
		$res1 = ExQuery($cons1);
		$fila1 = ExFetch($res1);
		
		$cons2 = "Select Codigo from Infraestructura.CodElementos 
		Where Compania = '$Compania[0]' and Clase = '$Clase' and Grupo='$fila[1]' and Codigo Is Not NULL Order By Codigo Desc";
		$res2 = ExQuery($cons2);
		$fila2 = ExFetch($res2);
		$Codigo = $fila2[0] + 1;
		if($fila[17]){$Codigo = "";}
		$cons2 = "Select AutoId from Infraestructura.CodElementos Where Compania='$Compania[0]' Order By AutoId Desc";
		$res2 = ExQuery($cons2);
		$fila2 = ExFetch($res2);
		$AutoId = $fila2[0] + 1;	
		
		for($i = 1; $i<=$Veces; $i++)
		{
			$cons3 = "Insert into InfraEstructura.CodElementos(Compania,AutoId,Codigo,FechaAdquisicion,Grupo,Impacto,Nombre,Caracteristicas,Modelo,Serie,
			Estado,Marca,CostoInicial,Depreciar,Activo,DepreciarEn,DepreciarDurante,Documentacion,Observaciones,Clase,UsuarioCrea,FechaCrea,Tipo) values
			('$Compania[0]',$AutoId,'$Codigo','$fila[0]','$fila[1]','$fila[2]','$fila[3]','$fila[4]','$fila[5]','$fila[6]','$fila[7]','$fila[8]','$fila[9]','$fila[10]',
			'$fila[11]','$fila[12]','$fila[13]','$fila[14]','$fila[15]','$Clase','$usuario[0]',
			'$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','Levantamiento Inicial')";
			$res = ExQuery($cons3);
			
			if($fila1)
			{
				$cons3 = "Insert into InfraEstructura.Ubicaciones(Compania,CentroCostos,Responsable,FechaIni,AutoId,UsuarioCrea,FechaCrea,Clase) values
				('$Compania[0]','$fila1[1]','$fila1[0]','$fila1[2]',$AutoId,'$usuario[0]',
				'$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Clase')";
				$res3 = ExQuery($cons3);	
			}
			$AutoId++;
			if($Codigo!=""){$Codigo++;}
		}
		?>
			<script language="javascript">
            	parent.document.FORMA.submit();
            </script>
		<?
	}
?>
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		//parent.document.getElementById('FrameOpener').style.top='1px';
		//parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="Codigo" value="<? echo $Codigo?>" />
<input type="hidden" name="Clase" value="<? echo $Clase?>" />
<input type="hidden" name="AutoId" value="<? echo $AutoId?>"  /> 
<table style='font : normal normal small-caps 10px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="2" align="center">DUPLICAR ELEMENTO</td></tr>
<tr bgcolor="#e5e5e5" style="font-weight:bold">
<td>
<select name="Veces">
	<? for($i = 1; $i<=100; $i++){ echo "<option value='$i'>$i</option>";} ?>
</select>
</td><td>Veces</td>
</tr>
<tr>
<td><input type="submit" name="Ejecutar" value="Ejecutar" style="font-size:10px" /></td>
<td><input type="button" name="Cerrar" value="Cerrar" style="font-size:10px" onClick="CerrarThis()" /></td>
</tr>
</table>
</form>
</body>