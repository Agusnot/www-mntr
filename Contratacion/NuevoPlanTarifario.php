<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	function Redondear($Valor,$Unds)
	{
		if($Unds=="Centenares")
		{
			$Valor=$Valor/100;
			$NValor=round($Valor,0);
			$NValor=$NValor*100;
			return $NValor;
		}
		elseif($Unds=="Miles")
		{
			$Valor=$Valor/1000;
			$NValor=round($Valor,0);
			$NValor=$NValor*1000;
			return $NValor;
		}
		else{
			$NValor=round($Valor,0);
			return $NValor;
		}
	}
	if($Guardar)
	{
		$cons = "Select AutoId from ContratacionSalud.PlanesTarifas where Compania = '$Compania[0]' order by AutoId desc";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		$Plan = $fila[0]+1;
		$cons = "Insert into ContratacionSalud.PlanesTarifas (NombrePlan,Compania,AutoId) values ('$NombrePlan','$Compania[0]','$Plan')";
		$res = ExQuery($cons);
		if($TraerDe)
		{
			$cons = "Select CUP,Valor from ContratacionSalud.CupsXPlanes where AutoId = '$TraerDe' and Compania = '$Compania[0]'";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				if($Variacion)
				{if($TipoVar == "Adicion"){$fila[1] = $fila[1] + ($fila[1]*($Variacion/100));}
				else{$fila[1] = $fila[1] - ($fila[1]*($Variacion/100));}}
				$cons0 = "Insert into ContratacionSalud.CUPSXPlanes (AutoId,CUP,Valor,Compania)
				values ('$Plan','$fila[0]','".Redondear($fila[1],$Redondeo)."','$Compania[0]')";
				$res0 = ExQuery($cons0);
			}
		}
		?><script language="javascript">location.href='PlanesTarifarios.php?DatNameSID=<? echo $DatNameSID?>&Plan=<? echo $Plan?>'</script><?
	}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		var b=0;
		if(document.FORMA.NombrePlan.value==""){b=1; alert("Debe llenar el Campo Nombre Plan");}
		if(b==1){return false;}
		
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table cellpadding="4"  border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>" width="30%">
	<tr>
    	<td bgcolor="#e5e5e5" width="10%"><strong>Nombre Plan:</strong></td>
        <td colspan="2"><input type="text" name="NombrePlan" value="<? echo $NombrePlan?>" style="width:100%" 
        onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" /></td>
    </tr>
    <tr>
        <td bgcolor="#e5e5e5"><strong>Traer de:</strong></td>
        <td colspan="2"><select name="TraerDe" style="width:100%"><option value=""></option>
        <?
        	$cons = "Select NombrePlan,AutoId from ContratacionSalud.PlanesTarifas where Compania='$Compania[0]'";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				echo "<option value='$fila[1]'>$fila[0]</option>";
			}
		?>
    	</select></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5"><strong>Variaci&oacute;n</strong></td>
        <td width="20%"><input type="text" name="Variacion" value="<? echo $Variacion?>" size="4" 
        onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/><strong>%</strong></td>
        <td width="80%"><select name="TipoVar">
        	<option value="Adicion">Adici&oacute;n</option>
            <option value="Reduccion">Reducci&oacute;n</option>
        </select></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5"><strong>Redondeo</strong></td>
        <td>
        	<select name="Redondeo">
            	<option value="Ninguno"></option>
                <option value="Miles">Miles</option>
                <option value="Centenares">Centenares</option>
            </select>
        </td>
    </tr>
</table>
<input type="submit" name="Guardar" value="Guardar" />
<input type="button" name="Cancelar" value="Cancelar" onClick="location.href='PlanesTarifarios.php?DatNameSID=<? echo $DatNameSID?>'" />
</form>
</body>