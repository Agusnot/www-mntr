<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
$ND=getdate();
$Anio="$ND[year]";
$Mes="$ND[mon]";
$DiasTr=30;
$NoHorasLab=240;
//-------------------------------------------------------------------------------
//	echo "inicia";
$cons="select mes from central.meses where numero=$Mes";
$res=ExQuery($cons);
$fila=ExFetch($res);
$MesL=$fila[0];
//-------------------------------------------------------------------------------	
if($Registrar)
{
	
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center" >
<tr><td colspan="4" bgcolor="#666699" style="color:white" align="center">Mes A Liquidar</td></tr>
<tr><td align="center">Mes</td>
	<td><select name="Mes" onChange="FORMA.submit();" >
            <option ></option>
                    <?
                    $cons = "select numero,mes from central.meses";
                    $resultado = ExQuery($cons);
                    while ($fila = ExFetch($resultado))
                    {                        
						 if($fila[0]==$Mes)
						 {
							 echo "<option value='$fila[0]' selected>$fila[1]</option>"; 
						 }
						 else{echo "<option value='$fila[0]'>$fila[1]</option>";}						 
                    }
				?>
            </select></td>
	<td align="center">Año</td>
    <td><? echo $Anio?></td></tr>
    </table>
    <tr><td><? echo "Mes Liquidacion : "."$MesL";?></td></tr>
    <tr><td>

<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center" >    
<tr><td colspan="6" bgcolor="#666699" style="color:white" align="center" >Datos A Liquidar</td></tr>
<tr><td style='font : normal normal small-caps 12px Tahoma;' align="center">Concepto</td>
	<td><select name="Concepto" >
            <option ></option>
                    <?
                    $cons = "select concepto,detconcepto from nomina.conceptosliquidacion where tipoconcepto='Campo'";
                    $resultado = ExQuery($cons,$conex);
                    while ($fila = ExFetch($resultado))
                    {                        
						if($TipoSangre==$fila[0])
                        {
                            echo "<option value='$fila[0]' selected>$fila[1]</option>";
                        }
                        else
                        {
                            echo "<option value='$fila[0]'>$fila[1]</option>";
                        }
                    }
				?>
            </select></td>
            <td style='font : normal normal small-caps 12px Tahoma;' align="center">Valor</td>
            <td><input type="text" name="Valor" ></td>
            <td><input type="submit" name="Registrar" value="Registrar"></td>
            <td><input type="button" name="Retirar" value="Retirar Movimiento"></td>
            </tr>
            </table>
            
<? 
$cons="select terceros.identificacion,terceros.primape,terceros.segape,terceros.primnom,terceros.segnom,detconcepto,valor from central.terceros,nomina.nomina where terceros.compania='$Compania[0]' and terceros.compania=nomina.compania and terceros.identificacion='$Identificacion' and terceros.identificacion=nomina.identificacion and terceros.tipo='Empleado' and nomina.anio='$Anio' and nomina.mes='$Mes'";
//echo $cons;
$res=ExQuery($cons);
while($fila=Exfetch($res))
	{
		$Empleados[$fila[0]][$fila[5]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6]);			
		$IdNombres[$fila[0]]=array($fila[0],trim("$fila[1] $fila2 $fila[3] $fila[4]"));			
	}
if($Empleados)
	{?>
		<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center" >
		<tr><td bgcolor="#666699" style="color:white" align="center" ><? echo "Liquidacion Mes de "."$MesL"." del "."$Anio";?></td></tr>
<?		foreach($IdNombres as $Empleado)
		{
			?>
            <tr><td><? echo $Empleado[1]."<br>";
			foreach($Empleados[$Empleado[0]] as $Auto)
			{
				if($Auto[6]!=0)
				{
					echo $Auto[5]." --> ".$Auto[6]."<br>";
				}
			}
			?>
            </td></tr> 
			<?
		}
?>		</table> 		
<?	} ?>
</body>
</html>
