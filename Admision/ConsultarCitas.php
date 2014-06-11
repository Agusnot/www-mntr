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
<title>Consulta de Citas</title>
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
//alert('Fecha1: '+document.FORMA.FechaIni.value+' Fecha2: '+document.FORMA.FechaFin.value+'');
if((document.FORMA.FechaIni.value=="")||(document.FORMA.cedula.value=="")){
	alert("Debes seleccionar la fecha y la cedula!");
}
else{
	location.href='?DatNameSID=<? echo $DatNameSID?>&FechaIni='+document.FORMA.FechaIni.value+'&cedula='+document.FORMA.cedula.value+'';
}
}
//-->
</script>
</head>
<?php
		$FechaIni=$_GET['FechaIni'];
		//echo $FechaIni;
		
        if($FechaIni==NULL){
			if($ND[mon]<10){$C1="0";}else{$C1="";}
			if($ND[mday]<10){$C2="0";}else{$C2="";}			
			$FechaIni="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";
		}
		
		$aaaa1=substr("$FechaIni", -10,4);
		$mm1=substr("$FechaIni", -5,2);
		if($mm1<10){$mm1=str_replace("0","","$mm1");}					
		$dd1=substr("$FechaIni", -2);
		if($dd1<10){$dd1=str_replace("0","","$dd1");}
		//echo "$aaaa1,$mm1,$dd1";
		
		$cedula=$_GET['cedula'];
		if($cedula==NULL){$cedula="Ingrese No. Documento";}
	   	
?>
<body>
<form id="FORMA" name="FORMA" method="post" action="">
  <div align="center">
    <table width="200" border="0" cellpadding="5" cellspacing="5" bordercolor="#DDDDDD" background="../Imgs/Fondo.jpg" bgcolor="#FFFFFF">
      <tr>
        <td colspan="20" nowrap="nowrap"><table width="200" align="left" cellpadding="5">
          <tr>
            <td nowrap="nowrap"><div align="justify"><strong>CONSULTA DE CITAS   </strong></div></td>
            </tr>
          <tr>
            <td height="10" nowrap="nowrap"><div align="justify"></div></td>
            </tr>

          <tr>
            <td nowrap="nowrap" bgcolor="#DDDDDD"><div align="justify"><strong>FECHA DE CONSULTA </strong></div></td>
            </tr>
          <tr>
            <td nowrap="nowrap"><div align="justify">DESDE
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
                <input name="FechaIni" type="hidden" id="FechaIni" />
            </div></td>
            </tr>
          
          
          <tr>
            <td height="10" nowrap="nowrap" bgcolor="#DDDDDD">
              
                <div align="justify"><strong>N&Uacute;MERO DE C&Eacute;DULA </strong></div></td>
          </tr>
          
          <tr>
            <td nowrap="nowrap">
              
                <div align="justify">
                  <input name="cedula" type="text" id="cedula" value="<?php echo"$cedula"; ?>" size="40"/>
                  </div></td>
          </tr>
          <tr>
            <td nowrap="nowrap"><div align="justify">
              <input name="Enviar" type="button" id="Enviar" onclick="Valida()" value="CONSULTAR" />
            </div></td>
          </tr>
          
          <tr>
            <td height="10" nowrap="nowrap"><div align="justify"></div></td>
          </tr>
        </table>
          <p>&nbsp;</p>
          <p>&nbsp;</p>        </td>
      </tr>
      
      <tr>
        <td nowrap="nowrap" bgcolor="#EEEEEE">&nbsp;</td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>FECHA</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>HORA INICIO </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>C&Eacute;DULA PACIENTE   </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>NOMBRE PACIENTE  </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>ESTADO</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>CANCELADA POR </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>ORIGEN CANCELACI&Oacute;N</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>MOTIVO CANCELACI&Oacute;N</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>SOLICITADA POR</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>USUARIO CREA</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>FECHA CREA</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>USUARIO CANCELA</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>FECHA CANCELACI&Oacute;N</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>USUARIO CONFIRMA</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>FECHA CONFIRMACI&Oacute;N</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>OBSERVACI&Oacute;N CONFIRMACI&Oacute;N </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>CONFIRMADA POR </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>USUARIO ACTIVA </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>FECHA ACTIVACI&Oacute;N </strong></td>
      </tr>
	   
	   <?php
 $cons="SELECT fecha, hrsini, minsini, salud.agenda.cedula, primape, segape, primnom, segnom, estado, 
       nomcancelador, origencancel, motivocancel, solicitadapor,

       (select central.usuarios.nombre FROM salud.agenda INNER JOIN central.usuarios on central.usuarios.usuario=salud.agenda.usucrea 
       WHERE salud.agenda.cedula='$cedula' and fecha = '$FechaIni') AS usuario_crea, fechacrea,

       (select central.usuarios.nombre FROM salud.agenda INNER JOIN central.usuarios on central.usuarios.usuario=salud.agenda.usucancel
       WHERE salud.agenda.cedula='$cedula' and fecha = '$FechaIni') AS usuario_cancela, fechacancel,

       (select central.usuarios.nombre FROM salud.agenda INNER JOIN central.usuarios on central.usuarios.usuario=salud.agenda.usuconfirma
       WHERE salud.agenda.cedula='$cedula' and fecha = '$FechaIni') AS usuario_confirma, fechaconfirm,observacionconfirm, nomconfrim,
       
       (select central.usuarios.nombre FROM salud.agenda INNER JOIN central.usuarios on central.usuarios.usuario=salud.agenda.usuactiva
       WHERE salud.agenda.cedula='$cedula' and fecha = '$FechaIni') AS usuario_activa, fechaactiva
            
FROM salud.agenda INNER JOIN central.terceros
on central.terceros.identificacion=salud.agenda.cedula
WHERE salud.agenda.cedula='$cedula' and fecha = '$FechaIni'";
//echo $cons;
$res=ExQuery($cons);
$ln=1;
while($fila=ExFetch($res)){ ?>
<tr>
        <td nowrap="nowrap"><?php echo $ln; ?></td>
        <td nowrap="nowrap"><?php echo $fila[0]; ?></td>
        <td nowrap="nowrap"><?php echo $fila[1]; ?>:<?php echo $fila[2]; ?></td>
        <td nowrap="nowrap"><?php echo $fila[3]; ?></td>
        <td nowrap="nowrap"><?php echo $fila[4]; ?> <?php echo $fila[5]; ?> <?php echo $fila[6]; ?> <?php echo $fila[7]; ?></td>
        <td nowrap="nowrap"><?php echo $fila[8]; ?></td>
        <td nowrap="nowrap"><?php echo $fila[9]; ?></td>
		<td nowrap="nowrap"><?php echo $fila[10]; ?></td>
        <td nowrap="nowrap"><?php echo $fila[11]; ?></td>
        <td nowrap="nowrap"><?php echo $fila[12]; ?></td>
        <td nowrap="nowrap"><?php echo $fila[13]; ?></td>
        <td nowrap="nowrap"><?php echo $fila[14]; ?></td>
        <td nowrap="nowrap"><?php echo $fila[15]; ?></td>
        <td nowrap="nowrap"><?php echo $fila[16]; ?></td>
        <td nowrap="nowrap"><?php echo $fila[17]; ?></td>
        <td nowrap="nowrap"><?php echo $fila[18]; ?></td>
        <td nowrap="nowrap"><?php echo $fila[19]; ?></td>
        <td nowrap="nowrap"><?php echo $fila[20]; ?></td>
        <td nowrap="nowrap"><?php echo $fila[21]; ?></td>
        <td nowrap="nowrap"><?php echo $fila[22]; ?></td>
      </tr>
	  <?php }
	  ?>
    </table>
  </div>
</form>
</body>
</html>