<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include("FuncionesUnload.php");
	@require_once ("xajax/xajax_core/xajax.inc.php");
	
	$obj = new xajax(); 
	$obj->registerFunction("Clear_Table");
	$obj->registerFunction("Modify_Table");
	$obj->processRequest(); 
	
	$ND = getdate();
	//UM:26-04-2011
        $cons = "Select Usuario,Cedula,PrimApe,SegApe,PrimNom,SegNom From Infraestructura.Administrador, Central.Terceros
	Where Administrador.Cedula = Terceros.Identificacion and Administrador.Compania = '$Compania[0]' and Terceros.Compania='$Compania[0]' and Usuario = '$usuario[0]'";
	//echo $cons;
	$res = ExQuery($cons);
	if(ExNumRows($res)==0)
	{
		$cons1 = "Select Cedula,PrimApe,SegApe,PrimNom,SegNom From Central.Usuarios, Central.Terceros Where
		Usuarios.Cedula = Terceros.Identificacion and Terceros.Compania='$Compania[0]'
		and Nombre = '$usuario[0]'";
		$res1 = ExQuery($cons1);
		$fila1 = ExFetch($res1);
		$Identificacion = $fila1[0];
		$Tercero = "$fila1[1] $fila1[2] $fila1[3] $fila[4]";
		$IRO = " readonly ";	
	}
	if($Guardar)
	{
		$cons = "Select cabecerademensaje,PiedeMensaje from Infraestructura.MensajeActas Where Compania='$Compania[0]'";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		$Cabecera=$fila[0];
		$Piede=$fila[1];
		//echo $Cabecera;
		//echo $Piede; exit;
		if(!$Edit)
		{
			$cons = "Select Numero from Infraestructura.Traslados Where Compania='$Compania[0]' and Numero IS NOT NULL
			order by Numero Desc";
			$res = ExQuery($cons);
			if(ExNumRows($res) == 0)
			{
				$cons1 = "Select NumInicial From Infraestructura.Numeracion Where Compania='$Compania[0]' and Anio=$Anio and Tipo = 'Traslados'";
				$res1 = ExQuery($cons1);
				$fila1 = ExFetch($res1);
				$Numero = $Anio.$fila1[0];
			}
			else
			{
				$fila = ExFetch($res);
				$Numero = $fila[0] + 1;
			}	
			
			$cons = "Update InfraEstructura.Traslados set Cedula = '$Identificacion', FechaSolicita = '$Anio-$Mes-$Dia',
			Numero='$Numero',TMPCOD='',CCDestino='$CCD', Estado='Solicitado', CabeceraTraslado='$Cabecera', PiedeTraslado='$Piede', 
			SubUbicacionDestino='$SubUb' 
			Where Compania='$Compania[0]' and TMPCOD='$TMPCOD'";
			//echo "NoEdit";
		}
		else
		{
			$cons = "Update InfraEstructura.Traslados set Cedula = '$Identificacion', FechaSolicita = '$Anio-$Mes-$Dia',
			CCDestino='$CCD', TMPCOD='', CabeceraTraslado='$Cabecera', PiedeTraslado='$Piede', SubUbicacionDestino='$SubUb' 
			Where Compania='$Compania[0]' and Numero='$Numero' and TMPCOD='$TMPCOD'";	
			//echo "Edit";
		}
		$res = ExQuery($cons);
		//echo $cons;
		?>
		<script language="javascript">
        	location.href="Movimiento.php?DatNameSID=<? echo $DatNameSID?>&Tipo=<? echo $Tipo?>&Clase=<? echo $Clase?>";	
        </script>	
		<?	
	}
	if(!$TMPCOD){$TMPCOD=strtotime("$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]").rand(1,9999);}
	if($Edit)
	{
		$cons = "Update Infraestructura.Traslados set TMPCOD = '$TMPCOD' Where Compania='$Compania[0]' and Numero='$Numero'";
		$res = ExQuery($cons);	
	}
	if(!$Numero)
	{
		$cons = "Select Numero from Infraestructura.Traslados Where Compania='$Compania[0]' and Numero IS NOT NULL
		order by Numero Desc";
		$res = ExQuery($cons);
		if(ExNumRows($res) == 0)
		{
			$cons1 = "Select NumInicial From Infraestructura.Numeracion Where Compania='$Compania[0]' and Anio=$Anio and Tipo = 'Traslados'";
			$res1 = ExQuery($cons1);
			$fila1 = ExFetch($res1);
			$Numero = $Anio.$fila1[0];
		}
		else
		{
			$fila = ExFetch($res);
			$Numero = $fila[0] + 1;
		}	
	}
	$cons = "Select AutoId from Infraestructura.Traslados Where Compania='$Compania[0]' and Numero='$Numero' and TMPCOD='$TMPCOD'";
	$res = ExQuery($cons);
	if(ExNumRows($res)==0){$DisGuardar = " disabled ";}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<? $obj->printJavascript("../xajax");?>
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
	function Validar()
	{
		if(document.FORMA.Dia.value==""){alert("Ingrese la fecha de manera correcta");return false;}
		if(document.FORMA.Tercero.value==""){alert("Tercero no Ingresado");return false;}
		if(document.FORMA.Identificacion.value==""){alert("Identificacion no Ingresada");return false;}
		if(document.FORMA.CCD.value==""){alert("El Centro de Costo debe escogerse desde el Asistente de Busqueda");return false;}
			
	}
	function AbrirOrdenCompra()
	{
		frames.FrameOpener.location.href="OrdenesCompra.php?DatNameSID=<? echo $DatNameSID?>&Identificacion="+document.FORMA.Identificacion.value+"&Tipo=<? echo $Tipo?>&TMPCOD=<? echo $TMPCOD?>&Clase=<? echo $Clase?>&Anio="+document.FORMA.Anio.value+"&Fecha="+document.FORMA.Anio.value+"-"+document.FORMA.Mes.value+"-"+document.FORMA.Dia.value
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='50px';
		document.getElementById('FrameOpener').style.left='15px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='690';
		document.getElementById('FrameOpener').style.height='390';
	}
	function AbrirElemento(Tipo,Frame,ElementoTipo,Identificacion,CC)
	{
		frames.FrameOpener.location.href="SeleccionElemento.php?DatNameSID=<? echo $DatNameSID?>&Tipo="+Tipo+"&Frame="+Frame+"&ElementoTipo="+ElementoTipo+"&Identificacion="+Identificacion+"&CC="+CC;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='50px';
		document.getElementById('FrameOpener').style.left='15px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='690';
		document.getElementById('FrameOpener').style.height='390';	
	}
</script>
<? $Campos = "CabeceraTraslado,PiedeTraslado";?>
</head>
<body background="/Imgs/Fondo.jpg" 
onUnload="if(document.FORMA.NoEliminar.value == ''){xajax_Clear_Table('Infraestructura.Traslados','<? echo $TMPCOD?>','<? echo $Campos?>');}"  />
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="Clase" value="<? echo $Clase?>" />
<input type="hidden" name="Tipo" value="<? echo $Tipo?>" />
<input type="hidden" name="Numero" value="<? echo $Numero?>" />
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD;?>" />
<input type="hidden" name="Edit" value="<? echo $Edit?>" />
<input type="hidden" name="NoEliminar" />
<table border="0">
<tr><td>
<table border="1" width="750" bordercolor="<? echo $Estilo[1]?>" style="font-family:<? echo $Estilo[8]?>;font-size:12;font-style:<? echo $Estilo[10]?>">
	<tr style="color:<? echo $Estilo[6]?>;font-weight:bold;text-align:center" bgcolor="<? echo $Estilo[1]?>">
    	<td colspan="4">Nuevo Traslado</td>
    </tr>
	<tr>
    	<td>Fecha</td>
		<td><input type="Text" name="Anio" style="width:40px;" onFocus="Ocultar()" readonly value="<? echo $Anio?>">
		<?
			$cons="Select * from Central.UsuariosxModulos where Usuario='$usuario[1]' and Modulo='Administrador'";
			$res=ExQuery($cons);
			if(ExNumRows($res)==1)
			{
		?>
			<select name="Mes" style="width:40px" onFocus="Ocultar()" <? if($IRO){ echo " disabled ";}?> />
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
		<input type="Text" name="Dia" maxlength="2" onFocus="Ocultar()" style="width:20px;" value="<? echo $Dia?>"  <? echo $IRO ?>>
		</td>
		<td>Numero</td>
		<td><input type="Text" name="Numero" onFocus="Ocultar()" readonly
        	style="width:170px;font-size:16px;color:blue;border:0px;font-weight:bold" value="<? echo $Numero?>"></td>
	<tr>
	<td>Tercero</td>
		<td><input type="Text" name="Tercero" value="<? echo $Tercero;?>" style="width:280px;" <? echo $IRO;?>
        		onKeyUp="xLetra(this);Mostrar();Identificacion.value='';frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Nombre&Nombre='+this.value"
                onKeyDown="xLetra(this)"/>
               </td>
		<td>Cedula</td>
		<td><input type="Text" value="<? echo $Identificacion?>" style="width:230px;" name="Identificacion" <? echo $IRO;?>
        onchange="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Identificacion&Identificacion='+this.value"
        onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"></td>
	 
	<tr>
		<td>Centro Costos</td>
		<td><input type="Text" value="<? echo $CCDestino?>" name="CCDestino" style="width:280px;"
        onfocus="Mostrar();
        frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&ObjetoCCVer=CCDestino&ObjetoCC=CCD&ObjetoValida=ValidaCC&Tipo=CCxTercero&CC='+this.value+'&Anio='+Anio.value+'&Cedula='+Identificacion.value;"
		onKeyUp="CCD.value = '';xLetra(this);
        frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&ObjetoCCVer=CCDestino&ObjetoCC=CCD&ObjetoValida=ValidaCC&Tipo=CCxTercero&CC='+this.value+'&Anio='+Anio.value+'&Cedula='+Identificacion.value;" 
        onKeyDown="xLetra(this)" /><input type="hidden" name="CCD" value="<? echo $CCD;?>" /></td>
        <td>SUB UBICACION</td>
        <td><input type="text" name="SubUb" onKeyDown="xLetra(this)" title="SubUbicacion"
        onfocus="Mostrar();
        frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=SubUbicacionxCC&SubUbicacion='+this.value+'&CC='+CCD.value+'&ObjUbicacion=SubUb&Anio=<? echo $ND[year]?>';"
        onkeyup="xLetra(this);
        frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=SubUbicacionxCC&SubUbicacion='+this.value+'&CC='+CCD.value+'&ObjUbicacion=SubUb&Anio=<? echo $ND[year]?>';" />
        </td>
    </tr>
</table>
</td></tr>
<tr><td>
<iframe id="NuevoMovimiento" height="350" frameborder="0" width="100%" scrolling="no" 
src="DetTraslados.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Clase=<? echo $Clase?>&TMPCOD=<? echo $TMPCOD ?>&Tipo=<? echo $Tipo?>&Numero=<? echo $Numero?>&Editar=<? echo $Editar?>">
</iframe><br>
</td></tr>
<tr><td><center>
	<input type="submit" name="Guardar" value="Guardar Registro" <? echo $DisGuardar;?> onClick="NoEliminar.value='1'" />
    <input type="button" name="Cancelar" value="Cancelar" onClick="location.href='Movimiento.php?DatNameSID=<? echo $DatNameSID?>&Tipo=<? echo $Tipo?>&Clase=<? echo $Clase?>'" />
</center></td></tr>
</table>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php" frameborder="0" height="400"></iframe>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>
</form>
</body>
</html>