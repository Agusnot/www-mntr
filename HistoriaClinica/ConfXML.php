<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	if($CodXML)
	{
		$cons="delete from historiaclinica.formatosxml where compania='$Compania[0]' and codigoxml=$CodXML";
		$res=ExQuery($cons);
		$cons="delete from historiaclinica.tagsxml where compania='$Compania[0]' and formato=$CodXML";
		$res=ExQuery($cons);
		$cons="delete from historiaclinica.etiquetasxformatoxml where compania='$Compania[0]' and formato=$CodXML";
		$res=ExQuery($cons);
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
	<table BORDER=1  style="font : normal normal small-caps 12px Tahoma;" border="1" bordercolor="#e5e5e5" cellpadding="3"> 
		<tr  bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    		<td>Formatos XML</td><td colspan="3"></td>
	  	</tr>
   	<?	$cons="select formatoxml,codigoxml from historiaclinica.formatosxml where compania='$Compania[0]'";
		$res=ExQuery($cons);
		while($fila=ExFetch($res)){?>
	        <tr lign='center' onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
    	    	<td><? echo $fila[0]?></td>
                <td><img src="/Imgs/s_process.png" title="Cofiguar Etiquetas del formato"
                	onClick="location.href='ConfEtiqXML.php?DatNameSID=<? echo $DatNameSID?>&CodFormatoXML=<? echo $fila[1]?>&NomFormatoXML=<? echo $fila[0]?>'">
              	</td>
                <td>	
                	<img src="/Imgs/b_edit.png" title="Editar" style="cursor:hand" 
                	onClick="location.href='NewConfXML.php?DatNameSID=<? echo $DatNameSID?>&CodXML=<? echo $fila[1]?>&EtiquetaXML=<? echo $fila[0]?>'">
              	</td>
                <td>
                	<img src="/Imgs/b_drop.png" title="Elimiar"
                	 onClick="if(confirm('Esta seguro de elimiar este registro')){location.href='ConfXML.php?DatNameSID=<? echo $DatNameSID?>&CodXML=<? echo $fila[1]?>'}">
              	</td>
	        </tr>
	<?	}?>            
        <tr align="center">
        	<td colspan="4"><input type="button" value="Nuevo" onClick="location.href='NewConfXML.php?DatNameSID=<? echo $DatNameSID?>'"></td>
        </tr>
    </table>
</form>    
</body>
</html>
