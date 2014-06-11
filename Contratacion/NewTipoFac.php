<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();	
	if($Guardar)
	{
		if(!$Edit)
		{
			$cons=" insert into facturacion.tipofactura (tipofact,ambitofac,compania) values ('$TipFac','$Ambito','$Compania[0]')";				
		}
		else
		{
			$cons=" update facturacion.tipofactura set ambitofac='$Ambito' where tipofact='$TipFac' and compania='$Comapania[0]'";
		}
		$res=ExQuery($cons);
		?>
        <script language="javascript">
			location.href='TiposFacs.php?DatNameSID=<? echo $DatNameSID?>'
		</script>
        <?
	}
?>	
<html>
<head>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.TipFac.value==""){alert("Debe digitar el tipo de factura!!!");return false;}
		if(document.FORMA.Ambito.value==""){alert("Debe seleccionar el ambito de la factura!!!");return false;}
	}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">  
<table BORDER=1  style='font : normal normal small-caps 11px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" cellspacing="2">  
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">    	
    	<td colspan="11">TIPOS FACTURAS</td>
	</tr>
    <tr>
    	<td  bgcolor="#e5e5e5" style="font-weight:bold">Tipo Factura</td>
        <td>
        	<input type="text" name="TipFac" onkeydown="xLetra(this)" onkeypress="xLetra(this)" onkeyup="xLetra(this)" value="<? echo $TipFac?>"
            style="width:250" <? if($Edit){?> disabled="disabled"<? } ?>/>
        </td>
    </tr>
    <tr>
    	<td  bgcolor="#e5e5e5" style="font-weight:bold">Proceso</td>
    <?	$cons="select ambito from salud.ambitos where compania='$Compania[0]' order by ambito";
		$res=ExQuery($cons);?>
        <td>
             <select name="Ambito">
                <option></option>
        <?		while($fila=ExFetch($res))
                {
                    if($fila[0]==$Ambito){echo "<option value='$fila[0]' selected>$fila[0]</option>";}	
                    else{echo "<option value='$fila[0]'>$fila[0]</option>";}	
                }?>
            </select>
      	</td>
    </tr>
    <tr align="center">
    	<td colspan="11">
        	<input type="submit" name="Guardar" value="Guardar" />
        	<input type="button" value="Cancelar" onclick="location.href='TiposFacs.php?DatNameSID=<? echo $DatNameSID?>'"/></td>
    </tr>
</table>
<input type="hidden" name="Edit" value="<? echo $Edit?>" />
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
</form>
</body>