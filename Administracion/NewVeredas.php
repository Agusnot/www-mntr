<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	require_once("xajax/xajax.inc.php");
	include("Funciones.php");
	
	if($Guardar){
		if(!$Edit){
			$cons="insert into central.veredas (departamento,municipio,codvereda,vereda) values ('$Dept','$Muni','$CodVereda','". strtoupper($NomVereda)."')";	
			
		}
		else{
			$cons="update central.veredas set departamento='$Dept',municipio='$Muni',codvereda='$CodVereda',vereda='". strtoupper($NomVereda)."'
			where departamento='$DeptAnt' and municipio='$MuniAnt' and codvereda='$CodVeredaAnt' and vereda='$NomVeredaAnt'";
		}
			//echo $cons;
			$res=ExQuery($cons);
		?><script language="javascript">location.href="Veredas.php?DatNameSID=<? echo $DatNameSID?>";</script><?
	}
	
	function BucaMpo($Dpto){
		$cons="select municipio,codmpo from central.municipios where departamento='$Dpto' order by codmpo";
		$res=ExQuery($cons);
		$cont=0;
		while($fila=ExFetch($res)){
			$Mpos[$cont]=array($fila[0],$fila[1]);
			$cont++;
		}
		return $Mpos;
	}
	function ActualizaMpo($Dpto){
		$Mpos=BucaMpo($Dpto);	
		$respuesta=new xajaxResponse();				
		//$respuesta->addScript("alert('Respondo!');");
		$contenido="<option value='hol'>Hola</option>";		
		for($i=0; $i<count($Mpos); $i++){
			// Crea una etiqueta option dentro del segundo select,
			$respuesta->addCreate("Muni", "option", "option".$i);
			// Le da una value al option con el nombre de la ciudad,
			$respuesta->addAssign("option".$i, "value", $Mpos[$i][1]);
			// Y dentro de la etiqueta también le pone la ciudad.
			$respuesta->addAssign("option".$i, "innerHTML", $Mpos[$i][0]);			
		}			
		return $respuesta->getXML();
	}
	//ActualizaMpo($Dept);
	$obj=new xajax();
	$obj->registerFunction("ActualizaMpo");
	$obj->processRequests();	
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<? $obj->printJavascript("/xajax");?>
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function Validar(){
		if(document.FORMA.Dept.value==""){alert("Debe seleccionar un departamento!!!");return false;}
		if(document.FORMA.CodVereda.value==""){alert("Debe digitar el codigo de la vereda!!!");return false;}
		if(document.FORMA.NomVereda.value==""){alert("Debe digitar el nombre de la verda!!!");return false;}
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2"> 		
    <tr>
    	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Departamento</td>
        <td>
        	<select name="Dept" onChange="xajax_ActualizaMpo(this.value)"><option></option>
      	<?	$cons="select departamento,codigo from central.departamentos order by departamento";
			$res=ExQuery($cons);
			while($fila=ExFetch($res)){
				if($Dept==$fila[1]){
					echo "<option value='$fila[1]' selected>$fila[0]</option>";
				}
				else{
					echo "<option value='$fila[1]'>$fila[0]</option>";
				}
			}?>
           	</select>
        </td>
    </tr>    
    <tr>
    	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Municipio</td>
        <td>
        	<select name="Muni" id="Muni" >
      	<?	$cons="select municipio,codmpo from central.municipios where departamento='$Dept' order by codmpo";
			$res=ExQuery($cons);
			while($fila=ExFetch($res)){
				if($Muni==$fila[1]){
					echo "<option value='$fila[1]' selected>$fila[0]</option>";
				}
				else{
					echo "<option value='$fila[1]'>$fila[0]</option>";
				}
			}?>
           	</select>
        </td>
    </tr>    
    <tr>
    	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Codigo Vereda</td>
        <td><input type="text" name="CodVereda" maxlength="5" style="width:50" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" onFocus="xNumero(this)" value="<? echo $CodVereda?>"/></td>
	</td>        
    <tr>
    	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Nombre Vereda</td>
        <td><input type="text" name="NomVereda" style="width:350" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" onFocus="xLetra(this)" value="<? echo $NomVereda?>"/></td>
	</td>        
    <tr align="center">
    	<td colspan="12">
        	<input type="submit" value="Guardar" name="Guardar"/>
        	<input type="button" value="Cancelar" onClick="location.href='Veredas.php?DatNameSID=<? echo $DatNameSID?>'"/>
      	</td>
    </tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="DeptAnt" value="<? echo $Dept?>">
<input type="hidden" name="MuniAnt" value="<? echo $Muni?>">
<input type="hidden" name="CodVeredaAnt" value="<? echo $CodVereda?>">
<input type="hidden" name="NomVeredaAnt" value="<? echo $NomVereda?>">

</form>
</body>
</html>
