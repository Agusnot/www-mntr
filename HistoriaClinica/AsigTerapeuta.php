<?php
if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	//echo "$Usuario, $Compania[0]";


include("../Funciones.php");

$ND=getdate();
if($ND[mon]<10){$C1="0";}else{$C1="";}
if($ND[mday]<10){$C2="0";}else{$C2="";}	
$FechaIni="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Asignar Terapeuta</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #000000;
}
body {
	margin-left: 0px;
	margin-right: 0px;
	background-image: url(../Imgs/Fondo.jpg);
	margin-top: 0px;
	margin-bottom: 0px;
}
select{background-color:#FFFFFF;
border-color:#EEEEEE;
border-style:solid;
border-width:thin;
color:#333333;
font-family:Verdana, Arial, Helvetica, sans-serif;
font-size:10px;
}

-->
</style>
<script type="text/JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
function traslado(){
<?php
$dato1=$_GET['usuarionb'];
//echo"el dato es $dato1";
$dato2=$_GET['profesional'];
$dato3=$_GET['cedula'];
$pabellon=$_GET['pabellon'];
if(($pabellon==NULL)||($pabellon=="0")){$pquery="";}
else{$pquery=" and pabellon='$pabellon'";}
if(($dato1!=NULL)&&($dato2!=NULL)&&($dato3!=NULL)){
	?>
	//alert('<?php //echo"$dato1, $dato2, $dato3";/*?>');
	alert('<?php echo"Cambio Realizado!";?>')
		<?php
		$conserv="select salud.servicios.numservicio from salud.agendainterna 
		inner join salud.servicios
		on salud.agendainterna.numservicio=salud.servicios.numservicio
		where salud.agendainterna.cedula='$dato3' and especialidad='Terapia Ocupacional' and salud.servicios.estado='AC'";
		$resserv=ExQuery($conserv);
		while($filaserv=ExFetch($resserv)){
			//echo $filaserv[0];
			$consup="update salud.agendainterna set usuario='$dato1', profecional='$dato2',fechamodterapeuta='$FechaIni' where cedula='$dato3' and especialidad='Terapia Ocupacional' and numservicio='$filaserv[0]' ";
			//$consup="update salud.agendainterna set profecional='$dato2' where cedula='$dato3' and especialidad='Terapia Ocupacional'";
			$res=ExQuery($consup);
		}
		?>
		location.href="?DatNameSID=<? echo $DatNameSID?>&pabellon=<? echo $pabellon?>&usuarionb=<? echo $dato1?>";		
	<?php
	}
	?>
}
//-->
</script>
</head>

<body onload="javascript:traslado();">
<form id="form1" name="form1" method="post" action="">
  
    <div align="center">
      <table width="50" border="1" cellpadding="5">

        <tr>
          <td colspan="3" nowrap="nowrap" bgcolor="#DDDDDD"><div align="left"><strong>SERVICIO</strong></div></td>
        </tr>
        <tr>
          <td colspan="3" nowrap="nowrap"><div align="left">
<select name="menu1" onchange="MM_jumpMenu('self',this,0)">
<option value="?DatNameSID=<? echo $DatNameSID?>&pabellon=0<? echo'"'; if($filap[0]==$pabellon){echo' selected="selected"';} ?>">TODOS</option>
<?php
$consp="select pabellon from salud.pabellones order by pabellon asc";
$resp=ExQuery($consp);
while($filap=ExFetch($resp)){
	?>
	<option value="?DatNameSID=<? echo $DatNameSID?>&pabellon=<?php echo "$filap[0]&usuarionb=$usuario[1]".'"'; if($filap[0]==$pabellon){echo' selected="selected"';} ?>><?php echo "$filap[0]"; ?></option>
	<?php
}
?>
</select>
          </div></td>
        </tr>
        <tr>
          <td colspan="3" nowrap="nowrap">&nbsp;</td>
        </tr>
        <tr>
		<td nowrap="nowrap" bgcolor="#DDDDDD"><strong>#</strong></td>
		  <td nowrap="nowrap" bgcolor="#DDDDDD"><strong># SERVICIO</strong></td>
          <td nowrap="nowrap" bgcolor="#DDDDDD"><strong>C&Eacute;DULA</strong></td>
          <td nowrap="nowrap" bgcolor="#DDDDDD"><strong>NOMBRE</strong></td>
          <td nowrap="nowrap" bgcolor="#DDDDDD"><strong>TERAPEUTA ASIGNADO </strong></td>
        </tr>
        <tr>
<?php
$nb=1;
$cons0="select  
salud.agendainterna.cedula,profecional, salud.agendainterna.numservicio 
from salud.agendainterna 
inner join salud.servicios
on salud.agendainterna.numservicio=salud.servicios.numservicio
and salud.agendainterna.cedula=salud.servicios.cedula
where especialidad='Terapia Ocupacional' and salud.agendainterna.compania='$Compania[0]' and salud.servicios.compania='$Compania[0]' and salud.servicios.estado='AC' ";
$res0=ExQuery($cons0);
while($fila0=ExFetch($res0)){
	$cons1="select primnom,segnom,primape,segape from central.terceros,salud.pacientesxpabellones where central.terceros.identificacion='$fila0[0]' and salud.pacientesxpabellones.cedula='$fila0[0]' and salud.pacientesxpabellones.estado='AC' $pquery order by primape asc";
//echo $cons1;
	$res1=ExQuery($cons1);
	while($fila1=ExFetch($res1)){
		?>
		<td><div align="center"><?php echo"$nb"; $nb++;?></div></td>
		<td><div align="center"><?php echo"$fila0[2]"; ?></div></td>
		<td><div align="center"><?php echo"$fila0[0]"; ?></div></td>
		<td nowrap="nowrap"><div align="left"><?php echo"$fila1[2] $fila1[3] $fila1[0] $fila1[1]"; ?></div><div align="left"></div></td>
		<td><div align="center">
		<select name="menu2" onchange="MM_jumpMenu('self',this,0)">
		<?php
		$cons3="select usuario from salud.medicos where especialidad='Terapia Ocupacional'";
		$res3=ExQuery($cons3);
		while($fila3=ExFetch($res3)){
			$cons2="select nombre from central.usuarios where usuario='$fila3[0]' limit 1";
			$res2=ExQuery($cons2);
			while($fila2=ExFetch($res2)){
				?>
				<option value="?DatNameSID=<? echo $DatNameSID?><?php echo"&usuario=$usuario[1]&profesional=$fila3[0]&cedula=$fila0[0]&pabellon=$pabellon&usuarionb=$usuario[1]".'"'; if($fila3[0]==$fila0[1]){echo' selected="selected"';} ?>><?php echo"$fila2[0]"; ?></option>
				<?php
			}
		}
		?>
		</select>
		</div></td>
		</tr>
		<?php
	}
}
?>
              </table>
  </div>
  </form>
</body>
</html>
