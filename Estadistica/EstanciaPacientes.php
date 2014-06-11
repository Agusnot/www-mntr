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
<title>D&iacute;as de Estancia de Pacientes</title>
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
						location.href='?DatNameSID=<? echo $DatNameSID?>&FechaIni='+document.FORMA.FechaIni.value+'&FechaFin='+document.FORMA.FechaFin.value+'&eps='+document.FORMA.eps.value+'';
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
			//echo$FechaIni="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";
			$FechaIni="$ND[year]-01-01";
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
		
	   $eps=$_GET['eps'];
	   
	   //echo "El valor de EPS es: $eps";
	   
	   $qp1="and eps = '$eps'";
	   if($eps==""){$qp1="";}
	   
	   //echo "El valor de QP1 es: $qp1";
	   	
?>
<body>
<form id="FORMA" name="FORMA" method="post" action="">
  <div align="center">
    <table width="200" border="1" cellpadding="5" bordercolor="#EEEEEE" background="../Imgs/Fondo.jpg" bgcolor="#FFFFFF">
      <tr>
        <td colspan="7" nowrap="nowrap"><table width="200" align="left" cellpadding="5">
          <tr>
            <td colspan="2" nowrap="nowrap"><div align="left"><strong>D&Iacute;AS DE ESTANCIA DE PACIENTES </strong></div></td>
            </tr>
          <tr>
            <td height="10" colspan="2" nowrap="nowrap">&nbsp;</td>
            </tr>

          <tr>
            <td nowrap="nowrap" bgcolor="#DDDDDD"><div align="left"><strong>FECHA DE CONSULTA </strong></div></td>
            <td nowrap="nowrap" bgcolor="#DDDDDD"><div align="left"><strong>ENTIDAD PROMOTORA DE SALUD </strong></div></td>
            </tr>
          <tr>
            <td nowrap="nowrap"><div align="left">DESDE
                <select name="fi1" id="fi1">
                  <?php
				for($i=1994;$i<=2020;$i++)
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
            <td nowrap="nowrap"><div align="left">
              <select name="eps" id="eps">
                <option value="" <?php if($eps==0){echo'selected="selected"';} ?> >TODOS</option>
                <?php
				  $consp1="select identificacion,primape from central.terceros where tipo ='Asegurador' order by primape";
				  $resp1=ExQuery($consp1);
				  while($filap1=ExFetch($resp1)){
				  echo'<option value="'.$filap1[0].'"';
				  if("$eps"=="$filap1[0]"){echo'selected="selected"';}
				  echo'>'.$filap1[1].'</option>';
				  }
				  ?>
              </select>
            </div></td>
            </tr>
          <tr>
            <td nowrap="nowrap"><div align="left">HASTA
                <select name="ff1" id="ff1">
                  <?php
				for($i=1994;$i<=2020;$i++)
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
            <td nowrap="nowrap"><div align="left">
              <input name="FechaIni" type="hidden" id="FechaIni" />
              <input name="FechaFin" type="hidden" id="FechaFin" />
            </div></td>
            </tr>
          
          <tr>
            <td height="10" colspan="2" nowrap="nowrap">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" nowrap="nowrap"><div align="left">
              <input name="Enviar" type="button" id="Enviar" onclick="Valida()" value="Consultar" />
            </div></td>
            </tr>
          <tr>
            <td height="10" colspan="2" nowrap="nowrap">&nbsp;</td>
          </tr>
        </table>
          <p>&nbsp;</p>
          <p>&nbsp;</p>        </td>
      </tr>
      
      <tr>
        <td nowrap="nowrap" bgcolor="#EEEEEE">&nbsp;</td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>IDENTIFICACI&Oacute;N</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>NOMBRE PACIENTE  </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>FECHA INGRESO  </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>PABELL&Oacute;N DE INGRESO </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>ENTIDAD PROMOTORA DE SALUD </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>D&Iacute;AS DE ESTANCIA  </strong></td>
      </tr>
	   <?php
$ln=1;
/*$cons="SELECT salud.servicios.cedula,salud.servicios.fechaing, pabellon,salud.servicios.numservicio 
FROM salud.servicios INNER JOIN salud.pacientesxpabellones ON salud.servicios.numservicio=salud.pacientesxpabellones.numservicio 
WHERE fechaing >= '$FechaIni' and fechaing <= '$FechaFin' and salud.servicios.estado='AC' 
and salud.pacientesxpabellones.estado='AC' AND salud.servicios.tiposervicio='Hospitalizacion' order by fechaing desc";*/
$cons="SELECT salud.servicios.cedula,salud.servicios.fechaing, pabellon,salud.servicios.numservicio 
FROM salud.servicios INNER JOIN salud.pacientesxpabellones ON salud.servicios.numservicio=salud.pacientesxpabellones.numservicio 
WHERE fechaing >= '$FechaIni' and fechaing <= '$FechaFin' AND salud.servicios.tiposervicio='Hospitalizacion' order by fechaing,salud.servicios.cedula desc";
//echo "$cons </br></br>";
$res=ExQuery($cons);
while($fila=ExFetch($res)){
	$cons1="SELECT primnom,segnom,primape,segape,eps FROM central.terceros WHERE identificacion='$fila[0]' $qp1";
	//echo "$cons1 </br></br>";
	$res1=ExQuery($cons1);
	while($fila1=Exfetch($res1)){
		$cons2="SELECT primape FROM central.terceros WHERE identificacion='$fila1[4]'";
		//echo "$cons2 </br></br>";
		$res2=ExQuery($cons2);
		while($fila2=ExFetch($res2)){
		
			$aaaa3=substr("$fila[1]", -19,4);
			$mm3=substr("$fila[1]", -14,2);
			if($mm3<10){$mm3=str_replace("0","","$mm3");}
			$dd3=substr("$fila[1]", -11,2);
			if($dd3<10){$dd3=str_replace("0","","$dd3");}
			//echo "$aaaa3,$mm3,$dd3  </br></br>";
			
			//$nbd=getdate();
			//if($nbd[mon]<10){$C1="0";}else{$C1="";}
			//if($nbd[mday]<10){$C2="0";}else{$C2="";}
			//echo"$nbd[year]-$C1$nbd[mon]-$C2$nbd[mday] </br></br>";
			
			$ano1 = $aaaa3; 
			$mes1 = $mm3; 
			$dia1 = $dd3; 
			$ano2 = $aaaa2; 
			$mes2 = $mm2; 
			$dia2 = $dd2; 
			$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
			$timestamp2 = mktime(4,12,0,$mes2,$dia2,$ano2); 
			$segundos_diferencia = $timestamp1 - $timestamp2; 
			$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
			$dias_diferencia = abs($dias_diferencia); 
			$dias_diferencia = floor($dias_diferencia); 
			
			echo'<tr>
					<td height="10">'.$ln.'</td>
					<td height="10">'.$fila[0].'</td>
					<td height="10">'."$fila1[0] $fila1[1] $fila1[2] $fila1[3]".'</td>
					<td height="10">'."$aaaa3-$mm3-$dd3".'</td>
					<td height="10">'.$fila[2].'</td>
					<td height="10">'.$fila2[0].'</td>
					<td height="10">'."$dias_diferencia".'</td>
				</tr>';
			$ln++;								
		}		
	}
}
	  ?>
    </table>
  </div>
</form>
</body>
</html>
