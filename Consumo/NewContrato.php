<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar)
	{
		if($FechaIni >= $FechaFin)
		{
			?><script language="javascript">alert("LA FECHA INICIAL NO PUEDE SER MAYOR O IGUAL A LA FECHA FINAL DEL CONTRATO");</script><?
			$FechaIni = "";
			$FechaFin = "";
		}
		else
		{
			if(!$Editar)
			{
				$cons = "Insert Into Consumo.Contratos 
				(Compania,AlmacenPpal,Proveedor,FechaInicio,FechaFin,Numero,Valor,Poliza,Contacto,Email,Telefono,Observaciones)
				values
				('$Compania[0]','$AlmacenPpal','$Cedula','$FechaIni','$FechaFin','$NumeroContrato',
				'$Valor','$Poliza','$Contacto','$Email','$Telefono','$Observaciones')";
				$res = ExQuery($cons);
				$Editar = 1;
			}
			else
			{
				$cons = "Update Consumo.Contratos set
				Proveedor = '$Cedula', FechaInicio = '$FechaIni', FechaFin = '$FechaFin',Numero = '$NumeroContrato',
				Valor = '$Valor',Poliza = '$Poliza',Contacto = '$Contacto',Email = '$Email',Telefono = '$Telefono',Observaciones = '$Observaciones'
				where Compania = '$Compania[0]' and AlmacenPpal = '$AlmacenPpal' and Numero = '$NumeroX'";
				$res = ExQuery($cons);
			}
		}
	}
?>
<script language='javascript' src="/Funciones.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
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
		var b = 0;
		if(document.FORMA.Tercero.value == ""){alert("Falta Llenar el Campo Proveedor");b = 1;}
		else{if(document.FORMA.FechaIni.value == ""){alert("Falta Llenar el Campo Fecha Inicial");b = 1;}
			else{if(document.FORMA.FechaFin.value == ""){alert("Falta Llenar el Campo Fecha Final");b = 1;}
				else{if(document.FORMA.NumeroContrato.value == ""){alert("Falta Llenar el Campo Numero");b = 1;}
					else{if(document.FORMA.Poliza.value == ""){alert("Falta Llenar el Campo Poliza");b = 1;}
						else{if(document.FORMA.Contacto.value == ""){alert("Falta Llenar el Campo Contacto");b = 1;}
							else{if(document.FORMA.Email.value == ""){alert("Falta Llenar el Campo Email");b = 1;}
								else{if(document.FORMA.Telefono.value == ""){alert("Falta Llenar el Campo Telefono");b = 1;}
									else{if(document.FORMA.Valor.value == ""){alert("Falta Llenar el Campo Valor");b = 1;}}}}}}}}}
		if(b==0)
		{
			if(document.FORMA.Cedula.value == ""){alert("Tercero Invalido");b=1;}
		}
		
		if(b == 1){ return false;}
	}
</script>
<?
	if($Editar)
	{
		$cons1 = "Select PrimApe,SegApe,PrimNom,SegNom from Central.Terceros where Identificacion = '$Cedula'";
		$res1 = ExQuery($cons1);
		$fila1 = ExFetch($res1);
		$Tercero = "$fila1[0] $fila1[1] $fila1[2] $fila1[3]";
		$cons = "Select Proveedor,FechaInicio,FechaFin,Numero,Poliza,Valor,Contacto,Email,Telefono,Observaciones
		from Consumo.Contratos where Compania = '$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Numero='$NumeroContrato'";
		$res = ExQuery($cons);
		$fila=ExFetch($res);
		$Cedula = $fila[0]; $FechaIni = $fila[1]; $FechaFin = $fila[2]; $NumeroContrato = $fila[3];
		$Poliza = $fila[4]; $Valor = $fila[5]; $Contacto = $fila[6]; $Email = $fila[7];
		$Telefono = $fila[8]; $Observaciones = $fila[9];
	}
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="Hidden" name="Cedula" value="<? echo $Cedula?>" />
<input type="Hidden" name="NumeroX" value="<? echo $NumeroContrato?>" />
	<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="600px">
    	<tr>
        	<td colspan="4"><input type="text" name="AlmacenPpal" value="<? echo $AlmacenPpal?>" readonly
            style="text-align:center; font-weight:bold; background:#e5e5e5; width:100%; border:thin" /></td>
        </tr>
    	<tr>
        	<td bgcolor="#e5e5e5">Proveedor:</td><td colspan="3">
            <input type="text" name="Tercero" style="width:100%" value="<? echo $Tercero?>" 
            onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Nombre&Nombre='+this.value" 
		    onkeyup="xLetra(this);FORMA.Cedula.value='';frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Nombre&Nombre='+this.value;" 
            onKeyDown="xLetra(this)"/></td>
        </tr>
        <tr>
        	<td bgcolor="#e5e5e5">Fecha de Inicio:</td><td>
            <input style="width:90px;" type="text" name="FechaIni" readonly onFocus="Ocultar()"
        			onclick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')"  value="<? echo $FechaIni; ?>"  />	
 			<td bgcolor="#e5e5e5">Fecha de Fin:</td><td>
            <input style="width:90px;" type="text" name="FechaFin" readonly onFocus="Ocultar()"
            		onclick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd'); this.value='';"  value="<? echo $FechaFin; ?>" /></td>
        </tr>
        <tr>
        	<td bgcolor="#e5e5e5">Numero:</td><td><input type="text" name="NumeroContrato" value="<? echo $NumeroContrato?>" onFocus="Ocultar()" 
            onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"/></td>
        	<td bgcolor="#e5e5e5">Poliza</td><td><input type="text" name="Poliza" value="<? echo $Poliza?>" onFocus="Ocultar()" 
            onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"/></td>
        </tr>
        <tr>
        	<td colspan="3" bgcolor="#e5e5e5" align="right">Valor Contrato:</td><td><input type="text" name="Valor" value="<? echo $Valor?>" onFocus="Ocultar()"
            onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/></td>
        </tr>
        <tr>
        	<td bgcolor="#e5e5e5">Contacto:</td><td colspan="3"><input type="text" name="Contacto" style="width:100%" value="<? echo $Contacto?>" onFocus="Ocultar()"
            onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" /></td>
        </tr>
        <tr>
            <td bgcolor="#e5e5e5">Email:</td><td><input type="text" name="Email" value="<? echo $Email?>" onFocus="Ocultar()" 
            onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"/></td>
            <td bgcolor="#e5e5e5">Telefono:</td><td><input type="text" name="Telefono" value="<? echo $Telefono?>" onFocus="Ocultar()"
            onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" maxlength="10"/></td>
        </tr>
        <tr>
        	<td bgcolor="#e5e5e5">Observaciones:</td><td colspan="3"><textarea name="Observaciones" style="width:100%" onFocus="Ocultar()"
            onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"><? echo $Observaciones?></textarea></td>
        </tr>
    <?
    if($Editar)
	{
		?>
		<tr><td colspan="4"><iframe frameborder="0" id="Productos" style="border:#e5e5e5 thin; width:100%" 
        src="ModProductosxContrato.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal=<? echo $AlmacenPpal?>&Numero=<? echo $NumeroContrato?>"></iframe></td></tr>
		<?	
	}
	?>
    </table>
    <input type="submit" id="Guardar" name="Guardar" value="Guardar" />
    <input type="button" id="Cerrar" name="Cerrar" value="Cerrar" onClick="location.href='Contratos.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal=<? echo $AlmacenPpal?>'"  />
    <input type="hidden" value="<? echo $Editar?>" name="Editar" />
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
</form>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php" frameborder="0" height="400"></iframe>
</body>