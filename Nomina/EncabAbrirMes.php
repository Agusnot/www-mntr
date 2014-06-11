<?php
if($DatNameSID){session_name("$DatNameSID");}
//echo $DatNameSID;
session_start();
include("Funciones.php");
$ND=getdate();
if(!$Anio){$Anio="$ND[year]";}
if(!$Mes){$Mes="$ND[mon]";}

//	echo $ContCons;
?>
<html>
<head>
<meta http-equiv="Content-
Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
function Validar()
{
   if(document.FORMA.Anio.value==""){alert("Por favor ingrese el Año a Liquidar !!!");return false;}
   if(document.FORMA.Mes.value==""){alert("Por favor ingrese el Mes a Liquidar !!!");return false;}       
}
function ValidarRet()
{
   if(document.FORMA.Anio.value==""){alert("Por favor ingrese el Año a Retirar !!!");return false;}
   if(document.FORMA.Mes.value==""){alert("Por favor ingrese el Mes a Retirar !!!");return false;}   
   if(confirm("Esta seguro de Retirar el movimiento?")){document.FORMA.RetirarMov.value=1;FORMA.submit();}
}

</script>
</head>
<body>

<form name="FORMA" method="post" action="CuerAbrirMes.php" target="Cuerpo" onSubmit="return Validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="RetirarMov" value="">
<?
$consIni="select identificacion from nomina.nomina where anio=$Anio and mes=$Mes";
$resIni=ExQuery($consIni);
$ContCons=ExNumRows($resIni);
?>
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
<tr>
	<td colspan="4" bgcolor="#666699" style="color:white" align="center">LIQUIDAR MES</td>
</tr>
<tr>
	<td bgcolor="#e5e5e5" style="font-weight:bold">AÑO</td>
    <td><select name="Anio" onChange="FORMA.submit();" >
            <option ></option>
                    <?
                    $cons = "select ano from nomina.minimo order by ano desc";
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
<center><input type="submit" name="Iniciar" value="INICIAR" <? if($ContCons>0){ echo "disabled";}?>/>
<input type="button" name="RetMovimiento" value="Retirar Movimiento" onClick="ValidarRet();" <? if($ContCons==0){ echo "disabled";}?> /></center>
</form>

</body>
</html>