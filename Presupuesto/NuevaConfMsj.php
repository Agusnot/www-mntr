<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
include("Funciones.php");	
	if($Guardar)
	{		
		if($Editar==0)
		{
			$cons1="Select Id from Presupuesto.MsjComprobantes where Compania='$Compania[0]'";
			$res1=ExQuery($cons1);
			$Id=ExNumRows($res1)+1;
			$cons="Insert into Presupuesto.msjcomprobantes(Id,Mensaje,Anio,Compania)
			values ($Id,'$Mensaje',$Anio,'$Compania[0]')";
		}
		if($Editar==1)
		{
			$cons="Update Presupuesto.msjcomprobantes set Mensaje='$Mensaje' where Id=$Id and Anio=$Anio and Compania='$Compania[0]'";		
		}
		$res=ExQuery($cons);
		echo ExError($res);
		?>
		<script language="javascript">
			location.href='ConfMensajeComprobantes.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>';
		</script>
		<?
	}	
	if($Editar)
	{
		$cons="Select * from Presupuesto.msjcomprobantes where Id='$Id' and Anio=$Anio and Compania='$Compania[0]'";
		$res=ExQuery($cons);
		$fila=ExFetchArray($res);
		$Id=$fila['id'];
		$Mensaje=$fila['mensaje'];$Anio=$fila['anio'];
		$Deshab="disabled";
		
	}
?>
	<script language="javascript" src="/Funciones.js"></script>
	<script language="javascript">
	function Validar()
	{
		if (document.FORMA.Mensaje.value == ""){alert("Ingrese el mensaje del Comprobante");return false;}     	
	}
	</script>


<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table cellpadding="4"  border="1" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>">
<tr><td bgcolor="#e5e5e5">A&ntilde;o</td>
  <td><select name="Anio" <? echo $Deshab?> onChange="FORMA.submit()" />
    	<?
    		$cons = "Select Anio from Central.Anios where Compania = '$Compania[0]' and Anio not in(Select Anio from Presupuesto.MsjComprobantes where Compania='$Compania[0]')";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				if($Anio==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else {echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
		?>
  </select></td>
<tr>
<tr><td bgcolor="#e5e5e5">Mensaje</td><td><input style="width:300px;" type="text" name="Mensaje" value="<?echo $Mensaje?>"/></td></tr>
</table>
<input type="submit" value="Guardar" name="Guardar"/>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="button" name="Cancelar" value="Cancelar" onClick="location.href='ConfMensajeComprobantes.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>';"/>
</form>