<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	$ND=getdate();
	include("Funciones.php");
	$cons="Select Codigo,CentroCostos,Tipo from Central.CentrosCosto WHERE Compania='$Compania[0]' and Anio=$ND[year] Order By Codigo";
	//echo $cons."<br>";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$CentCost[$fila[0]]=$fila[1];
	}
	if($Eliminar)
	{
		$cons="Delete from Salud.Pabellones where Pabellon='$Pabellon' and ambito='$Ambito'and Compania='$Compania[0]'";		
		$res=ExQuery($cons);echo ExError();
		$cons="Delete from salud.camasxunidades where unidad='$Pabellon' and ambito='$Ambito' and compania='$Compania[0]'";
		$res=ExQuery($cons);echo ExError();
	}	
	
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
	<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4"> 
	<tr align="center"><td colspan="9" align="center" style="font-weight:bold">Proceso <select name="Ambito" onChange="document.FORMA.submit()"><option></option>
		<? $cons="select ambito from salud.ambitos where compania='$Compania[0]' and consultaextern=0 and ambito!='Sin Ambito' order by ambito";	
			$res=ExQuery($cons);echo ExError();	
			while($fila = ExFetch($res)){
				if($fila[0]==$Ambito){
					echo "<option value='$fila[0]' selected>$fila[0]</option>";
				}
				else{
					echo "<option value='$fila[0]'>$fila[0]</option>";
				}
			}?>
        	</select></td>


	<?	$consult="Select * from Salud.Pabellones where ambito='$Ambito' and Compania='$Compania[0]' order by pabellon";		
		$result=ExQuery($consult);
		if(ExNumRows($result)>0){?>        	
	       	<tr bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="7"><? echo $fila[0]?></td></tr>
            <TR bgcolor="#e5e5e5" style="font-weight:bold" align="center"><TD>Servicio</TD><TD>No. Camas</TD><td>Sobrecupo</td><td>Observaciones</td>
            <td>Centro de Costos</td><td colspan="3"></td></TR>
		<?	while($row = ExFetchArray($result)){
				echo "<tr><td>".$row['pabellon']."</td><td align='center'>".$row['nocamas']."</td><td align='center'>".$row['sobrecupo']."</td><td>".$row['observaciones']."&nbsp;</td>
				<td>".$row['centrocosto']."-".$CentCost[$row['centrocosto']]."</td><td>";?>
				<img title="Editar" src="/Imgs/b_edit.png" style="cursor:hand" onClick="location.href='NewConfUnidades.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>&Edit=1&Pabellon=<? echo $row['pabellon']?>'"></td><td>
				<img title="Eliminar" style="cursor:hand" onClick="if(confirm('Desea eliminar este registro?')){location.href='ConfUnidades.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>&Eliminar=1&Pabellon=<? echo $row['pabellon']?>';}" src="/Imgs/b_drop.png"></td><td>
                <img title="Configuracion Camas" src="/Imgs/s_process.png" style="cursor:hand" onClick="location.href='ConfCamasxUnd.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>&UnidadMod=<? echo $row['pabellon']?>'"></td></tr>
<?			}
		}
		else{
			if($Ambito){
				echo "<tr><td bgcolor='#e5e5e5' style='font-weight:bold' align='center' colspan='7'>No se han asignado unidades a este proceso</td></tr>";
			}			
		}
		if($Ambito){?>
			<tr><td align="center" colspan="9">
            		<input type="button" onClick="location.href='NewConfUnidades.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>';" value="Nuevo">
            </td></tr>
	<?	}?>
        
</table><br>
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
</body>
</html>