<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar==2){
		if($Clase=='CUPS'){
			$cons="delete from contratacionsalud.planeservicios where autoid='$Autoid' and Compania='$Compania[0]'";		
			$res=ExQuery($cons);echo ExError();
			$cons="delete from contratacionsalud.cupsxplanservic where autoid='$Autoid' and Compania='$Compania[0]'";
			$res=ExQuery($cons);echo ExError();		
		}
		else if($Clase=='Medicamentos'){
			$cons="delete from contratacionsalud.planeservicios where autoid='$Autoid' and Compania='$Compania[0]'";		
			$res=ExQuery($cons);echo ExError();			
			$cons="delete from contratacionsalud.medsxplanservic where autoid='$Autoid' and Compania='$Compania[0]'";
			$res=ExQuery($cons);echo ExError();					
		}
		
	}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table cellpadding="4"  border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>">
	<tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Clase</td>
    	<td><select name="Clase" onChange="document.FORMA.submit()">
<option></option>                	
<?
		$cons9="Select Nombre from ContratacionSalud.ClasesPlanServicios where Compania='$Compania[0]' order by Nombre";		
		$res9=ExQuery($cons9);
		while($fila9=ExFetch($res9))
		{
			if($Clase==$fila9[0]){echo "<option selected value='$fila9[0]'>$fila9[0]</option>";}
			else{echo "<option value='$fila9[0]'>$fila9[0]</option>";}
		}
?>
        </select></td>
    <? if($Clase){ ?><td rowspan="2"><input type="button" name="Nuevo" value="Nuevo" onClick="location.href='NewPlanxServic.php?DatNameSID=<? echo $DatNameSID?>&Clase=<? echo $Clase?>'"/></td><? } ?>
	</tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Plan:</td>
        <td><select name="Plan" onChange="frames.BusquedaCUPSxPlanServ.location.href='BusquedaCUPSxPlanServ.php?DatNameSID=<? echo $DatNameSID?>&Clase='+Clase.value+'&Autoid='+this.value"><option></option>
        <?
        	$cons = "Select NombrePlan,AutoId from ContratacionSalud.PlaneServicios where Clase='$Clase' and Compania='$Compania[0]' order by NombrePlan";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				if($Autoid==$fila[1]){echo "<option selected value='$fila[1]'>$fila[0]</option>";
					if(!$Eliminar){						
					?><script language="javascript">					
                    	frames.BusquedaCUPSxPlanServ.location.href="BusquedaCUPSxPlanServ.php?DatNameSID=<? echo $DatNameSID?>&Clase=<? echo $Clase?>&Autoid=<? echo $Autoid?>&Eliminar=<? echo $Eliminar?>";					
                    </script><?
					}
				}
				else {echo "<option value='$fila[1]'>$fila[0]</option>";}
			}
		?>
    	</select></td>
    </tr>
</table>
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
<iframe frameborder="0" id="BusquedaCUPSxPlanServ" src="BusquedaCUPSxPlanServ.php?DatNameSID=<? echo $DatNameSID?>&Clase=<? echo $Clase?>" width="100%" height="85%"></iframe>
<?
	if($Autoid)
	{
		?><script language="javascript">
        	frames.BusquedaCUPSxPlanServ.location.href="BusquedaCUPSxPlanServ.php?DatNameSID=<? echo $DatNameSID?>&Autoid=<? echo $Autoid?>&Clase=<? echo $Clase?>&Eliminar=<? echo $Eliminar?>&Clase=<? echo $Clase?>";
        </script><?
	}
?>
</body>
</html>