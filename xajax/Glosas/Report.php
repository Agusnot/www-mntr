<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function CerrarThis()
	{
		for (i=0;i<parent.document.FORMA.elements.length;i++){
			if(parent.document.FORMA.elements[i].type == "checkbox"){
				parent.document.FORMA.elements[i].disabled = false;
			} 
		}
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">

        <input type="button" value=" X " onClick="parent.document.getElementById('Fac'+<? echo $NoFac?>).checked=false;CerrarThis()" style="position:absolute;top:1px;right:1px;" 
title="Cerrar esta ventana">


<table width="613" border="0.5" align="center" cellpadding="2" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;'>
  <tr>
    <td width="88" height="118" rowspan="2" align="center"><img src="../Imgs/Logo.jpg" width="88" height="78"></td>
    <td colspan="2" rowspan="2" align="center" >
    <?
	echo "<font size='4' >".$Compania[0]."</font>";
	echo "<br>CODIGO ".$Compania[17]."";
	echo "<br>".$Compania[1]."</br>";
	echo "".$Compania[2]." - ";
	echo " TELEFONOS ".$Compania[3]."";
	
	?>    
    
    </td>
    <td width="44" height="78"   >
    <div align="center">
    <? 
	 $co= "SELECT numero,nufactura FROM  facturacion.respuestaglosa where nufactura='$NoFac'";
	$r= ExQuery($co);	
	$fi=ExFetch($r);
	 ?>  
	<br>GLOSA NUMERO</br><font color='#000000' size='6' > <? echo $fi[0] ?></font>	 
	</div>
    </td>
  </tr>
  <tr>
    <td height="18" align="center" style="font-weight:bold" >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" align="center">
    
    <font size="4">FORMATO DE RESPUESTA DE GLOSA</font>
    <br></br>
    <?
	$conex="SELECT  primape,contrato,nocontrato FROM  facturacion.facturascredito ,central.terceros where facturascredito.compania='$Compania[0]' AND nofactura='$NoFac' and terceros.compania='$Compania[0]' and entidad= identificacion";
     $re=ExQuery($conex);
	 while($fila = ExFetch($re)){		
		
		echo " <b>ENTIDAD:</b> ".$fila[0]."  <br>"; 
		echo "<b>CONTRATO :</b>".$fila[1]."<br>";
		echo "<b>N° CONTRATO:</b> ".$fila[2];
	 }	
	?>
    <br></br>  
    <div align="left" >  
    <font size="2" style="font-weight:bold" >FACTURA N° </font> <? echo $NoFac ?>    
    </div>    
    </td>
  </tr>
  <tr>
  <td colspan="9" bgcolor="#e5e5e5" style="font-weight:bold">
  <table width="647"  border="0.5" align="center" cellpadding="2" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;'>  
  <tr>
  <td width="14" align="center" bgcolor="#e5e5e5" style="font-weight:bold">N°</td>
  <td width="103" align="center" bgcolor="#e5e5e5" style="font-weight:bold" >Tipo Glosa </td>
  <td width="72" align="center" bgcolor="#e5e5e5" style="font-weight:bold">vrglosa </td>
  <td width="100" align="center" bgcolor="#e5e5e5" style="font-weight:bold">fechaglosa </td>
  <td width="146" align="center" bgcolor="#e5e5e5" style="font-weight:bold">Valor Pagar IPS </td>
  <td width="174" align="center" bgcolor="#e5e5e5" style="font-weight:bold">Valor Glosa Aceptado </td>

  </tr>
  
  <?
 $con4="SELECT tipoglosa,vrglosa,fechaglosa,pagaipsglosa,aceptaglosa,pagarips FROM facturacion.motivoglosa where compania='$Compania[0]' AND nufactura='$NoFac'";
 $res4= ExQuery($con4);
while($fil=ExFetch($res4))
{	?>     
  <tr >
  <td align="center"><img src="../Imgs/down.gif" width="13" height="13"></td>
  <td align="center" > <? echo $fil[0]?></td>
  <td align="center"> <? echo $fil[1]?></td>
  <td align="center"> <? echo $fil[2]?></td>
  <td align="center"> <? echo $fil[3]?></td>
  <td align="center"> <? echo $fil[4]?></td> 
  </tr>   
   <? }   ?> 
  </table> 
  </td>
  </tr>
  <tr>
  <td height="30" colspan="7"></td> 
  </tr>  
 <tr>
   <td colspan="7">
    <? $cons= "SELECT * FROM  facturacion.respuestaglosa where compania='$Compania[0]' and nufactura='$NoFac'";
	$res= ExQuery($cons);	
	while($fila=ExFetch($res))	
	{	 			
	?>  
  <b> Valor Factura:  </b><? echo $fila[2];?><br></br>
  <b>Valor Glosado: </b><? echo $fila[3];?> <br></br>
  <b> Fecha:  </b><? echo $fila[4];?> <br></br>
  <b>Valor Pagar IPS:</b>   <? echo $fila[7];?><br></br>
  <b>Valor Glosa Aceptada:</b>    <? echo $fila[8];?><br></br>
  <b>Valor Pagar Ep segun IPS:</b>    <? echo $fila[9];?>
      </td>
  </tr>
  <? 
   }
	?>
  <tr>
    <td height="102" colspan="9"><div align="center">
      <p><?
	  $conexion="SELECT nombre,cedula FROM facturacion.firmasrtaglosas where compania='$Compania[0]' ";
	  $respuesta= ExQuery($conexion);
	  $fiz=ExFetch($respuesta);
		 	 
	  
  	if (file_exists($_SERVER['DOCUMENT_ROOT']."/Firmas/$fiz[1].GIF")){?>
      	
		<img src="/Firmas/<? echo $fiz[1]?>.GIF" width="158" height="63"><?
	} 
      echo "<br>_____________________________";
       echo "<br>".$fiz[0];
		  	
     ?>    
    </div>    </td>
  </tr>
</table>  
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</body>
</html>
