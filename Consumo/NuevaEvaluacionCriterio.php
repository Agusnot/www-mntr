<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include ("Funciones.php");
	$ND = getdate();
	if($Guardar)
	{
		while(list($cad,$val)=each($Criterio))
		{
			if(!$Editar)
			{
				$cons = "Insert into Consumo.CriteriosXProveedor (Compania,AlmacenPpal,Grupo,Criterio,Calificacion,Identificacion,Fecha,Tipo,Anio)
				values ('$Compania[0]','$AlmacenPpal','$Grupo','$cad','$val','$Cedula','$Fecha','$Tipo','$ND[year]')";
			}
			else
			{
				$cons = "Update Consumo.CriteriosXProveedor set Calificacion = '$val', Fecha = '$Fecha'
				where Criterio = '$cad' and Compania = '$Compania[0]' and AlmacenPpal = '$AlmacenPpal' 
				and Grupo = '$Grupo' and Identificacion = '$Cedula' and Fecha = '$Fecha' and Tipo = '$Tipo'";
			}
			$res = ExQuery($cons);
			?><script language="javascript">location.href="EvaluacionCriterios.php?DatNameSID=<? echo $DatNameSID?>&Tipo=<? echo $Tipo?>&Cedula=<? echo $Cedula?>&AlmacenPpal=<? echo $AlmacenPpal?>";</script><?
		}	
	}
?>
<script language='javascript' src="/Funciones.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
	function Validar()
	{
		b = 0;
		if(document.FORMA.Fecha.value==""){alert("No se ha ingresado Fecha");b=1;}
		else{
			var c = parseInt(document.FORMA.Cuenta.value);
			for(i=5;i<c+5;i++){if(document.FORMA.item(i).value==""){ b=1;}}
			}
		if(b==1){ return false;}
	}
	function Operar(Campo,Evento)
	{
		if(Campo.value!="")
		{
			if(Evento=='onfocus'){document.FORMA.Total.value = parseInt(document.FORMA.Total.value) - parseInt(Campo.value);}
			if(Evento=='onBlur'){document.FORMA.Total.value = parseInt(document.FORMA.Total.value) + parseInt(Campo.value);}
		}
		if(document.FORMA.Total.value > 100){document.FORMA.Guardar.disabled = true;}
		else{document.FORMA.Guardar.disabled = false;}	
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="Hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal?>" />
<input type="Hidden" name="Cedula" value="<? echo $Cedula?>" />
<input type="Hidden" name="Tipo" value="<? echo $Tipo?>"  />
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
	<tr><td bgcolor="e5e5e5">Fecha:</td>
    	<td><input type="text" name="Fecha" value="<? echo $Fecha?>" readonly
        	onclick="popUpCalendar(this, FORMA.Fecha, 'yyyy-mm-dd')" /></td>	
    </tr>
    <tr>
    	<td bgcolor="e5e5e5" width="30%">Grupo</td>
        <? if($Editar){$Disabled=" disabled ";}?>
    	<td width="70%"><select name="Grupo" onChange="FORMA.submit()" style="width:100%" <? echo $Disabled?> >
<?
			if($Tipo=='Seleccion')
			{
				$cons="Select Grupo from Consumo.Grupos
						where AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]' 
						and Grupo not in
							(Select Grupo from Consumo.CriteriosXProveedor where AlmacenPpal='Almacen Consumo' and Compania='Hospital San Rafael de Pasto' and Tipo='$Tipo')";
			}
			else{ $cons="Select Grupo from Consumo.Grupos where AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]'"; }
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				if($Grupo==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else {echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
?>
		</select></td>
<?
  		if($Editar)
		{
			$cons = "Select Criterio,Calificacion from Consumo.CriteriosXProveedor
			where Compania = '$Compania[0]' and AlmacenPpal = '$AlmacenPpal' and Grupo = '$Grupo' and Identificacion = '$Cedula' and Fecha = '$Fecha' and Tipo = '$Tipo'";
			//echo $cons;
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				$Criterio[$fila[0]]=$fila[1];
				$Total = $Total + $fila[1];
			}
		}
		else{ $Total = 0;}
		$cons = "Select Criterio from Consumo.CriteriosXGrupo 
				where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Grupo='$Grupo' and Tipo='$Tipo' and Completo='SI'";
		$res = ExQuery($cons); 
		if(ExNumRows($res)==0){echo "<em><tr><td colspan='2'>El Grupo no tiene criterios de $Tipo</td></tr></em>";$Cancelar=1;}
		else
		{
			echo "<tr align='center' bgcolor='e5e5e5' style='font-weight:bold;'><td>Criterio</td><td align='left'>Calificaci&oacute;n</td></tr>";
			while($fila = ExFetch($res))
			{
				$Cuenta = $Cuenta + 1;
				?><tr><td><? echo $fila[0]?></td>
               		  <td align="left">
                         <input type="text" name="Criterio[<? echo $fila[0]?>]" value="<? echo $Criterio[$fila[0]]?>" size="4" maxlength="3" style="text-align:right" 
                         onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" 
                         onBlur="campoNumero(this);Operar(this,'onBlur');" onFocus="Operar(this,'onfocus')" />
                     </td></tr><?
			}
			?>
            <tr bgcolor="e5e5e5">
            	<td align="right" style="font-weight:bold">Total</td>
                <td align="left"><input type="text" name="Total" size="4" maxlength="3" readonly="readonly" style="text-align:right" value="<? echo $Total?>" /> 
            <tr><td colspan="2">
            <input type="submit" name="Guardar" value="Guardar" />
			<?
		}
		
	if($Cancelar){ echo "<td colspan='2'>";}	
?>
    <input type="button" name="Cancelar" value="Cancelar"
         onclick="location.href='EvaluacionCriterios.php?DatNameSID=<? echo $DatNameSID?>&Tipo=<? echo $Tipo?>&Cedula=<? echo $Cedula?>&AlmacenPpal=<? echo $AlmacenPpal?>'" /></td></tr>
    </tr>
</table>
<input type="Hidden" name="Cuenta" value="<? echo $Cuenta?>" />
</form>
</body>