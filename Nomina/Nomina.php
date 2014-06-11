<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
function Validar()
{
   if(document.FORMA.Anio.value==""){alert("Por favor ingrese el Año a Suspender !!!");return false;}
   if(document.FORMA.Mes.value==""){alert("Por favor ingrese el Mes a Suspender !!!");return false;}   
}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onsubmit="return Validar()">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
<tr>
	<td colspan="4" bgcolor="#666699" style="color:white" align="center">SUSPENDER NOMINA MES</td>
</tr>
<tr>
	<td bgcolor="#e5e5e5" style="font-weight:bold">AÑO</td>
    <td><select name="Anio" onChange="FORMA.submit();" >
            <option ></option>
                    <?
                    $cons = "select ano from nomina.minimo";
                    $resultado = ExQuery($cons);
                    while ($fila = ExFetch($resultado))
                    {                        
						 if($fila[0]==$Anio)
						 {
							 echo "<option value='$fila[0]' selected>$fila[0]</option>"; 
						 }
						 else{echo "<option value='$fila[0]'>$fila[0]</option>";}						 
                    }
				?>
            </select></td>
    <td  bgcolor="#e5e5e5" style="font-weight:bold">MES</td>
    <td><select name="Mes" onChange="FORMA.submit();" >
            <option ></option>
                    <?
                    $cons = "select numero,mes from central.meses";
                    $resultado = ExQuery($cons);
                    while ($fila = ExFetch($resultado))
                    {                        
						 if($fila[0]==$Mes)
						 {
							 echo "<option value='$fila[0]' selected>$fila[1]</option>"; 
						 }
						 else{echo "<option value='$fila[0]'>$fila[1]</option>";}						 
                    }
				?>
            </select></td>
</tr>
</table>
<center><input type="submit" name="Suspender" value="SUSPENDER" /> </center>
</form>
</body>
</html>