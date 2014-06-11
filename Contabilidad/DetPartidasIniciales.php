<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar)
	{
		$AutoId++;
		$cons="Insert into Contabilidad.PartidaInicialConciliatoria(Compania,Usuario,Comprobante,Cuenta,Fecha,Numero,NoCheque,Identificacion,Debe,Haber,AutoId)
		values('$Compania[0]','$usuario[0]','$Comprobante','$Banco','$Fecha','$Numero','$NoCheque','$Tercero','$Debe','$Haber',$AutoId)";
		$res=ExQuery($cons);
	}
	if($Elim)
	{
		$cons="Delete from Contabilidad.PartidaInicialConciliatoria where Numero='$Numero' and Fecha='$Fecha' and NoCheque='$Cheque' and Identificacion='$Tercero' 
		and Compania='$Compania[0]'";
		$res=ExQuery($cons);
		echo ExError($res);
	}
?>
<script language='javascript' src="/calendario/popcalendar.js"></script> 
<script language="javascript" src="/Funciones.js"></script>
<script language="JavaScript">
	function Validar()
	{
		if(document.FORMA.Banco.value==""){alert("Seleccione un banco de la lista");return false;}
		if(document.FORMA.Comprobante.value==""){alert("Seleccione un comprobante de la lista");return false;}

		if(document.FORMA.Debe.value=="0" && document.FORMA.Haber.value=="0"){alert("Ingrese un valor");return false;}

		if(document.FORMA.NoCheque.value==""){alert("Ingrese el numero del cheque");return false;}
		if(document.FORMA.Fecha.value.length!=10){alert("Fecha Invalida");return false;}
		if(document.FORMA.Fecha.value==""){alert("Ingrese la fecha");return false;}
		if(document.FORMA.Numero.value==""){alert("Ingrese el numero del Comprobante");return false;}
		if(document.FORMA.Tercero.value==""){alert("Ingrese el tercero");return false;}
	}

	function SelTercero()
	{
		frames.FrameOpener.location.href='/Contabilidad/BusquedaxOtros.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Tercero&Campo=Tercero';
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='5px';
		document.getElementById('FrameOpener').style.left='1px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='690';
		document.getElementById('FrameOpener').style.height='390';
	
	}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta></head>
<body background="/Imgs/Fondo.jpg">
<table width="100%" border="1" rules="groups" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr style="font-weight:bold" bgcolor="#e5e5e5" align="center"><td>Numero</td><td>Fecha</td><td>Cheque</td><td>Tercero</td><td>Debe</td><td>Haber</td><td colspan="2"></td></tr>
<?
	$cons="Select Numero,Fecha,NoCheque,Identificacion,Debe,Haber,FechaConciliado from Contabilidad.PartidaInicialConciliatoria where Comprobante='$Comprobante' and Cuenta='$Banco' Order By Fecha";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo "<tr><td>$fila[0]</td><td>$fila[1]</td><td align='right'>$fila[2]</td><td align='center'>$fila[3]</td><td align='right'>".number_format($fila[4],2)."</td><td align='right'>".number_format($fila[5],2)."</td>";
		if($fila[6]==""){?>
		<td align='center'><img style="cursor:hand" onClick="location.href='DetPartidasIniciales.php?DatNameSID=<? echo $DatNameSID?>&Elim=1&Numero=<? echo $fila[0]?>&Fecha=<? echo $fila[1]?>&Cheque=<? echo $fila[2]?>&Tercero=<? echo $fila[3]?>&Comprobante=<? echo $Comprobante?>&Banco=<? echo $Banco?>'" src="/Imgs/b_drop.png"></td><?}
		else{echo "<td><em><center><font color='#0000ff'>Conciliado</em></td>";}
		echo "</tr>";
	}
	echo "<tr>
	<form name='FORMA' onsubmit='return Validar()'>
	<td><input type='Text' name='Numero' style='width:60px;' onKeyUp='xNumero(this);' onKeyDown='xNumero(this)' onBlur='campoNumero(this)'></td>";?>
	<td><input type='Text' name='Fecha' style='width:80px;' onKeyPress="return false;" onClick="popUpCalendar(this, FORMA.Fecha, 'yyyy/mm/dd');NoCheque.focus()"></td>
<?	echo "<td><input type='Text' name='NoCheque' style='width:60px;' onKeyUp='xNumero(this);' onKeyDown='xNumero(this)' onBlur='campoNumero(this)'></td>";?>
	<td><input type='Text' name="Tercero" style='width:100px;' onKeyPress="return false;" onClick="SelTercero()"></td>
<?	echo "<td><input type='Text' name='Debe' style='width:90px;' value=0 onKeyUp='xNumero(this);' onKeyDown='xNumero(this)' onBlur='campoNumero(this)'></td>
	<td><input type='Text' name='Haber' style='width:90px;' value=0 onKeyUp='xNumero(this);' onKeyDown='xNumero(this)' onBlur='campoNumero(this)'></td>
	<input type='Hidden' value='$Comprobante' name='Comprobante'>
	<input type='Hidden' value='$Banco' name='Banco'>
	<input type='Hidden' value='$AutoId' name='AutoId'>
	<input type='hidden' name='DatNameSID' value='$DatNameSID'>
	<td colspan=2><input type='Submit' value='Guardar' name='Guardar'></td>
	</form>
	</tr>";
?>
</table>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>
</body>