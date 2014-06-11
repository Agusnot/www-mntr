<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();	
	if($Guardar)
	{
		$cons="insert into contratacionsalud.frecagendainterna (compania,especialidad,formato,frecuencia,entidad,contrato,numero,ambito) 
		values ('$Compania[0]','$Especialidad','$Formato',$Frecuencia,'$Entidad','$Contrato','$Numero','$Ambito')";	
		$res=ExQuery($cons);				
		$Eliminar=""; $Frecuencia="";
	}
	if($Eliminar==1)
	{
		$cons="delete from contratacionsalud.frecagendainterna where compania='$Compania[0]' and especialidad='$EspecialidadElim' and entidad='$Entidad' and contrato='$Contrato'
		and numero='$Numero'";
		$res=ExQuery($cons);
		$Eliminar=0;
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.Ambito.value==""){alert("Debe seleccionar una especialidad!!!"); return false;}
		if(document.FORMA.Especialidad.value==""){alert("Debe seleccionar una especialidad!!!"); return false;}
		if(document.FORMA.Frecuencia.value==""){alert("Debe digitar la frecuencia!!!"); return false;}
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table  BORDER=1  style='font : normal normal small-caps 11px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center"> 
	<tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Proceso</td>
        <td>
        <?	$cons="select ambito from salud.ambitos where compania='$Compania[0]' and ambito!='Sin Ambito' and hospitalizacion=1";
			$res=ExQuery($cons);?>
            <select name="Ambito" onChange="document.FORMA.submit()">
            	<option></option>
          <?	while($fila=ExFetch($res))
				{
					if($fila[0]==$Ambito){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
					else{echo "<option value='$fila[0]'>$fila[0]</option>";}
				}?>    
            </select>
        </td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Especialidad</td>
  	<?	$cons="select especialidad,formato,frecuencia from contratacionsalud.frecagendainterna where compania='$Compania[0]' and entidad='$Entidad' and contrato='$Contrato'
		and numero='$Numero'";
		$res=ExQuery($cons);

	
		$cons="select especialidad from salud.especialidades where compania='$Compania[0]' order by especialidad";
		$res=ExQuery($cons);?>
        <td>
        	<select name="Especialidad" onChange="document.FORMA.submit()">
            	<option></option>
       		<?	while($fila=ExFetch($res))
				{
					if($fila[0]==$Especialidad){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
					else{echo "<option value='$fila[0]'>$fila[0]</option>";}
				}?>
            </select>
        </td>
    </tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Formato</td>
  	<?	$cons="select formato from historiaclinica.formatos where compania='$Compania[0]' and tipoformato='$Especialidad' 
		and formato not in (select formato from contratacionsalud.frecagendainterna where compania='$Compania[0]' and entidad='$Entidad' and contrato='$Contrato'
		and numero='$Numero' and especialidad='$Especialidad' and ambito='$Ambito') and estado='AC'
		order by formato";
		$res=ExQuery($cons);?>
        <td>
        	<select name="Formato">            	
       		<?	while($fila=ExFetch($res))
				{
					if($fila[0]==$Formato){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
					else{echo "<option value='$fila[0]'>$fila[0]</option>";}
				}?>
            </select>
        </td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Frecuencia</td>
        <td><input type="text" name="Frecuencia" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" value="<? echo $Frecuencia?>" style="width:30"/> Dias</td>
    </tr>
    <tr align="center">
    	<td colspan="2">
        	<input type="submit" name="Guardar" value="Guardar">
            <input type="button" value="Regresar" 
            onClick="location.href='NewContratos.php?DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $Entidad?>&Contrato=<? echo $Contrato?>&Numero=<? echo $Numero?>&Edit=1'">
      	</td>
    </tr>
</table>
<?
$cons="select especialidad,formato,frecuencia,ambito from contratacionsalud.frecagendainterna where compania='$Compania[0]' and entidad='$Entidad' and contrato='$Contrato'
and numero='$Numero' order by especialidad";
$res=ExQuery($cons);
if(ExNumRows($res)>0)
{?>
<br>
<table  BORDER=1  style='font : normal normal small-caps 11px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center"> 
<tr  bgcolor="#e5e5e5" style="font-weight:bold" align="center">
	<td>Especialidad</td><td>Formato</td><td>Frecuencia</td><td>Proceso</td><td></td>
</tr>
<?
	while($fila=ExFetch($res))
	{?>
		<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" align="center">
        	<td><? echo $fila[0]?></td><td><? echo $fila[1]?></td><td><? echo $fila[2]?> Dias</td><td><? echo $fila[3]?></td>
            <td><img src="/Imgs/b_drop.png" title="Eliminar" style="cursor:hand"
            	onClick="if(confirm('Desa elimnar este registro?')){location.href='AgendaInterna.php?DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $Entidad?>&Contrato=<? echo $Contrato?>&Numero=<? echo $Numero?>&Eliminar=1&EspecialidadElim=<? echo $fila[0]?>';}">
            </td>
        </tr>	
<?	}?>
</table>
<?
}
?>
<input type="hidden" name="Entidad" value="<? echo $Entidad?>"> 
<input type="hidden" name="Contrato" value="<? echo $Contrato?>"> 
<input type="hidden" name="Numero" value="<? echo $Numero?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
</form>    
</body>
</html>
