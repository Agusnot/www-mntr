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
<title>Registro de Medicamentos</title>
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
    <table width="200" border="1" cellpadding="5" bordercolor="#DDDDDD" background="../Imgs/Fondo.jpg" bgcolor="#FFFFFF">
      <tr>
        <td colspan="8" nowrap="nowrap"><table width="200" align="left" cellpadding="5">
          <tr>
            <td nowrap="nowrap"><div align="left"><strong>CONSULTA DE REGISTRO DE MEDICAMENTOS</strong></div></td>
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
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>C&Eacute;DULA </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>NOMBRE PACIENTE  </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>MEDICAMENTO </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>CANTIDAD DESPACHADA </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>CANTIDAD REGISTRADA </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>DIFERENCIA </strong></td>
      </tr>
	   <?php
 echo $cons1="select cedula from salud.servicios where tiposervicio='Hospitalizacion' and estado='AC'";
//echo $cons;
$res1=ExQuery($cons1);
$ln=1;
while($fila1=ExFetch($res1)){
	echo'<tr>';
	$cons2="select primnom,segnom,primape,segape from central.terceros where identificacion='$fila1[0]'";
	$res2=ExQuery($cons2);
	while($fila2=ExFetch($res2)){
		$cons3="select autoid from consumo.movimiento where cedula='$fila1[0]' and fecha between '$FechaIni' and '$FechaFin'";
		$res3=ExQuery($cons3);
		while($fila3=ExFetch($res3)){
			echo'<td height="10">'.$ln.'</td>';
			echo'<td height="10">'.$fila1[0].'</td>';
			echo'<td height="10">'."$fila2[0]"." "."$fila2[1]"." "."$fila2[2]"." "."$fila2[3]".'</td>';
			$cons4="select nombreprod1,unidadmedida from consumo.codproductos where autoid='$fila3[0]' and almacenppal='FARMACIA'";
			$res4=ExQuery($cons4);
			$fila4=ExFetch($res4);
			echo'<td height="10">'."$fila4[0] $fila4[1]".'</td>';
			$cons5="select sum(cantidad) from consumo.movimiento where cedula='$fila1[0]' and autoid='$fila3[0]' and fecha between '$FechaIni' and '$FechaFin'";
			$res5=ExQuery($cons5);
			$fila5=ExFetch($res5);
			echo'<td height="10">'.$fila5[0].'</td>';
			$cons6="select sum(cantidad) from salud.registromedicamentos where cedula='$fila1[0]' and autoid='$fila3[0]' and fechacre between '$FechaIni' and '$FechaFin'";
			$res6=ExQuery($cons6);
			$fila6=ExFetch($res6);
			echo'<td height="10">'.$fila6[0].'</td>';
			$diferencia=$fila5[0]-$fila6[0];
			echo'<td height="10">'.$diferencia.'</td>';
			echo'</tr>';	$ln++;
	}}
	
}
	  ?>
    </table>
  </div>
</form>
</body>
</html>