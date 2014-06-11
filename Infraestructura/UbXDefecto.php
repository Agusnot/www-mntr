<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
        if($Guardar)
	{
		if(!$Editar)
		{
			$cons2="select autoid from Infraestructura.Ubicaciones order by autoid desc";
			$res2 = ExQuery($cons2);
			$fila2 = ExFetch($res2);
			if($fila2[0]=''){
			$fila2[0]='1';}
			$cons = "Insert into Infraestructura.Ubicaciones (Compania,CentroCostos,Responsable,UsuarioCrea,FechaCrea,XDefecto,FechaIni,AutoId,Clase,SubUbicacion)
			values('$Compania[0]','$CC','$Cedula','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',1,'$ND[year]-01-01',$fila2[0],'Devolutivos','$SubUb')";
		}
		else
		{
			$cons = "Update Infraestructura.Ubicaciones set CentroCostos = '$CC', Responsable = '$Cedula', UsuarioCrea = '$usuario[0]',
			FechaCrea='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]', SubUbicacion = '$SubUb' Where Compania='$Compania[0]' and XDefecto = 1";	
		}
		$res = ExQuery($cons);
	}
	$cons = "Select Ubicaciones.CentroCostos, PrimNom, SegNom, PrimApe, SegApe, FechaIni, CentrosCosto.CentroCostos, Ubicaciones.Responsable, Ubicaciones.SubUbicacion 
			 From Central.Terceros,Infraestructura.Ubicaciones,Central.CentrosCosto
			 Where Ubicaciones.Compania='$Compania[0]' and Terceros.Identificacion = Ubicaciones.Responsable
			 and CentrosCosto.Codigo = Ubicaciones.CentroCostos and CentrosCosto.Compania = '$Compania[0]' and CentrosCosto.Anio = $ND[year]
			 and XDefecto=1";
	$res = ExQuery($cons);
	if(ExNumRows($res)>0)
	{
		$Editar = 1;
		$fila = ExFetch($res);
		$CC = $fila[0];
		$Responsable = "$fila[1] $fila[2] $fila[3] $fila[4]";
		$FechaIni = $fila[5];
		$Cedula = $fila[7];
		$SubUb = $fila[8];
                if($Cedula){$Editar=1;}
	}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function Mostrar()
	{
		document.getElementById('Busquedas').style.position='absolute';
		document.getElementById('Busquedas').style.top='80px';
		document.getElementById('Busquedas').style.right='10px';
		document.getElementById('Busquedas').style.display='';
	}
	function Ocultar()
	{
		document.getElementById('Busquedas').style.display='none';
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="Editar" value="<? echo $Editar?>"  />
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="50%" >
	<tr bgcolor="#e5e5e5" style="font-weight:bold;" align="center">
    	<td colspan="4">UBICACION POR DEFECTO PARA INGRESOS</td>
    </tr>
    <tr bgcolor="#e5e5e5" style="font-weight:bold;" align="center">
    	<td width="10%">CC</td><td width="10%">Sub Ubicaci&oacute;n</td><td width="30%">Responsable</td><td width="3%">&nbsp;</td>
    </tr>
    <tr>
    	<td><input type="text" name="CC" style="width:100%;text-align:right;" value="<? echo $CC;?>" 
        onFocus="document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Centro='+this.value+'&Tipo=CCG&Anio=<? echo $ND[year]?>';
            	Mostrar();"
        onkeyup="SubUb.value='';Responsable.value='';document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Centro='+this.value+'&Tipo=CCG&Anio=<? echo $ND[year]?>';
        xNumero(this);" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" /></td>
        <td><input type="text" name="SubUb" onkeydown="xLetra(this)" value="<? echo $SubUb?>"
        onfocus="Mostrar();
        frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=SubUbicacionxCC&SubUbicacion='+this.value+'&CC='+CC.value+'&ObjUbicacion=SubUb&Anio=<? echo $ND[year]?>';"
        onkeyup="xLetra(this);
        frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=SubUbicacionxCC&SubUbicacion='+this.value+'&CC='+CC.value+'&ObjUbicacion=SubUb&Anio=<? echo $ND[year]?>';" /></td>
        <td><input type="text" name="Responsable" style="width:100%;" value="<? echo $Responsable;?>"
        onfocus="document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&ObjId=Cedula&ObjTercero=Responsable&Tercero='+this.value+'&Tipo=TerceroxCC&CC='+CC.value+'&Anio=<? echo $ND[year]?>';Mostrar();"
        onkeyup="ExLetra(this);ID.value='';
        document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&ObjId=Cedula&ObjTercero=Responsable&Tercero='+this.value+'&Tipo=TerceroxCC&CC='+CC.value+'&Anio=<? echo $ND[year]?>';" onKeyDown="ExLetra(this)" />
         <input type="hidden" name="Cedula" value="<? echo $Cedula?>" /></td>
        <td><button type="submit" name="Guardar" title="Guardar Registro" onClick="Ocultar()"><img src="/Imgs/b_check.png" /></button></td>
    </tr>

</table>
</form>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php" frameborder="0" height="400"></iframe>
</body>