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
<title>Medicamentos NO POS</title>
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
        <td colspan="49" nowrap="nowrap"><table width="200" align="left" cellpadding="5">
          <tr>
            <td nowrap="nowrap"><div align="left"><strong>MEDICAMENTOS NO POS </strong></div></td>
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
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>FECHA</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>HORA </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>USUARIO </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>CARGO </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>IDENTIFICACI&Oacute;N</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>NOMBRE PACIENTE </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>UNIDAD HOSPITALIZACI&Oacute;N  </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong># SERVICIO </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>ID DIAGN&Oacute;STICO  </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>DIAGN&Oacute;STICO </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>MEDICAMENTO</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>CONCENTRACI&Oacute;N </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>FORMA FARMAC&Eacute;UTICA  </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>V&Iacute;A ADMINISTRACI&Oacute;N  </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>DOSIS </strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>FRECUENCIA</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>TIEMPO</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>GRUPO</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>CANTIDAD</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>REG. INVIMA </strong></td>		
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>EXPERIMENTACI&Oacute;N?</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>AUTORIZADO</strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>RIESGO</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>EFECTO</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>TIEMPO RESPUESTA </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>EFECTOS SECUNDARIOS </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>ALTERNATIVA POS  #1 </strong></td>		
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>CONCENTRACI&Oacute;N #1 </strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>FORMA FARMAC&Eacute;UTICA #1 </strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>DOSIS</strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>TIEMPO UTILIZACI&Oacute;N </strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>ALTERNATIVA POS  #2 </strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>CONCENTRACI&Oacute;N #2 </strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>FORMA FARMAC&Eacute;UTICA #2</strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>DOSIS</strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>TIEMPO UTILIZACI&Oacute;N </strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>ALTERNATIVA POS  #3</strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>CONCENTRACI&Oacute;N #3</strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>FORMA FARMAC&Eacute;UTICA #3</strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>DOSIS</strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>TIEMPO UTILIZACI&Oacute;N </strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>RAZONES</strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>FECHA RECEPCI&Oacute;N </strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>CASO ASIGNADO </strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>OBSERVACIONES</strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>FECHA CTC </strong></td>
      </tr>
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
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td> 
      </tr>
	   <?php
 $cons="SELECT
	fecha,
	hora,
	usuario,
	cargo,
	cedula,
	primape,
	segape,
	primnom,
	segnom,
	unidadhosp,
	numservicio,dx1 as Diagnostico1,
	diagnostico,
	cmp00002 as Medicamento,
	cmp00018 as Concentracion,
	cmp00019 as FormaFarma,
	cmp00003 as Via_Admin,
	cmp00004 as Dosis,
	cmp00005 as Frecuencia,
	cmp00006 as Tiempo,
	cmp00007 as Grupo,
	cmp00008 as Cantidad,
	cmp00048 as RegInvima,
	cmp00010 as Experimentacion,
	cmp00011 as Autorizado,
	cmp00012 as Riesgo,
	cmp00013 as Efecto,
	cmp00014 as Tiempo_Rpta,
	cmp00015 as Efectos_secundarios, 
	cmp00022 as NombreGenerico1,
	cmp00023 as Concentracion1,
	cmp00024 as FormaFarma1,
	cmp00025 as Dosis1,
	cmp00026 as Tiempoutili1,cmp00027 as NombreGenerico2,
	cmp00028 as Concentracion2,
	cmp00029 as FormaFarma2,
	cmp00030 as Dosis2,
	cmp00031 as Tiempoutili2,
	cmp00032 as NombreGenerico3,
	cmp00033 as Concentracion3,
	cmp00034 as FormaFarma3,
	cmp00035 as Dosis3,
	cmp00036 as Tiempoutili3,
	cmp00037 as razones,
	cmp00041 as fecharecepdctos,
	cmp00042 as casoasignado,
	cmp00044 as observaciones,
	cmp00043 as fechaETC
FROM
	histoclinicafrms.tbl00049
	INNER JOIN 
	central.terceros
	ON
	identificacion=histoclinicafrms.tbl00049.cedula
	INNER JOIN
	salud.cie
	ON	
	codigo=histoclinicafrms.tbl00049.dx1
WHERE
	fecha between '$FechaIni' and '$FechaFin'
	order by fecha";
//echo $cons;
$res=ExQuery($cons);
$ln=1;
while($fila=ExFetch($res)){
echo'<tr>
		<td height="10">'.$ln.'</td>
        <td height="10">'.$fila[0].'</td>
        <td height="10">'.$fila[1].'</td>
        <td height="10">'.$fila[2].'</td>
        <td height="10">'.$fila[3].'</td>
        <td height="10">'.$fila[4].'</td>
        <td height="10">'.$fila[5].' '.$fila[6].' '.$fila[7].' '.$fila[8].'</td>
        <td height="10">'.$fila[9].'</td>
        <td height="10">'.$fila[10].'</td>
        <td height="10">'.$fila[11].'</td>
        <td height="10">'.$fila[12].'</td>
        <td height="10">'.$fila[13].'</td>
        <td height="10">'.$fila[14].'</td>
        <td height="10">'.$fila[15].'</td>
        <td height="10">'.$fila[16].'</td>
        <td height="10">'.$fila[17].'</td>
        <td height="10">'.$fila[18].'</td>
        <td height="10">'.$fila[19].'</td>
        <td height="10">'.$fila[20].'</td>
        <td height="10">'.$fila[21].'</td>
        <td height="10">'.$fila[22].'</td>
        <td height="10">'.$fila[23].'</td>
        <td height="10">'.$fila[24].'</td>
        <td height="10">'.$fila[25].'</td>
        <td height="10">'.$fila[26].'</td>
        <td height="10">'.$fila[27].'</td>
        <td height="10">'.$fila[28].'</td>
        <td height="10">'.$fila[29].'</td>
        <td height="10">'.$fila[30].'</td>
        <td height="10">'.$fila[31].'</td>
        <td height="10">'.$fila[32].'</td>
        <td height="10">'.$fila[33].'</td>
        <td height="10">'.$fila[34].'</td>
        <td height="10">'.$fila[35].'</td>
        <td height="10">'.$fila[36].'</td>
        <td height="10">'.$fila[37].'</td>
        <td height="10">'.$fila[38].'</td>
        <td height="10">'.$fila[39].'</td>
        <td height="10">'.$fila[40].'</td>
        <td height="10">'.$fila[41].'</td>
        <td height="10">'.$fila[42].'</td>
        <td height="10">'.$fila[43].'</td>
        <td height="10">'.$fila[44].'</td>
        <td height="10">'.$fila[45].'</td>
        <td height="10">'.$fila[46].'</td>
        <td height="10">'.$fila[47].'</td>
        <td height="10">'.$fila[48].'</td>
      </tr>';
	  $ln++;
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
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
  </div>
</form>
</body>
</html>