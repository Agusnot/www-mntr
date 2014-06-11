<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function CerrarThis()
	{		
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
	function CerrarThisNoGuarda()
	{
		parent.document.FORMA.Parto.checked=false;
		CerrarThis();
	}
	function VerDx(e,Objeto){
		x = e.clientX;
		y = e.clientY;
		frames.FrameOpener.location.href="VerDX.php?DatNameSID=<? echo $DatNameSID?>&NomCampo="+Objeto.name;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=y-100;
		document.getElementById('FrameOpener').style.left='1px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='100%';
		document.getElementById('FrameOpener').style.height='350';
	}
	function VerDx2(e,Objeto){
		x = e.clientX;
		y = e.clientY;
		frames.FrameOpener.location.href="VerDXMuerte.php?DatNameSID=<? echo $DatNameSID?>&NomCampo="+Objeto.name;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=y-100;
		document.getElementById('FrameOpener').style.left='1px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='100%';
		document.getElementById('FrameOpener').style.height='350';
	}
	function Validar()
	{
		if(document.FORMA.EdadGesta.value==''){alert('Debe digitar la edad gestacional!!!');return false;}	
		if(document.FORMA.PesoRN.value==''){alert('Debe digitar el peso del recien nacido!!!');return false;}	
		if(document.FORMA.DxRN.value==''){alert('Debe seleccionar el diagnostico del recien nacido!!!');return false;}	
		if(document.FORMA.DxRNMuerte.value!=''){
			if(document.FORMA.FechaDead.value==''){alert('Debe seleccionar la fecha de la muerte!!!');return false;}
		}
	}
</script>	
<?
if($Guardar)
{
	if($DxRNMuerte){$DM=",dxmuerte,fechamuerte,horamuerte";$DM2=",'$AuxDxRNMuere','$FechaDead','$HoraDead:$MinsDead'";}
	
	$cons="insert into salud.partos (compania,idmadre,fechanac,horanac,edadgesta,controlprenantal,sexorn,pesborn,dxrn,noliq,usucrea,fechacrea $DM) 
	values	('$Compania[0]','$CedPac','$FechaNac','$HoraNac:$MinsNac','$EdadGesta','$ControlPrent','$SexoRN','$PesoRN','$AuxDxRN','$NoLiq','$usuario[1]'
	,'$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]' $DM2)";	
	$res=ExQuery($cons);
	//echo $cons."<br>";
	if($Fac)
	{
		$cons="update facturacion.liquidacion set parto='1' where compania='$Compania[0]' and noliquidacion='$NoLiq'";
		$res=ExQuery($cons);
		//echo $cons."<br>";
	}?>
	<script language="javascript">
		CerrarThis();
	</script>	<?
}
else{
	$cons="update facturacion.liquidacion set parto=NULL where compania='$Compania[0]' and noliquidacion='$NoLiq'";
	$res=ExQuery($cons);
	$cons="delete from salud.partos where compania='$Compania[0]' and idmadre='$CedPac' and noliq=$NoLiq";
	$res=ExQuery($cons);
}
?>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="button" value=" X " onClick="CerrarThisNoGuarda()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' bordercolor="#e5e5e5" cellpadding="2" align="center">  
<tr><td colspan="4" align="center" bgcolor="#e5e5e5" style="font:bold">DATOS DEL PARTO</td></tr>
<tr>    
<?	$cons="select fechacrea from facturacion.liquidacion where noliquidacion=$NoLiq";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$FecCrea=explode(" ",$fila[0]);
	$FecNac=explode("-",$FecCrea[0]);
	$HNac=explode(":",$FecCrea[1]);?>
    <td bgcolor="#e5e5e5" style="font:bold">Fecha Naciemiento</td>
<?	if(!$FechaNac){$FechaNac=$FecCrea[0];}?>
    <td><input type="text" name="FechaNac" value="<? echo $FechaNac?>" readonly onClick="popUpCalendar(this, FORMA.FechaNac, 'yyyy-mm-dd')" style="width:80"></td>
    <td bgcolor="#e5e5e5" style="font:bold">Hora Naciemiento</td>
<?	if(!$HoraNac){$HoraNac=$HNac[0];}
	if(!$MinsNac){$MinsNac=$HNac[1];} ?>
    <td>
    	<select name="HoraNac"> 
        <?	for($i=0;$i<24;$i++)
			{
				if($HoraNac==$i){echo "<option value='$i' selected>$i</option>";}
				else{echo "<option value='$i' >$i</option>";}	
			}?>	
        </select> :
        <select name="MinsNac">
        <?	for($i=0;$i<60;$i++)
			{
				if($MinsNac==$i){echo "<option value='$i' selected>$i</option>";}
				else{echo "<option value='$i' >$i</option>";}	
			}?>	
        </select>
    </td>
</tr>
<tr>
	<td bgcolor="#e5e5e5" style="font:bold">Edad Gestacional</td>
    <td><input type="text" name="EdadGesta" value="<? echo $EdadGesta?>" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" style="width:30" maxlength="2"></td>
    <td bgcolor="#e5e5e5" style="font:bold">Control Prenatal</td>
    <td>
    	<select name="ControlPrent">
        	<option value="1" <? if($ControlPrent=="1"){?> selected<? }?>>Si</option>
            <option value="2" <? if($ControlPrent=="2"){?> selected<? }?>>No</option>
        </select>
    </td>
</tr>
<tr>
	<td bgcolor="#e5e5e5" style="font:bold">Sexo Recien Nacido</td>
    <td>
    	<select name="SexoRN">
        	<option value="F" <? if($ControlPrent=="F"){?> selected<? }?>>Femenino</option>
            <option value="M" <? if($ControlPrent=="M"){?> selected<? }?>>Masculino</option>
        </select>
	</td>        
    <td bgcolor="#e5e5e5" style="font:bold">Peso Recien Nacido(gr)</td>
    <td><input type="text" name="PesoRN" value="<? echo $PesoRN?>" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" style="width:50" maxlength="4"></td>
</tr>
<tr>
	<td bgcolor="#e5e5e5" style="font:bold">Dx Recien Nacido</td>
    <td colspan="3">
    	<input type="text" name="DxRN" id="DxRN" readonly style="width:370px" readonly onFocus="VerDx(event,this)">
    </td>
</tr>
<tr>
	<td bgcolor="#e5e5e5" style="font:bold">Dx En Caso de Muerte</td>
    <td colspan="3">
    	<input type="text" name="DxRNMuerte" id="DxRNMuerte" readonly style="width:370px" readonly onFocus="VerDx2(event,this)">
    </td>
</tr>
<tr>
	<td bgcolor="#e5e5e5" style="font:bold">Fecha Muerte</td>
	<td><input type="text" name="FechaDead" value="<? echo $FechaDead?>" readonly onClick="popUpCalendar(this, FORMA.FechaDead, 'yyyy-mm-dd')" style="width:80">   
    <td bgcolor="#e5e5e5" style="font:bold">Hora Muerte</td>
    <td>
    	<select name="HoraDead">
        	<option></option>
        <?	for($i=0;$i<24;$i++)
			{
				if($HoraDead==$i){echo "<option value='$i' selected>$i</option>";}
				else{echo "<option value='$i' >$i</option>";}	
			}?>	
        </select> :
        <select name="MinsDead">
        	<option></option>
        <?	for($i=0;$i<60;$i++)
			{
				if($MinsDead==$i){echo "<option value='$i' selected>$i</option>";}
				else{echo "<option value='$i' >$i</option>";}	
			}?>	
        </select>
    </td>
</tr>
<tr><td colspan="4" align="center"><input type="submit" name="Guardar" value="Guardar"></td></tr>
</table>
<input type="hidden" name="AuxDxRN" value="<? echo $AuxDxRN?>">
<input type="hidden" name="AuxDxRNMuere" value="<? echo $AuxDxRNMuere?>">
<input type="hidden" name="FechaActual" value="<? echo "$ND[year]-$ND[mon]-$ND[mday]"?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="NumServ" value="<? echo $NumServ?>">
<input type="hidden" name="NoLiq" value="<? echo $NoLiq?>">
<input type="hidden" name="CedPac" value="<? echo $CedPac?>">
<input type="hidden" name="Fac" value="<? echo $Fac?>">
</form>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge">
</body>
</html>    