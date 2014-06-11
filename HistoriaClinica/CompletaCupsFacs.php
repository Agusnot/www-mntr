<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	
	?>
    <script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
		
		parent.document.getElementById('FrameFondo').style.position='absolute';
		parent.document.getElementById('FrameFondo').style.top='1px';
		parent.document.getElementById('FrameFondo').style.left='1px';
		parent.document.getElementById('FrameFondo').style.width='1';
		parent.document.getElementById('FrameFondo').style.height='1';
		parent.document.getElementById('FrameFondo').style.display='none';
		//parent.document.FORMA.submit();
	}
	function ValidaDiagnostico2(Objeto1,Objeto2)
	{		
		frames.FrameOpener2.location.href="/HistoriaClinica/Formatos_Fijos/ValidaDiagnostico2.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD2=2";
		document.getElementById('FrameOpener2').style.position='absolute';
		document.getElementById('FrameOpener2').style.top='60px';
		document.getElementById('FrameOpener2').style.left='60px';
		document.getElementById('FrameOpener2').style.display='';
		document.getElementById('FrameOpener2').style.width='800px';
		document.getElementById('FrameOpener2').style.height='350px';
	}
	function Validar()
	{
		if(document.FORMA.CodDiagnostico1.value==""){alert("Debe seleccionar el diagnostico!!!");return false;}	
		if(document.FORMA.NomDiagnostico1.value==""){alert("Debe seleccionar el diagnostico!!!");return false;}	
	}
	</script>	

<?	if($Guardar)
	{
		if($Guardar)
		{
			while( list($cad,$val) = each($Finalidad))
			{
				$DatLiq=explode("****",$cad);
				$cons="update facturacion.detalleliquidacion set dxppal='$CodDiagnostico1',finalidad='$val',causaext='".$CausaExterna[$cad]."'
				where compania='$Compania[0]' and noliquidacion=$DatLiq[1] and codigo='$DatLiq[0]'";
				echo $cons."<br>";
				$res=ExQuery($cons);
			}
		}?>
		<script language="javascript">
			CerrarThis();
		</script>
<?	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<!--<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">-->
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' bordercolor="#e5e5e5" cellpadding="2" align="center">
	<tr>
    	<td colspan="8" align="center">
        	<input type="submit" name="Guardar" value="Guardar">
        </td>        
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="8">Cups de Consulta Incompletos</td>        
    </tr>
    <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td>CUP</td><td>Finalidad</td><td>Causa Externa</td>
   	</tr>
<?	$cons="select codigo,nombre,liquidacion.noliquidacion from facturacion.detalleliquidacion,facturacion.liquidacion
	where liquidacion.compania='$Compania[0]' and numservicio=$NumServicio and estado='AC' and detalleliquidacion.compania='$Compania[0]'
	and detalleliquidacion.noliquidacion=liquidacion.noliquidacion and tipo='00004' 
	and (dxppal is null or dxppal='' or finalidad='' or finalidad is null or causaext is null or causaext='')
	group by codigo,nombre,liquidacion.noliquidacion order by codigo,nombre";	
	$res=ExQuery($cons);
	while($fila=Exfetch($res))
	{?>    
    	<tr>
    		<td><? echo "<b>$fila[0] - $fila[1]</b>";?></td>        
            <td>
            <?	$cons2="select codigo,finalidad,pordefecto from salud.finalidadesact where tipo=1 order by finalidad";
				$res2=ExQuery($cons2);?>
                <select name="Finalidad[<? echo "$fila[0]****$fila[2]"?>]">                	
             	<?	while($fila2=ExFetch($res2))
					{
						if($fila2[2]=="1"){echo "<option value='$fila2[0]' selected>$fila2[1]</option>";}	
						else{echo "<option value='$fila2[0]'>$fila2[1]</option>";}	
					}?>
                </select>
            </td>
          	<td>
             <?	$cons2="select codigo,causa,pordefecto from salud.causaexterna order by causa";
				$res2=ExQuery($cons2);?>
                <select name="CausaExterna[<? echo "$fila[0]****$fila[2]"?>]">                	
             	<?	while($fila2=ExFetch($res2))
					{
						if($fila2[2]=="1"){echo "<option value='$fila2[0]' selected>$fila2[1]</option>";}	
						else{echo "<option value='$fila2[0]'>$fila2[1]</option>";}	
					}?>
                </select>
            </td>
    	</tr>
<?	}
	$cons="select dxserv,diagnostico from salud.servicios,salud.cie where servicios.compania='$Compania[0]' and dxserv=codigo
	and numservicio=$NumServicio";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	if(!$CodDiagnostico1){$CodDiagnostico1=$fila[0];}
	if(!$NomDiagnostico1){$NomDiagnostico1=$fila[1];}?>
	<tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="8">DIAGNOSTICO</td></tr>
    <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center" ><td>Codigo</td><td>Nombre</td><td>Tipo Diagnostico</td></tr>
    <tr align="center">    	
        <td><input  style="width:100" type="text" readonly name="CodDiagnostico1" onFocus="ValidaDiagnostico2(this,NomDiagnostico1)"  
        onKeyUp="ValidaDiagnostico2(this,NomDiagnostico1);xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $CodDiagnostico1?>"></td>        
        <td width="100%"><input type="text" style="width:100%" name="NomDiagnostico1" readonly onFocus="ValidaDiagnostico2(CodDiagnostico1,this)" 
        onKeyUp="ValidaDiagnostico2(CodDiagnostico1,this);xLetra(this)" onKeyDown="ExLetra(this)" value="<? echo $NomDiagnostico1?>"></td>
        <td>
        <?	$cons="select tipodiagnost,codigo from salud.tiposdiagnostico where compania='$Compania[0]' order by tipodiagnost";
			$res=ExQuery($cons);?>
            <select name="TipoDx"><?
				while($fila=ExFetch($res)){
		    	    if($TipoDx==$fila[1]){
    	    			echo "<option value='$fila[1]' selected>$fila[0]</option>";
	        	  	}
					else{
						echo "<option value='$fila[1]'>$fila[0]</option>";
					}			
				}
    	?>	</select>
        </td>
    </tr>
</table>

<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="SFFormato" value="<? echo $SFFormato?>">
<input type="hidden" name="IdHistoOrigen" value="<? echo $IdHistoOrigen?>">
<input type="hidden" name="SFTF" value="<? echo $SFTF?>">
<input type="hidden" name="Formato" value="<? echo $Formato?>">
<input type="hidden" name="SoloUno" value="<? echo $SoloUno?>">
<input type="hidden" name="NumServicio" value="<? echo $NumServicio?>">
<input type="hidden" name="Frame" value="<? echo $Frame?>">
</form>
<iframe scrolling="no" id="FrameOpener2" name="FrameOpene2" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe> 
</body>
</html>