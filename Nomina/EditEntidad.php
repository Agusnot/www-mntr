<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
//	echo $CodArp;
	if($Guardar)
	{
		$cons="select codigo from nomina.idcompania where identificacion='$Arp' and codigo='$CodArp' and actividadeconomica='$CodActEco'";
		$res=ExQuery($cons);					
		if(ExNumRows($res)==0)
		{			
			$cons="update nomina.idcompania set claseaportante='$Aportante',naturajuridica='$NatJuri',tipopersona='$TipoPer',actividadeconomica='$CodActEco' where compania='$Compania[0]' and identificacion='$Arp' and codigo='$CodArp'";
        //echo $cons;
		$res=ExQuery($cons);
		?><!--<script language="javascript">location.href="DatosEntidad.php?DatNameSID=<? echo $DatNameSID?>";</script>--><?
		}
		else
		{
			?><script language="javascript">alert("La Entidad ya Existe !!!");</script><?
			?><script language="javascript">location.href="DatosEntidad.php?DatNameSID=<? echo $DatNameSID?>";</script><?		
		}		
	}
	if($Editar)
	{
		$cons="select * from nomina.idcompania where identificacion='$Arp' and codigo='$CodArp'";
	//	echo $cons;
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		if(!$Aportante)$Aportante=$fila[3];
		if(!$NatJuri)$NatJuri=$fila[4];
		if(!$TipoPer)$TipoPer=$fila[5];
		if(!$CodActEco)$CodActEco=$fila[6];
		$CodAntEco=$fila[6];
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
function Validar()
{
	if(document.FORMA.Arp.value==""){alert("Por favor Seleccione la ARP !!!");return false;}
	if(document.FORMA.CodArp.value==""){alert("Por favor Ingrese el codigo de la ARP !!!");return false;}
	if(document.FORMA.Aportante.value==""){alert("Por favor Seleccione el tipo de Aportante !!!");return false;}
	if(document.FORMA.NatJuri.value==""){alert("Por favor Seleccione la Naturaleza Juridica !!!");return false;}
	if(document.FORMA.TipoPer.value==""){alert("Por favor Seleccione el Tipo de Persona !!!");return false;}
	if(document.FORMA.CodActEco.value==""){alert("Por favor ingrese el Codigo de la Actividad Economica !!!");return false;}
}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
<tr>
	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">ARP</td>
    <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Codigo</td>
</tr>
<tr>
	<td><select name="Arp" style="width:300px;" onChange="FORMA.submit();" disabled>
            <option ></option>
                    <?
                    $cons = "select identificacion,primape from central.terceros where tipo='Asegurador' and compania='$Compania[0]' order by primape";
                    $resultado = ExQuery($cons);
                    while ($fila = ExFetch($resultado))
                    {                        
						 if($fila[0]==$Arp)
						 {echo "<option value='$fila[0]' selected>$fila[1]</option>"; }
						 else{echo "<option value='$fila[0]'>$fila[1]</option>";}						 
                    }
				?>
            </select>
     </td>
     <td><input type="text" name="CodArp" size="44" value="<? echo $CodArp?>" readonly/></td>
</tr>
<tr>
	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Clase Aportante</td>
    <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Naturaleza Jurica</td>
</tr>
<tr>
	<td><select name="Aportante" style="width:300px;" onChange="FORMA.submit();">
            <option ></option>
                    <?
                    $cons = "select codigo,aportante from nomina.aportantes";
                    $resultado = ExQuery($cons);
                    while ($fila = ExFetch($resultado))
                    {                        
						 if($fila[0]==$Aportante)
						 {echo "<option value='$fila[0]' selected> $fila[1] </option>"; }
						 else{echo "<option value='$fila[0]'> $fila[1] </option>";}						 
                    }
				?>
            </select>
    </td>
	<td><select name="NatJuri" style="width:300px;" onChange="FORMA.submit();">
            <option ></option>
                    <?
                    $cons = "select codigo,naturaleza from nomina.natujuridica order by codigo";
                    $resultado = ExQuery($cons);
                    while ($fila = ExFetch($resultado))
                    {                        
						 if($fila[0]==$NatJuri)
						 {echo "<option value='$fila[0]' selected>$fila[1]</option>"; }
						 else{echo "<option value='$fila[0]'>$fila[1]</option>";}						 
                    }
				?>
            </select>
     </td>
</tr>
<tr>
	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Tipo de Persona</td>
    <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Codigo Actividad Economica</td>
</tr>
<tr>
	<td><select name="TipoPer" style="width:300px;" onChange="FORMA.submit();">
            <option ></option>
                    <?
                    $cons = "select codigo,tipo from central.tipospersonas order by codigo";
                    $resultado = ExQuery($cons);
                    while ($fila = ExFetch($resultado))
                    {                        
						 if($fila[0]==$TipoPer)
						 {echo "<option value='$fila[0]' selected>$fila[1]</option>"; }
						 else{echo "<option value='$fila[0]'>$fila[1]</option>";}						 
                    }
				?>
            </select>
     </td>
     <td><input type="text" name="CodActEco" size="44" value="<? echo $CodActEco?>"/></td>
</tr>
</table>
<center><input type="submit" value="Guardar" name="Guardar"><input type="button" value="Cancelar" name="Cancelar" onClick="location.href='DatosEntidad.php?DatNameSID=<? echo $DatNameSID?>';"></center>
</form>
</body>
</html>