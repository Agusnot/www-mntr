<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
//	$Reporte="";
	$ND=getdate();
	if(!$Anio){$Anio="$ND[year]";}
	if(!$Mes){$Mes="$ND[mon]";}
//	echo $Anio." - ".$Mes;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" target="Abajo" action="ResultReportes.php" >
<input type="hidden" name="Codigo" >
<input type="hidden" name="Reporte" value="<? echo $Reporte?>">
<table border="1" bordercolor="white" bgcolor="#e5e5e5"  style="font-family:Tahoma;font-size:13">
	<tr style="text-align:center;">
    <td>Reporte</td>
    <td>Mes</td>
    <td>Año</td>
    <td>Tipo Vinculacion</td>
    </tr>
    <tr>
    <td><select name="Reporte">
        <option value="Reporte 1" selected <? if ($Reporte=="Reporte 1"){ echo "selected";}?>>Registro General de Pagos</option>
        <option value="Reporte 2" selected <? if ($Reporte=="Reporte 2"){ echo "selected";}?>>Comprobante x Funcionario x Seccion</option>
        <option value="Reporte 3" selected <? if ($Reporte=="Reporte 3"){ echo "selected";}?>>Desprendible x Funcionario</option>
        <option value="Reporte 4" selected <? if ($Reporte=="Reporte 4"){ echo "selected";}?>>Comprobante x Funcionario</option>
		<option value="Reporte 5" selected <? if ($Reporte=="Reporte 5"){ echo "selected";}?>>Comprobante x Funcionario x Centro de Costo</option>
  		<option value="Reporte 6" selected <? if ($Reporte=="Reporte 6"){ echo "selected";}?>>Registro Detallado x Concepto</option>
        <option value="Reporte 7" selected <? if ($Reporte=="Reporte 7"){ echo "selected";}?>>Reporte Total x Empleados</option>        
        <option value="Reporte 8" selected <? if ($Reporte=="Reporte 8"){ echo "selected";}?>>Planilla Unificada</option>
        </select>
    </td>
    <td><select name="Mes" id="Mes" >
    	<?
		$cons="select numero,mes from central.meses order by numero";
		$Resultado=ExQuery($cons);
		while($fila=ExFetch($Resultado))
		{
			if($fila[0]==$Mes)
			{
					echo "<option value='$fila[0]' selected>$fila[1]</option>"; 
			}
			else
			{
				echo "<option value='$fila[0]'>$fila[1]</option>";
            }
		}
        ?>
        </select>
        </td>
    <td style="text-align:center">
    <select name="Anio" id="Anio" value="<? echo $Anio?>">
    <?
		$consA="Select anio from central.anios where compania='$Compania[0]' order by anio desc";
		 $resultadoA = ExQuery($consA);
        while ($filaA = ExFetch($resultadoA))
            {                        
			if($filaA[0]==$Anio)
				{
					echo "<option value='$filaA[0]' selected>$filaA[0]</option>"; 
				}
			else{echo "<option value='$filaA[0]'>$filaA[0]</option>";}						 
                }
	?>
	</select> 
    </td>
    <td/><select name="Vinculacion" >
         <?
       	$cons = "select codigo,tipovinculacion from nomina.tiposvinculacion where compania='$Compania[0]' order by codigo";
        $resultado = ExQuery($cons);
        while ($fila = ExFetch($resultado))
            {                        
			if($fila[1]==$Vinculacion)
				{
					echo "<option value='$fila[1]' selected>$fila[1]</option>"; 
				}
			else{echo "<option value='$fila[1]'>$fila[1]</option>";}						 
                }
			?>
            </select>
        </td>
        <td><input type="Submit" name="Ver" value="Ver"></td>
    </tr>
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</table>
</form>
</body>