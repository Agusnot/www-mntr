<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($TipFacElim)
	{
		$cons="delete  from facturacion.tipofactura where compania='$Compania[0]' and tipofact='$TipFacElim'";	
		$res=ExQuery($cons);
	}
	$cons="select tipofact,ambitofac from facturacion.tipofactura where compania='$Compania[0]' order by tipofact";
	$res=ExQuery($cons);
	
?>	
<html>
<head>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">  
<table BORDER=1  style='font : normal normal small-caps 11px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" cellspacing="2">  
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">    	
    	<td colspan="11">TIPOS FACTURAS</td>
	</tr>
    <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td>Tipo Factura</td><td>Proceso</td><td colspan="2"></td>
    </tr>
<?	while($fila=ExFetch($res))
	{?>
		<tr>
        	<td><? echo $fila[0]?></td><td><? echo $fila[1]?></td>
            <td><img src="/Imgs/b_edit.png" style="cursor:hand" title="Editar"
            	onclick="location.href='NewTipoFac.php?DatNameSID=<? echo $DatNameSID?>&Edit=1&TipFac=<? echo $fila[0]?>&Ambito=<? echo $fila[1]?>'"/>
          	</td>
            <td>
            	<img src="/Imgs/b_drop.png" style="cursor:hand" title="Elimiar"
                onclick="if(confirm('Esta seguro de elimiar este registro?')){location.href='TiposFacs.php?DatNameSID=<? echo $DatNameSID?>&Edit=1&TipFacElim=<? echo $fila[0]?>';}"/>
            </td>
        </tr>		
<?	}?>    
    <tr align="center">
    	<td colspan="11"><input type="button" value="Nuevo" onclick="location.href='NewTipoFac.php?DatNameSID=<? echo $DatNameSID?>'"/></td>
    </tr>
</table>
<input type="hidden" name="TipFacElim" value="<? echo $TipFacElim?>" />
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
</form>
</body>