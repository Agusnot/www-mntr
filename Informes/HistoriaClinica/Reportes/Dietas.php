<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center">
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
	<td colspan="15">DIETAS</td>
</tr>
<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold"><td>Dieta</td><td>Proceso</td><td>Servicio</td></tr>
<tr>
	<td>
    <?	$cons="select dieta from salud.dietas where compania='$Compania[0]' order by dieta";
		$res=ExQuery($cons);?>
        <select name="Dieta" onchange="document.FORMA.submit();">
        	<option></option>
       	<?	while($fila=Exfetch($res))
			{
				if($fila[0]==$Dieta){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}
			}?>
        </select>
    </td>
     <td>
    <?	
        $cons="select ambito from salud.ambitos where compania='$Compania[0]' and ambito!='Sin Ambito' order by ambito";
        $res=ExQuery($cons);?>
        <select name="Ambito" onChange="document.FORMA.submit()">    	
            <option></option>
        <? 	while($fila=ExFetch($res))
            {
                if($fila[0]==$Ambito){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
                else{echo "<option value='$fila[0]'>$fila[0]</option>";}
            }
        ?>
        </select>
    </td>
    <td>
    <? 	$cons="select pabellon from salud.pabellones where compania='$Compania[0]' and ambito='$Ambito'  order by pabellones";
        $res=ExQuery($cons);?>
        <select name="Pabellon" onChange="document.FORMA.submit()">    	
            <option></option>
        <? 	while($fila=ExFetch($res))
            {
                if($fila[0]==$Pabellon){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
                else{echo "<option value='$fila[0]'>$fila[0]</option>";}
            }
        ?>
        </select>
    </td>
</tr>
<tr align="center"><td colspan="3"><input type="submit" value="Ver" name="Ver" /></td></tr>
</table>
<BR />
<?
if($Ver)
{
	if($Dieta){$Diet=" and dieta='$Dieta' ";}
	if($Ambito){$Amb=" and tiposervicio='$Ambito' "; $Amb2=" and ambito='$Ambito'";}
	if($Pabellon){$Pab=" and pabellon='$Pabellon'" ;
	   $Pab2=" and plantilladietas.numservicio in 
	   	(select numservicio from salud.pacientesxpabellones where compania='$Compania[0]' and estado='AC' and fechae is null $Amb2 $Pab)";
	   //echo "etraaaa";
	}?>
	<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center">
		<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
        	<td></td><td>Nombre</td><td>Identificacion</td><td>Proceso</td><td>Servicio</td><td>Dieta</td>
		</tr>
  	<?	$cons="select pabellon,numservicio from salud.pacientesxpabellones where compania='$Compania[0]' and estado='AC' and fechae is null $Amb2 $Pab";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$Pabs[$fila[1]]=$fila[0];	
		}
		
		$cons="select primape,segape,primnom,segnom,plantilladietas.cedula,tiposervicio,dieta,plantilladietas.numservicio
		from salud.plantilladietas,salud.servicios,central.terceros
		where plantilladietas.compania='$Compania[0]' and servicios.compania='$Compania[0]' and terceros.compania='$Compania[0]'
		and plantilladietas.estado='AC' and servicios.estado='AC' and servicios.numservicio=plantilladietas.numservicio and plantilladietas.cedula=identificacion
		$Diet $Amb $Pab2 order by primape,segape,primnom,segnom";
		//echo $cons;
		$res=ExQuery($cons);
		$cont=0;
		while($fila=ExFetch($res)){ 
			$cont++;?>
			<tr  onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
            	<td><? echo $cont?></td>
            	<td><? echo "$fila[0] $fila[1] $fila[2] $fila[3]";?></td><td><? echo $fila[4]?></td><td><? echo $fila[5]?></td>
                <td><? echo $Pabs[$fila[7]]?>&nbsp;</td><td><? echo $fila[6]?></td>
            </tr>
	<?	}?>
	</table><?	
}?>
</form>
</body>
</html>