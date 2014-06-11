<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Cancelar){
	?><script language="javascript">
        	location.href = "PlanesServicio.php?DatNameSID=<? echo $DatNameSID?>&Clase=<? echo $Clase?>&Guardado=0&Autoid=<? echo $AutoId?>";
        </script><?
	}
	if($Guardar)
	{		
		$cons = "Select AutoId from ContratacionSalud.PlaneServicios where Compania = '$Compania[0]' order by AutoId desc";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		$AutoId = $fila[0] +1;
		$cons = "Insert into ContratacionSalud.PlaneServicios (AutoId,NombrePlan,Clase,Compania) 
		values ('$AutoId','$Nombreplan','$Clase','$Compania[0]')";
		$res = ExQuery($cons);
		if($TraerDe){
			if($Clase=="Medicamentos")
			{
				$cons="select codigo,reqvobo,facturable,minimos,maximos,almacenppal from contratacionsalud.medsxplanservic where compania='$Compania[0]' and autoid='$TraerDe'";
				$res=ExQuery($cons);
				while($fila=ExFetch($res))
				{
					$cons2="insert into contratacionsalud.medsxplanservic (codigo,reqvobo,facturable,minimos,maximos,almacenppal,compania,autoid) values
					('$fila[0]',$fila[1],$fila[2],$fila[3],$fila[4],'$fila[5]','$Compania[0]',$AutoId)";
					$res2=ExQuery($cons2);
				}
			}
			else
			{
				$cons="select cup,reqvobo,facturable,minimos,maximos,clase from  contratacionsalud.cupsxplanservic where compania='$Compania[0]' and autoid=$TraerDe";
				//echo $cons;
				$res=ExQuery($cons);
				while($fila=ExFetch($res)){
					$cons2="insert into contratacionsalud.cupsxplanservic (cup,reqvobo,facturable,minimos,maximos,clase,compania,autoid) values
					('$fila[0]',$fila[1],$fila[2],$fila[3],$fila[4],'$fila[5]','$Compania[0]',$AutoId)";
					$res2=ExQuery($cons2);
				}			
			}
		}
		?><script language="javascript">
        	location.href = "PlanesServicio.php?DatNameSID=<? echo $DatNameSID?>&Clase=<? echo $Clase?>&Guardado=1&Autoid=<? echo $AutoId?>";
        </script><?
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
	function Validar()
	{
		if(!<? echo $Cancelar?>){
			if(document.FORMA.Nombreplan.value=="")
			{
				alert("Debe ingresar un Nombre!!!");return false;
			}
		}
	}
</script>
<script language='javascript' src="/Funciones.js"></script>

</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table cellpadding="4"  border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>">
	<tr>
    	<td  bgcolor="#e5e5e5" align="center" style="font-weight:bold">Nombre</td><td><input type="text" name="Nombreplan" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"/></td>
    </tr>
    <tr>
    	<td  bgcolor="#e5e5e5" align="center" style="font-weight:bold">Tipo</td><td><input type="text" name="Clase" readonly value="<? echo $Clase?>"> 
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" align="center" style="font-weight:bold">Traer de:</td>
        <td><select name="TraerDe" style="width:100%"><option></option>
        	<? $cons = "Select NombrePlan,AutoId from ContratacionSalud.PlaneServicios where Clase='$Clase' and Compania='$Compania[0]' order by NombrePlan";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				echo "<option value='$fila[1]'>$fila[0]</option>";
			}
			?>
        </select></td>
    </tr>
    <tr>
    	<td align="center" colspan="2">
        	<input type="submit" name="Guardar" value="Guardar" /><input type="submit" name="Cancelar" value="Cancelar">
        </td>
    </tr>
</table>

</form>  
</body>
</html>
