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
<title>Egreso de Pacientes</title>
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
function Valida()
{
document.FORMA.FechaIni.value=''+document.FORMA.fi1.value+'-'+document.FORMA.fi2.value+'-'+document.FORMA.fi3.value+'';
document.FORMA.FechaFin.value=''+document.FORMA.ff1.value+'-'+document.FORMA.ff2.value+'-'+document.FORMA.ff3.value+'';
//alert('Fecha1: '+document.FORMA.FechaIni.value+' Fecha2: '+document.FORMA.FechaFin.value+'');
if(document.FORMA.FechaIni.value=="")
	{
		alert("Debes seleccionar la fecha inicial.");
	}
else{
		if(document.FORMA.FechaFin.value=="")
			{
				alert("Debes seleccionar la fecha final.");
			}
		else{
				if(document.FORMA.FechaIni.value>document.FORMA.FechaFin.value)
					{
						alert("La fecha inicial debe ser menor a la fecha final.");
					}
				else{
						location.href='?DatNameSID=<? echo $DatNameSID?>&FechaIni='+document.FORMA.FechaIni.value+'&FechaFin='+document.FORMA.FechaFin.value+'';
					}
			}
	}
}
//-->
</script>
</head>
<?php
		$FechaIni=$_GET['FechaIni'];
		//echo $FechaIni;
		$FechaFin=$_GET['FechaFin'];
		//echo $FechaFin;
		
        if($FechaIni==NULL){
			if($ND[mon]<10){$C1="0";}else{$C1="";}
			if($ND[mday]<10){$C2="0";}else{$C2="";}			
			$FechaIni="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";
		}
		
		if($FechaFin==NULL){
			if($ND[mon]<10){$C1="0";}else{$C1="";}
			if($ND[mday]<10){$C2="0";}else{$C2="";}
			$FechaFin="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";
		}
		
		$aaaa1=substr("$FechaIni", -10,4);
		$mm1=substr("$FechaIni", -5,2);
		if($mm1<10){$mm1=str_replace("0","","$mm1");}					
		$dd1=substr("$FechaIni", -2);
		if($dd1<10){$dd1=str_replace("0","","$dd1");}
		//echo "$aaaa1,$mm1,$dd1";
		
		$aaaa2=substr("$FechaFin", -10,4);
		$mm2=substr("$FechaFin", -5,2);
		if($mm2<10){$mm2=str_replace("0","","$mm2");}					
		$dd2=substr("$FechaFin", -2);
		if($dd2<10){$dd2=str_replace("0","","$dd2");}
		//echo "$aaaa2,$mm2,$dd2";
	   	
?>
<body>
<form id="FORMA" name="FORMA" method="post" action="">
  <div align="center">
    <table width="200" border="1" cellpadding="5" bordercolor="#EEEEEE" background="../Imgs/Fondo.jpg" bgcolor="#FFFFFF">
      <tr>
        <td colspan="8" nowrap="nowrap"><table width="200" align="left" cellpadding="5">
          <tr>
            <td nowrap="nowrap"><div align="left">
              <p><strong>EGRESO DE PACIENTES </strong></p>
              </div></td>
            </tr>
          <tr>
            <td height="10" nowrap="nowrap">&nbsp;</td>
            </tr>

          <tr>
            <td nowrap="nowrap" bgcolor="#DDDDDD"><div align="left"><strong>FECHA DE CONSULTA </strong></div></td>
            </tr>
          <tr>
            <td nowrap="nowrap"><div align="left">DESDE
                <select name="fi1" id="fi1">
                  <?php
				for($i=2010;$i<=2020;$i++)
					{
						echo'<option value="'.$i.'"';
						if($aaaa1==$i)
							{
								echo'selected="selected"';
							}
						echo'>'.$i.'</option>';
					}
				  ?>
                </select>
                <select name="fi2" id="fi2">
                  <option value="01"<?php if("$mm1"=='01'){echo'selected="selected"';}?>>Enero</option>
                  <option value="02"<?php if("$mm1"=='02'){echo'selected="selected"';}?>>Febrero</option>
                  <option value="03"<?php if("$mm1"=='03'){echo'selected="selected"';}?>>Marzo</option>
                  <option value="04"<?php if("$mm1"=='04'){echo'selected="selected"';}?>>Abril</option>
                  <option value="05"<?php if("$mm1"=='05'){echo'selected="selected"';}?>>Mayo</option>
                  <option value="06"<?php if("$mm1"=='06'){echo'selected="selected"';}?>>Junio</option>
                  <option value="07"<?php if("$mm1"=='07'){echo'selected="selected"';}?>>Julio</option>
                  <option value="08"<?php if("$mm1"=='08'){echo'selected="selected"';}?>>Agosto</option>
                  <option value="09"<?php if("$mm1"=='09'){echo'selected="selected"';}?>>Septiembre</option>
                  <option value="10"<?php if("$mm1"=='10'){echo'selected="selected"';}?>>Octubre</option>
                  <option value="11"<?php if("$mm1"=='11'){echo'selected="selected"';}?>>Noviembre</option>
                  <option value="12"<?php if("$mm1"=='12'){echo'selected="selected"';}?>>Diciembre</option>
                </select>
                <select name="fi3" id="fi3">
                  <?php
				for($i=1;$i<=31;$i++)
					{
						if($i<10){$cnb="0";}else{$cnb="";}
						echo'<option value="'.$cnb.$i.'"';
						if($dd1==$i)
							{
								echo'selected="selected"';
							}
						echo'>'.$i.'</option>';
					}
				  ?>
                </select>
</div></td>
            </tr>
          <tr>
            <td nowrap="nowrap"><div align="left">HASTA
                <select name="ff1" id="ff1">
                  <?php
				for($i=2010;$i<=2020;$i++)
					{
						echo'<option value="'.$i.'"';
						if($aaaa2==$i)
							{
								echo'selected="selected"';
							}
						echo'>'.$i.'</option>';
					}
				  ?>
                </select>
                <select name="ff2" id="ff2">
                  <option value="01"<?php if("$mm2"=='01'){echo'selected="selected"';}?>>Enero</option>
                  <option value="02"<?php if("$mm2"=='02'){echo'selected="selected"';}?>>Febrero</option>
                  <option value="03"<?php if("$mm2"=='03'){echo'selected="selected"';}?>>Marzo</option>
                  <option value="04"<?php if("$mm2"=='04'){echo'selected="selected"';}?>>Abril</option>
                  <option value="05"<?php if("$mm2"=='05'){echo'selected="selected"';}?>>Mayo</option>
                  <option value="06"<?php if("$mm2"=='06'){echo'selected="selected"';}?>>Junio</option>
                  <option value="07"<?php if("$mm2"=='07'){echo'selected="selected"';}?>>Julio</option>
                  <option value="08"<?php if("$mm2"=='08'){echo'selected="selected"';}?>>Agosto</option>
                  <option value="09"<?php if("$mm2"=='09'){echo'selected="selected"';}?>>Septiembre</option>
                  <option value="10"<?php if("$mm2"=='10'){echo'selected="selected"';}?>>Octubre</option>
                  <option value="11"<?php if("$mm2"=='11'){echo'selected="selected"';}?>>Noviembre</option>
                  <option value="12"<?php if("$mm2"=='12'){echo'selected="selected"';}?>>Diciembre</option>
                </select>
                <select name="ff3" id="ff3">
                  <?php
				for($i=1;$i<=31;$i++)
					{
						if($i<10){$cnb="0";}else{$cnb="";}
						echo'<option value="'.$cnb.$i.'"';
						if($dd2==$i)
							{
								echo'selected="selected"';
							}
						echo'>'.$i.'</option>';
					}
				  ?>
                </select>
</div></td>
            </tr>
          
          <tr>
            <td height="10" nowrap="nowrap"><input name="FechaIni" type="hidden" id="FechaIni" />
              <input name="FechaFin" type="hidden" id="FechaFin" /></td>
          </tr>
          <tr>
            <td nowrap="nowrap"><div align="left">
              <input name="Enviar" type="button" id="Enviar" onclick="Valida()" value="Consultar" />
            </div></td>
            </tr>
          <tr>
            <td height="10" nowrap="nowrap">&nbsp;</td>
          </tr>
        </table>
          <p>&nbsp;</p>
          <p>&nbsp;</p>        </td>
      </tr>
      
      <tr>
        <td nowrap="nowrap" bgcolor="#EEEEEE">&nbsp;</td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>C&Eacute;DULA</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>NOMBRE PACIENTE </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>FECHA EGRESO </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>DETALLE EGRESO PACIENTE </strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>AUTORIZACI&Oacute;N </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>NOMBRE TRAMITANTE </strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>PAGADOR</strong></td>
      </tr>
	   <?php
 echo $cons="SELECT salud.servicios.cedula, central.terceros.primape, central.terceros.segape, central.terceros.primnom, central.terceros.segnom,salud.servicios.fechaegr,salud.ordenesmedicas.detalle, salud.servicios.usuegreso, central.usuarios.nombre,salud.servicios.autorizac1
 FROM salud.servicios
 INNER JOIN central.terceros on salud.servicios.cedula=central.terceros.identificacion
 INNER JOIN salud.ordenesmedicas on salud.servicios.numservicio=salud.ordenesmedicas.numservicio
 INNER JOIN central.usuarios on salud.servicios.usuegreso=central.usuarios.usuario
 WHERE salud.servicios.fechaegr  between '$FechaIni 00:00:00' and '$FechaFin 00:00:00'
 AND salud.servicios.tiposervicio = 'Hospitalizacion'
 AND salud.ordenesmedicas.tipoorden='Orden Egreso'
 AND salud.ordenesmedicas.fecha  between '$FechaIni 00:00:00' and '$FechaFin 00:00:00'
 ORDER BY salud.servicios.fechaegr";
 //echo $cons;
 
$res=ExQuery($cons);
$ln=1;
while($fila=ExFetch($res)){
	$fila[5]=substr("$fila[5]", -19,10);
	$cons2="SELECT numservicio FROM salud.servicios WHERE cedula='$fila[0]' AND estado='AC'";
	//echo "$cons2</br>";
	$res2=ExQuery($cons2);
	while($fila2=ExFetch($res2)){
	 	$cons3="SELECT entidad FROM salud.pagadorxservicios WHERE numservicio='$fila2[0]'";
		//echo "$cons3</br>";
		$res3=ExQuery($cons3);
		while($fila3=ExFetch($res3)){
			$cons4="SELECT primape FROM central.terceros WHERE identificacion='$fila3[0]'";
			//echo "$cons4</br>";
			$res4=ExQuery($cons4);
			$eps="";
			while($fila4=ExFetch($res4)){
				$eps=$eps."$fila4[0]\n";
			}
		}
	}
echo'<tr>
		<td nowrap="nowrap" height="10">'.$ln.'</td>
		<td nowrap="nowrap" height="10">'.$fila[0].'</td>
		<td nowrap="nowrap" height="10">'."$fila[1] $fila[2] $fila[3] $fila[4]".'</td>
        <td nowrap="nowrap" height="10">'.$fila[5].'</td>
        <td height="10">'.$fila[6].'</td>
        <td height="10">'.$fila[9].'</td>
		<td nowrap="nowrap" height="10">'.$fila[8].'</td>
		<td height="10">'.$eps.'</td>
      </tr>';
	  $ln++;
	  }
	  ?>
    </table>
  </div>
</form>
</body>
</html>
