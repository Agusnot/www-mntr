<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$ND=getdate();
	$Fec="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]";
	if($Guardar)
	{
		
		$consn="select numero from nomina.vacaciones order by numero desc";
		$resn=ExQuery($consn);$fila=ExFetch($resn);
		if($fila){$Numero=$fila[0]+1;}else{$Numero=1;}
		$cons="insert into nomina.Vacaciones (compania,identificacion,concepto,fecinicio,fecfinal,dias,detalle,resolucion,autorizacion,estado,usuario,fecha,numero) values ('$Compania[0]','$Identificacion','$RegNomina','$FecInicio','$FecFinal',$Dias,'$Detalle','$Resolucion','$Autorizacion','$Estado','$usuario[1]','$Fec','$Numero')";
		$res=ExQuery($cons);
		$cons="select movimiento,claseconcepto,detconcepto from nomina.conceptosliquidacion where concepto='$RegNomina'";
		$res=ExQuery($cons);
		?>
        <script>alert("La Vacacion ha sido Guardada !!!");</script>
<?        
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
<form name="FORMA1" method="post" onSubmit="return Validar();">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
<tr><td colspan="2" bgcolor="#666699" style="color:white" align="center">EMPLEADO VACACIONES</td></tr>
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
	<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
	<input type="hidden" name="Identificacion" value="<? echo $Identificacion?>">
	<input type="hidden" name="TipVinculacion" value="<? echo $TipVinculacion?>">    
	<tr>
		<td colspan="6" bgcolor="#666699" style="color:white" align="center">VACACIONES</td>
	</tr>
	<tr>
		<td>Registro de Nomina</td>
		<td colspan="2">
		<select name="RegNomina" onChange="FORMA.submit()" style="width:100%" >
			<option></option>
			<?
			$cons="select concepto,detconcepto from nomina.conceptosliquidacion where compania='$Compania[0]' and claseconcepto='Dias' and novedad='Vacaciones'";
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
	</tr>
	<tr>
		<td>Fecha Inicio</td>
		<td><input type="text" name="FecInicio" value="<? echo $FecInicio?>" onClick="popUpCalendar(this,this,'yyyy-mm-dd')" maxlength="10" onChange="document.FORMA.FecFinal.value=SumaDiasFecha(this,document.FORMA.Dias)" onKeyDown="document.FORMA.FecFinal.value=SumaDiasFecha(this,document.FORMA.Dias)" onKeyUp="document.FORMA.FecFinal.value=SumaDiasFecha(this,document.FORMA.Dias)" readonly/></td>
		<td>Dias de Licencia</td>
		<td><input type="text" name="Dias" value="<? echo $Dias?>" onKeyDown="xNumero(this);document.FORMA.FecFinal.value=SumaDiasFecha(document.FORMA.FecInicio,this);" onKeyUp="xNumero(this);document.FORMA.FecFinal.value=SumaDiasFecha(document.FORMA.FecInicio,this)" maxlength="2" onChange="document.FORMA.FecFinal.value=SumaDiasFecha(document.FORMA.FecInicio,this)" /></td>
		<td>Fecha Final</td>
		<td><input type="text" name="FecFinal" value="<? echo $FecFinal?>"  maxlength="10" readonly/></td>
	</tr>
	<tr>
		<td colspan="6" colspan=4 bgcolor="#666699" style="color:white" align="center">Detalle</td>
	</tr>
	<tr>    
		<td colspan="6"><textarea name="Detalle" style="width:100%" rows="4" ></textarea></td>
	</tr>
	<tr>
		<td>Resolucion o Acuerdo No.</td>
		<td><input type="text" name="Resolucion" value="<? echo $Resolucion?>" /></td>
		<td>Codigo Autorizacion</td>
		<td><input type="text" name="Autorizacion" value="<? echo $Autorizacion?>" /></td>
        <td>Estado</td>
    	<td><select name="Estado" onChange="FORMA.submit()" style="width:100%">
			<option></option>
            <option value="Aprobado" <? if($Estado=="Aprobado"){echo "selected";}?>>Aprobado</option>
            <option value="Rechazado" <? if($Estado=="Rechazado"){echo "selected";}?>>Rechazado</option>            
    		</select>
        </td>
	</tr>
	</table>
	<center><input type="submit" name="Guardar" value="Guardar" /></center>
	</form>
<? 
}
?>
</body>
</html>