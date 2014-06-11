<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	//echo $TipoVinc
	if(!$Codigo)
	{
		$conscon="select codigo from nomina.cargos where compania='$Compania[0]' and vinculacion='$TipoVinc' order by codigo desc";
		$rescon=ExQuery($conscon);
		$filaC=ExFetch($rescon);
		$Codigo=$filaC[0]+1;
		if($Codigo>0&&$Codigo<10)
		{
			$Codigo = "000$Codigo";
		}
		elseif($Codigo>9&&$Codigo<100)
		{
			$Codigo = "00$Codigo";
		}
		elseif($Codigo>99&&$Codigo<1000)
		{
			$Codigo = "0$Codigo";
		}
	}
//	echo $Codigo;
	if($Guardar)
	{		
//		echo $Codigo;
		if(!$Editar)
		{
			$cons="select codigo from nomina.cargos where codigo='$Codigo' and Vinculacion='$TipoVinc'";
			$res=ExQuery($cons);					
			if(ExNumRows($res)==0)
			{			
				$cons="insert into nomina.cargos (compania,codigo,cargo,vinculacion) values('$Compania[0]','$Codigo','$Cargo','$TipoVinc')";
				$res=ExQuery($cons); 
				?><script language="javascript">location.href="Cargos.php?DatNameSID=<? echo $DatNameSID?>&TipoVinc=<? echo $TipoVinc?>";</script><?
			}
			else
			{
				?><script language="javascript">alert("El Codigo del Cargo que desea ingresar ya existe!!!");</script><?		
			}			
		}
		else
		{	
			//echo $Codigo." -- ".$CodAnt;exit;		
			if($Codigo==$CodAnt)
			{
				$cons="update nomina.cargos set codigo='$Codigo',cargo='$Cargo' where compania='$Compania[0]' and vinculacion='$TipoVinc' and codigo='$CodAnt'";	
				$res=ExQuery($cons);
				?><script language="javascript">location.href="Cargos.php?DatNameSID=<? echo $DatNameSID?>&TipoVinc=<? echo $TipoVinc?>";</script><?			
			}
			else
			{
				$cons="select codigo from nomina.cargos where codigo='$Codigo' and Vinculacion='$TipoVinc'";
				$res=ExQuery($cons);				
				//echo $cons;exit;	
				if(ExNumRows($res)==0)
				{
					$cons="update nomina.cargos set codigo='$Codigo',cargo='$Cargo' where compania='$Compania[0]' and vinculacion='$TipoVinc' and codigo='$CodAnt'";	
					$res=ExQuery($cons);
					?><script language="javascript">location.href="Cargos.php?DatNameSID=<? echo $DatNameSID?>&TipoVinc=<? echo $TipoVinc?>";</script><?					
				}
				else
				{
					?><script language="javascript">alert("El Codigo del Cargo que desea ingresar ya existe!!!");</script><?		
				}
			}
		}	
	}	
	if($Editar)
	{
		$cons="select codigo,cargo from nomina.cargos where codigo='$Codigo' and vinculacion='$TipoVinc'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		if(!$Codigo){$Codigo=$fila[0];}
		if(!$Cargo){$Cargo=$fila[1];}		
		if(!$CodAnt){$CodAnt=$fila[0];}
	}
	$cons="select tipovinculacion from nomina.tiposvinculacion where codigo='$TipoVinc'";
	$res=ExQuery($cons);
	$fila=ExFetch($res)
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
function Validar()
{
   if(document.FORMA.Codigo.value==""){alert("Por favor ingrese el Codigo del Cargo!!!");return false;}
   if(document.FORMA.Cargo.value==""){alert("Por favor ingrese el Nombre del Cargo!!!");return false;}
}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar();">
<input type="hidden" name="TipoVinc" value="<? echo $TipoVinc?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input  type="hidden" name="CodAnt" value="<? echo $CodAnt?>">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
<tr>
	<td colspan="2" bgcolor="#666699" style="color:white" align="center"> <? echo $fila[0];?></td>
</tr>
<tr>
	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" >codigo</td>
    <td bgcolor="#e5e5e5" style="font-weight:bold" align="center" >Cargo</td>
</tr>
<tr>
	<td><input type="text" name="Codigo" value="<? echo $Codigo?>" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" style="width:70px" maxlength="4"/></td>
    <td><input type="text" name="Cargo" value="<? echo $Cargo?>" onKeyUp="ExLetra(this)" onKeyDown="ExLetra(this)"/></td>
</tr>
</table>
<center><input type="submit" value="Guardar" name="Guardar" ><input type="button" value="Cancelar" onClick="location.href='Cargos.php?DatNameSID=<? echo $DatNameSID?>&TipoVinc=<? echo $TipoVinc?>&Editar=<? echo $Editar?>';"></center>
</form>
</body>
</html>