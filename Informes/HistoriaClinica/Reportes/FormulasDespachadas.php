<script language='javascript' src="../calendario/popcalendar.js"></script> 
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#66999f" onLoad="document.reporte.fechai.focus()"><h2 >Reporte Fórmulas Médicas</h2> 
<form action="" method="post" name="reporte">
<table border=1 align="center">



	<tr><td colspan=3>Digite Fecha(Año-mes-dia)(FI-FF)</td></tr>
	<tr><td><input type="text" name="fechai" maxlength="10" id="dateArrival" onClick="popUpCalendar(this, reporte.dateArrival, 'yyyy/mm/dd');" size="9" ></td>
	<td><input type="text" name="fechaf" maxlength="10" id="dateArrival2" onClick="popUpCalendar(this, reporte.dateArrival2, 'yyyy/mm/dd');" size="9"></td>
	
	<? //<td><input type="text" name="fechaf" size=7 maxlength="10"></td>?>
	<td><input type="submit" value="Ver" ></td>
	
	</tr>
	
</table>
<div align="center"><br>	
  
    <?php 
		$conex=mysql_connect("localhost","root","");
			mysql_select_db("salud",$conex);
			$sql="SELECT count( Cedula ) , Cedula, FechaDespacho
					FROM `salidamedicamentos` 
					WHERE fechadespacho >= '$fechai'
					AND FechaDespacho <= '$fechaf'
					GROUP BY Cedula, FechaDespacho";
				$resultado=mysql_query($sql,$conex);
				/*$sql1="SELECT  Cedula, FechaDespacho
					FROM `salidamedicamentos` 
					WHERE fechadespacho >= '$fechai'
					AND FechaDespacho <= '$fechaf'";*/
				$sql1="SELECT Cedula, FechaDespacho FROM salidamedicamentos,codmedicamentos WHERE 
                 salidamedicamentos.Codigo=codmedicamentos.AutoID and fechadespacho >= '$fechai'
				 AND FechaDespacho <= '$fechaf' and CodPos!= '0' ";	
				 
				 $sql2="SELECT Cedula, FechaDespacho FROM salidamedicamentos,codmedicamentos WHERE 
                 salidamedicamentos.Codigo=codmedicamentos.AutoID and fechadespacho >= '$fechai'
				 AND FechaDespacho <= '$fechaf' and CodPos = '0' ";	
				 
					
				
				$resultado1=mysql_query($sql1);	
				$resultado2=mysql_query($sql2);	
					
				$total=mysql_num_rows($resultado);
				$total1=mysql_num_rows($resultado1);
				$total2=mysql_num_rows($resultado2);
				echo "<table border=1>";
				
				echo "<tr><td><b>Periodo:</b></td><td><b>$fechai / $fechaf</b></td></tr>";
				echo "<td>Número de Fórmulas </td><td align=center> $total </td></tr>";
				echo "<td>Total medicamentos POS </td><td align=center>$total1 </td></tr>";
				echo " <tr><td>Total Medicamentos No Pos </td><td align=center>$total2</td></tr>";
				echo "</table>";
				mysql_free_result($resultado);
				mysql_free_result($resultado1)
				
				
						
					
?>
</div>
</form>
<div align="center"><a href="Reportes.php">Cerrar</a><br>
</div>
</body>
</html>
