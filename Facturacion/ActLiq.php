<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();	
	if($Actualizar){
	   $upLiqDes=$upLiqHas=false;
	   $consLiqDes="select noliquidacion from facturacion.liquidacion WHERE noliquidacion='$NoLiqDes' limit 1";	
	   $resLiqDes=ExQuery($consLiqDes);
	   while($filaLiqDes=ExFetch($resLiqDes))
	      if($filaLiqDes[0]==$NoLiqDes)
	         $upLiqDes=true;
	   $consLiqHas="select noliquidacion from facturacion.liquidacion WHERE noliquidacion='$NoLiqHas' limit 1";	
	   $resLiqHas=ExQuery($consLiqHas);
	   while($filaLiqHas=ExFetch($resLiqHas))
	      if($filaLiqHas[0]==$NoLiqHas)
	         $upLiqHas=true;
	   if(!$upLiqDes){?><script>alert("El No. de Liquidacion Desde no Existe!!!");</script><?}
	   if(!$upLiqHas){?><script>alert("El No. de Liquidacion Hasta no Existe!!!");</script><?}
	   if(!$upLiqDes&&!$upLiqHas){?><script>alert("Ningun No. de Liquidacion Existe!!!");</script><?}
	   if($upLiqDes&&$upLiqHas){
	       $cons="UPDATE facturacion.liquidacion
			        SET nofactura='$NoFac'
			        WHERE noliquidacion BETWEEN '$NoLiqDes' AND '$NoLiqHas'";	
	         $res=ExQuery($cons);
	       ?><script>alert("Se ha realizado la actualizacion con exito!!!");</script><?
	       }
	}
?>
<html>
<head>
<script language="javascript">
    function Validar2()
	{
		if(document.FORMA.NoFac.value==""){ 
			alert("Debe Digitar un Numero de Factura!!!"); 
		}
		if(document.FORMA.NoLiqDes.value==""){ 
			alert("Debe Digitar El Numero de Liquidacion Inicial!!!"); return false; 
		}		
		if(document.FORMA.NoLiqHas.value==""){ 
			alert("Debe Digitar El Numero de Liquidacion Final!!!");  return false;
		}		
	}
	function Validar()
	{ 
		if(document.FORMA.NoFac.value==""){ 
			alert("Debe Digitar un Numero de Factura!!!"); 
		}
		else{
		    if(document.FORMA.NoLiqDes.value==""){ 
			alert("Debe Digitar El Numero de liquidacion Inicial!!!"); 
		    }
			else if(document.FORMA.NoLiqHas.value==""){ 
				alert("Debe Digitar El Numero de Liquidacion Final!!!"); 
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
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">No. Factura</td>
        <td ><input type="text" name="NoFac" style="width:70px" value="<?echo $NoFac?>"></td>
	</tr>
	<tr>    
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Desde No. Liquidaci&oacute;n</td>
        <td ><input type="text" name="NoLiqDes" style="width:70px" value="<?echo $NoLiqDes?>"></td></tr>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Hasta No. Liquidaci&oacute;n </td>
        <td><input type="text" name="NoLiqHas" style="width:70px" value="<?echo $NoLiqHas?>"></td>    
        <tr align="center">
    	<td colspan="9"><input type="button" value="Actualizar" onClick="Validar();"></td>        
    </tr>
</table>
<input type="hidden" name="Actualizar">
</form>    
</body>
</html>
