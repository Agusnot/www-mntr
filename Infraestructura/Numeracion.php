<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
	if(!$Anio){ $Anio = $ND[year];}
	
	if($Guardar)
	{
		while(list($cad,$val) = each($NumInicial))
		{
			//echo "$cad  ---- $val";
            if($cad=="Compras"){$ComCont = $CompContableCOMPRAS;}
            if($cad=="Bajas"){$ComCont = $CompContableBAJAS;}
            if($NumInicial[$cad] != "")
			{
				if(!$Editar[$cad])
				{
					$cons = "Insert into Infraestructura.Numeracion (Compania,Anio,NumInicial,Tipo,compcontable)
					values ('$Compania[0]',$Anio,'$val','$cad','$ComCont')";	
				}
				else
				{
					$cons = "Update Infraestructura.Numeracion set NumInicial = '$val', compcontable = '$ComCont'
					Where Compania='$Compania[0]' and Anio = $Anio and Tipo = '$cad'";	
				}
				$res = ExQuery($cons);
			}
		}
	}
    
    $cons = "Select NumInicial,Tipo,compcontable From Infraestructura.Numeracion 
	Where Compania='$Compania[0]' and (Tipo='Orden Compra' or Tipo = 'Compras' or Tipo='Traslados' or Tipo='Bajas') and Anio = $Anio";
	$res = ExQuery($cons);
    while($fila = ExFetch($res))
	{
		if($fila[1] == "Orden Compra"){$NumInicial["Orden Compra"] = $fila[0];$Editar["Orden Compra"]=1;}
		if($fila[1] == "Compras"){$NumInicial[Compras] = $fila[0]; $Editar[Compras] = 1; $CompContableC = $fila[2];}
		if($fila[1] == "Traslados"){$NumInicial[Traslados] = $fila[0];$Editar[Traslados] = 1;}
		if($fila[1] == "Bajas"){$NumInicial[Bajas] = $fila[0];$Editar[Bajas] = 1; $CompContableB = $fila[2];}
	}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.getElementById("NumInicial[Orden Compra]").value == "" && 
		   document.getElementById("NumInicial[Compra]").value == "" && 
		   document.getElementById("NumInicial[Traslados]").value == "" && 
		   document.getElementById("NumInicial[Bajas]").value == ""){alert("No se Ingresaron Numeros Iniciales");return false;}
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5">
	<tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold;">A&ntilde;o</td>
        <td><select name="Anio" onChange="location.href='Numeracion.php?DatNameSID=<? echo $DatNameSID?>&Anio='+this.value">
        <?
        	$cons = "Select Anio from Central.Anios Where Compania='$Compania[0]' order by Anio";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				if($Anio == $fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
		?>
        </select></td>
    </tr>
    <tr  bgcolor="#e5e5e5" style="font-weight:bold;">
        <td align="center">COMPROBANTE</td><td>NUMERO INICIAL</td><td>COMPROBANTE CONTABLE</td>
    </tr>
	
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold;">Orden de Compra</td>
        <td><input type="text" name="NumInicial[Orden Compra]" id="NumInicial[Orden Compra]" 
        value="<? echo $NumInicial["Orden Compra"];?>" size="8" maxlength="6" style="text-align:right;" 
        	onkeyup="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" /></td>
            <input type="hidden" name="Editar[Orden Compra]" value="<? echo $Editar["Orden Compra"]?>" />
            <td> - </td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold;">Traslados</td>
        <td><input type="text" name="NumInicial[Traslados]" id="NumInicial[Traslados]" 
        value="<? echo $NumInicial[Traslados];?>" size="8" maxlength="6" style="text-align:right;" 
        	onkeyup="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" /></td>
            <input type="hidden" name="Editar[Traslados]" value="<? echo $Editar[Traslados]?>" />
            <td> - </td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold;">Compra</td>
        <td><input type="text" name="NumInicial[Compras]" id="NumInicial[Compras]" 
        value="<? echo $NumInicial[Compras];?>" size="8" maxlength="6" style="text-align:right;" 
        	onkeyup="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" /></td>
            <input type="hidden" name="Editar[Compras]" value="<? echo $Editar[Compras]?>" />
        <td>
            <select name="CompContableCOMPRAS"><option></option>
                <?
                $cons = "Select COmprobante from contabilidad.Comprobantes
                Where Compania='$Compania[0]' order by Comprobante";
                $res = ExQuery($cons);
                while($fila=ExFetch($res))
                {
                    if($CompContableC == $fila[0]){$Selected=" selected ";}else{$Selected="";}
                    ?>
                    <option <?echo $Selected?> value="<?echo $fila[0]?>"><?echo $fila[0]?></option>
                    <?
                }
                ?>
            </select>
        </td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold;">Bajas</td>
        <td><input type="text" name="NumInicial[Bajas]" id="NumInicial[Bajas]" 
        value="<? echo $NumInicial[Bajas];?>" size="8" maxlength="6" style="text-align:right;" 
        	onkeyup="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" /></td>
            <input type="hidden" name="Editar[Bajas]" value="<? echo $Editar[Bajas]?>" />
        <td>
            <select name="CompContableBAJAS"><option></option>
                <?
                $cons = "Select COmprobante from contabilidad.Comprobantes
                Where Compania='$Compania[0]' order by Comprobante";
                $res = ExQuery($cons);
                while($fila=ExFetch($res))
                {
                    if($CompContableB == $fila[0]){$Selected=" selected ";}else{$Selected="";}
                    ?>
                    <option <? echo $Selected?> value="<?echo $fila[0]?>"><?echo $fila[0]?></option>
                    <?
                }
                ?>
            </select>
        </td>
    </tr>
</table>
<input type="submit" name="Guardar" value="Guardar" />
</form>
</body>