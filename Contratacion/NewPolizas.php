<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar)
	{
		$ND=getdate();
		$cons="insert into contratacionsalud.polizasxcontrato (nopoliza,vrpoliza,tipopoliza,entidad,contrato,nocontrato,fechaing,usuarioing,compania) values
		('$Nopoliza',$Vrpoliza,'$TipoPoliza','$Entidad','$Contrato','$Numero','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$usuario[1]','$Compania[0]')";
		$res=ExQuery($cons);
	}
	if($Eliminar)
	{
		$cons="delete from contratacionsalud.polizasxcontrato where compania='$Compania[0]' and entidad='$Entidad' and contrato='$Contrato' and nocontrato='$Numero' 
		and nopoliza='$NumPol' and vrpoliza=$VrPol and tipopoliza='$TipoPol'";
		$res=ExQuery($cons);
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.Nopoliza.value==""){alert("Debe digitar el numero de la poliza!!!");return false;}
		if(document.FORMA.Vrpoliza.value==""){alert("Debe digitar el valor de la poliza!!!");return false;}
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table border="1" bordercolor="#e5e5e5"  align="center" style='font : normal normal small-caps 13px Tahoma;'>  
	<tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Numero Poliza</td>
        <td><input type="text" name="Nopoliza" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $fila['nopoliza']?>"></td>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Valor Poliza</td>
        <td><input type="text" name="Vrpoliza" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" value="<? echo $fila['vrpoliza']?>" onFocus="Ocultar()"></td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Tipo Poliza</td>
        <?	$result=ExQuery("select tipo from contratacionsalud.tipospoliza");?>
        <td>
        	<select name="TipoPoliza">
            <?	while($row=ExFetch($result))
				{
					if($TipoPoliza==$fila['tipopoliza'])
					{echo "<option value='$row[0]' selected>$row[0]</option>";}
					else
					{echo "<option value='$row[0]'>$row[0]</option>";}
				}
            ?>
            </select>
        </td>        
    </tr>	 
    <tr align="center">
    	<td colspan="7"><input type="submit" value="Guardar" name="Guardar"><input type="button" value="Regresar" 
        onClick="location.href='NewContratos.php?DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $Entidad?>&Contrato=<? echo $Contrato?>&Numero=<? echo $Numero?>&Edit=1'"></td>
    </tr>
</table>
<br>
<?
$cons="select nopoliza,vrpoliza,tipopoliza from contratacionsalud.polizasxcontrato where compania='$Compania[0]' and entidad='$Entidad' and contrato='$Contrato' and nocontrato='$Numero'
order by nopoliza";
$res=ExQuery($cons);
if(ExNumRows($res)>0){?>
	<table border="1" bordercolor="#e5e5e5"  align="center" style='font : normal normal small-caps 13px Tahoma;'>  
    	<tr  bgcolor="#e5e5e5" style="font-weight:bold" align="center">
        	<td>No. Poliza</td><td>Vr Poliza</td><td>Tipo Poliza</td><td></td>
		</tr>        
<?	while($fila=ExFetch($res))
	{?>	
		<tr>
        <td><? echo $fila[0]?></td><td align="right"><? echo number_format($fila[1],2);?></td><td><? echo $fila[2]?></td>
        <td><img src="../Imgs/b_drop.png" title="Eliminar" onClick="if(confirm('Esta seguro de eliminar este registro?')){location.href='NewPolizas.php?DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $Entidad?>&Contrato=<? echo $Contrato?>&Numero=<? echo $Numero?>&Eliminar=1&NumPol=<? echo $fila[0]?>&VrPol=<? echo $fila[1]?>&TipoPol=<? echo $fila[2]?>'}"></td>
<?	}?>        
	</table><?
}?>    
<input type="hidden" name="Entidad" value="<? echo $Entidad?>">
<input type="hidden" name="Contrato" value="<? echo $Contrato?>">
<input type="hidden" name="Numero" value="<? echo $Numero?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
