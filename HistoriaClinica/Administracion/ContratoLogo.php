<?php
//232102
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	if($Guardar)
	{
		$cons="Update HistoriaClinica.Formatos set entidadlogo='$Entidad', ContratoLogo='$Contrato', NoContratoLogo='$NoContrato'
		where Formato='$Formato' and TipoFormato='$TipoFormato' and Compania='$Compania[0]'";
		$res=ExQuery($cons);
		?><script language="javascript">		
		parent.document.getElementById('TamLogo').style.position='absolute';
		parent.document.getElementById('TamLogo').style.top='0';
		parent.document.getElementById('TamLogo').style.left='0';
		parent.document.getElementById('TamLogo').style.display='';
		parent.document.getElementById('TamLogo').style.width='0';
		parent.document.getElementById('TamLogo').style.height='0';
		parent.document.FORMA.submit();
        </script><?	
	}		
	$cons="Select EntidadLogo, ContratoLogo, NoContratoLogo from HIstoriaClinica.Formatos where Formato='$Formato' and TipoFormato='$TipoFormato' 
	and Compania='$Compania[0]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);	
	if($PV)
	{
		if(!$Entidad){$Entidad=$fila[0];}
		if(!$Contrato){$Contrato=$fila[1];}
		if(!$NoContrato){$NoContrato=$fila[2];}	
	}
?>
<head>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
function Salir()
{
	parent.document.getElementById('ContLogo').style.position='absolute';
	parent.document.getElementById('ContLogo').style.top='0';
	parent.document.getElementById('ContLogo').style.left='0';
	parent.document.getElementById('ContLogo').style.display='';
	parent.document.getElementById('ContLogo').style.width='0';
	parent.document.getElementById('ContLogo').style.height='0';		
}
function Validar()
{
	//if(document.FORMA.Entidad.value==""){alert("Por favor Seleccione la Entidad!!!");return false;}	
	if((document.FORMA.Contrato.value==''||document.FORMA.NoContrato.value=='')&&(document.FORMA.Contrato.value!=''&&document.FORMA.Contrato.value!=''))
	{alert("Debe seleccionar Contrato y Numero de Contrato!!!");return false;}	
}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar();">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="Formato" value="<? echo $Formato?>" />
<input type="hidden" name="TipoFormato" value="<? echo $TipoFormato?>" />
<input type="hidden" name="PV" />
<table border="1" bordercolor="#e5e5e5" align="center" style='font : normal normal small-caps 11px Tahoma;'>
<tr bgcolor="#e5e5e5" style="font-weight:bold">
<td align="center" colspan="2">Configurar Entidad y/o Contrato al que se asocia con el Logo</td></tr>
<tr bgcolor="#e5e5e5" style="font-weight:bold">
<td bgcolor="#e5e5e5" style="font-weight:bold">Entidad</td>
<?	
$cons="Select identificacion,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom)  from Central.Terceros,contratacionsalud.contratos
where Tipo='Asegurador' and Terceros.Compania='$Compania[0]' and contratos.compania='$Compania[0]' and entidad=identificacion
group by identificacion,primape,segape,primnom,segnom order by primape";
//echo $cons;?>
<td>
<select name="Entidad" onChange="document.FORMA.submit()">
<option></option>
<?	$res=ExQuery($cons);
while($row = ExFetch($res))
{
if($Entidad==$row[0])
{ ?>				
	<option value="<? echo $row[0]?>" selected><? echo $row[1]?></option>
<? }
else
{
?>
	<option value="<? echo $row[0]?>"><? echo $row[1]?></option>
<? }
}?>
</select>
</td>
</tr>
<tr>
<td bgcolor="#e5e5e5" style="font-weight:bold">Contrato</td>
<?	$cons="Select contrato from contratacionsalud.contratos
where contratos.compania='$Compania[0]' and entidad='$Entidad'";
//echo $cons;?>
<td>
<select name="Contrato" onChange="document.FORMA.submit()"><option></option>
<?	$res=ExQuery($cons);
while($row = ExFetch($res))
{
if($Contrato==$row[0])
{ ?>				
	<option value="<? echo $row[0]?>" selected><? echo $row[0]?></option>
<? }
else
{
?>
	<option value="<? echo $row[0]?>"><? echo $row[0]?></option>
<? }
}?>
</select>
</td>
</tr>
<tr>
<td bgcolor="#e5e5e5" style="font-weight:bold">No Contrato</td>
<?	$cons="Select numero from contratacionsalud.contratos
where contratos.compania='$Compania[0]' and entidad='$Entidad' and contrato='$Contrato'";
//echo $cons;?>
<td>
	<select name="NoContrato" onChange="document.FORMA.submit()">
    <option></option>
 <?	$res=ExQuery($cons);
	while($row = ExFetch($res))
	{
		if($NoContrato==$row[0])
		{ ?>				
			<option value="<? echo $row[0]?>" selected><? echo $row[0]?></option>
	 <? }
		else
		{
		?>
			<option value="<? echo $row[0]?>"><? echo $row[0]?></option>
	  <? }
	  }?>
	</select>
</td>   
</tr>
</table>
<center>
<input type="submit" name="Guardar" value="Guardar" style="font-size:11px" />
<input type="button" name="Cancelar" value="Cancelar" onClick="Salir();" style="font-size:11px"  />
</center>
</form>
</body>