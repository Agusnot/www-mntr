<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include("FuncionesUnload.php");
	@require_once ("xajax/xajax_core/xajax.inc.php");
	
	$obj = new xajax(); 
	$obj->registerFunction("Clear_Table"); 
	$obj->processRequest(); 

	$ND = getdate();
	if(!$TMPCOD){$TMPCOD=strtotime("$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]").rand(1,9999);}
	if(!$Anio){$Anio = $ND[year];}
	if(!$Mes){$Mes = $ND[mon];}
	if(!$Dia){$Dia = $ND[mday];}
	if($Tipo=="Bajas"){$Tabla = "Bajas";$TipoNumInicial="Bajas";}
	if($Tipo=="Traslados"){$Tabla="Traslados";$TipoNumInicial="Traslados";}
	if(!$Numero)
	{
		if($Tipo=="Bajas"){$ad=",TextoActa";}
		$cons = "Select Numero $ad from Infraestructura.$Tabla Where Compania='$Compania[0]' and Numero IS NOT NULL
		and SUBSTR(Numero,0,5) = '$Anio' order by Numero Desc";
		$res = ExQuery($cons);
		if(ExNumRows($res) == 0)
		{
			$cons1 = "Select NumInicial From Infraestructura.Numeracion Where Compania='$Compania[0]' and Anio=$Anio and Tipo = '$TipoNumInicial'";
			$res1 = ExQuery($cons1);
			$fila1 = ExFetch($res1);
			$Numero = $Anio.$fila1[0];
		}
		else
		{
			$fila = ExFetch($res);
			$Numero = $fila[0] + 1;
			$Acta = $fila[1];
		}	
	}
	if($Guardar)
	{
		$cons = "Select Numero from Infraestructura.$Tabla Where Compania='$Compania[0]' and Numero='$Numero'";
		$res = ExQuery($cons);
		if(ExNumRows($res)>0)
		{
			$cons1 = "Select Numero from Infraestructura.$Tabla Where Compania='$Compania[0]' and Numero IS NOT NULL
			and SUBSTR(Numero,0,5) = $Anio order by Numero Desc";
			$res1 = ExQuery($cons1);
			$fila1 = ExFetch($res1);
			$Numero = $fila1[0] + 1;
		}
		if($Tipo=="Bajas")
		{
			$CamposUPT="Estado='Ejecutado',Fecha='$Anio-$Mes-$Dia',UsuarioCrea='$usuario[0]',TMPCOD='',FechaCrea='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',
						Clase='Devolutivos',Numero='$Numero',TextoActa='$Acta',UsuarioAR='$usuario[0]',FechaAR='$Anio-$Mes-$Dia'";
		}
		if($Tipo=="Traslados")
		{
			$CamposUPT="Estado='Ejecutado',Cedula='$Identificacion', FechaSolicita='$Anio-$Mes-$Dia',Numero='$Numero',CCDestino='$CCD', UsuarioAR='$usuario[0]',
			FechaAR='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]', UsuarioCrea='$usuario[0]',
			FechaCrea='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',TMPCOD=''";	
		}
		$cons="Update Infraestructura.$Tabla set $CamposUPT Where Compania='$Compania[0]' and TMPCOD='$TMPCOD'";
		$res = ExQuery($cons);
		if($Tipo=="Bajas")
		{/* Dar de Baja por Cod Elementos*/
			$cons = "Update Infraestructura.CodElementos set Tipo = 'Baja',UsuarioMod='$usuario[0]',
			FechaMod='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]' Where Compania='$Compania[0]'
			and AutoId in(Select AutoId from Infraestructura.Bajas Where Compania='$Compania[0]' and Numero='$Numero')";
			$res = ExQuery($cons);
		}
		if($Tipo=="Traslados")
		{/* Realizar Traslado por Ubicaciones*/
			$cons = "Select AutoId from InfraEstructura.Traslados Where Compania='$Compania[0]' and Numero = '$Numero'";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				$cons1 = "Update Infraestructura.Ubicaciones set FechaFin = '$ND[year]-$ND[mon]-$ND[mday]',Clase='Devolutivos' Where AutoId = $fila[0]
				and FechaFin is NULL and Compania='$Compania[0]'";
				$res1 = ExQuery($cons1);
				$cons1 = "Insert into InfraEstructura.Ubicaciones (Compania,CentroCostos,Responsable,FechaIni,AutoId,UsuarioCrea,FechaCrea,SubUbicacion,Clase)
				values('$Compania[0]','$CCD','$Identificacion','$ND[year]-$ND[mon]-$ND[mday]',$fila[0],
				'$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$SubUb','Devolutivos')";
				$res1 = ExQuery($cons1);	
			}
		}
		?><script language="javascript">location.href="Movimiento.php?DatNameSID=<? echo $DatNameSID?>&Tipo=<? echo $Tipo?>&Origen=Masivo";</script><?
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<? $obj->printJavascript("../xajax");?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function Validar(Tipo)
	{
		if(Tipo=="Bajas")
		{
			if(document.FORMA.Acta.value == ""){alert("Por favor, llenar el acta para la baja a realizar");return false;}	
		}
		if(Tipo=="Traslados")
		{
			if(document.FORMA.Identificacion.value == ""){alert("Tercero Invalido");return false;}
			if(document.FORMA.CCDestino.value == ""){alert("Escoger el centro de Costos"); return false;}	
		}	
	}
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
</head>
<? if($Tipo=="Bajas"){$Nulos="Fecha,UsuarioCrea,FechaCrea,Clase,Numero,TextoActa,UsuarioAR,FechaAR";}
	if($Tipo=="Traslados"){$Nulos="Cedula,FechaSolicita,Numero,CCDestino,Estado,UsuarioAR,FechaAR,UsuarioCrea,FechaCrea";}?>
<body background="/Imgs/Fondo.jpg"
onUnload="if(document.FORMA.NoEliminar.value != '1'){xajax_Clear_Table('Infraestructura.<? echo $Tabla?>','<? echo $TMPCOD?>','<? echo $Nulos?>');}">
<form name="FORMA" method="post" onSubmit="return Validar('<? echo $Tipo?>')">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="Tipo" value="<? echo $Tipo?>" />
<input type="hidden" name="Numero" value="<? echo $Numero?>" />
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD;?>" />
<input type="hidden" name="Edit" value="<? echo $Edit?>" />
<input type="hidden" name="NoEliminar" />
<table border="0">
<tr><td>
<table border="1" width="750" bordercolor="<? echo $Estilo[1]?>" style="font-family:<? echo $Estilo[8]?>;font-size:12;font-style:<? echo $Estilo[10]?>">
	<tr style="color:<? echo $Estilo[6]?>;font-weight:bold;text-align:center" bgcolor="<? echo $Estilo[1]?>">
    	<td colspan="4">Nuevo <? echo $Tipo ?> Masiva</td>
    </tr>
	<tr>
    	<td>Fecha</td>
		<td><input type="Text" name="Anio" style="width:40px;" onFocus="Ocultar()" readonly="yes" value="<? echo $Anio?>">
		<?
			$cons="Select * from Central.UsuariosxModulos where Usuario='$usuario[1]' and Modulo='Administrador'";
			$res=ExQuery($cons);
			if(ExNumRows($res)==1)
			{
		?>
			<select name="Mes" style="width:40px" onFocus="Ocultar()">
		<?
			for($i=1;$i<=12;$i++)
			{
				if($i==$Mes){echo "<option selected value='$i'>$i</option>";}
				else{echo "<option value='$i'>$i</option>";}
			}
		?>
			</select>
		<?
            }
            else
            {
        ?>
			<input type="Text" name="Mes" readonly="yes" style="width:20px" maxlength="2" onFocus="Ocultar()" value="<? echo $Mes?>">
		<?
			}
		if(!$Dia){$Dia=$ND[mday];}
		if($Dia<10 && !$Edit){$Dia="0".$Dia;}
		if(!$FechaDocumento){$FechaDocumento="$Anio-$Mes-$Dia";}
		?>
		<input type="Text" name="Dia" maxlength="2" onFocus="Ocultar()" style="width:20px;" value="<?echo $Dia?>">
		</td>
		<td>Numero</td>
		<td><input type="Text" name="Numero" onFocus="Ocultar()" readonly
        	style="width:170px;font-size:16px;color:blue;border:0px;font-weight:bold" value="<? echo $Numero?>"></td>
	<?
    if($Tipo=="Traslados")
	{
	?>
	<tr>
	<td>Tercero</td>
		<td><input type="Text" name="Tercero" value="<? echo $Tercero;?>" style="width:250px;" <? echo $IRO;?> 
        		onKeyUp="xLetra(this);Mostrar();Identificacion.value='';frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Nombre&Nombre='+this.value"
                onKeyDown="xLetra(this)"/>
               </td>
		<td>Cedula</td>
		<td><input type="Text" value="<? echo $Identificacion?>" style="width:230px;" name="Identificacion" <? echo $IRO;?>
        onchange="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Identificacion&Identificacion='+this.value"
        onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"></td>
	 
	<tr>
		<td>Centro Costos</td>
		<td colspan="3"><input type="Text" value="<? echo $CCDestino?>" name="CCDestino" style="width:480px;" 
        onfocus="Mostrar();
        frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&ObjetoCCVer=CCDestino&ObjetoCC=CCD&ObjetoValida=ValidaCC&Tipo=CCxTercero&CC='+this.value+'&Anio='+Anio.value+'&Cedula='+Identificacion.value;"
		onKeyUp="SubUb.value = '';CCD.value = '';xLetra(this);
        frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&ObjetoCCVer=CCDestino&ObjetoCC=CCD&ObjetoValida=ValidaCC&Tipo=CCxTercero&CC='+this.value+'&Anio='+Anio.value+'&Cedula='+Identificacion.value;" 
        onKeyDown="xLetra(this)" /><input type="hidden" name="CCD" value="<? echo $CCD;?>" />
        <input type="text" name="SubUb" onKeyDown="xLetra(this)" title="SubUbicacion" 
        onfocus="Mostrar();
        frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=SubUbicacionxCC&SubUbicacion='+this.value+'&CC='+CCD.value+'&ObjUbicacion=SubUb&Anio=<? echo $ND[year]?>';"
        onkeyup="xLetra(this);
        frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=SubUbicacionxCC&SubUbicacion='+this.value+'&CC='+CCD.value+'&ObjUbicacion=SubUb&Anio=<? echo $ND[year]?>';" />
        </td>
    </tr>
	<?	
	}
	if($Tipo=="Bajas")
	{
	?>
	<tr>
    	<td>Acta de Baja</td>
        <td colspan="3"><textarea name="Acta" style="width:100%;background:/Imgs/Fondo.jpg" rows="5"><? echo $Acta;?></textarea></td>
    </tr>
	<?		
	}
	?>
    
</table>
</td></tr>
<tr><td>
<iframe id="NuevoMovimiento" height="320" frameborder="0" width="100%"
src="DetAccionMasiva.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&TMPCOD=<? echo $TMPCOD ?>&Tipo=<? echo $Tipo?>&Numero=<? echo $Numero?>&Editar=<? echo $Editar?>">
</iframe><br>
</td></tr>
<tr><td><center>
	<input type="submit" disabled name="Guardar" onClick="NoEliminar.value='1'" value="Guardar Registro" <? echo $DisGuardar;?> />
    <input type="button" name="Cancelar" value="Cancelar" onClick="location.href='Movimiento.php?DatNameSID=<? echo $DatNameSID?>&Tipo=<? echo $Tipo?>&Origen=Masivo'" />
</center></td></tr>
</table>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php" frameborder="0" height="400"></iframe>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>
</form>
</body>
</html>