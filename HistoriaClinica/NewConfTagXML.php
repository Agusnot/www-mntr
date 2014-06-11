<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	if($Guardar)
	{
		$TagXML=strtoupper($TagXML);
		$cons="insert into historiaclinica.tagsxml (formato,tag,orden,compania) values ($CodFormatoXML,'$TagXML',$Orden,'$Compania[0]')";
		$res=ExQuery($cons);?>
		<script language="javascript">
			location.href='ConfEtiqXML.php?DatNameSID=<? echo $DatNameSID?>&CodFormatoXML=<? echo $CodFormatoXML?>&NomFormatoXML=<? echo $NomFormatoXML?>&Tag=<? echo $TagXML?>';
		</script>	
<?	}
	if(!$Orden){
		$cons="select orden from historiaclinica.tagsxml where compania='$Compania[0]' and formato=$CodFormatoXML order by orden desc";
		$res=ExQuery($cons); $fila=ExFetch($res);
		$Orden=$fila[0]+1;
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.TagXML.value==""){alert("Debe digitar el nombre del nuevo TAG XML!!!");return false;}
		if(document.FORMA.Orden.value==""){alert("Debe digitar el orden del nuevo TAG XML!!!");return false;}
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
	<table BORDER=1  style="font : normal normal small-caps 12px Tahoma;" border="1" bordercolor="#e5e5e5" cellpadding="3"> 		
         <tr align="center">
         	<td bgcolor="#e5e5e5" style="font-weight:bold">tag XML</td>
         </tr>
         <tr>         	
    		<td><input type="text" name="TagXML" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" maxlength="99" value="<? echo $TagXML?>"></td>
	  	</tr>
        <tr align="center">
        	<td bgcolor="#e5e5e5" style="font-weight:bold">Orden</td>
   		</tr>
        <tr align="center">  
            <td><input type="text" name="Orden" onKeyDown="xNumero(this)" onKeyPress="xNumero(this)" onKeyUp="xNumero(this)" maxlength="4" value="<? echo $Orden?>" style=" width:40"></td>
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
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<body>
</body>
</html>
