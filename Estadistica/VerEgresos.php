<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
?>	

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>

<body background="/Imgs/Fondo.jpg"><?
if($Anio!=''&&$MesFin!=''&&$DiaIni!=''&&$DiaFin!=''&&$Ambito!='')
{
	$cons="select primape,segape,primnom,segnom,identificacion,eps,servicios.fechaing,servicios.fechaegr,servicios.numservicio,servicios.estado,sexo,fecnac	
	from central.terceros,salud.servicios
	where terceros.compania='$Compania[0]' and servicios.compania='$Compania[0]' and identificacion=servicios.cedula and servicios.fechaegr>='$Anio-$MesIni-$DiaIni' and 	
	servicios.fechaegr<='$Anio-$MesFin-$DiaFin' and servicios.tiposervicio='$Ambito'
	group by primape,segape,primnom,segnom,identificacion,eps,servicios.fechaing,servicios.fechaegr,servicios.numservicio,servicios.estado,sexo,fecnac
	order by primape,segape,primnom,segnom";	
	$res=ExQuery($cons);?>
	<table bordercolor="#e5e5e5" border="1"  cellpadding="1" cellspacing="1"style='font : normal normal small-caps 11px Tahoma;' >	
    <?	if(ExNumRows($res)>0)
		{	$cont=1;?>
    		<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
            	<td>&nbsp;</td><td>Nombre</td><td>Identificacion</td><td>Fecha Ingreso</td><td>Fecha Egreso</td><td>No Servicio</td>
                <td>Dx Ingreso</td><td>Entidad</td>	<td>Sexo</td><td>Edad</td>				
         	</tr> 
	<?		while($fila=ExFetch($res))
			{	
				$cont++;?>
				<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"><?
				$cons2="select cie.diagnostico,cie.codigo from salud.diagnosticos,salud.cie where compania='$Compania[0]' and cedula='$fila[4]' 
				and numservicio=$fila[8] and clasedx='Ingreso' and cie.codigo=diagnosticos.diagnostico";
				//echo "$cons2<br>";
				$res2=ExQuery($cons2); 
				
				$FechaIng=substr($fila[6],0,11);if($FechaIng==''){$FechaIng="&nbsp;";}
				$FechaEgr=substr($fila[7],0,11);if($FechaEgr==''){$FechaEgr="&nbsp;";}
								
				$cons5="select primape,segape,primnom,segnom from central.terceros where compania='$Compania[0]' and identificacion='$fila[5]'";
				//echo $cons5;
				$res5=ExQuery($cons5);$fila5=ExFetch($res5);				
                echo "<td>$cont</td><td>$fila[0] $fila[1] $fila[2] $fila[3]</td>";?>
				<td align='center' onClick="location.href='/HistoriaClinica/ResultBuscarHC.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $fila[4]?>&Buscar=1'">
			<?	echo "$fila[4]</td><td align='center'>$FechaIng</td><td align='center'>$FechaEgr</td>
				<td align='center'>$fila[8]</td><td>";
				while($fila2=ExFetch($res2)){echo "$fila2[1] - $fila2[0]<br>";}if($fila2[0]==''){echo "&nbsp;";}
				echo "</td><td>$fila5[0]</td><td>$fila[10]&nbsp;</td><td>".ObtenEdad($fila[11])."&nbsp;</td></tr>";				
			}?>
            <tr><td colspan="11">&nbsp;</td></tr>
            <tr><td colspan="11"><strong>Total Egresos Periodo: <? echo $cont?></strong></td></tr>
	<?	}
		else
		{?>
        	<tr><td>No Hay Egresos Durante El Periodo Seleccionado</td></tr>
 	<?	}?>
	</table><?
}?>
</body>
</html>
