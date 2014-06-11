<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();	
	
	
	
?>
<html >
<head>


</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()"> 
<table width="365" border="2" align="center" cellpadding="2" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;'>
   <tr lign="center"  bgcolor="#e5e5e5" style="font-weight:bold">
   <td colspan="3" align="center">OFICIOS DISPONIBLES</td>   
   </tr>
   <tr >
    <td width="124">Numero De Oficio</td> 	 
     <td width="221"> Numero de facturas</td>        
   </tr>
   <?
   $cons="SELECT numeroinforme FROM facturacion.informerespuesglosa WHERE compania='$Compania[0]' GROUP BY numeroinforme ";
   $res= ExQuery($cons);
   while($filas=ExFetch($res)){	             
    
	 ?>
     <tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
<td align='center' style="cursor:hand" title="Ver" onClick="open('RespuestaGlosaphp.php?DatNameSID=<? echo $DatNameSID?>&NoFac=<? echo $filas[0]?>','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES')">
<? echo $filas[0]?>        
       </td>
<td>		   
<? 
	   $Codigo=$filas[0];	  
	   $cons1="SELECT nufactura FROM facturacion.informerespuesglosa where numeroinforme='$Codigo'";	   
	   $res1=ExQuery($cons1);
	   while($row=ExFetch($res1)){
	     echo "&nbsp;-&nbsp;".$row[0];
	                            }  
	   
?>
</td>  				
   <?  }  ?>   
   </tr>  
  </table>
  <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
  <input type="hidden" name="Guardar" value="">
  </form>  
</body>
</html>
