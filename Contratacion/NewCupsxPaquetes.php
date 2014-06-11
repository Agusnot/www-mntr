<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Agregar)
	{
		$cons="insert into contratacionsalud.itemsxpaquete (compania,idpaq,usucrea,fechacrea,codigo,detalle,tipo,cantidad,tipofinalidad,finalidad,justificacion,nota) 		values
		('$Compania[0]',$IdPaquete,'$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Codigo','$Nombre','CUP','$Cantidad'
		,'$TipoFnld','$FinalidadProc','$Justific','$Observ')";	 
		$res=ExQuery($cons);
	?>
    	<script language="javascript">
			location.href='NewPaquete.php?DatNameSID=<? echo $DatNameSID?>&IdPaquete=<? echo $IdPaquete?>&Editar=1';
		</script>
    <?		
	}
	$cons="select paquete,entidad,contrato,nocontrato from contratacionsalud.paquetesxcontratos where compania='$Compania[0]' and idpaquete=$IdPaquete";	
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	
	$Entidad=$fila[1]; $Contrato=$fila[2]; $NoContrato=$fila[3];
	$cons2="select planbeneficios from contratacionsalud.contratos where compania='$Compania[0]' and entidad='$fila[1]' and contrato='$fila[2]'
	and numero='$fila[3]'";
	$res2=ExQuery($cons2);
	$fila2=ExFetch($res2);

?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function AsitenteNew()	
	{		
		frames.FrameOpener.location.href="/Facturacion/VerCupsoMeds.php?DatNameSID=<? echo $DatNameSID?>&Codigo="+document.FORMA.Codigo.value+"&Nombre="+document.FORMA.Nombre.value+"&Pagador=<? echo $Entidad?>&Contrato=<? echo $Contrato?>&NoContrato=<? echo $NoContrato?>&IdPaquete=<? echo $IdPaquete?>&TipoNuevo=Cup&OpcPaquete=1";
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=130;
		document.getElementById('FrameOpener').style.left=150;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='750px';
		document.getElementById('FrameOpener').style.height='350px';
		
		st = parent.document.body.scrollTop;
		//parent.frames.FrameOpener.location.href="NewFactura.php?NumServicio=<? echo $NumServicio?>&DatNameSID=<? echo $DatNameSID?>&Edit=1&TMPCOD=<? echo $TMPCOD?>&Tipo=Medicamentos&CedPac=<? echo $CedPac?>&Entidad=<? echo $Entidad?>&Contrato=<? echo $Contrato?>&NumServ=<? echo $NumServ?>";
	}
	function Validar()
	{  
		if(document.FORMA.Codigo.value==""){alert("Debe selecionar un CUP!!!"); return false;}
		if(document.FORMA.Nombre.value==""){alert("Debe selecionar un CUP!!!"); return false;}
		if(document.FORMA.TipoFnld.value==""){alert("Debe selecionar el tipo!!!"); return false;}
		if(document.FORMA.FinalidadProc.value==""){alert("Debe selecionar una finalidad!!!"); return false;}
	}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="IdPaquete" value="<? echo $IdPaquete?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="Tipo" value="<? $Tipo?>">
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center"> 
	<tr align="center">
    	<td colspan="11" bgcolor="#e5e5e5" style="font-weight:bold">Paquete <? echo $fila[0]?></td>
  	</tr>
    <tr>
    	<td  bgcolor="#e5e5e5" style="font-weight:bold" align="center">Codigo</td>
            <td><input name="Codigo" onKeyDown="xLetra(this)" onKeyUp="xLetra(this);AsitenteNew()" 
        	onKeyPress="AsitenteNew()" onFocus="AsitenteNew()" style="width:90px" value="<? echo $Codigo?>"></td>    
    	<td  bgcolor="#e5e5e5" style="font-weight:bold"  align="center">Nombre</td>
        <td colspan="3"><input name="Nombre" onKeyDown="xLetra(this)" onKeyUp="xLetra(this);AsitenteNew()" 
        	onKeyPress="AsitenteNew()" onFocus="AsitenteNew()" style="width:580px"  value="<? echo $Nombre?>"></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Cantidad</td>
        <td><input type="text" name="Cantidad" value="<? echo $Cantidad?>" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" onKeyPress="xNumero(this)" 
        style="width:20">
        </td>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Tipo</td>
        <?	if($Tipo&&!$TipoFnld){
				if($Tipo=='00004'){$TipoFnld="1";}
				else{$TipoFnld="2";}
			}?>
        <td>
        	<select name="TipoFnld" onChange="document.FORMA.submit()">
            	<option></option>
                <option value="1"<? if($TipoFnld=="1"){?> selected<? }?>>Consulta</option>
                <option value="2"<? if($TipoFnld=="2"){?> selected<? }?>>Procedimiento</option>
            </select>
        </td>
	<?  if($TipoFnld){
			$cons="select finalidad,codigo from salud.finalidadesact where tipo=$TipoFnld";	
    	    $res=ExQuery($cons);
		}?>
        <td bgcolor="#e5e5e5" style="font-weight:bold">Finalidad Procedimiento</td>
        <td>
            <select name="FinalidadProc"><?
			if($TipoFnld)
			{
				while($fila=ExFetch($res)){
					if($FinalidadProc==$fila[1]){
						echo "<option value='$fila[1]' selected>$fila[0]</option>";
					}
					else{
						echo "<option value='$fila[1]'>$fila[0]</option>";
					}	
				}
			}?>
            </select>
        </td>    
    </tr>
    <tr align="center">
    	<td colspan="11" bgcolor="#e5e5e5" style="font-weight:bold">Justificacion</td>
  	</tr>
    <tr>
        <td colspan="11">
        	<textarea name="Justific" rows="3" style="width:100%"><? echo $Justific?></textarea>
        </td>
  	</tr>
     <tr align="center">
    	<td colspan="11" bgcolor="#e5e5e5" style="font-weight:bold">Observaciones</td>
   	</tr>
    <tr>
        <td colspan="11">
        	<textarea name="Observ" rows="3" style="width:100%"><? echo $Observ?></textarea>
        </td>
  	</tr>
    <tr>
    	<td colspan="11" align="center">
        	<input type="submit" value="Agregar" name="Agregar">
            <input type="button" value="Cancelar" onClick="location.href='NewPaquete.php?DatNameSID=<? echo $DatNameSID?>&IdPaquete=<? echo $IdPaquete?>&Editar=1'">
        </td>
    </tr>
</table>
</form>
<iframe scrolling="yes" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>  
</body>
</html>    