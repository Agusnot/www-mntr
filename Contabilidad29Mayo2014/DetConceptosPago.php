<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar)
	{
		$cons = "Delete from Contabilidad.ConceptosPagoxCC 
		Where Compania='$Compania[0]' and Concepto='$Concepto' and Comprobante='$Comprobante' and Anio=$Anio
		and CC='$CC' and porcDist='$porcDist' and CuentaDebe='$CuentaDebe'";
		$res = ExQuery($cons);
		
		$cons = "Update Contabilidad.ConceptosPagoxCC set Completa = 0 
		Where Compania='$Compania[0]' and Concepto='$Concepto' and Comprobante='$Comprobante' and Anio=$Anio";
		$res = ExQuery($cons);
	}
	$cons = "Select PorcDist from Contabilidad.ConceptosPagoxCC Where Compania='$Compania[0]' and Concepto='$Concepto'
	and Anio=$Anio";
	$res = ExQuery($cons);
	if(ExNumRows($res)>0)
	{
		$Totalporc = 0;
		while($fila = ExFetch($res)){$Totalporc += $fila[0];}
		$valorDefecto = 100 - $Totalporc;
	}
	else{$valorDefecto = 100;}
	if($Guardar)
	{
		$cons="Insert into Contabilidad.ConceptosPagoxCC(Compania,Concepto,CC,PorcDist,CuentaDebe,Comprobante,Anio) 
		values ('$Compania[0]','$Concepto','$CC','$porcDist','$Cuenta','$Comprobante',$Anio)";
		$res = ExQuery($cons);
		if(1==1)
		{ 
			$cons = "Update Contabilidad.ConceptosPagoxCC set Completa = 1 
			Where Compania='$Compania[0]' and Concepto='$Concepto' and Comprobante='$Comprobante' and Anio=$Anio";
			$res = ExQuery($cons);
		}
		$CC = "";
		$porcDist = "";
		$Cuenta = ""; 
		?><script language="javascript">parent.Ocultar();</script><?
	}
	$cons = "Select PorcDist from Contabilidad.ConceptosPagoxCC Where Compania='$Compania[0]' and Concepto='$Concepto'
	and Anio=$Anio";
	$res = ExQuery($cons);
	if(ExNumRows($res)>0)
	{
		$Totalporc = 0;
		while($fila = ExFetch($res)){$Totalporc += $fila[0];}
		$valorDefecto = 100 - $Totalporc;
	}
	else{$valorDefecto = 100;}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
		parent.document.FORMA.submit();
	}
	
	function Validar()
	{
		if(document.FORMA.CC.value==""){alert("Ingrese el campo Centro de Costo");return false;}
		if(document.FORMA.porcDist.value=="" || (document.FORMA.porcDist.value)*1<=0){alert("Valor del campo es Invalido o Nulo");return false;}
		if(document.FORMA.Cuenta.value==""){alert("Ingrese el campo Cuenta");return false;}
	}

</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table border="0" width="100%">
	<tr align="right"><td><button type="button" name="Cerrar" onClick="parent.Ocultar();CerrarThis()" title="Cerrar"><img src="/Imgs/b_drop.png" /></button></td></tr>
</table>
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="<? echo $Estilo[1]?>" width="100%" >
<tr style="color:#FFFFFF; font-weight:bold" align="center" bgcolor="<? echo $Estilo[1]?>">
<td colspan="4" align="center">Cta Debito <? echo $Concepto?></td></tr>
<tr style="color:#FFFFFF; font-weight:bold" align="center" bgcolor="<? echo $Estilo[1]?>"><td>CC</td><td>%Dist</td><td colspan="2">Cuenta</td></tr>
<?
	$cons = "Select CentroCostos,porcDist,CuentaDebe,CC from Contabilidad.ConceptosPagoxCC, Central.CentrosCosto 
	Where ConceptosPagoxCC.CC = CentrosCosto.Codigo and ConceptosPagoxCC.Compania='$Compania[0]' and CentrosCosto.Compania='$Compania[0]'
	and Concepto='$Concepto'
	and ConceptosPagoxCC.Anio=$Anio and CentrosCosto.Anio=$Anio";
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		echo "<tr><td align = 'left'>$fila[0]</td><td align = 'right'>".number_format($fila[1],4)."</td><td align = 'right'>$fila[2]</td>";
		?><td align="center"><img border="0" src="/Imgs/b_drop.png" title="Eliminar" style="cursor:hand"
        onclick="location.href='DetConceptosPago.php?DatNameSID=<? echo $DatNameSID?>&CC=<? echo $fila[3]?>&porcDist=<? echo $fila[1]?>&CuentaDebe=<? echo $fila[2]?>&Eliminar=1&Concepto=<? echo $Concepto?>&Comprobante=<? echo $Comprobante?>&Anio=<? echo $Anio?>'" /></td><?
	}
?>
<tr>
	<td width="40%">
    <input onFocus="parent.document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=FrameOpener&Centro='+this.value+'&Tipo=CCG&Anio=<? echo $Anio?>';
    parent.Mostrar();" 
    onkeyup="parent.document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=FrameOpener&Centro='+this.value+'&Tipo=CCG&Anio=<? echo $Anio?>';xNumero(this)"
    type="text" name="CC" value="<? echo $CC?>" style="width:100%"/>
    </td>
    <td width="10%"><input type="text" name="porcDist" onFocus="parent.Ocultar()" value="<? echo $valorDefecto?>" 
    onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" 
    onBlur="campoNumero(this);if(parseInt(this.value)><? echo $valorDefecto?>){this.value='<? echo $valorDefecto?>'};" 
    style="width:100%; text-align:right"></td>
    <td width="40%"><input type="text" name="Cuenta" value="<? echo $Cuenta?>" style="text-align:right; width:100%"
        onFocus="parent.Mostrar();
        parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=FrameOpener&NoMovimiento=1&ObjCuenta=Cuenta&Tipo=PlanCuentas&Cuenta='+this.value+'&Anio=<? echo $Anio?>'" 
		onkeyup="xNumero(this);
        parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=FrameOpener&NoMovimiento=1&ObjCuenta=Cuenta&Tipo=PlanCuentas&Cuenta='+this.value+'&Anio=<? echo $Anio?>';"
        onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/></td>
    <td width="3%" align="center"><button type="submit" name="Guardar" title="Guardar"><img src="/Imgs/b_save.png" /></button></td>
</tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php?DatNameSID=<? echo $DatNameSID?>" frameborder="0" height="400"></iframe>
</body>