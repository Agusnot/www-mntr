<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($EtiqXML)
	{
		$cons="delete from historiaclinica.etiquetasxformatoxml where compania='$Compania[0]' and formato=$CodFormatoXML and etiqueta='$EtiqXML' and tag='$Tag'";
		$res=ExQuery($cons);
	}
	if($TagElim)
	{
		$cons="delete from historiaclinica.tagsxml where compania='$Compania[0]' and formato=$CodFormatoXML and tag='$TagElim'";
		$res=ExQuery($cons);
		$cons="delete from historiaclinica.etiquetasxformatoxml where compania='$Compania[0]' and formato=$CodFormatoXML and tag='$TagElim'";
		$res=ExQuery($cons);
	}	
	$cons="select tag,orden from historiaclinica.tagsxml where compania='$Compania[0]' and formato=$CodFormatoXML order by orden";
	$res=ExQuery($cons);	
	
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
	<table BORDER=1  style="font : normal normal small-caps 12px Tahoma;" border="1" bordercolor="#e5e5e5" cellpadding="3"> 
		<tr  bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    		<td>Formato: <? echo $NomFormatoXML?></td>           
      	</tr>
        <tr  bgcolor="#e5e5e5" style="font-weight:bold" align="center">
        	<td>tags</td>
        </tr>
        <tr>
        	<td align="center">
            	<select name="Tag" onChange="document.FORMA.submit()"><option></option>
			<?	while($fila=ExFetch($res))
                {
                 	if($Tag==$fila[0]){echo "<option value='$fila[0]' selected>$fila[0]</option>";}   
					else{echo "<option value='$fila[0]'>$fila[0]</option>";}
                }?> 
	            </select>	
          	<?	if($Tag)
				{ 	$cons="select orden from historiaclinica.tagsxml where compania='$Compania[0]' and formato=$CodFormatoXML and tag='$Tag'";
					$res=ExQuery($cons); $fila=ExFetch($res);
					echo "- Orden:$fila[0]";
				}?>
          	</td>
        </tr>	
    	 <tr align="center">
        	<td colspan="4">
            	<input type="button" value="Nuevo TAG" onClick="location.href='NewConfTagXML.php?DatNameSID=<? echo $DatNameSID?>&CodFormatoXML=<? echo $CodFormatoXML?>&NomFormatoXML=<? echo $NomFormatoXML?>&Tag=<? echo $Tag?>'">
         	<?	if($Tag){?>
            		<input type="button" value="Agregar Etiqueta" onClick="location.href='NewConfEtiqXML.php?DatNameSID=<? echo $DatNameSID?>&CodFormatoXML=<? echo $CodFormatoXML?>&NomFormatoXML=<? echo $NomFormatoXML?>&Tag=<? echo $Tag?>'">
                    <input type="button" value="Retirar TAG"  onClick="if(confirm('Esta seguro de elimiar este registro')){location.href='ConfEtiqXML.php?DatNameSID=<? echo $DatNameSID?>&CodFormatoXML=<? echo $CodFormatoXML?>&NomFormatoXML=<? echo $NomFormatoXML?>&TagElim=<? echo $Tag?>'}">
            <?	}?>
                <input type="button" value="Regresar" onClick="location.href='ConfXML.php?DatNameSID=<? echo $DatNameSID?>'">
            </td>
        </tr>
  	</table>
    <br>
    <br>
<?	if($Tag)
	{
		$cons="select etiqueta,formato,longitud,tipodato,obliga,descripcion,tag,orden from historiaclinica.etiquetasxformatoxml 
		where compania='$Compania[0]' and formato=$CodFormatoXML and tag='$Tag' order by etiqueta";
		$res=ExQuery($cons);?>
		<table BORDER=1  style="font : normal normal small-caps 12px Tahoma;" border="1" bordercolor="#e5e5e5" cellpadding="3"> 
        	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
            	<td>Etiqueta</td><td>Longitud</td><td>Tipo Dato</td><td>Obliga</td><td>Descripcion</td><td>Orden</td><td colspan="2"></td>
            </tr>	
        <?	while($fila=ExFetch($res))
			{?>
				<tr align='center' onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
					<td><? echo $fila[0]?></td><td><? echo $fila[2]?></td><td><? echo $fila[3]?></td><td><? echo $fila[4]?></td><td><? echo $fila[5]?>&nbsp;</td><td><? echo $fila[7]?></td>
					<td>	
						<img src="/Imgs/b_edit.png" title="Editar" style="cursor:hand" 
						onClick="location.href='NewConfEtiqXML.php?DatNameSID=<? echo $DatNameSID?>&EtiquetaXML=<? echo $fila[0]?>&CodFormatoXML=<? echo $CodFormatoXML?>&NomFormatoXML=<? echo $NomFormatoXML?>&Tag=<? echo $Tag?>'">
					</td>
					<td>
						<img src="/Imgs/b_drop.png" title="Elimiar"
						 onClick="if(confirm('Esta seguro de elimiar este registro')){location.href='ConfEtiqXML.php?DatNameSID=<? echo $DatNameSID?>&EtiqXML=<? echo $fila[0]?>&CodFormatoXML=<? echo $CodFormatoXML?>&NomFormatoXML=<? echo $NomFormatoXML?>&Tag=<? echo $Tag?>'}">
					</td> 	
				</tr>
		<?	}?>
        </table>	
<?	}?>    
	<input type="hidden" name="CodFormatoXML" value="<? echo $CodFormatoXML?>">
    <input type="hidden" name="NomFormatoXML" value="<? echo $NomFormatoXML?>">
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>    
</body>
</html>
