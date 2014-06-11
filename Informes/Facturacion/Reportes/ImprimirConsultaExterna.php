<?php
if($DatNameSID){session_name("$DatNameSID");}
if($DatNameSID==NULL){$Compania[0]="Clinica San Juan de Dios";}
session_start();
include("../../../Funciones.php");
$ND=getdate();
		$FechaIni=$_GET['FechaIni'];
		//echo $FechaIni;
		$FechaFin=$_GET['FechaFin'];
		//echo $FechaFin;
		
        if($FechaIni==NULL){
			$ND2=$ND[mon]-1;
			if($ND[mon]==1){$ND2=12;}
			if($ND2<10){$C1="0";}else{$C1="";}
			if($ND[mday]<10){$C2="0";}else{$C2="";}			
			$FechaIni="$ND[year]-$C1$ND2-$C2$ND[mday]";
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
		
	  
	  $pagador=$_GET['pagador'];
	  $qp="and pagador='$pagador'";
	  if(($pagador=="0")||($pagador==NULL)){$qp="and pagador is not null";}
	  
	  $tipousu=$_GET['tipousu'];
	  $qt="and tipousu='$tipousu'";
	  if(($tipousu=="0")||($tipousu==NULL)){$qt="and tipousu is not null";}
	  
	  $ambito=$_GET['ambito'];
	  $qa="and ambito='$ambito'";
	  if(($ambito=="0")||($ambito==NULL)){$qa="and ambito is not null";}	  
		
$cons="select nombre,nit,codsgsss,direccion,telefonos from central.compania where nombre='$Compania[0]'";
//echo $cons;
$res=ExQuery($cons); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Consolidado por Consulta Externa</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #333333;
}
select {
background-color:transparent;
border-color:transparent;
font-family:Verdana, Arial, Helvetica, sans-serif;
font-size:10px;
color:#333333;
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
						location.href='?DatNameSID=<? echo $DatNameSID?>&FechaIni='+document.FORMA.FechaIni.value+'&FechaFin='+document.FORMA.FechaFin.value+'&pagador='+document.FORMA.pagador.value+'&tipousu='+document.FORMA.tipousu.value+'';
					}
			}
	}
}
//-->
</script>
</head>
<body>

<div align="center">
  <?php
	while($fila=ExFetch($res)){ ?>
</div>
  <div align="center">
  <form id="FORMA" name="FORMA" method="post" action="">
	<center>
	</center>
		<table width="100%">
          <tr>
            <td width="25%"><div align="left"><img src="../../../Imgs/Logo.png" width="200" height="150" /></div></td>
            <td width="50%">              <div align="center"><strong><? echo"$fila[0]"; ?></strong><br />
              Hermanos Hospitalarios de San Juan de Dios<br />
              Nit: <? echo"$fila[1]"; ?><br />
              Codigo SGSSS <? echo"$fila[2]"; ?><br />
              <? echo"$fila[3]"; ?>
              </div>
              <p align="center">CLIENTE:
                <?php
		if($pagador=="0"){echo"TODOS LOS CLIENTES";}
		$cons2="select primape,identificacion from central.terceros where tipo='Asegurador' and identificacion='$pagador'";
		$res2=ExQuery($cons2);
		while($fila2=ExFetch($res2)){?>
                  <?php echo $fila2[0]; ?>
                  <?php			
		}
		?>
                <br />
                <? if($pagador!=0){echo"NIT: $pagador";} ?>
              </p>
              <p align="center"><strong>
                <? if($ambito!="0"){echo"Consolidado por $ambito";} ?>
                </strong><br />
                <br />
                Periodo Comprendido entre el <?php echo $dd1; ?> de
  <?php switch($mm1){case 1:echo"Enero";break;case 2:echo"Febrero";break;case 3:echo"Marzo";break;case 4:echo"Abril";break;case 5:echo"Mayo";break;case 6:echo"Junio";break;case 7:echo"Julio";break;case 8:echo"Agosto";break;case 9:echo"Septiembre";break;case 10:echo"Octubre";break;case 11:echo"Noviembre";break;case 12:echo"Diciembre";break;} ?>
                de <?php echo $aaaa1; ?> al <?php echo $dd2; ?> de
  <?php switch($mm2){case 1:echo"Enero";break;case 2:echo"Febrero";break;case 3:echo"Marzo";break;case 4:echo"Abril";break;case 5:echo"Mayo";break;case 6:echo"Junio";break;case 7:echo"Julio";break;case 8:echo"Agosto";break;case 9:echo"Septiembre";break;case 10:echo"Octubre";break;case 11:echo"Noviembre";break;case 12:echo"Diciembre";break;} ?>
                de <?php echo $aaaa2; ?> <br />
                Para tipo de usuario :
  <?php if($tipousu=="0"){echo"TODOS";}else{echo $tipousu;}?>
  <br />
  <br />
  <strong> Fecha de   Expedici&oacute;n: <?php echo "$ND[mday]"; ?> de
  <?php switch($ND[mon]){case 1:echo"Enero";break;case 2:echo"Febrero";break;case 3:echo"Marzo";break;case 4:echo"Abril";break;case 5:echo"Mayo";break;case 6:echo"Junio";break;case 7:echo"Julio";break;case 8:echo"Agosto";break;case 9:echo"Septiembre";break;case 10:echo"Octubre";break;case 11:echo"Noviembre";break;case 12:echo"Diciembre";break;} ?>
    de <?php echo"$ND[year]";?></strong></p></td>
            <td width="25%">&nbsp;</td>
          </tr>
        </table>
		<p>&nbsp;</p>
    <table border="0" cellpadding="5">
      <tr>
        <td nowrap="nowrap" bgcolor="#CCCCCC">NO.</td>
        <td width="10" nowrap="nowrap" bgcolor="#CCCCCC">&nbsp;</td>
        <td nowrap="nowrap" bgcolor="#CCCCCC">FACTURA</td>
        <td width="10" nowrap="nowrap" bgcolor="#CCCCCC">&nbsp;</td>
        <td nowrap="nowrap" bgcolor="#CCCCCC">PACIENTE</td>
        <td width="10" nowrap="nowrap" bgcolor="#CCCCCC">&nbsp;</td>
        <td nowrap="nowrap" bgcolor="#CCCCCC">IDENTIFICACI&Oacute;N</td>
        <td width="10" nowrap="nowrap" bgcolor="#CCCCCC">&nbsp;</td>
		<td nowrap="nowrap" bgcolor="#CCCCCC">ENTIDAD</td>
        <td width="10" nowrap="nowrap" bgcolor="#CCCCCC">&nbsp;</td>
		<td nowrap="nowrap" bgcolor="#CCCCCC">TIPO USUARIO</td>
        <td width="10" nowrap="nowrap" bgcolor="#CCCCCC">&nbsp;</td>
		<td nowrap="nowrap" bgcolor="#CCCCCC">PROCESO</td>
        <td width="10" nowrap="nowrap" bgcolor="#CCCCCC">&nbsp;</td>
		<td nowrap="nowrap" bgcolor="#CCCCCC">FECHA INGRESO </td>
        <td width="10" nowrap="nowrap" bgcolor="#CCCCCC">&nbsp;</td>
        <td nowrap="nowrap" bgcolor="#CCCCCC">FECHA EGRESO </td>
        <td width="10" nowrap="nowrap" bgcolor="#CCCCCC">&nbsp;</td>
        <td nowrap="nowrap" bgcolor="#CCCCCC">VALOR FACTURA </td>
        <td width="10" nowrap="nowrap" bgcolor="#CCCCCC">&nbsp;</td>
        <td nowrap="nowrap" bgcolor="#CCCCCC">VALOR COPAGO</td>
        <td width="10" nowrap="nowrap" bgcolor="#CCCCCC">&nbsp;</td>
        <td nowrap="nowrap" bgcolor="#CCCCCC">VALOR DESCUENTO</td>
        <td width="10" nowrap="nowrap" bgcolor="#CCCCCC">&nbsp;</td>
        <td nowrap="nowrap" bgcolor="#CCCCCC">VALOR TOTAL</td>
      </tr>
      <?php 
	   $i=1;
	   $c1=0;
	   $c2=0;
	   $c3=0;
	   $c4=0;
	  $cons4="select nofactura,cedula,fechaini,fechafin,ambito,tipousu,pagador from facturacion.liquidacion where fechafin between '$FechaIni' and '$FechaFin' and nofactura is not null $qp $qt $qa
	  group by nofactura,cedula,fechaini,fechafin,ambito,tipousu,pagador
	  order by nofactura";
	  //echo $cons4;
	  $res4=ExQuery($cons4);
	  while($fila4=ExFetch($res4)){
	  	$cons5="select primnom,segnom,primape,segape from central.terceros where identificacion='$fila4[1]'";
		//echo $cons5;
		$res5=ExQuery($cons5);
		while($fila5=ExFetch($res5)){
			$cons6="select subtotal,copago,descuento,total from facturacion.facturascredito where nofactura='$fila4[0]'";
			//echo $cons6;
			$res6=ExQuery($cons6);
			while($fila6=ExFetch($res6)){
			$cons8="select primape from central.terceros where identificacion='$fila4[6]'"; 
			$res8=ExQuery($cons8); 
			while ($fila8=ExFetch($res8)){
		?>
      <tr>
        <td nowrap="nowrap"><?php echo $i; ?></td>
        <td width="10" nowrap="nowrap">&nbsp;</td>
        <td nowrap="nowrap"><?php echo $fila4[0]; ?></td>
        <td width="10" nowrap="nowrap">&nbsp;</td>
    	<td nowrap="nowrap"><?php echo "$fila5[0] $fila5[1] $fila5[2] $fila5[3]"; ?></td>
        <td width="10" nowrap="nowrap">&nbsp;</td>
		<td nowrap="nowrap"><?php echo $fila4[1]; ?></td>
        <td width="10" nowrap="nowrap">&nbsp;</td>
		<td nowrap="nowrap"><?php echo $fila8[0]; ?></td>
        <td width="10" nowrap="nowrap">&nbsp;</td>
		<td nowrap="nowrap"><?php echo $fila4[5]; ?></td>
        <td width="10" nowrap="nowrap">&nbsp;</td>
		<td nowrap="nowrap"><?php echo $fila4[4]; ?></td>
        <td width="10" nowrap="nowrap">&nbsp;</td>
        <td nowrap="nowrap"><?php echo $fila4[2]; ?></td>
        <td width="10" nowrap="nowrap">&nbsp;</td>
        <td nowrap="nowrap"><?php echo $fila4[3]; ?></td>
        <td width="10" nowrap="nowrap">&nbsp;</td>
        <td nowrap="nowrap">$<?php echo number_format($fila6[0],2); $c1=$c1+$fila6[0];?></td>
        <td width="10" nowrap="nowrap">&nbsp;</td>
        <td nowrap="nowrap">$<?php echo number_format($fila6[1],2); $c2=$c2+$fila6[1];?></td>
        <td width="10" nowrap="nowrap">&nbsp;</td>
        <td nowrap="nowrap">$<?php echo number_format($fila6[2],2); $c3=$c3+$fila6[2];?></td>
        <td width="10" nowrap="nowrap">&nbsp;</td>
        <td nowrap="nowrap">$<?php echo number_format($fila6[3],2); $c4=$c4+$fila6[3];?></td>
      </tr>
      <?php $i++;} }} }?>
	  <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td><strong>TOTALES</strong></td><td>&nbsp;</td>
	  <td nowrap="nowrap"><div align="center"><strong>$<?php echo number_format($c1,2) ?></strong></div></td><td>&nbsp;</td><td nowrap="nowrap"><div align="center"><strong>$<?php echo number_format($c2,2) ?></strong></div></td><td>&nbsp;</td>
	  <td nowrap="nowrap"><div align="center"><strong>$<?php echo number_format($c3,2) ?></strong></div></td><td>&nbsp;</td><td nowrap="nowrap"><div align="center"><strong>$<?php echo number_format($c4,2) ?></strong></div></td><td>&nbsp;</td>

    </table>
    <p>&nbsp;</p>
  </form>
</div>
	
		
	    <?php }
?>

</body>
</html>
