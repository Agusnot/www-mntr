<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar==1)
	{
		$cons="Delete from nomina.centrabajo where Compania='$Compania[0]' and codigo='$Codigo' and detalle='$Detalle'";					
		$res=ExQuery($cons);		
		$Eliminar=0;	

	}	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
		    <tr  bgcolor="#666699" style="color:white" align="center" style="font-weight:bold">
    		<td colspan="5">CENTRO DE TRABAJO</td>
    	</tr>
<?
		$cons = "Select compania,codigo,detalle,clase,porcentaje from nomina.centrabajo where compania='$Compania[0]' order by codigo";
//		echo $cons;
		$res = ExQuery($cons);
		if(ExNumRows($res)>0)
		{
?>		
        <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
	       	<td>Codigo</td><td>Detalle</td><td>Clase de Riesgo</td><td>Porcentaje de Riesgo</td>
        </tr>
        <?  while($fila = ExFetch($res))
			{
    		?>	<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"> <?
				echo "<td>$fila[1]&nbsp;</td><td>$fila[2]&nbsp;</td><td>$fila[3]&nbsp;</td><td align='center'>$fila[4]&nbsp;</td>";
				
					?> <td width="16px"><a href="#" onClick="if(confirm('Desea Eliminar el Riesgo ?')){location.href='CenTrabajos.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Codigo=<? echo $fila[1]?>&Detalle=<? echo $fila[2]?>'}"><img src="/Imgs/b_drop.png" border="0" title="Eliminar"/></a></td>					
				<? 
				
				echo "</tr>";
			}
		?> </table>	<? 	
		} 
	 ?>
    <input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
<center><input type="button" name="Nuevo" value="NUEVO" onClick="location.href='NewCenTrabajo.php?DatNameSID=<? echo $DatNameSID?>';" /></center>
</body>
</html>