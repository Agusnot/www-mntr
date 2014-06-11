<?
	if($DatNameSID){session_name("$DatNameSID");}
	else{$Compania[0]='Clinica San Juan de Dios';}
	session_start();
	include("../Funciones.php");
	$ND=getdate();
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Perfil Farmacoterap&eacute;utico</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #000000;
}
body {
	margin-left: 20px;
	margin-right: 20px;
	background-image: url(../Imgs/Fondo.jpg);
	margin-top: 20px;
	margin-bottom: 20px;
}
select {
	background-color:#FFFFFF;
	border-color:#EEEEEE;
	border-style:solid;
	border-width:thin;
	color:#333333;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:10px;
}
a:link {
	color: #333333;
}
a:visited {
	color: #333333;
}
a:hover {
	color: #FF0000;
}
a:active {
	color: #333333;
}
-->
</style>
<script type="text/JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
</head>
<body>
<form id="form1" name="form1" method="post" action="">
<table width="100%" border="0" cellpadding="5">
<tr>
<td>
<div align="justify">SELECCIONAR PABELL&Oacute;N:
<select name="menu1" onchange="MM_jumpMenu('self',this,0)">
<?php

//$FechaIni=$_GET['FechaIni'];
$FechaIni='2012-06-20';
//echo $FechaIni;

if($FechaIni==NULL){
	if($ND[mon]<10){$C1="0";}else{$C1="";}
	if($ND[mday]<10){$C2="0";}else{$C2="";}			
	$FechaIni="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";
}
		
$aaaa1=substr("$FechaIni", -10,4);
$mm1=substr("$FechaIni", -5,2);
//if($mm1<10){$mm1=str_replace("0","","$mm1");}					
$dd1=substr("$FechaIni", -2);
//if($dd1<10){$dd1=str_replace("0","","$dd1");}
//echo "$aaaa1,$mm1,$dd1";

$pabellon=$_GET['pabellon'];
$usuario=$_GET['usuario'];
$qp="and pabellon='$pabellon'";
if($pabellon==NULL){echo'<option value="" selected="selected">SELECCIONE</option>';}
?>
<option value="?pabellon=1" <?php if($pabellon==1){$qp="";echo'selected="selected"';} ?>>TODOS</option>
<?php
$cons="select pabellon from salud.pabellones order by pabellon";
$res=ExQuery($cons);
while($fila=ExFetch($res)){
	echo'<option value="?pabellon='.$fila[0].'" ';if($fila[0]==$pabellon){echo'selected="selected"';}echo'>'.$fila[0].'</option>';		  
}
?>
</select>
</div>
</td>
</tr>
<tr><td height="20">&nbsp;</td>
</tr>
<?php
if($pabellon!=NULL){
	switch($usuario){
		case NULL:
			$cons1="select cedula,numservicio,idcama,fechai from salud.pacientesxpabellones where estado='AC' $qp";
			//echo"$cons1 </br>";
			$res1=ExQuery($cons1);
			echo'<tr><td><div align="justify"><table border="0" cellpadding="5">
			<tr>
			<td><div align="left"><strong>NOMBRE</strong></div></td>
			<td><div align="right"><strong>C&Eacute;DULA</strong></div></td>
			</tr>';
			while($fila1=ExFetch($res1)){
				$cons2="select primnom,segnom,primape,segape,sexo,fecnac from central.terceros where identificacion='$fila1[0]' order by primnom asc";
				//echo"$cons2 1</br>";
				$res2=ExQuery($cons2);
				while($fila2=ExFetch($res2)){
					echo'<tr>
					<td nowrap="nowrap"><div align="left"><a href="?pabellon='.$pabellon.'&usuario='.$fila1[0].'">'."$fila2[0] $fila2[1] $fila2[2] $fila2[3]".'</a> </div></td>
					<td nowrap="nowrap"><div align="right">'.$fila1[0].'</div></td>
					</tr>';
				}
			}
			break;
		default:
			$cons1="select cedula,numservicio,idcama,fechai from salud.pacientesxpabellones where estado='AC' and cedula='$usuario' $qp";
			//echo"$cons1 </br>";
			$res1=ExQuery($cons1);
			echo'<tr><td><div align="justify"><table border="0" cellpadding="5">';
			if($fila1=ExFetch($res1)){
				$cons2="select primnom,segnom,primape,segape,sexo,fecnac from central.terceros where identificacion='$usuario' order by primnom asc";
				//echo"$cons2 2</br>";
				$res2=ExQuery($cons2);
				$fila2=ExFetch($res2);
				echo'<tr><td nowrap="nowrap"><div align="left"><strong>NOMBRE:</strong></div></td><td nowrap="nowrap"><div align="left">'."$fila2[0] $fila2[1] $fila2[2] $fila2[3]".'</div></td></tr>';
				echo'<tr><td nowrap="nowrap"><div align="left"><strong>C&Eacute;DULA:</strong></div></td><td nowrap="nowrap"><div align="left">'.$usuario.'</div></td></tr></table>';
					echo'<table border="0" cellpadding="5"><tr><td nowrap="nowrap"><div align="left"><strong>PABELL&Oacute;N:</strong></div></td><td nowrap="nowrap"><div align="left">'.$pabellon.'</div></td><td nowrap="nowrap"><div align="left"><strong>CAMA:</strong></div></td><td nowrap="nowrap"><div align="left">'.$fila1[2].'</div></td><td nowrap="nowrap"><div align="left"><strong>FECHA INGRESO:</strong></div></td><td nowrap="nowrap"><div align="left">'.$fila1[3].'</div></td></tr></table>';
				$cons3="select entidad from salud.pagadorxservicios where numservicio='$fila1[1]'";
				//echo"$cons3 </br>";
				$res3=ExQuery($cons3);
				while($fila3=ExFetch($res3)){
					$showpagador="";
					$cons4="select primape from central.terceros where identificacion='$fila3[0]'";
					//echo"$cons4 </br>";
					$res4=ExQuery($cons4);
					while($fila4=ExFetch($res4)){
						$showpagador="$showpagador".$fila4[0]."</br>";
					}
					echo'<table border="0" cellpadding="5"><tr><td nowrap="nowrap"><div align="left"><strong>PAGADOR:</strong></div></td><td nowrap="nowrap"><div align="left">'.$showpagador.'</div></td>';
					$cons5="select cmp00030,cmp00031,cmp00032,dx1,dx2,dx3,dx4,dx5,fecha from histoclinicafrms.tbl00021 where cedula='$usuario'";
					//echo"$cons5 </br>";
					$res5=ExQuery($cons5);
					switch($mm1){
						case 1:
							$diames=31;
						break;
						case 2:
							if(($aaaa1%4)==0){
								$diames=29;
							}
							$diames=28;
						break;
						case 3:
							$diames=31;
						break;
						case 4:
							$diames=30;
						break;
						case 5:
							$diames=31;
						break;
						case 6:
							$diames=30;
						break;
						case 7:
							$diames=31;
						break;
						case 8:
							$diames=31;
						break;
						case 9:
							$diames=30;
						break;
						case 10:
							$diames=31;
						break;
						case 11:
							$diames=30;
						break;
						case 12:
							$diames=31;
						break;						
					}
					while($fila5=ExFetch($res5)){
						echo'<td nowrap="nowrap"><div align="left"><strong>FECHA NACIMIENTO:</strong></div></td><td nowrap="nowrap"><div align="left">'.$fila2[5].'</div></td>';
						echo'<td nowrap="nowrap"><div align="left"><strong>SEXO:</strong></div></td><td nowrap="nowrap"><div align="left">'.$fila2[4].'</div></td>';
						echo'<td nowrap="nowrap"><div align="left"><strong>PESO:</strong></div></td><td nowrap="nowrap"><div align="left">'.$fila5[0].'</div></td>';
						echo'<td nowrap="nowrap"><div align="left"><strong>TALLA:</strong></div></td><td nowrap="nowrap"><div align="left">'.$fila5[1].'</div></td>';
						echo'<td nowrap="nowrap"><div align="left"><strong>IMC:</strong></div></td><td nowrap="nowrap"><div align="left">'.$fila5[2].'</div></td></tr></table>';
						echo'<table border="0" cellpadding="5"><tr><td nowrap="nowrap"><div align="right"><strong>FECHA DX:</strong></div></td><td nowrap="nowrap"><div align="left">'.$fila5[8].'</div></td><td nowrap="nowrap"><div align="right"><strong>DX1:</strong></div></td><td nowrap="nowrap"><div align="left">'.$fila5[3].'</div></td><td nowrap="nowrap"><div align="right"><strong>DX2:</strong></div></td><td nowrap="nowrap"><div align="left">'.$fila5[4].'</div></td><td nowrap="nowrap"><div align="right"><strong>DX3:</strong></div></td><td nowrap="nowrap"><div align="left">'.$fila5[5].'</div></td><td nowrap="nowrap"><div align="right"><strong>DX4:</strong></div></td><td nowrap="nowrap"><div align="left">'.$fila5[6].'</div></td><td nowrap="nowrap"><div align="right"><strong>DX5:</strong></div></td><td nowrap="nowrap"><div align="left">'.$fila5[7].'</div></td></tr></table></br></br><table border="0" cellpadding="5">';
						
						
						$cons6="select autoid,fecha,numero,grupo,cantidad from consumo.movimiento where fecha >= '$aaaa1-$mm1-01' and fecha <= '$aaaa1-$mm1-$diames' and cedula='$fila1[0]' and grupo like 'Medicamento%' and estado='AC' and tipocomprobante='Salidas' order by fecha asc";
						//echo"$cons6 </br>";
						$res6=ExQuery($cons6);
						while($fila6=ExFetch($res6)){
							$cons7="select codigo1,nombreprod1,unidadmedida,presentacion,clasificacion from consumo.codproductos where autoid='$fila6[0]' and anio='$aaaa1' and estado='AC'";
							//echo"$cons7 </br>";
							$res7=ExQuery($cons7);
							while($fila7=ExFetch($res7)){
								$detallevar="$fila7[1] $fila7[2] $fila7[3]";
								$cons8="select posologia,viasumin from salud.ordenesmedicas where detalle='$detallevar' and cedula='$fila1[0]'";
								//echo"$cons8 </br>";
								$res8=ExQuery($cons8);
								if($fila8=ExFetch($res8)){
									echo'<tr><td nowrap="nowrap" bgcolor="#7B99BB"><div align="left"><strong>FECHA:</strong></div></td><td nowrap="nowrap"><div align="left">'.$fila6[1].'</div></td><td nowrap="nowrap" bgcolor="#87A2C1"><div align="left"><strong>PRINCIPIO ACTIVO:</strong></div></td><td nowrap="nowrap"><div align="left">'.$fila7[1].'</div></td><td nowrap="nowrap" bgcolor="#93ACC7"><div align="left"><strong>CONCENTRACI&Oacute;N:</strong></div></td><td nowrap="nowrap"><div align="left">'.$fila7[2].'</div></td><td nowrap="nowrap" bgcolor="#9FB5CE"><div align="left"><strong>FORMA:</strong></div></td><td nowrap="nowrap"><div align="left">'.$fila7[3].'</div></td><td nowrap="nowrap" bgcolor="#ABBED4"><div align="left"><strong>POSOLOG&Iacute;A:</strong></div></td><td nowrap="nowrap"><div align="left">'.$fila8[0].'</div></td><td nowrap="nowrap" bgcolor="#B7C7DA"><div align="left"><strong>V&Iacute;A:</strong></div></td><td nowrap="nowrap"><div align="left">'.$fila8[1].'</div></td><td nowrap="nowrap" bgcolor="#C3D1E0"><div align="left"><strong>GRUPO:</strong></div></td><td nowrap="nowrap"><div align="left">'.$fila6[3].'</div></td>';
								}
							}
						}				
					}
				}
			}
			break;
	}	
echo'</table></div></td></tr>';
}
?>
</table>
</form>
</body>
</html>
