<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar)
	{
		$cons = "Delete from Consumo.CuentasxCC where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' 
		and Grupo='$Grupo' and Anio=$Anio";
		$res = ExQuery($cons);
		while(list($cad,$val)=each($CuentaCC))
		{
			if($val!="")
			{
				$cons="Insert into Consumo.CuentasxCC (Compania,AlmacenPpal,CentroCostos,Cuenta,Anio,Grupo)
				values ('$Compania[0]','$AlmacenPpal','$cad','$val',$Anio,'$Grupo')";	
				$res=ExQuery($cons);
			}
		}
		?><script language="javascript">location.href="CuentasxCC.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&AlmacenPpal=<? echo $AlmacenPpal?>&Grupo=<? echo $Grupo?>"</script><?
	}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
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
		$cons="Select CuentasxCC.CentroCostos,CentrosCosto.CentroCostos,Cuenta from 
		Consumo.CuentasxCC,Central.CentrosCosto where 
		CuentasxCC.CentroCostos=CentrosCosto.Codigo and CuentasxCC.Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' 
		and Grupo='$Grupo' and CuentasxCC.Anio = $Anio";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$CuentaCC[$fila[0]]=$fila[2];	
		}
	}
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="Hidden" name="Anio" value="<? echo $Anio?>" />
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php" frameborder="0" height="400"></iframe>
<table style='font : normal normal small-caps 12px Tahoma;' border='1' bordercolor='#e5e5e5'>
	<tr bgcolor="#e5e5e5" align="center">
    	<td colspan="2"><strong>Almacen Principal:  </strong><? echo $AlmacenPpal;?></td>
    </tr>
    <tr bgcolor="#e5e5e5" align="center">
    	<td colspan="2"><strong>Grupo:  </strong><? echo $Grupo;?></td>
    </tr>
    <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td>Centro de Costos</td><td>Cuenta</td></tr>
    <?
    	$cons="Select Codigo,CentroCostos from Central.CentrosCosto where Tipo = 'Detalle' and Compania='$Compania[0]' and Anio = $Anio order by Codigo";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			?>
			<tr>
            	<td><? echo "$fila[0] - $fila[1]"?></td>
                <td><input type="text" name="CuentaCC[<? echo $fila[0];?>]" id="CuentaCC[<? echo $fila[0]?>]" value="<? echo $CuentaCC[$fila[0]]?>" 
        			onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&Objeto=CuentaCC[<? echo $fila[0]?>]';" 
					onkeyup="xNumero(this);
                    frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&Objeto=CuentaCC[<? echo $fila[0]?>]';"
                    onKeyDown="xNumero(this)" onBlur="campoNumero(this)" /></td>
            </tr>
			<?
		}
	?>
</table>
<input type="submit" name="Guardar" value="Guardar" />
<input type="button" border="Cancelar" value="Cancelar" 
 onClick="location.href='CuentasxCC.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&AlmacenPpal=<? echo $AlmacenPpal?>&Grupo=<? echo $Grupo?>'" />
<input type="Hidden" name="Editar" value="<? echo $Editar?>" />
<input type="Hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal?>" >
<input type="hidden" name="Grupo" value="<? echo $Grupo?>" >
</form>
</body>