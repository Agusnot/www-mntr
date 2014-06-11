<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar)
	{
		$cons = "Delete from Infraestructura.CuentasDepxCC where Compania='$Compania[0]' and Grupo='$Grupo' and Anio=$Anio";
		$res = ExQuery($cons);
		while(list($cad,$val)=each($CuentaCC))
		{
			if($val!="")
			{
				$cons="Insert into Infraestructura.CuentasDepxCC (Compania,CentroCostos,Cuenta,Anio,Grupo)
				values ('$Compania[0]','$cad','$val',$Anio,'$Grupo')";	
				$res=ExQuery($cons);
			}
		}
		?><script language="javascript">location.href="ConfNewGdeElementos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Editar=1&Grupo=<? echo $Grupo?>"</script><?		
	}
	$cons="Select CuentasDepxCC.CentroCostos,CentrosCosto.CentroCostos,Cuenta from 
		Infraestructura.CuentasDepxCC,Central.CentrosCosto where 
		CuentasDepxCC.CentroCostos=CentrosCosto.Codigo and CuentasDepxCC.Compania='$Compania[0]'
		and Grupo='$Grupo' and CuentasDepxCC.Anio = $Anio";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$CuentaCC[$fila[0]]=$fila[2];	
		}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function Mostrar()
	{
		sT = document.body.scrollTop;
		document.getElementById('Busquedas').style.position='absolute';
		document.getElementById('Busquedas').style.top=sT+50;
		document.getElementById('Busquedas').style.right='10px';
		document.getElementById('Busquedas').style.display='';
	}
	function Ocultar()
	{
		document.getElementById('Busquedas').style.display='none';
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="Grupo" value="<? echo $Grupo;?>" />
<input type="hidden" name="Anio" value="<? echo $Anio?>" />
<table style='font : normal normal small-caps 12px Tahoma;' border='1' bordercolor='#e5e5e5'>
	<tr bgcolor="#e5e5e5" align="center">
    	<td colspan="2"><strong>A&ntilde;o:  </strong><? echo $Anio;?></td>
    </tr>
    <tr bgcolor="#e5e5e5" align="center">
    	<td colspan="2"><strong>Grupo:  </strong><? echo $Grupo;?></td>
    </tr>
    <?
    	$cons="Select Codigo,CentroCostos from Central.CentrosCosto where Tipo = 'Detalle' and Compania='$Compania[0]' and Anio = $Anio order by Codigo";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			if($fila[0] != "000")
			{
			?><tr>
            	<td><? echo "$fila[0] - $fila[1]"?></td>
                <td><input type="text" name="CuentaCC[<? echo $fila[0];?>]" id="CuentaCC[<? echo $fila[0]?>]" value="<? echo $CuentaCC[$fila[0]]?>" 
        			onFocus="Mostrar();
                    frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&NoMovimiento=1&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&ObjCuenta=CuentaCC&ID=<? echo $fila[0]?>';" 
					onkeyup="xNumero(this);
                    frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&NoMovimiento=1&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&ObjCuenta=CuentaCC&ID=<? echo $fila[0]?>';"
                    onKeyDown="xNumero(this)" onBlur="campoNumero(this)" /></td>
            </tr>
			<?	
			}
		}
	?>
</table>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php" frameborder="0" height="400"></iframe>
<input type="submit" name="Guardar" value="Guardar" />
<input type="button" name="Volver" value="Volver" onClick="location.href='ConfNewGdeElementos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Editar=1&Grupo=<? echo $Grupo?>'" />
</form>
</body>