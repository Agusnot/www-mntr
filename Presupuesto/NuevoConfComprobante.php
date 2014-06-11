<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	
	if($Guardar)
	{		
		if($Editar==0)
		{
			$cons="Insert into Presupuesto.Comprobantes(Comprobante,TipoComprobant,NumeroInicial,Archivo,Compania)
			values ('".trim($Comprobante)."','$TipoComprobante','$NoInicial','$Archivo','$Compania[0]')";
			//echo $cons;
			//exit;
			
		}
		if($Editar==1)
		{
			$cons="Update Presupuesto.Comprobantes set TipoComprobant='$TipoComprobante',NumeroInicial='$NoInicial',
			Archivo='$Archivo' where Comprobante='$Comprobante' and Compania='$Compania[0]'";		
		}
		$res=ExQuery($cons);
		echo ExError($res);
		?>
		<script language="javascript">
			location.href='ConfComprobantes.php?DatNameSID=<? echo $DatNameSID?>';
		</script>
		<?
	}	
	if($Editar)
	{
		$cons="Select * from Presupuesto.Comprobantes where Comprobante='$Comprobante' and Compania='$Compania[0]'";
		$res=ExQuery($cons);
		$fila=ExFetchArray($res);
		$Comprobante=$fila['comprobante'];
		$TipoComprobante=$fila['tipocomprobant'];$NoInicial=$fila['numeroinicial'];$Archivo=$fila['archivo'];
		
	}

?>
	<script language="javascript" src="/Funciones.js"></script>
	<script language="javascript">
	function Validar()
	{
		if (document.FORMA.Comprobante.value == ""){alert("Ingrese un nombre de comprobante");return false;}
		else{if (document.FORMA.TipoComprobante.value == ""){alert("Escoja un Tipo de comprobante");return false;}
		     else{if (document.FORMA.NoInicial.value == ""){alert("Ingrese un Numero Inicial");return false;}
				  else {if (document.FORMA.Archivo.value == ""){alert("Ingrese el Archivo");return false;}
      					else {return true}}}}
	}
	</script>


<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table cellpadding="4"  border="1" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>">

<tr><td bgcolor="#e5e5e5">Nombre Comprobante</td><td><input style="width:300px;" type="text" name="Comprobante" value="<?echo $Comprobante?>"/></td>
<tr><td bgcolor="#e5e5e5">Tipo Comprobante</td>
<td>
<select name="TipoComprobante">
<option></option>
<?
	$cons="Select Tipo from Presupuesto.TiposComprobante";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($TipoComprobante==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
	}
	if(!$NoInicial){$NoInicial="000001";}
?>
</select>
</td>

<tr><td bgcolor="#e5e5e5">Numero Inicial</td>
<td><input type="text" style="width:100px;" maxlength="6" name="NoInicial" value="<?echo $NoInicial?>" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)"/></td>


<tr><td bgcolor="#e5e5e5">Archivo</td>
<td>
<select name="Archivo">
<?
	$RutaRoot=$_SERVER['DOCUMENT_ROOT'];
    $midir=opendir("$RutaRoot/Informes/Presupuesto/Formatos/");
	while($files=readdir($midir))
    {
		$ext=substr($files,-3);
		if (!is_dir($files) && ($ext=="php"))
		$files="Formatos/".$files;
		if($files!="." && $files!=".."){
		if($files==$Archivo){echo "<option selected value='$files'>$files</option>";}
		else{echo "<option value='$files'>$files</option>";}}
      }
?>
</select>
</td>
</tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="submit" value="Guardar" name="Guardar"/>
<input type="button" name="Cancelar" value="Cancelar" onClick="location.href='ConfComprobantes.php?DatNameSID=<? echo $DatNameSID?>';"/>
</form>