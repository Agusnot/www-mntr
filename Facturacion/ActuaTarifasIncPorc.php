<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar)
	{
		if($NewFechaIni>=$NewFechaFin)
		{
			echo "<em>La Fecha inicial de la vigencia no puede ser mayor o igual a la Fecha final</em>";	
		}
		else
		{
			if($PorcInc)
			{
				$cons="Select Codigo from Central.CUPS";
				$res=ExQuery($cons);
				echo ExError();
				while($fila=ExFetch($res))
				{
					$cons1="Select Valor,Codigo,FechaIni,FechaFin from Facturacion.TarifasxCUPS 
					where Codigo='$fila[0]' and Compania='$Compania[0]' and Tarifario='$Tarifario' 
					order by FechaIni Desc";	
					$res1=ExQuery($cons1);
					$fila1=ExFetch($res1);
					if($Tipo=='Incremento')
					{
						$Valor=$fila1[0]+($fila1[0]*$PorcInc/100);
					}
					else
					{
						$Valor=$fila1[0]-($fila1[0]*$PorcInc/100);
					}
					$cons2="Insert into Facturacion.TarifasxCUPS (Compania,Tarifario,Codigo,FechaIni,FechaFin,Valor)
					values ('$Compania[0]','$Tarifario','$fila[0]','$NewFechaIni','$NewFechaFin','$Valor')";
					$res2=ExQuery($cons2);
					if($fila1[2]=='0000-00-00')
					{
						$cons3="Update Facturacion.TarifasxCUPS set FechaFin='$NewFechaIni' 
						where Codigo='$fila[0]' and Compania='$Compania[0]' Tarifario='$Tarifario'
						and FechaIni='$fila1[1]'";
						$res3=ExQuery($cons3);
					}
				}
			}
			else
			{
				?><script language="javascript">
				alert("No se hara Incremento en el precio");
                </script> <?	
			}
			?>
				<script language="javascript">
                	location.href="ActualizarTarifasCUPS.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal=<? echo $AlmacenPpal?>&Tarifario=<? echo $Tarifario?>";
                </script>
			<?
		}
	}
?>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
	function Validar(x)
	{
		if(x==0)
		{
			
		}
		if(x==1)
		{
			//alert("Pasa 1");
			document.FORMA.action= "ActuaTarifas.php?DatNameSID=<? echo $DatNameSID?>";
			document.FORMA.submit();
		}
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar(0)">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
	<table style='font : normal normal small-caps 12px Tahoma;' border='1' bordercolor='#e5e5e5'>
    	<tr bgcolor="#e5e5e5" align="center">
            <td colspan="2"><input type="text" style="text-align:center; border-style:solid; background-color:#e5e5e5; font-weight:bold"
            name="Tarifario" value="<? echo $Tarifario;?>" /></td>
        </tr>
        <tr>
        	<td bgcolor="#e5e5e5">Porcentaje: </td>
            <td><input type="text" name="PorcInc" size="5" maxlength="5" style="text-align:right;" />%</td>
            <td>Tipo:</td>
            <td><select name="Tipo">
            	<option value="Incremento">Incremento</option>
                <option value="Reduccion">Reduccion</option>
            </select></td>
        </tr>
        <tr>
        	<td colspan="4" align="center" bgcolor="#e5e5e5"><strong>VIGENCIA</strong></td>
        </tr>
        <tr>
        	<td colspan="2"><strong>desde</strong><input type="text" name="NewFechaIni" style="width:100%;" 
            onclick="popUpCalendar(this, FORMA.NewFechaIni, 'yyyy-mm-dd')"  value="<? echo $NewFechaIni; ?>" readonly="yes"  /></td>
            <td colspan="2"><strong>hasta</strong><input type="text" name="NewFechaFin" style="width:100%;" 
            onclick="popUpCalendar(this, FORMA.NewFechaFin, 'yyyy-mm-dd')"  value="<? echo $NewFechaFin; ?>" readonly="yes"  /></td>
        </tr>
    </table>
    <input type="submit" name="Guardar" value="Guardar" />
    <input type="button" name="Cancelar" value="Cancelar" onClick="Validar(1)" />
</form>
</body>