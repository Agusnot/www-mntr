<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include ("Funciones.php");
	if(!$Anio){$ND = getdate();$Anio = $ND[year];}
	if(!$AlmacenPpal || $AlmacenPpal=="")
	{
		$cons = "Select AlmacenPpal from Consumo.UsuariosxAlmacenes where Usuario='$usuario[0]' and Compania='$Compania[0]'";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		$AlmacenPpal = $fila[0];		
	}
	if(!$Grupo || $Grupo=="")
	{
		$cons="Select Grupo from Consumo.Grupos where Anio=$Anio and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' order by Grupo";
		$res=ExQuery($cons);
		$fila = ExFetch($res);
		$Grupo = $fila[0];
	}
?>
<script language="javascript">
	function Validar(x)
	{
		if(document.FORMA.AlmacenPpal.value!="")
		{
			document.FORMA.action = "NewCuentasxCC.php?Editar="+x;
			document.FORMA.submit();	
		}
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
A&ntilde;o:
<select name="Anio" onChange="document.FORMA.AlmacenPpal.value='';document.FORMA.Grupo.value='';FORMA.submit();" />
    <?
    	$cons = "Select Anio from Central.Anios where Compania = '$Compania[0]'";
		$res = ExQuery($cons);
		while($fila = ExFetch($res))
		{
			if($Anio==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
			else {echo "<option value='$fila[0]'>$fila[0]</option>";}
		}
	?>
</select>
<?
	if($Anio)
	{ ?>
	<select name="AlmacenPpal" onChange="document.FORMA.Grupo.value='';document.FORMA.submit();">
        	<?
            $cons = "Select AlmacenPpal from Consumo.UsuariosxAlmacenes where Usuario='$usuario[0]' and Compania='$Compania[0]'";
			$res = ExQuery($cons);
			echo ExError();
			while($fila=ExFetch($res))
			{
				if($AlmacenPpal==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
			?>
</select>
<?
	if($AlmacenPpal)
	{
?>
		<select name="Grupo" onChange="document.FORMA.submit()">
        	<?
            $cons = "Select Grupo from Consumo.Grupos where AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]' and Anio = $Anio
			order by Grupo";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				if($Grupo == $fila[0]){ echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else { echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
			?>
        </select>
<?		
		if($Grupo)
		{
			$cons="Select CuentasxCC.CentroCostos,CentrosCosto.CentroCostos,Cuenta from 
			Consumo.CuentasxCC,Central.CentrosCosto,Consumo.Grupos where 
			CuentasxCC.CentroCostos=CentrosCosto.Codigo and Grupos.Grupo = CuentasxCC.Grupo
			and CuentasxCC.Compania='$Compania[0]' and CentrosCosto.Compania='$Compania[0]' 
			and CentrosCosto.Compania = '$Compania[0]' and CuentasxCC.AlmacenPpal='$AlmacenPpal' and Grupos.AlmacenPpal='$AlmacenPpal' 
			and Grupos.Anio = $Anio and CuentasxCC.Anio = $Anio and CentrosCosto.Anio=$Anio and CuentasxCC.Grupo='$Grupo'";
			//echo $cons;
			$res=ExQuery($cons);
			if(ExNumRows($res)>0)
			{
		 		echo "<table width='600px' style='font : normal normal small-caps 12px Tahoma;' border='1' bordercolor='#e5e5e5'>
						<tr bgcolor='#e5e5e5' align='center' style='font-weight:bold'><td>Centro de Costos</td><td>Cuenta</td></tr>";
				while($fila=ExFetch($res))
				{
					if($fila[2]!=""){echo"<tr><td>$fila[0]-$fila[1]</td><td>$fila[2]</td></tr>";}
				}
				?></table>
            	<input type="button" name="Editar" value="Editar Lista" onClick="Validar(1)"  /><?
			}
			else
			{
				?></br><font color="red"><em>No existen Registros</em></font></br>
            	<input type="button" name="Nuevo" value="Crear Lista" onClick="Validar(0)"  /><?	
			}
		}
	}
?>
</form>
<?	
	}
?>
</body>