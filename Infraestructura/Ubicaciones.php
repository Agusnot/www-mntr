<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
	if($Eliminar)
	{
		if($FechaFin){ $CondFechaFin = " and FechaFin = '$FechaFin' ";}
		$cons = "Delete from Infraestructura.Ubicaciones Where Compania='$Compania[0]' and AutoId = $AutoId 
				and FechaIni = '$FechaIni' $CondFechaFin";
		$res = ExQuery($cons);	
				
	}
	if($Guardar)
	{
		if(!$FechaFin)
		{ 
			$cons = "Insert into Infraestructura.Ubicaciones (Compania,CentroCostos, Responsable, AutoId, FechaIni,UsuarioCrea,FechaCrea,SubUbicacion,Clase)
			values ('$Compania[0]', '$CC', '$ID', '$AutoId', '$FechaIni','$usuario[0]',
			'$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$SubUb','Devolutivos')";
		}
		else
		{
			$cons = "Insert into Infraestructura.Ubicaciones (Compania,CentroCostos, Responsable, AutoId, FechaIni, FechaFin,UsuarioCrea,FechaCrea,SubUbicacion)
			values ('$Compania[0]', '$CC', '$ID', '$AutoId', '$FechaIni', '$FechaFin','$usuario[0]',
			'$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$SubUb')";
		}
		
		$res = ExQuery($cons);	
	}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.CC.value == ""){alert("Debe escribir un valor para el Centros de Costo");return false;}
		if(document.FORMA.Responsable.value == ""){alert("Debe escribir un valor para el Responsable");return false;}
		if(document.FORMA.ID.value == ""){alert("Debe seleccionar un valor de la lista de Terceros");return false;}
		if(document.FORMA.FechaIni.value == ""){alert("Debe seleccionar un valor para la Fecha Inicial");return false;}
		if(document.FORMA.FechaFin.value != "")
		{
			if(document.FORMA.FechaFin.value <= document.FORMA.FechaIni.value){alert("La fecha Final no puede ser menor o Igual que la fecha Inicial");return false;}	
		}
		if(document.FORMA.UltFechaFin.value != "")
		{
			if(document.FORMA.FechaIni.value < document.FORMA.UltFechaFin.value){alert("La fecha Inicial no puede ser Menor o que la Ultima Fecha Final");return false;}
		}
		
	}
</script>
<form name="FORMA" method="post" onsubmit="return Validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="AutoId" value="<? echo $AutoId;?>"  />
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="80%">
	<tr bgcolor="#e5e5e5" style="font-weight:bold;" align="center">
    	<td width="15%">CC</td><td width="30%">Responsable</td><td>SubUbicacion</td>
        <td width="10%">Fecha Inicial</td><td width="10%">Fecha Final</td><td colspan="2" width="3%">&nbsp;</td>
    </tr>
    <?
    	$cons = "Select distinct Ubicaciones.CentroCostos, PrimNom, SegNom, PrimApe, SegApe, FechaIni, FechaFin, CentrosCosto.CentroCostos, Responsable,SubUbicacion 
		From Central.Terceros,Infraestructura.Ubicaciones,Central.CentrosCosto
		Where Ubicaciones.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]' and AutoId=$AutoId and Terceros.Identificacion = Ubicaciones.Responsable
		and CentrosCosto.Codigo = Ubicaciones.CentroCostos and CentrosCosto.Compania = '$Compania[0]' and CentrosCosto.Anio = $ND[year] order by FechaFin";
		//echo $cons;
		$res = ExQuery($cons);
		$NumFilas = ExNumRows($res); $c = 0;
		while($fila = ExFetch($res))
		{
			echo "<tr><td>$fila[0] - $fila[7]</td><td>$fila[1] $fila[2] $fila[3] $fila[4]</td><td>$fila[9]</td>
			<td align='center'>$fila[5]</td><td align='center'>$fila[6]&nbsp;</td>";
			$c++;
			if($c == $NumFilas)
			{
				?><td><button type="button" name="Eliminar" title="Eliminar" 
                onclick="location.href='Ubicaciones.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&AutoId=<? echo $AutoId;?>&FechaIni=<? echo $fila[5];?>&FechaFin=<? echo $fila[6];?>'">
                <img src="/Imgs/b_drop.png" /></button></td><?
			}
			if(!$fila[6] || $fila[6]=="")
			{ 
				$NoIng = 1;
			?>
				<td><button type="button" name="AbrirFechaFin" title="Asignar Fecha Final" 
                onclick="parent.AbrirAlt(event,'NuevaFechaFin.php','AutoId','<? echo $AutoId?>','310','250')">
                <img src="/Imgs/b_usredit.png" /></button></td></tr>			
			<?
			}
			else{ $NoIng = NULL;}
			$UltFechaFin = $fila[6];
			?>
			<script language="javascript">
            	parent.document.FORMA.Identificacion.value='<? echo $fila[8];?>';
				parent.document.FORMA.CC.value = '<? echo $fila[0]?>';
            </script>
			<?	
		}
	if(!$NoIng)
	{
	?>
	<input type="hidden" name="UltFechaFin" value="<? echo $UltFechaFin?>" />
    <tr>
    	<td><input type="text" name="CC" style="width:100%;text-align:right;" 
        onFocus="parent.document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=Contenido&Centro='+this.value+'&Tipo=CCG&Anio=<? echo $ND[year]?>';
            	parent.Mostrar();"
        onkeyup="SubUb.value='';Responsable.value='';parent.document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=Contenido&Centro='+this.value+'&Tipo=CCG&Anio=<? echo $ND[year]?>';
        xNumero(this);" onkeydown="xNumero(this)" onblur="campoNumero(this)" /></td>
        <td><input type="text" name="Responsable" style="width:100%;" 
        onfocus="parent.document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&ObjId=ID&ObjTercero=Responsable&Frame=Contenido&Tercero='+this.value+'&Tipo=TerceroxCC&CC='+CC.value+'&Anio=<? echo $ND[year]?>';parent.Mostrar();"
        onkeyup="ExLetra(this);ID.value='';
        parent.document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&ObjId=ID&ObjTercero=Responsable&Frame=Contenido&Tercero='+this.value+'&Tipo=TerceroxCC&CC='+CC.value+'&Anio=<? echo $ND[year]?>';" onkeydown="ExLetra(this)" /><input type="hidden" name="ID" /></td>
        
        <td><input type="text" name="SubUb" onkeydown="xLetra(this)" 
        onfocus="parent.Mostrar();
        parent.document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=SubUbicacionxCC&SubUbicacion='+this.value+'&CC='+CC.value+'&Frame=Contenido&ObjUbicacion=SubUb&Anio=<? echo $ND[year]?>';"
        onkeyup="xLetra(this);
        parent.document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=SubUbicacionxCC&SubUbicacion='+this.value+'&CC='+CC.value+'&Frame=Contenido&ObjUbicacion=SubUb&Anio=<? echo $ND[year]?>';" /></td>
        
        <td align="center"><input type="text" name="FechaIni" size="8" onfocus="parent.Ocultar()" value="<? echo $FechaIni?>"
        	onclick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')" ondblclick="this.value=''"  readonly /></td>
        <td align="center"><input type="text" name="FechaFin" size="8" onfocus="parent.Ocultar()"
        	onclick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')" ondblclick="this.value=''" readonly /></td>
        <td><button type="submit" name="Guardar" title="Guardar Registro" onclick="parent.Ocultar();"><img src="/Imgs/b_check.png" /></button></td>
    </tr>
	<? }?>
</table>
</form>