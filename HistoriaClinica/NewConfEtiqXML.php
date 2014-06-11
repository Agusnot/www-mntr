<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	if($Guardar)
	{
		if($Edit)
		{
			$cons="update historiaclinica.etiquetasxformatoxml set etiqueta='$EtiquetaXML',longitud=$LogEtiq,tipodato='$TipoDato',obliga='$Obligatorio',descripcion='$Descripcion'
			,tag='$Tag',orden=$Orden	where compania='$Compania[0]' and formato=$CodFormatoXML and etiqueta='$EtiquetaXMLAnt'";
		}
		else
		{				
			$cons="insert into historiaclinica.etiquetasxformatoxml (formato,etiqueta,longitud,tipodato,obliga,descripcion,tag,orden,compania) values
			($CodFormatoXML,'$EtiquetaXML',$LogEtiq,'$TipoDato','$Obligatorio','$Descripcion','$Tag',$Orden,'$Compania[0]')";	
		}
		$res=ExQuery($cons);?>
		<script language="javascript">
			location.href='ConfEtiqXML.php?DatNameSID=<? echo $DatNameSID?>&CodFormatoXML=<? echo $CodFormatoXML?>&NomFormatoXML=<? echo $NomFormatoXML?>&Tag=<? echo $Tag?>';
		</script>
<?	}	
	if($EtiquetaXML)
	{
		$cons="select longitud,tipodato,obliga,descripcion,orden from historiaclinica.etiquetasxformatoxml 
		where compania='$Compania[0]' and formato=$CodFormatoXML and tag='$Tag' and etiqueta='$EtiquetaXML'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$LogEtiq=$fila[0]; $TipoDato=$fila[1]; $Obligatorio=$fila[2]; $Descripcion=$fila[3]; $Orden=$fila[4];		
	}
	if(!$Orden){
		$cons="select orden from historiaclinica.etiquetasxformatoxml 
		where compania='$Compania[0]' and formato=$CodFormatoXML and tag='$Tag' order by orden desc";
		//echo $cons;
		$res=ExQuery($cons);
		$fila=ExFetch($res); $Orden=$fila[0]+1;		
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.EtiquetaXML.value==""){alert("Debe digitar el nombre de la nueva etiqueta XML!!!");return false;}
		if(document.FORMA.LogEtiq.value==""){alert("Debe digitar la longitud de la nueva etiqueta XML!!!");return false;}		
		if(document.FORMA.Orden.value==""){alert("Debe digitar el orden de la nueva etiqueta XML!!!");return false;}
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg" onLoad="document.FORMA.EtiquetaXML.focus()">
<form name="FORMA" method="post" onSubmit="return Validar()">
	<table BORDER=1  style="font : normal normal small-caps 12px Tahoma;" border="1" bordercolor="#e5e5e5" cellpadding="3"> 		
         <tr>
         	<td bgcolor="#e5e5e5" style="font-weight:bold">Etiqueta XML</td>
    		<td><input type="text" name="EtiquetaXML" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" maxlength="99" value="<? echo $EtiquetaXML?>"></td>
	  	</tr>
        <tr>
        	<td bgcolor="#e5e5e5" style="font-weight:bold">Longitud</td>
            <td><input type="text" name="LogEtiq" onKeyDown="xNumero(this)" onKeyPress="xNumero(this)" onKeyUp="xNumero(this)" value="<? echo $LogEtiq?>" maxlength="5"></td>
        </tr>
        <tr>
        	<td bgcolor="#e5e5e5" style="font-weight:bold">Tipo Dato</td>
        	<td>
            	<select name="TipoDato">
                	<option value="Cadena" <? if($TipoDato=="Cadena"){?> selected<? }?>>Cadena</option>
                    <option value="Numero" <? if($TipoDato=="Numero"){?> selected<? }?>>Numero</option>
                </select>
            </td>
        </tr>
        <tr>
        	<td bgcolor="#e5e5e5" style="font-weight:bold">Obligatorio</td>
        	<td>
            	<select name="Obligatorio">
                	<option value="Si" <? if($Obligatorio=="Si"){?> selected<? }?>>Si</option>
                    <option value="No" <? if($Obligatorio=="No"){?> selected<? }?>>No</option>
                </select>
            </td>
        </tr>
        <tr>
        	<td bgcolor="#e5e5e5" style="font-weight:bold">Descripcion</td>
        	<td>
            	<textarea name="Descripcion" cols="30" rows="5"><? echo $Descripcion?></textarea>
            </td>
        </tr>
        <tr>
	        <td bgcolor="#e5e5e5" style="font-weight:bold">Orden</td>
            <td><input type="text" name="Orden" onKeyDown="xNumero(this)" onKeyPress="xNumero(this)" onKeyUp="xNumero(this)" value="<? echo $Orden?>" maxlength="4" style="width:40"></td>
      	</tr>
        <tr align="center">
        	<td>
            	<input type="submit" value="Guardar" name="Guardar">
                <input type="button" value="Cancelar" 
                onClick="location.href='ConfEtiqXML.php?DatNameSID=<? echo $DatNameSID?>&CodFormatoXML=<? echo $CodFormatoXML?>&NomFormatoXML=<? echo $NomFormatoXML?>&Tag=<? echo $Tag?>'">
            </td>
        </tr>
  	</table>
    <input type="hidden" name="CodFormatoXML" value="<? echo $CodFormatoXML?>">
    <input type="hidden" name="NomFormatoXML" value="<? echo $NomFormatoXML?>">
    <input type="hidden" name="EtiquetaXMLAnt" value="<? echo $EtiquetaXML?>">
    <input type="hidden" name="Edit" value="<? echo $EtiquetaXML?>"
    <input type="hidden" name="Tag" value="<? echo $Tag?>">
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>    
</body>
</html>
