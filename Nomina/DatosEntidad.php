<?php
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");	
//$Editar=0;
if($Guardar)
{
	$cons="select identificacion,codigo,claseaportante,naturajuridica,tipopersona,actividadeconomica from nomina.idcompania ";
	$res=ExQuery($cons);
	if(ExNumRows($res)==0)
	{
		$cons="insert into nomina.idcompania(compania,identificacion,codigo,claseaportante,naturajuridica,tipopersona,actividadeconomica)
		values ('$Compania[0]','$Arp','$CodArp','$Aportante','$NatJuri','$TipoPer','$CodActEco')";
		$res=ExQuery($cons);
//		echo $cons;
	}	
	else
	{
		$cons="update nomina.idcompania set identificacion='$Arp',codigo='$CodArp', claseaportante='$Aportante', naturajuridica='$NatJuri', Tipopersona='$TipoPer',
		actividadeconomica='$CodActEco' where compania='$Compania[0]' and identificacion='$ArpAnt' and codigo='$CodArpAnt'";
		$res=ExQuery($cons);
	}
}
$cons="select identificacion,codigo,claseaportante,naturajuridica,tipopersona,actividadeconomica from nomina.idcompania where compania='$Compania[0]' ";
$res=ExQuery($cons);
$cont=ExNumRows($res);
$fila=ExFetch($res);
if(!$Arp){$Arp=$fila[0];};
if(!$ArpAnt){$ArpAnt=$fila[0];};
if(!$CodArp){$CodArp=$fila[1];};
if(!$CodArpAnt){$CodArpAnt=$fila[1];};
if(!$Aportante){$Aportante=$fila[2];};
if(!$NatJuri){$NatJuri=$fila[3];};
if(!$TipoPer){$TipoPer=$fila[4];};
if(!$CodActEco){$CodActEco=$fila[5];};

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
	if(document.FORMA.TipoPer.value==""){alert(document.FORMA.TipoPer.value);return false;}
	if(document.FORMA.CodActEco.value==""){alert("Por favor ingrese el Codigo de la Actividad Economica !!!");return false;}
}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="ArpAnt" value="<? echo $ArpAnt?>">
<input type="hidden" name="CodArpAnt" value="<? echo $CodArpAnt?>">

<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
<tr>
	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">ARP</td>
    <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Codigo</td>
</tr>
<tr>
	<td><select name="Arp" style="width:300px;" onChange="FORMA.submit();" >
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
     <td><input type="text" name="CodArp" size="44" value="<? echo $CodArp?>" maxlength="10" /></td>
</tr>
<tr>
	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Clase Aportante</td>
    <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Naturaleza Juridica</td>
</tr>
<tr>
	<td><select name="Aportante" style="width:300px;" onChange="FORMA.submit();" >
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
	<td><select name="NatJuri" style="width:300px;" onChange="FORMA.submit();" >
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
     <td><input type="text" name="CodActEco" size="44" value="<? echo $CodActEco?>" /></td>
</tr>
</table>
<center>
<input type="submit" value="Guardar" name="Guardar">
<!--<input type="button" value="Cancelar" name="Cancelar" onClick="location.href='DatosEntidad.php?DatNameSID=<? echo $DatNameSID?>';" ></center>-->
</form>
</body>
</html>