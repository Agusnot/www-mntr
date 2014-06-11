<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<?
	
	if($Identificacion || $Nombre)
	{
		$cons = "Select Identificacion,Primape,Direccion,Telefono,Replegal from Central.Terceros where Tipo='Asegurador' and Compania = '$Compania[0]' and Identificacion ilike '$Identificacion%' and Primape ilike '$Nombre%' order by primape";				
		$res = ExQuery($cons);
		if(ExNumRows($res)>0)
		{
?>		<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4">
		    <tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
    		<td>Nit</td><td>Nombre</td><td></td>
    	</tr>
        <?  while($fila = ExFetch($res))
			{
    		?>	<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"> <?
				echo "<td>$fila[0]</td><td>$fila[1]</td>";
				
					?><td width="16px"><a href='NewAseguradoras.php?DatNameSID=<? echo $DatNameSID?>&Idant=<? echo $Identificacion?>&Nombre=<? echo $Nombre?>&Editar=1&Identificacion=<? echo $fila[0]?>' target="_parent"><img title="Editar" border=0 src='/Imgs/b_edit.png'></a></td>					
				<? 
				
				echo "</tr>";
			}
		?> </table>	<? 	
		} 
	} ?>
    <input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
</body>
</html>
