<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include ("Funciones.php");
	if(!$Anio){$ND=getdate();$Anio = $ND[year];}
	if($Eliminar)
	{
		$cons = "Delete from Presupuesto.msjcomprobantes where Compania = '$Compania[0]' and Anio = '$Anio' and Id = '$Id'";
		$res = ExQuery($cons);
	}
	if($Nuevo){?><script language="javascript">location.href="NuevaConfMsj.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>";</script><? }
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
  <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">

    <table border="0">
		<tr>
		  <td>A&ntilde;o</td>
		  <td><select name="Anio" onChange="FORMA.submit()" />
    	<?
    		$cons = "Select Anio from Central.Anios where Compania = '$Compania[0]' Order By Anio";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				if($Anio==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else {echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
		?>
		</select></td>
		</tr>
	</table>
	<? 
	if($Anio)
	{ ?>
	<table cellpadding="4"  border="1" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>">
		<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
			<td>Mensaje</td><td colspan="2">&nbsp;</td>
		</tr>
		<?	
		$cons = "Select Mensaje,Id from Presupuesto.msjcomprobantes where Compania='$Compania[0]'and Anio='$Anio' Order By Id";
		$res = ExQuery($cons);
		$NumFilas = ExNumRows($res);
		if($NumFilas){$Deshab="disabled";}		
		while ($fila = ExFetch($res))
		{			
			?><tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor='#FFFFFF'"><?
			echo "<td>$fila[0]</td>";
			?><td width="16px">
               <a href="NuevaConfMsj.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&Anio=<? echo $Anio?>&Id=<? echo $fila[1]?>">
               <img border=0 src="/Imgs/b_edit.png"></a></td>			
			   <td width="16px"><a href="#" onClick="if(confirm('Desea eliminar el registro?'))
                {location.href='ConfMensajeComprobantes.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Anio=<? echo $Anio?>&Id=<? echo $fila[1]?>';}">
				<img border="0" src="/Imgs/b_drop.png"/></a></td>
              </tr>	<?		
		}
		?>
	</table>
	<input type="submit" value="Nuevo" name = "Nuevo" <? echo $Deshab?>/>
	<? } ?>
</form>
</body>