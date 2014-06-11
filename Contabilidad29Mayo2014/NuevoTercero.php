<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if(!$Pais){$Pais="COLOMBIA";}
	$ValDepto=0;$ValMpo=0;
	if($Guardar)
	{
		if(!$Edit){if($DV || $DV=="0"){$Identificacion=$Identificacion."-".$DV;}}
		if($TipoPer=="PN"){$RazonSocial="";}
		else{
			if($RazonSocial){$PrimApe=$RazonSocial;$SegApe="";$PrimNom="";$SegNom="";}
		}

		$PrimApe=strtoupper($PrimApe);
		$SegApe=strtoupper($SegApe);
		$PrimNom=strtoupper($PrimNom);
		$SegNom=strtoupper($SegNom);
		
				if ($TipoPer == "PN") {
					$TipoPersona = "Persona Natural";
				} 
				if ($TipoPer == "PJ") {
					$TipoPersona = "Persona Juridica";
				} 
				
		if($Edit)
		{
			$cons="Update Central.Terceros set Identificacion='$Identificacion',PrimNom='$PrimNom',SegNom='$SegNom',PrimApe='$PrimApe',SegApe='$SegApe',
			RepLegal='$Representante',Direccion='$Direccion',Email='$Email',
			Telefono='$Telefono',Pais='$Pais',Departamento='$Departamento',Municipio='$Municipio',Tipo='$TipoTercero',tipopersona = '$TipoPersona', Regimen='$Regimen',AutoReteFte='$ReteFte',  codactividadeconomica = '$Actividad', 
			AutoReteIVA='$ReteIVA' where Identificacion='$Identificacion' and Terceros.Compania ilike '$Compania[0]'";
		}
		else
		{
			$cons98="Select * from Central.Terceros where Identificacion='$Identificacion' and Compania='$Compania[0]'";
			$res98=ExQuery($cons98);
			if(ExNumRows($res98)==0)
			{	
				
				$cons="Insert into Central.Terceros(Identificacion,PrimApe,SegApe,PrimNom,SegNom,RepLegal,Direccion,Telefono,Pais,Departamento,Municipio,Tipo, TipoPersona, Regimen,  AutoReteFte,AutoReteIVA,Email,Compania, codactividadeconomica)
				values
				('$Identificacion','$PrimApe','$SegApe','$PrimNom','$SegNom','$Representante','$Direccion','$Telefono','$Pais','$Departamento','$Municipio','$TipoTercero','$TipoPersona','$Regimen','$ReteFte','$ReteIVA','$Email','$Compania[0]','$Actividad')";
			}
			else
			{
				$NoAplica=1;$cons=$cons98;
			}
		}
		$res=ExQuery($cons);
		echo ExError($res);
		if($NoAplica)
		{
							echo "<font size=2 style='color:red'><strong>LA IDENTIFICACION NO PUEDE DUPLICARSE!!</font>";exit;

		}
		else
		{
			if($Cerrar)
			{?>
				<script language="JavaScript">
					opener.parent.document.FORMA.Tercero.value="<?echo "$PrimApe $SegApe $PrimNom $SegNom"?>";
					opener.parent.document.FORMA.Identificacion.value="<?echo "$Identificacion"?>";
	<?
					$cons2="Update Presupuesto.TmpMovimiento set Identificacion='$Identificacion' where NumReg='$NUMREG'";
					$res2=ExQuery($cons2);?>
					opener.parent.frames.NuevoMovimiento.location.href=opener.parent.frames.NuevoMovimiento.location.href + '&NoInsert=1';
					window.close();
				</script>
	<?		}
			else
			{
			?>
			<script language="JavaScript">
				parent(0).document.FORMA.submit();
			</script>
			<?
			}
		}
	}
	if($Identificacion)
	{
		$cons="Select Identificacion,PrimApe,SegApe,PrimNom,SegNom,RepLegal,Direccion,Telefono,Pais,Departamento,Municipio,Tipo,Regimen,AutoReteFte,AutoReteIVA,Email,DigitoVerificacion , tipopersona, codactividadeconomica
		from Central.Terceros where Identificacion='$Identificacion' and Terceros.Compania ilike '$Compania[0]'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$PrimApe=$fila[1];$SegApe=$fila[2];$PrimNom=$fila[3];$SegNom=$fila[4];
		$Identificacion=$fila[0];$Representante=$fila[5];$Direccion=$fila[6];$Telefono=$fila[7];$Pais=$fila[8];$Departamento=$fila[9];
		$Municipio=$fila[10];$TipoTercero=$fila[11];$Regimen=$fila[12];$ReteFte=$fila[13];$ReteIVA=$fila[14];$Email=$fila[15];
		$ValDepto=1;$ValMpo=1;$DV=$fila[16];$TipoPersona =$fila[17]; $Actividad = $fila[18];
	}
?>
<script language='javascript' src="/Funciones.js"></script>
<script language="JavaScript">
	function Validar()
	{

		if(document.FORMA.Identificacion.value==""){alert("Ingrese la identificacion del tercero!!!");return false;}
		if(document.FORMA.Identificacion.value=="0"){alert("Ingrese la identificacion del tercero!!!");return false;}
		if(document.FORMA.Direccion.value==""){alert("Ingrese la direccion del tercero!!!");return false;}
		if(document.FORMA.Telefono.value==""){alert("Ingrese el Telefono del tercero!!!");return false;}
		if(document.FORMA.Pais.value==""){alert("Ingrese el pais del tercero!!!");return false;}
		if(document.FORMA.Departamento.value=="" || document.FORMA.ValDepto.value=="0"){alert("Seleccione un departamento de la lista!!!");return false;}
		if(document.FORMA.Municipio.value=="" || document.FORMA.ValMpo.value=="0"){alert("Seleccione un municipio de la lista!!!");return false;}
		if(document.FORMA.TipoTercero.value==""){alert("Seleccione un tipo de tercero!!!");return false;}
		if(document.FORMA.Regimen.value==""){alert("Seleccione el Regimen del tercero!!!");return false;}
		if(document.FORMA.Actividad.value=="" || document.FORMA.ValActividad.value=="0"){alert("Seleccione una actividad economica de la lista!!!");return false;}
	}
</script>

<script language="JavaScript">
	function BuscarDigito()
	{
		MatrizRegistros= new Array(3,7,13,17,19,23,29,37,41,43,47,53,59,67,71);
		Ceros="000000000000000";
		m=0;Subtotal=0;Total=0;
		document.FORMA.TempIdentificacion.value=Ceros.substring(0,15-document.FORMA.Identificacion.value.length)+ document.FORMA.Identificacion.value;
		for(n=14;n>=0;n=n-1)
		{	
			ParteDoc=document.FORMA.TempIdentificacion.value.substring(n,n+1);
			Multip=ParteDoc*MatrizRegistros[m];
			Subtotal=Subtotal+Multip;
			m=m+1;
		}
		SubDigito=(Subtotal % 11);
		if(SubDigito==1){Digito=1;}
		else{Digito=11-SubDigito;}
		if(Digito==11){Digito=0;}
		document.FORMA.DV.value=Digito;
	}
</script>
<script language="JavaScript">
	function BuscarDuplicados(Cedula)
	{
		
	}
</script>
<title>OH MENTOR</title>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta></head>
<body background="/Imgs/Fondo.jpg">

<form name="FORMA" onSubmit="return Validar()">
<table cellpadding="4" border="1" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
<tr><td colspan="6" style="color:<?echo $Estilo[6]?>;font-weight:bold" bgcolor="<?echo $Estilo[1]?>"><center>TERCERO</td></tr>
<tr>
<td colspan="4">
<?	
	if($TipoPersona=="Persona Natural"){$CheckedPN=" checked ";$CheckedPJ="";$VisiblePN="visible";$VisiblePJ="hidden";}
	elseif ($TipoPersona=="Persona Juridica"){$CheckedPN="";$CheckedPJ=" checked ";$VisiblePJ="visible";$VisiblePN="hidden";}
	
?>
Persona Natural <input type="Radio" value="PN" name="TipoPer" <? echo $CheckedPN ?> onClick="if(this.checked==1){Tabla1.style.visibility='visible';Tabla2.style.visibility='hidden'}else{Tabla1.style.visibility='hidden';Tabla2.style.visibility='visible'}"> 
Persona Juridica <input type="Radio" value="PJ" name="TipoPer" <? echo $CheckedPJ ?> onClick="if(this.checked==1){Tabla2.style.visibility='visible';Tabla1.style.visibility='hidden'}else{Tabla2.style.visibility='hidden';Tabla1.style.visibility='visible'}">

<table id="Tabla1" style="visibility:<?echo $VisiblePN ?>;position:absolute;left:10px;top:70px;" width="100%" cellpadding="4" border="1" bordercolor="white" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
<tr align="center" style="color:white;font-weight:bold" bgcolor="<?echo $Estilo[1]?>"><td>Primer Apellido</td><td>Segundo Apellido</td><td>Primer Nombre</td><td>Segundo Nombre</td></tr>
<tr>
<td><input type="Text" name="PrimApe" value="<?echo $PrimApe?>" onKeyUp="document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=TercerosxTodos&PrimApe='+PrimApe.value+'&SegApe='+SegApe.value+'&PrimNom='+PrimNom.value+'&SegNom='+SegNom.value"></td>
<td><input type="Text" name="SegApe" value="<?echo $SegApe?>" onKeyUp="document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=TercerosxTodos&PrimApe='+PrimApe.value+'&SegApe='+SegApe.value+'&PrimNom='+PrimNom.value+'&SegNom='+SegNom.value"></td>
<td><input type="Text" name="PrimNom" value="<?echo $PrimNom?>" onKeyUp="document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=TercerosxTodos&PrimApe='+PrimApe.value+'&SegApe='+SegApe.value+'&PrimNom='+PrimNom.value+'&SegNom='+SegNom.value"></td>
<td><input type="Text" name="SegNom" value="<?echo $SegNom?>" onKeyUp="document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=TercerosxTodos&PrimApe='+PrimApe.value+'&SegApe='+SegApe.value+'&PrimNom='+PrimNom.value+'&SegNom='+SegNom.value"></td>
</tr>
</table>

<table id="Tabla2" style="visibility:<? echo $VisiblePJ?>" width="100%" cellpadding="4" border="1" bordercolor="white" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
<tr align="center" style="color:white;font-weight:bold" bgcolor="<?echo $Estilo[1]?>"><td>Razn Social</td></tr>
<tr>
<td><input type="Text" name="RazonSocial" value="<?echo $PrimApe?>" style="width:600px;" onKeyUp="document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=TercerosxTodos&PrimApe='+this.value"></td>
</tr>
</table>
<td>Identificacion</td><td>
<?if(!$Edit){?>
Sin Digito de Verificacion<input type="checkbox" onClick="if(this.checked){DV.disabled=true}else{DV.disabled=false}"><br>

<input type="Text" name="Identificacion" style="width:120px;" onBlur="BuscarDigito();BuscarDuplicados(this.value)" onFocus="BuscarDigito()" onChange="BuscarDigito()" value="<?echo $Identificacion?>"
onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)">
<input type="Text" name="DV" style="width:20px;" maxlength="1" value="<? echo $DV?>" readonly>

<input type="Hidden" name="TempIdentificacion">

<?}
else
{	
	echo $Identificacion;
	if($DV){ echo "-" .$DV;}
?>
<input type="Hidden" name="Identificacion" value="<?echo $Identificacion?>">
<?
}?>
</td>
</tr>
<tr><td>Representante Legal</td><td><input type="Text" name="Representante" style="width:170px;" value="<?echo $Representante?>"></td>
<td>Direccion</td><td><input type="Text" name="Direccion" style="width:200px;" value="<?echo $Direccion?>" onFocus="document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Direccion'"></td>
<td>Telefono</td><td><input type="Text" name="Telefono" style="width:90px;" value="<?echo $Telefono?>"></td></tr>
<tr>
<td>Pais</td><td><input readonly="yes" type="Text" name="Pais" style="width:160px;" value="<?echo $Pais?>"></td>
<td>Departamento</td><td><input type="Text" name="Departamento" onChange="ValDepto.value=0" style="width:160px;" value="<?echo $Departamento?>" onKeyUp="document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Departamentos&Departamento='+this.value;" onFocus="document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Departamentos&Departamento='+this.value"></td>
<td>Municipio</td><td><input type="Text" name="Municipio" onChange="ValMpo.value=0"  style="width:160px;" value="<?echo $Municipio?>" onKeyUp="document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Municipio&Departamento='+Departamento.value+'&Municipio='+this.value" onFocus="document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Municipio&Departamento='+Departamento.value+'&Municipio='+this.value">
<input type="Hidden" name="ValDepto" value="<?echo $ValDepto?>">
<input type="Hidden" name="ValMpo" value="<?echo $ValMpo?>">

</td>
</tr>
<tr><td>Email</td><td><input type="Text" name="Email" style="width:160px;" value="<?echo $Email?>"></td></tr>
 
<tr><td colspan="6" style="color:<?echo $Estilo[6]?>;font-weight:bold" bgcolor="<?echo $Estilo[1]?>"><center>INFORMACION TRIBUTARIA</td></tr>
<tr>
<td>Tipo de Tercero</td>
<td>
<select name="TipoTercero">
<?
	$cons="Select * from Central.TiposTercero Order By Tipo";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($TipoTercero==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
	}
?>
</select>
</td>
<td>Regimen</td>
<td>
<select name="Regimen">
<option>
<?
	$cons="Select * from Central.RegimenTercero Order By Regimen";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($Regimen==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
	}
?>
</select>
</td>
<td>Autoretencion</td>
<td>En la Fuente
<?
	if($ReteFte=="on"){
?>
<input type="Checkbox" name="ReteFte" checked>
<?}else{?>
<input type="Checkbox" name="ReteFte">
IVA
<?}
	if($ReteIVA=="on"){
?>
<input type="Checkbox" name="ReteIVA" checked><?
}else{?>
<input type="Checkbox" name="ReteIVA"><? } ?>

<input type="Hidden" name="Edit" value="<?echo $Edit?>">
<input type="Hidden" name="Cerrar" value="<?echo $Cerrar?>">
<input type="Hidden" name="NUMREG" value="<?echo $NUMREG?>">
</td>
</tr>
<tr>
<td>Actividad Economica</td><td><input type="Text" name="Actividad" onChange="ValActividad.value=0" style="width:160px;" value="<?echo $Actividad ; ?>" onKeyUp="document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Actividad&Actividad='+this.value;" onFocus="document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Actividad&Actividad='+this.value"></td>
<input type="Hidden" name="ValActividad" value="<?echo $ValActividad?>">
</tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<br><input type="Submit" name="Guardar" value="Guardar"><BR>
<iframe id="Busquedas" name="Busquedas" src="Busquedas.php?DatNameSID=<? echo $DatNameSID?>&" frameborder="0"></iframe>
</form>
</body>