<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>	
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table cellpadding="4"  border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>" >
	<tr>
    	<td bgcolor="#e5e5e5">Plan:</td>
        <td><select name="Plan" onChange="FORMA.submit()">
        		<option></option>
			<? 	$cons = "Select NombrePlan,AutoId from ContratacionSalud.PlanesTarifas where Compania='$Compania[0]' order by NombrePlan";
                $res = ExQuery($cons);
                while($fila = ExFetch($res))
                {
                    if($Plan==$fila[1]){echo "<option selected value='$fila[1]'>$fila[0]</option>";}
                    else {echo "<option value='$fila[1]'>$fila[0]</option>";}
                }            ?>
    	</select></td>
        <td >
        <input type="button" name="Nuevo" value="Nuevo" onClick="location.href='NuevoPlanTarifario.php?DatNameSID=<? echo $DatNameSID?>'" />
        </td>
    </tr>
</table>
</form>
<iframe frameborder="0" id="CupsxPlanesTarif" src="CupsxPlanesTarif.php?DatNameSID=<? echo $DatNameSID?>" width="100%" height="85%"></iframe>
<?
	if($Plan)
	{
		?><script language="javascript">
        	frames.CupsxPlanesTarif.location.href="CupsxPlanesTarif.php?DatNameSID=<? echo $DatNameSID?>&Plan=<? echo $Plan?>";
        </script><?
	}
?>
</body>