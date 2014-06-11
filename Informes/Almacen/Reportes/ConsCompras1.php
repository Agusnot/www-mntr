<?php
if($DatNameSID){session_name("$DatNameSID");}
else{$Compania[0]='Clinica San Juan de Dios';}
session_start();
include("../../../Funciones.php");

if($Anio){//echo "$Anio, ";
}
if($MesIni){//echo "$MesIni, ";
if(($MesIni=="01")||($MesIni=="02")||($MesIni=="03")||($MesIni=="04")||($MesIni=="05")||($MesIni=="06")||($MesIni=="07")||($MesIni=="08")||($MesIni=="09")){
$MesIni=str_replace("0","",$MesIni);
}
}
if($DiaIni){//echo "$DiaIni, ";
if(($DiaIni=="01")||($DiaIni=="02")||($DiaIni=="03")||($DiaIni=="04")||($DiaIni=="05")||($DiaIni=="06")||($DiaIni=="07")||($DiaIni=="08")||($DiaIni=="09")){
$DiaIni=str_replace("0","",$DiaIni);
}
}
if($MesFin){//echo "$MesFin, ";}
if(($MesFin=="01")||($MesFin=="02")||($MesFin=="03")||($MesFin=="04")||($MesFin=="05")||($MesFin=="06")||($MesFin=="07")||($MesFin=="08")||($MesFin=="09")){
$MesFin=str_replace("0","",$MesFin);
}
}
if($DiaFin){//echo "$DiaFin, ";
if(($DiaFin=="01")||($DiaFin=="02")||($DiaFin=="03")||($DiaFin=="04")||($DiaFin=="05")||($DiaFin=="06")||($DiaFin=="07")||($DiaFin=="08")||($DiaFin=="09")){
$DiaFin=str_replace("0","",$DiaFin);
}
}
if($AlmacenPpal){//echo "$AlmacenPpal </br>";
}

if($FechaIni==NULL){
	if($MesIni<10){$C1="0";}else{$C1="";}
	if($DiaIni<10){$C2="0";}else{$C2="";}			
	$FechaIni="$Anio-$C1$MesIni-$C2$DiaIni";
}
		
if($FechaFin==NULL){
	if($MesFin<10){$C1="0";}else{$C1="";}
	if($DiaFin<10){$C2="0";}else{$C2="";}
	$FechaFin="$Anio-$C1$MesFin-$C2$DiaFin";
}

$totall=0;
$ln=1;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Consolidado Compras</title>
<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-image: url(file://///10.18.176.100/html/Imgs/Fondo.jpg);
}
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #000000;
}
-->
</style></head>

<body>
<div align="center">
  <form id="form1" name="form1" method="post" action="">
    <table width="200" border="1" cellpadding="5" bordercolor="#EEEEEE" background="../Imgs/Fondo.jpg" bgcolor="#FFFFFF" style='font : normal normal small-caps 11px Tahoma;'>
      
      <tr>
        <td nowrap="nowrap" bgcolor="#EEEEEE">&nbsp;</td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong># ENTRADA</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>C&Oacute;DIGO </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>NOMBRE PRODUCTO </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>GRUPO</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>FORMA FARMAC&Eacute;UTICA </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>PROVEEDOR</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>CUM</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>REG INVIMA </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>FABRICANTE</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>CANTIDAD</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>COSTO UNITARIO</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>COSTO TOTAL</strong></td>
      </tr>
<?php
switch($AlmacenPpal){
	case "FARMACIA":
		$cons="select autoid,cantidad,vrcosto,totcosto,cedula,numero,grupo from consumo.movimiento where compania='$Compania[0]' and almacenppal='$AlmacenPpal' and tipocomprobante='Entradas' and fecha >= '$FechaIni' and fecha <= '$FechaFin' order by numero";
		//echo "$cons </br>";
		$res=ExQuery($cons);
		while($fila=ExFetch($res)){
			$cons2="select autoid,reginvima,laboratorio from consumo.lotes where numero='$fila[5]' and autoid='$fila[0]'";
			//echo "$cons2 </br>";
			$res2=ExQuery($cons2);
			while($fila2=ExFetch($res2)){
				$cons3="select codigo1,nombreprod1,presentacion from consumo.codproductos where autoid='$fila[0]' and almacenppal='$AlmacenPpal' and anio=$Anio order by presentacion asc";
				//echo "$cons3 </br></br>";
				$res3=ExQuery($cons3);
				while($fila3=ExFetch($res3)){
					$cons4="select primnom,segnom,primape,segape from central.terceros where identificacion='$fila[4]'";
					//echo "$cons4 </br>";
					$res4=ExQuery($cons4);
					while($fila4=ExFetch($res4)){
						$cons5="select cum from consumo.cumsxproducto where autoid='$fila[0]' and laboratorio='$fila2[2]' and reginvima='$fila2[1]'";
						//echo "$cons5 </br>";
						$res5=ExQuery($cons5);
						while($fila5=ExFetch($res5)){
							echo'<tr><td height="10">'.$ln.'<div align="center"></div></td>';
							echo'<td height="10">'.$fila[5].'<div align="center"></div></td>';
							echo'<td height="10">'.$fila3[0].'<div align="center"></div></td>';
							echo'<td height="10">'.$fila3[1].'<div align="center"></div></td>';
							echo'<td height="10">'.$fila[6].'<div align="center"></div></td>';
							echo'<td height="10">'.$fila3[2].'<div align="center"></div></td>';	
							echo'<td height="10">'."$fila4[0] $fila4[1] $fila4[2] $fila4[3]".'<div align="center"></div></td>';	
							echo'<td height="10">'.$fila5[0].'<div align="center"></div></td>';				
							echo'<td height="10">'.$fila2[1].'<div align="center"></div></td>';
							echo'<td height="10">'.$fila2[2].'<div align="center"></div></td>';
							echo'<td height="10">'.$fila[1].'<div align="center"></div></td>';	
							echo'<td height="10">';echo number_format($fila[2],2);echo'<div align="center"></div></td>';
							echo'<td height="10">';echo number_format($fila[3],2);echo'<div align="center"></div></td></tr>';
							$totall=$totall+$fila[3];
							$ln++;						
						}
					}
				}
			}
		}
		break;
		case "SUMINISTROS":
			$cons="select autoid,cantidad,vrcosto,totcosto,cedula,numero,grupo from consumo.movimiento where compania='$Compania[0]' and almacenppal='$AlmacenPpal' and tipocomprobante='Entradas' and fecha >= '$FechaIni' and fecha <= '$FechaFin' order by numero";
			//echo "$cons </br>";
			$res=ExQuery($cons);
			while($fila=ExFetch($res)){
				//$cons2="select autoid,reginvima,laboratorio from consumo.lotes where numero='$fila[5]' and autoid='$fila[0]'";
				//echo "$cons2 </br>";
				//$res2=ExQuery($cons2);
				//while($fila2=ExFetch($res2)){
					$cons3="select codigo1,nombreprod1,presentacion from consumo.codproductos where autoid='$fila[0]' and almacenppal='$AlmacenPpal' and anio=$Anio order by presentacion asc";
					//echo "$cons3 </br></br>";
					$res3=ExQuery($cons3);
					while($fila3=ExFetch($res3)){
						$cons4="select primnom,segnom,primape,segape from central.terceros where identificacion='$fila[4]'";
						//echo "$cons4 </br>";
						$res4=ExQuery($cons4);
						while($fila4=ExFetch($res4)){
							//$cons5="select cum from consumo.cumsxproducto where autoid='$fila[0]' and laboratorio='$fila2[2]' and reginvima='$fila2[1]'";
							//echo "$cons5 </br>";
							//$res5=ExQuery($cons5);
							//while($fila5=ExFetch($res5)){
								echo'<tr><td height="10">'.$ln.'<div align="center"></div></td>';
								echo'<td height="10">'.$fila[5].'<div align="center"></div></td>';
								echo'<td height="10">'.$fila3[0].'<div align="center"></div></td>';
								echo'<td height="10">'.$fila3[1].'<div align="center"></div></td>';
								echo'<td height="10">'.$fila[6].'<div align="center"></div></td>';
								echo'<td height="10">'.$fila3[2].'<div align="center"></div></td>';	
								echo'<td height="10">'."$fila4[0] $fila4[1] $fila4[2] $fila4[3]".'<div align="center"></div></td>';	
								echo'<td height="10">'.$fila5[0].'<div align="center"></div></td>';				
								echo'<td height="10">'.$fila2[1].'<div align="center"></div></td>';
								echo'<td height="10">'.$fila2[2].'<div align="center"></div></td>';
								echo'<td height="10">'.$fila[1].'<div align="center"></div></td>';	
								echo'<td height="10">';echo number_format($fila[2],2);echo'<div align="center"></div></td>';
								echo'<td height="10">';echo number_format($fila[3],2);echo'<div align="center"></div></td></tr>';
								$totall=$totall+$fila[3];
								$ln++;						
							//}
						}
					}
				//}
			}
			break;
	}
		
?>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="center"><strong>TOTAL</strong></div></td>
        <td><div align="center"><strong><?php echo number_format($totall,2);?></strong></div></td>
      </tr>
    </table>
  </form>
</div>
</body>
</html>
