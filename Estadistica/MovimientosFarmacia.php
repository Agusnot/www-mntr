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
<title>Movimientos Farmacia</title>
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
document.FORMA.FechaIni.value=''+document.FORMA.fia.value+'-'+document.FORMA.fi2.value+'-'+document.FORMA.fi3.value+' '+document.FORMA.fi4.value+':'+document.FORMA.fi5.value+':00';
document.FORMA.FechaFin.value=''+document.FORMA.fia.value+'-'+document.FORMA.ff2.value+'-'+document.FORMA.ff3.value+' '+document.FORMA.ff4.value+':'+document.FORMA.ff5.value+':59';
//alert('Fecha 1: '+document.FORMA.FechaIni.value+' Fecha 2: '+document.FORMA.FechaFin.value+'');
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
			if($ND[hours]<10){$C3="0";}else{$C3="";}	
			if($ND[minutes]<10){$C4="0";}else{$C4="";}		
			$FechaIni="$ND[year]-$C1$ND[mon]-$C2$ND[mday] $C3$ND[hours]:$C4$ND[minutes]:00";
		}
		
		if($FechaFin==NULL){
			if($ND[mon]<10){$C1="0";}else{$C1="";}
			if($ND[mday]<10){$C2="0";}else{$C2="";}
			if($ND[hours]<10){$C3="0";}else{$C3="";}	
			if($ND[minutes]<10){$C4="0";}else{$C4="";}
			$FechaFin="$ND[year]-$C1$ND[mon]-$C2$ND[mday] $C3$ND[hours]:$C4$ND[minutes]:59";
		}
		
		$aaaa1=substr("$FechaIni", -19,4);
		$mm1=substr("$FechaIni", -14,2);
		if($mm1<10){$mm1=str_replace("0","","$mm1");}					
		$dd1=substr("$FechaIni", -11,2);
		if($dd1<10){$dd1=str_replace("0","","$dd1");}
		$hh1=substr("$FechaIni", -8,2);
		if($hh1<10){$hh1=str_replace("0","","$hh1");}
		$mmm1=substr("$FechaIni", -5,2);
		if($mmm1<10){$mmm1=str_replace("0","","$mmm1");}
		//echo "$aaaa1-$mm1-$dd1 $hh1:$mmm1:00 </br>";
		
		$aaaa2=substr("$FechaFin", -19,4);
		$mm2=substr("$FechaFin", -14,2);
		if($mm2<10){$mm2=str_replace("0","","$mm2");}					
		$dd2=substr("$FechaFin", -11,2);
		if($dd2<10){$dd2=str_replace("0","","$dd2");}
		$hh2=substr("$FechaFin", -8,2);
		if($hh2<10){$hh2=str_replace("0","","$hh2");}
		$mmm2=substr("$FechaFin", -5,2);
		if($mmm2<10){$mmm2=str_replace("0","","$mmm2");}
		//echo "$aaaa2-$mm2-$dd2 $hh2:$mmm2:59 </br>";
?>
<body>
<form id="FORMA" name="FORMA" method="post" action="">
  <div align="center">
    <table width="200" border="1" cellpadding="5" bordercolor="#EEEEEE" background="../Imgs/Fondo.jpg" bgcolor="#FFFFFF">
      <tr>
        <td colspan="11" nowrap="nowrap"><table width="200" align="left" cellpadding="5">
          <tr>
            <td nowrap="nowrap"><div align="left"><strong>MOVIMIENTO FARMACIA  </strong></div></td>
            </tr>
          <tr>
            <td height="10" nowrap="nowrap">&nbsp;</td>
            </tr>

          <tr>
            <td nowrap="nowrap" bgcolor="#DDDDDD"><div align="left"><strong>FECHA DE CONSULTA </strong></div></td>
            </tr>
          <tr>
            <td nowrap="nowrap"><div align="left">A&Ntilde;O: 
              <select name="fia" id="fia">
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
            </div></td>
          </tr>
          <tr>
            <td nowrap="nowrap"><div align="left"><strong>FECHA INICIAL:</strong> 
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
              
              <strong>HORA FECHA INICIAL:</strong> 
              <select name="fi4" id="fi4">
              <?php
				for($i=0;$i<=23;$i++)
					{
						if($i<10){$cnb="0";}else{$cnb="";}
						echo'<option value="'.$cnb.$i.'"';
						if($hh1==$i)
							{
								echo'selected="selected"';
							}
						echo'>'.$cnb.$i.'</option>';
					}
				  ?>
            </select>
            HH:
            <select name="fi5" id="fi5">
              <?php
				for($i=0;$i<=59;$i++)
					{
						if($i<10){$cnb="0";}else{$cnb="";}
						echo'<option value="'.$cnb.$i.'"';
						if($mmm1==$i)
							{
								echo'selected="selected"';
							}
						echo'>'.$cnb.$i.'</option>';
					}
				  ?>
            </select>
            MM
            </div></td>
          </tr>
          <tr>
            <td nowrap="nowrap"><div align="left"><strong>FECHA FINAL:</strong>
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
                <strong>HORA FECHA FINAL</strong> 
                <select name="ff4" id="ff4">
                  <?php
				for($i=0;$i<=23;$i++)
					{
						if($i<10){$cnb="0";}else{$cnb="";}
						echo'<option value="'.$cnb.$i.'"';
						if($hh2==$i)
							{
								echo'selected="selected"';
							}
						echo'>'.$cnb.$i.'</option>';
					}
				  ?>
                </select>
HH:
<select name="ff5" id="ff5">
  <?php
				for($i=0;$i<=59;$i++)
					{
						if($i<10){$cnb="0";}else{$cnb="";}
						echo'<option value="'.$cnb.$i.'"';
						if($mmm2==$i)
							{
								echo'selected="selected"';
							}
						echo'>'.$cnb.$i.'</option>';
					}
				  ?>
</select>
MM
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
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>ALMAC&Eacute;N </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>TIPO COMPROBANTE </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>N&Uacute;MERO</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>C&Eacute;DULA</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>DETALLE</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>NOMBRE PRODUCTO </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>UNIDAD MEDIDA </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>PRESENTACION</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>USUARIO</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>FECHA CREACI&Oacute;N </strong></td>
      </tr>
	   <?php
 $cons="SELECT 
	consumo.movimiento.almacenppal,
	consumo.movimiento.tipocomprobante,
	consumo.movimiento.numero,
	consumo.movimiento.cedula,
	consumo.movimiento.detalle,
	consumo.codproductos.nombreprod1,
	consumo.codproductos.unidadmedida,
	consumo.codproductos.presentacion,
	consumo.movimiento.usuariocre,
	consumo.movimiento.fechacre
FROM
	consumo.movimiento
INNER JOIN
	salud.pacientesxpabellones
	ON consumo.movimiento.cedula=salud.pacientesxpabellones.cedula
INNER JOIN
	consumo.codproductos
	ON consumo.codproductos.autoid=consumo.movimiento.autoid
	AND consumo.codproductos.almacenppal=consumo.movimiento.almacenppal
	WHERE consumo.movimiento.fechacre BETWEEN '$FechaIni' and '$FechaFin'
	AND salud.pacientesxpabellones.estado='AC'
	AND consumo.codproductos.anio='$aaaa1'";
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
        <td height="10">'.$fila[5].'</td>
        <td height="10">'.$fila[6].'</td>
        <td height="10">'.$fila[7].'</td>
        <td height="10">'.$fila[8].'</td>
        <td height="10">'.$fila[9].'</td>
      </tr>';
	  $ln++;
	  }
	  ?>
    </table>	
  </div>
</form>
</body>
</html>
