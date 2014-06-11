<?	
    if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$ND=getdate();
	
	$cons="select nombre,usuarios.usuario,cargo from central.usuarios,salud.medicos where compania='$Compania[0]' and medicos.usuario=usuarios.usuario";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Usus[$fila[1]]=$fila[0];
		$Cargos[$fila[1]]=$fila[2];
	}
	
	$cons="select (primape || ' ' || segape || ' ' || primnom || ' ' || segnom) as nom,identificacion from central.terceros 
	where compania='$Compania[0]' and tipo='Asegurador'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Aseguradores[$fila[1]]=$fila[0];		
	}
	
	function ValidaFecha($HI,$MI,$Fecha)
	{
		$ND=getdate();
		if($ND[mon]<10){$C1="0";}else{$C1="";}
		if($ND[mday]<10){$C2="0";}else{$C2="";}
		if($ND[minutes]<10){$C3="0";}else{$C3="";}				
		$FechaAct=$ND[year]."-".$C1.$ND[mon]."-".$C2.$ND[mday];			
	   	$fecha_Cita=strtotime($Fecha); 
		$fecha_sis=strtotime($FechaAct);  	
		
		if($fecha_sis > $fecha_Cita)
		{
			return 1;
		}
		else
		{
			if($HI==$ND[hours]){
				if($MI<$ND[minutes]){
					return 1;
				}
				else{
					return 0;
				}
			}
			else{
				if($HI<$ND[hours]){
					return 1;
				}
				else{
					return 0;
				}
			}
		}
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.FechaIni.value>document.FORMA.FechaFin.value){alert("La fecha inicial debe ser menor a la fecha fina!!!");return false;}
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">  
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center"> 
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
		<td align="center" colspan="8">Citas Expiradas</td>
	</tr>
	<tr>    	
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Desde</td>
   	<?	if(!$FechaIni){
			if($ND[mon]<10){$C1="0";}
			$FechaIni="$ND[year]-$C1$ND[mon]-01";
		}
		if(!$FechaFin){
			if($ND[mon]<10){$C1="0";}if($ND[mday]<10){$C2="0";}
			$FechaFin="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";
		}?>
        <td ><input type="text" readonly name="FechaIni" onClick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')" style="width:70px" value="<? echo $FechaIni?>"
        	onChange="document.FORMA.submit()">
       	</td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Hasta</td>
        <td><input type="text" readonly name="FechaFin" style="width:70px" value="<? echo $FechaFin?>"
        	onChange="document.FORMA.submit()" >
      	</td> 
        <td>
        	<input type="submit" value="Ver" name="Ver">
        </td>
	</tr>    
</table>
<br>   
<?	
if($Ver){?>
	<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">     
<?	$cons="select hrsini,minsini,hrsfin,minsfin,fecha,id,entidad,cedula,medico,cup,nombre,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom) as nom 
	from salud.agenda,contratacionsalud.cups,central.terceros
	where fecha>='$FechaIni' and fecha<='$FechaFin' and agenda.compania='$Compania[0]' and cups.compania='$Compania[0]' and cups.codigo=cup
	and estado='Pendiente' and terceros.compania='$Compania[0]' and terceros.identificacion=cedula order by fecha,id,hrsini,minsini,hrsfin,minsfin";
	//echo $cons;
	$res=ExQuery($cons);	?>
    	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">    	
	    	<td>Fecha</td><td>Hora</td><td>Paciente</td><td>Identificaicon</td><td>Entidad</td><td>Medico</td><td>Codigo</td><td>Procedimiento</td>
       </tr>
<?	while($fila=ExFetch($res))
	{		
		if($fila[0]<10){$CH1="0";}else{$CH1="";}
		if($fila[1]<10){$CH2="0";}else{$CH2="";}
		if(ValidaFecha($fila[0],$fila[1],$fila[4]))
		{?>
			<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand" title="Cancelar"
          	onClick="location.href='/Admision/Agenda/CancelCitaAgend.php?DatNameSID=<? echo $DatNameSID?>&Profecional=<? echo $fila[8]?>&Id=<? echo $fila[5]?>&Fecha=<? echo $fila[4]?>&HrIni=<? echo $fila[0]?>&MinIni=<? echo $fila[1]?>&Exp=1&Especialidad=<? echo $Cargos[$fila[8]]?>&FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>'">		
     	<?	echo "<td>$fila[4]</td><td>$CH1$fila[0]:$CH2$fila[1]</td><td>$fila[11]</td><td>$fila[7]</td><td>".$Aseguradores[$fila[6]]."</td>
		<td>".$Usus[$fila[8]]."</td><td>$fila[9]</td><td>$fila[10]</td>";?>
            </tr>
	<?	}
	}?>    
    </table><?
}?>

<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
</form>
</body>
</html>
