<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Guardar){
		$cons="select numservicio from salud.servicios where cedula='$Paciente[1]' and compania='$Compania[0]' and estado='AC'";
		$res = ExQuery($cons);echo ExError($res);
		$fila = ExFetch($res);
		$NumServ=$fila[0];		
		$cons="select numorden from salud.ordenesmedicas where cedula='$Paciente[1]' and compania='$Compania[0]' and idescritura='$IdEscritura' order by numorden desc";
		$res = ExQuery($cons);echo ExError($res);
		if(ExNumRows($res)){
			$fila = ExFetch($res);		
			$AutoId = $fila[0]+1;
		}
		else{
			$AutoId=1;
		}
//---------------------------------------------------------------------------Insercion en la tabla servicos-------------------------------------------------------------------------------
		if($EstadoSalida='Muerto'){$EstadoSalida='2';}else{$EstadoSalida='1';}
		$cons="update salud.servicios set usuordensalida='$usuario[1]',ordensalida='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]', estadosalida='$EstadoSalida'
		where compania='$Compania[0]' and 	
		cedula='$Paciente[1]' and numservicio=$NumServ";
		$res = ExQuery($cons);echo ExError($res);
//-----------------------------------------------------------------------Insercion en la tabla ordenes medicas----------------------------------------------------------------------------
		$noju='';
        if($Justificacion){
            $noju .= " - Justificación: ".$Justificacion;
        }
        if($Observaciones){
            $noju .= " - Observación: ".$Observaciones;
        }
		$Detalle="Egreso paciente por: ".$MotivoSalida." ".$noju;
		//$Detalle="Egreso paciente por: ".$MotivoSalida." - ".$Justificacion." - ".$Observaciones;
		$cons="insert into salud.ordenesmedicas(compania,fecha,cedula,numservicio,detalle,idescritura,numorden,usuario,tipoorden,estado,acarreo, iniciado) values 
		('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]',$NumServ,'$Detalle',$IdEscritura,$AutoId,'$usuario[1]','Orden Egreso','AC',1, 0)";				
		$res = ExQuery($cons);echo ExError($res);
		//echo $cons;
//-------------------------------------------------------------------------INSERCION EN TABLA DIAGNOSTICOS--------------------------------------------------------------------------------	
		$cons7 = "Select IdDx from salud.diagnosticos where Compania = '$Compania[0]' order by IdDx desc";					
		//echo $cons5;
		$res7 = ExQuery($cons7);echo ExError($res7);
		$fila7 = ExFetch($res7);			
		$IdDx = $fila7[0]+1;
		$cons2="insert into salud.diagnosticos(compania,cedula,numservicio,clasedx,usuario,fecha,iddx,diagnostico) values ('$Compania[0]','$Paciente[1]',$NumServ,'Egreso','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',$IdDx,'$CodDiagnostico1')";
		//echo $cons2;
		$res2=ExQuery($cons2);echo ExError();
		if($CodDiagnostico2){
			$cons7 = "Select IdDx from salud.diagnosticos where Compania = '$Compania[0]' order by IdDx desc";					
			//echo $cons5;
			$res7 = ExQuery($cons7);
			$fila7 = ExFetch($res7);			
			$IdDx = $fila7[0]+1;
			$cons2="insert into salud.diagnosticos(compania,cedula,numservicio,clasedx,usuario,fecha,iddx,diagnostico) values ('$Compania[0]','$Paciente[1]',$NumServ,'Egreso','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',$IdDx,'$CodDiagnostico2')";
			//echo $cons2;
			$res2=ExQuery($cons2);echo ExError();
		}
		if($CodDiagnostico3){
			$cons7 = "Select IdDx from salud.diagnosticos where Compania = '$Compania[0]' order by IdDx desc";					
			//echo $cons5;
			$res7 = ExQuery($cons7);echo ExError($res7);
			$fila7 = ExFetch($res7);			
			$IdDx = $fila7[0]+1;
			$cons2="insert into salud.diagnosticos(compania,cedula,numservicio,clasedx,usuario,fecha,iddx,diagnostico) values ('$Compania[0]','$Paciente[1]',$NumServ,'Egreso','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',$IdDx,'$CodDiagnostico3')";
			//echo $cons2;
			$res2=ExQuery($cons2);echo ExError();
		}
		if($CodDiagnostico4){
			$cons7 = "Select IdDx from salud.diagnosticos where Compania = '$Compania[0]' order by IdDx desc";					
			//echo $cons5;
			$res7 = ExQuery($cons7);echo ExError($res7);
			$fila7 = ExFetch($res7);			
			$IdDx = $fila7[0]+1;
			$cons2="insert into salud.diagnosticos(compania,cedula,numservicio,clasedx,usuario,fecha,iddx,diagnostico) values ('$Compania[0]','$Paciente[1]',$NumServ,'Egreso','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',$IdDx,'$CodDiagnostico4')";
			//echo $cons2;
			$res2=ExQuery($cons2);echo ExError();
		}
		if($CodDiagnostico5){
			$cons7 = "Select IdDx from salud.diagnosticos where Compania = '$Compania[0]' order by IdDx desc";					
			//echo $cons5;
			$res7 = ExQuery($cons7);echo ExError($res7);
			$fila7 = ExFetch($res7);			
			$IdDx = $fila7[0]+1;
			$cons2="insert into salud.diagnosticos(compania,cedula,numservicio,clasedx,usuario,fecha,iddx,diagnostico) values ('$Compania[0]','$Paciente[1]',$NumServ,'Egreso','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',$IdDx,'$CodDiagnostico5')";
			//echo $cons2;
			$res2=ExQuery($cons2);echo ExError();
		}
		if($CodDiagnostico6){
			$cons7 = "Select IdDx from salud.diagnosticos where Compania = '$Compania[0]' order by IdDx desc";					
			//echo $cons5;
			$res7 = ExQuery($cons7);echo ExError($res7);
			$fila7 = ExFetch($res7);			
			$IdDx = $fila7[0]+1;
			$cons2="insert into salud.diagnosticos(compania,cedula,numservicio,clasedx,usuario,fecha,iddx,diagnostico) values ('$Compania[0]','$Paciente[1]',$NumServ,'Muerte','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',$IdDx,'$CodDiagnostico6')";
			//echo $cons2;
			$res2=ExQuery($cons2);echo ExError();
		}?>
		<script language="javascript">
			location.href='NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>';
		</script>
	<?
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function ValidaDiagnostico2(Objeto1,Objeto2)
	{		
		frames.FrameOpener2.location.href="ValidaDiagnostico2.php?DatNameSID=<? echo $DatNameSID?>&NameCod="+Objeto1.name+"&NameNom="+Objeto2.name;
		document.getElementById('FrameOpener2').style.position='absolute';
		document.getElementById('FrameOpener2').style.top='360px';
		document.getElementById('FrameOpener2').style.left='50px';
		document.getElementById('FrameOpener2').style.display='';
		document.getElementById('FrameOpener2').style.width='800px';
		document.getElementById('FrameOpener2').style.height='200px';
	}
function validar(){			
	
	if(document.FORMA.CodDiagnostico1.value==""){
		alert("Debe haber al menos un Diagnostico!!!");return false;
	}
	if(document.FORMA.EstadoSalida.value==""){
		alert("Debe seleccionar el estado de salida"); return false;
	}
	if(document.FORMA.EstadoSalida.value="Muerto"&&document.FORMA.CodDiagnostico6.value==""){
		alert("Debe digitar el diagnostico de muerte!!!");return false;
	}
}
</script>	
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return validar()">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center"> 
	<tr>
    	<td align="center" colspan="4" bgcolor="#e5e5e5" style="font-weight:bold">Diagnostico de Egreso</td>
    </tr>
    <tr>    	
    	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Codigo</td><td  bgcolor="#e5e5e5" style="font-weight:bold" colspan="3" align="center">Nombre</td>      
    </tr>
    <tr>
    	<td ><input style="width:100" type="text" readonly name="CodDiagnostico1" onFocus="ValidaDiagnostico2(this,NomDiagnostico1)"  onKeyUp="ValidaDiagnostico2(this,NomDiagnostico1);xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $CodDiagnostico1?>"></td>
        <td colspan="3"><input type="text" style="width:500" name="NomDiagnostico1" readonly onFocus="ValidaDiagnostico2(CodDiagnostico1,this)"  onKeyUp="ValidaDiagnostico2(CodDiagnostico1,this);xLetra(this)" onKeyDown="ExLetra(this)" value="<? echo $NomDiagnostico1?>"></td>
    </tr>   
    <tr>
    	<td><input style="width:100" type="text" readonly name="CodDiagnostico2" onFocus="ValidaDiagnostico2(this,NomDiagnostico2)"  onKeyUp="ValidaDiagnostico2(this,NomDiagnostico2);xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $CodDiagnostico2?>"></td>
        <td colspan="3"><input type="text" style="width:100%" name="NomDiagnostico2" onFocus="ValidaDiagnostico2(CodDiagnostico2,this)"  onKeyUp="ValidaDiagnostico2(CodDiagnostico2,this);xLetra(this)" readonly onKeyDown="ExLetra(this)" value="<? echo $NomDiagnostico2?>"></td>
    </tr>
     <tr>
    	<td><input style="width:100" type="text" readonly name="CodDiagnostico3" onFocus="ValidaDiagnostico2(this,NomDiagnostico3)"  onKeyUp="ValidaDiagnostico2(this,NomDiagnostico3);xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $CodDiagnostico3?>"></td>
        <td colspan="3"><input type="text" style="width:100%" name="NomDiagnostico3" onFocus="ValidaDiagnostico2(CodDiagnostico3,this)"  onKeyUp="ValidaDiagnostico2(CodDiagnostico3,this);xLetra(this)" readonly onKeyDown="ExLetra(this)" value="<? echo $NomDiagnostico3?>"></td>
    </tr>  
     <tr>
    	<td><input style="width:100" type="text" readonly name="CodDiagnostico4" onFocus="ValidaDiagnostico2(this,NomDiagnostico4)"  onKeyUp="ValidaDiagnostico2(this,NomDiagnostico4);xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $CodDiagnostico4?>"></td>
        <td colspan="3"><input type="text" style="width:100%" name="NomDiagnostico4" onFocus="ValidaDiagnostico2(CodDiagnostico4,this)"  onKeyUp="ValidaDiagnostico2(CodDiagnostico4,this);xLetra(this)" readonly onKeyDown="ExLetra(this)" value="<? echo $NomDiagnostico4?>"></td>
    </tr>
     <tr>
    	<td><input style="width:100" type="text" name="CodDiagnostico5" readonly onFocus="ValidaDiagnostico2(this,NomDiagnostico5)"  onKeyUp="ValidaDiagnostico2(this,NomDiagnostico5);xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $CodDiagnostico5?>"></td>
        <td colspan="3"><input type="text" style="width:100%" name="NomDiagnostico5" onFocus="ValidaDiagnostico2(CodDiagnostico5,this)"  onKeyUp="ValidaDiagnostico2(CodDiagnostico5,this);xLetra(this)" readonly onKeyDown="ExLetra(this)" value="<? echo $NomDiagnostico5?>"></td>
    </tr>
    <tr><td align="center" bgcolor="#e5e5e5" style="font-weight:bold"> Estado Salida</td>
    	<td><select name="EstadoSalida" onChange="document.FORMA.submit()"><option></option>
       	<? 	if($EstadoSalida=='Vivo'){?>
        		<option value="Vivo" selected>Vivo</option>
		<?	}
			else{?>
				<option value="Vivo">Vivo</option>
		<?	}?>
		<? 	if($EstadoSalida=='Muerto'){?>
        		<option value="Muerto" selected>Muerto</option>
		<?	}
			else{?>
				<option value="Muerto">Muerto</option>
		<?	}?>
        </select></td>
    </tr>        
<?	if($EstadoSalida=='Muerto'){?>
    	<tr>
    		<td align="center" colspan="4" bgcolor="#e5e5e5" style="font-weight:bold">Diagnostico de Muerte</td>
	    </tr>
        <tr>
        	
            <td><input style="width:100" type="text" name="CodDiagnostico6" readonly onFocus="ValidaDiagnostico2(this,NomDiagnostico6)"  onKeyUp="ValidaDiagnostico2(this,NomDiagnostico6);xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $CodDiagnostico6?>"></td>
       		<td colspan="3"><input type="text" style="width:100%" name="NomDiagnostico6" onFocus="ValidaDiagnostico2(CodDiagnostico6,this)"  onKeyUp="ValidaDiagnostico2(CodDiagnostico6,this);xLetra(this)" readonly onKeyDown="ExLetra(this)" value="<? echo $NomDiagnostico6?>"></td>
    	</tr>
<?	}?>    
	<tr>
    	<td align="center" bgcolor="#e5e5e5" style="font-weight:bold">Motivo Salida</td>
        <td><select name="MotivoSalida">
		<option></option>
    <?	$cons="Select motivosalida from salud.motivosalida where compania='$Compania[0]' and estadosalida='$EstadoSalida'";
		$res=ExQuery($cons);
		while($fila=ExFetch($res)){
			if($MotivoSalida==$fila[0]){
				echo "<option value='$fila[0]' selected>$fila[0]</option>";
			}
			else{
				echo "<option value='$fila[0]'>$fila[0]</option>";
			}
		}
		?>
        </select></td>
    </tr>
    <tr>
    	<td align="center" bgcolor="#e5e5e5" style="font-weight:bold">Justificación</td>
        <td><textarea name="Justificacion"  onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" cols="60" rows="6"><? echo $Justificacion?></textarea></td>
    </tr>
    <tr>
    	<td align="center" bgcolor="#e5e5e5" style="font-weight:bold">Observaciones</td>
        <td><textarea name="Observaciones"  onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" cols="60" rows="6"><? echo $Observaciones?></textarea></td>
    </tr>
    <tr>
	    <td colspan="4" align="center"><input type="submit" name="Guardar" value="Guardar"><input type="button" value="Cancelar" onClick="location.href='NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>'"></td>
	</tr>    
</table>
<input type="hidden" name="IdEscritura" value="<? echo $IdEscritura?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<iframe scrolling="yes" id="FrameOpener2" name="FrameOpener2" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>    
</body>
</html>
