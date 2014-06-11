<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$ND=getdate();
	$Fec="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]";
	if($Guardar)
	{
		$cons1="select * from nomina.$Novedad where compania='$Compania[0]' and identificacion='$Identificacion' and fecinicio <= '$FecInicio' and fecfinal >= '$FecInicio'";
//		echo $cons1;
		$res=ExQuery($cons1);
		$cont=ExNumRows($res);
//		echo $cont;
		if($cont==0)
		{
				$consn="select numero from nomina.$Novedad order by numero desc";
		//		echo $consn;
				$resn=ExQuery($consn);$fila=ExFetch($resn);
				if($fila){$Numero=$fila[0]+1;}else{$Numero=1;}
				$cons="insert into nomina.$Novedad (compania,identificacion,concepto,fecinicio,fecfinal,dias,detalle,resolucion,autorizacion,estado,usuario,fecha,numero) values ('$Compania[0]','$Identificacion','$RegNomina','$FecInicio','$FecFinal',$Dias,'$Detalle','$Resolucion','$Autorizacion','','$usuario[1]','$Fec','$Numero')";
				$res=ExQuery($cons);
				$cons="select movimiento,claseconcepto,detconcepto from nomina.conceptosliquidacion where concepto='$RegNomina'";
				//$res=ExQuery($cons);
				?>
				<script>alert("Las <? echo $Novedad?> ha sido Guardadas !!!");</script>
				<script language="javascript">location.href="MenuNovedades.php?DatNameSID=<? echo $DatNameSID?>&Novedad=<? echo $Novedad?>";</script>
	<?	}
	     else
		{
			?>
            <script language="javascript">alert("Ya Existe Una <? echo $Novedad?> para la Fecha Seleccionada")</script>
            <?
		}   
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/calendario/popcalendar.js"></script>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
function Validar()
{
   if(document.FORMA.RegNomina.value==""){alert("Por favor ingrese el Registro de Nomina!!!");return false;}
   if(document.FORMA.FecInicio.value==""){alert("Por favor ingrese la Fecha de Inicio!!!");return false;}
   if(document.FORMA.Dias.value==""){alert("Por favor ingrese los Dias!!!");return false;}
//   if(document.FORMA.Resolucion.value==""){alert("Por favor ingrese el Numero de Resolucion!!!");return false;}
//   if(document.FORMA.Autorizacion.value==""){alert("Por favor ingrese el Numero de Autorizacion!!!");return false;}   
}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
<form name="FORMA1" method="post" onSubmit="return Validar();">
<input type="hidden" name="Novedad" value="<? echo $Novedad?>">
<tr><td colspan="2" bgcolor="#666699" style="color:white" align="center">EMPLEADO <? echo strtoupper($Novedad)?></td></tr>
<tr>
	<td>Tipo Vinculacion</td>
    <td><select name="TipVinculacion" onChange="FORMA1.submit()" >
       	<option></option>
        <?
        $cons="select codigo,tipovinculacion from nomina.tiposvinculacion where compania='$Compania[0]'";
//		echo $cons;
		$res=ExQuery($cons);
		while($fila=Exfetch($res))
		{
			if($fila[1]==$TipVinculacion)
			{
				echo "<option value='$fila[1]' selected>$fila[1]</option>";
			}
			else
			{
				echo "<option value='$fila[1]'>$fila[1]</option>";
			}
		}
		?>
    </select>
    </td>
</tr>
<tr>
	<td>Nombre</td>
    <td><select name="Identificacion" <? if(!$TipVinculacion){ echo "disabled";} ?> onChange="FORMA1.submit()" >
       	<option></option>
        <? 
		if($TipVinculacion)
		{
			$cons="select identificacion,primape,segape,primnom,segnom from central.terceros where compania='$Compania[0]' and tipo='$TipVinculacion' order by primape";
	//		echo $cons;
			$res=ExQuery($cons);
			while($fila=Exfetch($res))
			{
				if($fila[0]==$Identificacion)
				{
					echo "<option value='$fila[0]' selected>$fila[1] $fila[2] $fila[3] $fila[4]</option>";
				}
				else
				{
					echo "<option value='$fila[0]'>$fila[1] $fila[2] $fila[3] $fila[4]</option>";
				}
			}
		}
			?>
	</select>
    </td>
</tr>
</table>
</form>
<?
if($TipVinculacion&&$Identificacion)
{
?>	
	<form name="FORMA" method="post" onSubmit="return Validar();">
    <input type="hidden" name="Novedad" value="<? echo $Novedad?>">
    <input type="hidden" name="TipVinculacion" value="<? echo $TipVinculacion?>">
    <input type="hidden" name="Identificacion" value="<? echo $Identificacion?>">
	<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
	<tr>
		<td colspan="6" bgcolor="#666699" style="color:white" align="center"><? echo strtoupper($Novedad)?></td>
	</tr>
	<tr>
		<td>Registro de Nomina</td>
		<td colspan="2">
		<select name="RegNomina" onChange="FORMA.submit()" style="width:100%" >
			<option></option>
			<?
			$cons="select concepto,detconcepto from nomina.conceptosliquidacion where compania='$Compania[0]' and claseconcepto='Dias' and novedad='$Novedad' and tipovinculacion='$TipVinculacion'";
			//echo $cons;
			$res=ExQuery($cons);
			while($fila=Exfetch($res))
			{
				if($fila[0]==$RegNomina)
				{
					echo "<option value='$fila[0]' selected>$fila[1]</option>";
				}
				else
				{
					echo "<option value='$fila[0]'>$fila[1]</option>";
				}
			}
			?>
		</select>
		</td>
        <?
		if($Novedad=="Incapacidades")
		{
			
		?>
        	<td>Prorroga
    		<input type="checkbox" name="prorroga">
    		</td>
        <?
		}
		?>
	</tr>
	<tr>
		<td>Fecha Inicio</td>
		<td><input type="text" name="FecInicio" value="<? echo $FecInicio?>" onClick="popUpCalendar(this,this,'yyyy-mm-dd')" maxlength="10" onChange="document.FORMA.FecFinal.value=SumaDiasFecha(this,document.FORMA.Dias)" onKeyDown="document.FORMA.FecFinal.value=SumaDiasFecha(this,document.FORMA.Dias)" onKeyUp="document.FORMA.FecFinal.value=SumaDiasFecha(this,document.FORMA.Dias)" readonly/></td>
		<td>Dias de <? echo $Novedad?></td>
	<td><input type="text" name="Dias" value="<? echo $Dias?>" onKeyDown="xNumero(this);document.FORMA.FecFinal.value=SumaDiasFecha(document.FORMA.FecInicio,this);" onKeyUp="xNumero(this);document.FORMA.FecFinal.value=SumaDiasFecha(document.FORMA.FecInicio,this)" maxlength="3" onChange="document.FORMA.FecFinal.value=SumaDiasFecha(document.FORMA.FecInicio,this)" onBlur="if(parseInt(this.value)>364){alert('El valor de los dias no puede ser mayor a 364!!!');this.value=364;document.FORMA.FecFinal.value=SumaDiasFecha(document.FORMA.FecInicio,this);}" /></td>
		<td>Fecha Final</td>
		<td><input type="text" name="FecFinal" value="<? echo $FecFinal?>"  maxlength="10" /></td>
	</tr>
	<tr>
		<td colspan="6" colspan=4 bgcolor="#666699" style="color:white" align="center">Detalle</td>
	</tr>
	<tr>    
		<td colspan="6"><textarea name="Detalle" style="width:100%" rows="4" ></textarea></td>
	</tr>
	<tr>
		<td>Resolucion o Acuerdo No.</td>
		<td colspan="2"><input type="text" name="Resolucion" value="<? echo $Resolucion?>" style="width:100%" /></td>
		<td>Codigo Autorizacion</td>
		<td colspan="2"><input type="text" name="Autorizacion" value="<? echo $Autorizacion?>" style="width:100%" /></td>
	</tr>
	</table>
	<center><input type="submit" name="Guardar" value="Guardar" /></center>
	</form>
<? 
}
?>
</body>
</html>