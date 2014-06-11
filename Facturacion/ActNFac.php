<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();		
	if($Actualizar){
	   $update=false;
	   $consNF="UPDATE facturacion.detallefactura
			        SET vrtotal='0'
			        WHERE nofactura='$NoFacAct'";
       $resNF=ExQuery($consNF);sleep(10);
	   $consN="select nofactura from facturacion.detallefactura WHERE nofactura='$NoFacAct' limit 1";	
	   $resN=ExQuery($consN);
	   while($filaN=ExFetch($resN)){
	      if($filaN[0]==$NoFacAct){
	         $cons="UPDATE facturacion.detallefactura
			        SET nofactura='$NoFacNu'
			        WHERE nofactura='$NoFacAct'";	
	         $res=ExQuery($cons);
	         ?><script>alert("Se ha realizado la actualizacion con exito!!!");</script><?
	         $update=true;
	      }	
	   }
	   if(!$update){?><script>alert("El No. de Factura Actual no Existe!!!");</script><?}	
	}
?>
<html>
<head>
<script language="javascript">
    function Validar2()
	{
		if(document.FORMA.NoFacAct.value==""){ 
			alert("Debe Digitar un Numero de Factura Actual!!!"); return false; 
		}		
		if(document.FORMA.NoFacNu.value==""){ 
			alert("Debe Digitar un Numero de Factura Nueva!!!");  return false;
		}		
	}
	function Validar()
	{ 
		if(document.FORMA.NoFacAct.value==""){ 
			alert("Debe Digitar un Numero de Factura Actual!!!"); 
		}
		else{
			if(document.FORMA.NoFacNu.value==""){ 
				alert("Debe Digitar un Numero de Factura Nueva!!!"); 
			}
			else{								
					document.FORMA.Actualizar.value=1;
					document.FORMA.submit();					
			}
		}
	}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">   
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">	
	<tr align="center">
    	<td colspan="9" bgcolor="#e5e5e5" style="font-weight:bold">Actualizar</td>        
  	</tr> 
	<tr>    
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">No. Factura Actual</td>
        <td ><input type="text" name="NoFacAct" style="width:70px" value="<?echo $NoFacAct?>"></td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">No. Factura Nueva</td>
        <td><input type="text" name="NoFacNu" style="width:70px" value="<?echo $NoFacNu?>"></td>    
        <tr align="center">
    	<td colspan="9"><input type="button" value="Actualizar" onClick="Validar();"></td>        
    </tr>
</table>
<input type="hidden" name="Actualizar">
</form>    
</body>
</html>
