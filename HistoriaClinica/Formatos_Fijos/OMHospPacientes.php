<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$ND=getdate();
	$cons6="select numservicio,tiposervicio from salud.servicios 
	where compania='$Compania[0]' and servicios.cedula='$Paciente[1]' and estado='AC' order by numservicio desc";
	$res6=ExQuery($cons6);
	$fila6=ExFetch($res6);
	$NumServAnt=$fila6[0]; 
	$TipoServ=$fila6[1]; 
	
	if(!$NumServAnt){$NumServAnt="0";}
	else
	{
		$consAm="select urgencias from salud.ambitos where compania='$Compania[0]' and ambito='$TipoServ'";	
		$resAm=ExQuery($consAm); $filaAm=ExFetch($resAm); if($filaAm[0]=='1'){$SoloCambio=1;}
	}
	if($Guardar){
		$cons5 = "Select numservicio from Salud.Servicios where Compania = '$Compania[0]' order by numservicio desc";					
		//echo $cons5;
		$res5 = ExQuery($cons5);
		$fila5 = ExFetch($res5);			
		$AutoId = $fila5[0]+1;
		//$consfactura="update histoclinicafrms.tbl00047 set ambito='Hospitalizacion',numservicio='$AutoId' where numservicio='$fila6[0]' and cedula='$Paciente[1]'";
		$resfactura = ExQuery($consfactura);
		$filafactura = ExFetch($resfactura);
//---------------------------------INSERCION EN TABLA SERVICIOS---------------------------------------------------------------------		
		$cons43="Select usuario from salud.medicos where asignadefecto='1'";
		$res43=ExQuery($cons43);
		$fila43=ExFetch($res43);
		$MedTte=$fila43[0];
			
			
		if($SoloCambio!=1){	
			$cons6="update salud.servicios set estado='AN',fechaegr='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[mons]:$ND[minutes]',usuegreso='$usuario[1]'
			,usumodserv='$usuario[1]',fecmodserv='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',pagmodif='OMHospPacientes.php',
			medicotte='$MedTte'
			where servicios.cedula='$Paciente[1]' and numservicio=$NumServAnt and compania='$Compania[0]'";
			//echo $cons6;
			$res=ExQuery($cons6);
			$cons6="insert into 		
			salud.servicios
			(cedula,numservicio,tiposervicio,fechaing,tipousu,nivelusu,autorizac1,autorizac2,autorizac3,estado,nocarnet,compania,usuarioingreso,viaingreso,causaexterna,medicotte,clinica,pagina,usucreaserv) 
			values ('$Paciente[1]','$AutoId','$Ambito','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Tipousu','$Nivelusu','$Autorizac1','$Autorizac2',
			'$Autorizac3','AC','$Nocarnet','$Compania[0]','$usuario[1]','$ViaIngreso','$CausaExterna','$Medicotte','$Clinica','OMHospPacientes.php','$usuario[1]')";
			//echo $cons6;
			$res6=ExQuery($cons6);
		}
		else
		{
			$AutoId=$NumServAnt;
			$cons6="update salud.servicios set tiposervicio='$Ambito',usuarioingreso='$usuario[1]',viaingreso='$ViaIngreso',causaexterna='$CausaExterna'
			,clinica='$Clinica',fechaing='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]'
			,usumodserv='$usuario[1]',fecmodserv='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',pagmodif='OMHospPacientes.php',medicotte='$Medicotte' 
			where servicios.cedula='$Paciente[1]' and numservicio=$NumServAnt and compania='$Compania[0]'";
			$res6=ExQuery($cons6);
		}
		if($Entidad!=''){
			$cons="insert into salud.pagadorxservicios (numservicio,compania,entidad,contrato,nocontrato,fechaini,usuariocre,fechacre) values
			($AutoId,'$Compania[0]','$Entidad','$Contrato','$NoContrato','$ND[year]-$ND[mon]-$ND[mday]','$usuario[1]',
			'$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]')";
			//echo $cons;
			$res=ExQuery($cons);
		}
	
//----------------------------------------------------------------INSERCION EN TABLAS PACIENTES X PABELLONES---------------------------------------------------------------------		
		
		$cons2="insert into salud.pacientesxpabellones(usuario,cedula,pabellon,estado,fechai,horai,ambito,numservicio,compania,idcama) values ('$usuario[1]','$Paciente[1]','$UnidadHosp','AC','$ND[year]-$ND[mon]-$ND[mday]','$ND[hours]:$ND[minutes]:$ND[seconds]','$Ambito',$AutoId,'$Compania[0]',0)";
		//echo $cons2;
		$res2=ExQuery($cons2);echo ExError();
//-----------------------------------------------------------------INSERCION EN TABLA DIAGNOSTICOS--------------------------------------------------------------------------------	
		$cons7 = "Select IdDx from salud.diagnosticos where Compania = '$Compania[0]' order by IdDx desc";					
		//echo $cons5;
		$res7 = ExQuery($cons7);
		$fila7 = ExFetch($res7);			
		$IdDx = $fila7[0]+1;
		$cons2="insert into salud.diagnosticos(compania,cedula,numservicio,clasedx,tipodx,usuario,fecha,iddx,diagnostico) values ('$Compania[0]','$Paciente[1]',$AutoId,'Ingreso','$TipoDiag','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',$IdDx,'$CodDiagnostico1')";
		//echo $cons2;
		$res2=ExQuery($cons2);echo ExError();
		if($CodDiagnostico2){
			$cons7 = "Select IdDx from salud.diagnosticos where Compania = '$Compania[0]' order by IdDx desc";					
			//echo $cons5;
			$res7 = ExQuery($cons7);
			$fila7 = ExFetch($res7);			
			$IdDx = $fila7[0]+1;
			$cons2="insert into salud.diagnosticos(compania,cedula,numservicio,clasedx,tipodx,usuario,fecha,iddx,diagnostico) values ('$Compania[0]','$Paciente[1]',$AutoId,'Ingreso','$TipoDiag','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',$IdDx,'$CodDiagnostico2')";
			//echo $cons2;
			$res2=ExQuery($cons2);echo ExError();
		}
		if($CodDiagnostico3){
			$cons7 = "Select IdDx from salud.diagnosticos where Compania = '$Compania[0]' order by IdDx desc";					
			//echo $cons5;
			$res7 = ExQuery($cons7);
			$fila7 = ExFetch($res7);			
			$IdDx = $fila7[0]+1;
			$cons2="insert into salud.diagnosticos(compania,cedula,numservicio,clasedx,tipodx,usuario,fecha,iddx,diagnostico) values ('$Compania[0]','$Paciente[1]',$AutoId,'Ingreso','$TipoDiag','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',$IdDx,'$CodDiagnostico3')";
			//echo $cons2;
			$res2=ExQuery($cons2);echo ExError();
		}
		if($CodDiagnostico4){
			$cons7 = "Select IdDx from salud.diagnosticos where Compania = '$Compania[0]' order by IdDx desc";					
			//echo $cons5;
			$res7 = ExQuery($cons7);
			$fila7 = ExFetch($res7);			
			$IdDx = $fila7[0]+1;
			$cons2="insert into salud.diagnosticos(compania,cedula,numservicio,clasedx,tipodx,usuario,fecha,iddx,diagnostico) values ('$Compania[0]','$Paciente[1]',$AutoId,'Ingreso','$TipoDiag','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',$IdDx,'$CodDiagnostico4')";
			//echo $cons2;
			$res2=ExQuery($cons2);echo ExError();
		}
		if($CodDiagnostico5){
			$cons7 = "Select IdDx from salud.diagnosticos where Compania = '$Compania[0]' order by IdDx desc";					
			//echo $cons5;
			$res7 = ExQuery($cons7);
			$fila7 = ExFetch($res7);			
			$IdDx = $fila7[0]+1;
			$cons2="insert into salud.diagnosticos(compania,cedula,numservicio,clasedx,tipodx,usuario,fecha,iddx,diagnostico) values ('$Compania[0]','$Paciente[1]',$AutoId,'Ingreso','$TipoDiag','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',$IdDx,'$CodDiagnostico5')";
			//echo $cons2;
			$res2=ExQuery($cons2);echo ExError();
		}
//---------------------------------------------------------------------------INSERCION EN TABLA ORDENES MEDICAS---------------------------------------------------------------		
		$cons8 = "Select numorden from salud.ordenesmedicas where cedula='$Paciente[1]' and Compania = '$Compania[0]' and idescritura=$IdEscritura order by numorden desc";					
		//echo $cons5;
		$res8 = ExQuery($cons8);
		if(ExNumRows($res8)>0){
			$fila8 = ExFetch($res8);			
			$Numorden = $fila8[0]+1;
		}
		else{
			$Numorden = 1;
		}
		$Detalle="Ingresar paciente en Unidad ".$UnidadHosp;
		$cons2="insert into salud.ordenesmedicas(compania,fecha,cedula,numservicio,detalle,idescritura,numorden,usuario,tipoorden,estado,acarreo) values ('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]',$AutoId,'$Detalle',$IdEscritura,'$Numorden','$usuario[1]','Ingreso','AC',0)";
		//echo $cons2;
		$res2=ExQuery($cons2);echo ExError();
	?>	<script language="javascript">
		location.href='NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>';
		</script>
	<?
	}

	if(!$Entidad&&$Ban!=1){
		$cons="select eps from central.terceros where compania='$Compania[0]' and identificacion='$Paciente[1]'";	
		$res=ExQuery($cons);echo ExError();
		$fila=ExFetch($res);
		$Entidad=$fila[0];	
		$Ban=1;
		if(!$Entidad){
			$cons="Select identificacion,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom) as Nombre,cuotamoderadora  from Central.Terceros where Tipo='Asegurador' and Compania='$Compania[0]' order by primape";
			$res=ExQuery($cons);echo ExError();
			$fila=ExFetch($res);
			$Entidad=$fila[0];	
		}
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function ValidaDiagnostico2(Objeto1,Objeto2)
	{		
		st = document.body.scrollTop;
		frames.FrameOpener2.location.href="ValidaDiagnostico2.php?DatNameSID=<? echo $DatNameSID?>&NameCod="+Objeto1.name+"&NameNom="+Objeto2.name;
		document.getElementById('FrameOpener2').style.position='absolute';
		document.getElementById('FrameOpener2').style.top=st+80;
		document.getElementById('FrameOpener2').style.left='50px';
		document.getElementById('FrameOpener2').style.display='';
		document.getElementById('FrameOpener2').style.width='800px';
		document.getElementById('FrameOpener2').style.height='200px';
	}
function validar(){		
	
	if(document.FORMA.Medicotte.value==''){
		alert("Debe elegir un medico tratante!!!");return false;
	}
	if(document.FORMA.UnidadHosp.value==''){
		alert("Debe haber una Unidad!!!");return false;
	}
	if(document.FORMA.CodDiagnostico1.value==""){
		alert("Debe haber al menos un Diagnostico!!!");return false;
	}
	if(document.FORMA.CamasDispo.value=='0'){
		alert("Esta unidad no tiene cams disponibles!!!");return false;
	}
	if(document.FORMA.Entidad.value==""){		
		alert("Debe seleccionar una entidad!!!");return false;	
	}
	if(document.FORMA.Contrato.value==''){
		alert("Debe haber un Contrato!!!");return false;
	}			
	if(document.FORMA.NoContrato.value==''){
		alert("Debe haber un Numero de Contrato!!!");return false;
	}
	if(document.FORMA.Nocarnet.value==''){
		alert("Debe digitar el Numer de Carnet!!!");return false;
	}	
}	
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return validar()">
	<input type="hidden" name="CamasDispo">
	<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center"> 
	<!--
	<tr>
	
	    <td bgcolor="#e5e5e5" style="font-weight:bold">Medico Tratante</td>
        <td><select name="Medicotte">
        	<option></option>
		<?
		$cons="select nombre,medicos.usuario from salud.cargos,salud.medicos,central.usuarios where salud.cargos.compania='$Compania[0]' and salud.medicos.compania='$Compania[0]' and salud.cargos.cargos=salud.medicos.cargo and Medicos.usuario=usuarios.usuario and asistencial=1 and UPPER(salud.medicos.especialidad)='PSIQUIATRIA' order by nombre";
		//echo $cons;
		$res=ExQuery($cons);		
		while($fila=ExFetch($res))
		{	
					
			if($fila[1]==$Medicotte){
				?><option selected value="<? echo $fila[1];?>"><? echo $fila[0];?></option><? 
			}
			else{?>			
			<option value="<? echo $fila[1];?>"><? echo $fila[0];?></option>			
			<? }			 
		}?>
		</select></td>
	</tr>
	-->
	<tr>
	<td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Proceso</td>
    <td>
	

	
 <?	if($Ambito==''){ echo "<input type='hidden' name='Ambito' value='1'>";$Ambito=1;}?>
    <select name="Ambito" onChange="document.FORMA.submit()"><option></option>    
		<?	
			$cons="select ambito from salud.ambitos where compania='$Compania[0]' and ambito!='Sin Ambito' and consultaextern=0 and hospitalizacion=1 order by ambito";	
			$res=ExQuery($cons);echo ExError();	
			while($fila = ExFetch($res)){
				if($fila[0]==$Ambito){
					echo "<option value='$fila[0]' selected>$fila[0]</option>";
				}
				else{
					echo "<option value='$fila[0]'>$fila[0]</option>";
				}
			}?>
   		</select></td>
  
   	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Servicio</td>
   <?	$consult="Select * from Salud.Pabellones where ambito='$Ambito' and Compania='$Compania[0]'";		
		$result=ExQuery($consult);
		if(ExNumRows($result)>0){?>        	           
		<td><select name="UnidadHosp" onChange="frames.FrameOpener.location.href='CamasHospitalizacion.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>&UnidadHosp='+this.value">        	
		<?	while($row = ExFetchArray($result)){				
				if($row[0]==$UnidadHosp){
					echo "<option value='$row[0]' selected>$row[0]</option>";
				}
				else{
					echo "<option value='$row[0]'>$row[0]</option>";
				}
			}
		?>	</select></td><?
		}
		else{
			if($Ambito){
				echo "<input type='hidden' name='UnidadHosp' value=''>";
				if($Ambito!=1){
					echo "<td style='font-weight:bold' align='center' colspan='7'>No se han hasignado unidades a este proceso</td>";
				}
			}			
		}?>
 	</tr>
    <tr><td align="center" colspan="4"><iframe scrolling="no"  id="FrameOpener" name="FrameOpener" style="display:" frameborder="0" height="100">
</iframe></td>
    </tr>
	<!--
    <tr>
    	<td align="center" colspan="4" bgcolor="#e5e5e5" style="font-weight:bold">Diagnostico de Ingreso</td>
    </tr>
    <tr>    	
    	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Codigo</td><td  bgcolor="#e5e5e5" style="font-weight:bold" colspan="3" align="center">Nombre</td>      
    </tr>
    <tr>
    	<td ><input style="width:100" type="text" readonly name="CodDiagnostico1" onFocus="ValidaDiagnostico2(this,NomDiagnostico1)"  onKeyUp="ValidaDiagnostico2(this,NomDiagnostico1);xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $CodDiagnostico1?>"></td>
        <td colspan="3"><input type="text" style="width:100%" name="NomDiagnostico1" readonly onFocus="ValidaDiagnostico2(CodDiagnostico1,this)"  onKeyUp="ValidaDiagnostico2(CodDiagnostico1,this);xLetra(this)" onKeyDown="ExLetra(this)" value="<? echo $NomDiagnostico1?>"></td>
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
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Clinica</td>
        <?	$cons="select clinica from salud.clinicashc where compania='$Compania[0]' order by clinica";
        $res=ExQuery($cons);?>
        <td colspan="5">
            <select name="Clinica">
                <option></option>
            <?	while($fila=ExFetch($res))
                {
                    if($fila[0]==$Clinica){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
                    else{echo "<option value='$fila[0]'>$fila[0]</option>";}
                }?>
            </select>
		</td>
    </tr>   
    <tr>
    <? 	$cons="select codigo,tipodiagnost from salud.tiposdiagnostico where compania='$Compania[0]'";?>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Tipo Diagnostico</td>
    	<td colspan="3"><select name="TipoDiag">
     <? $res=ExQuery($cons);echo ExError();	
		while($fila = ExFetch($res)){
			if($fila[0]==$TipoDiag){
				echo "<option value='$fila[0]' selected>$fila[1]</option>";
			}
			else{
				echo "<option value='$fila[0]'>$fila[1]</option>";
			}
		}?>
        </select></td>
    </tr>
     <tr>
    <? 	$cons="select codigo,viaingreso from salud.viasingreso order by viaingreso";?>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Via de Ingreso</td>
    	<td colspan="3"><select name="ViaIngreso">
     <? $res=ExQuery($cons);echo ExError();	
		while($fila = ExFetch($res)){
			if($fila[0]==$ViaIngreso){
				echo "<option value='$fila[0]' selected>$fila[1]</option>";
			}
			else{
				echo "<option value='$fila[0]'>$fila[1]</option>";
			}
		}?>
        </select></td>
    </tr>
    <?	//if($TipoFinalidad=="1"){    
	$cons="select causa,codigo from salud.causaexterna order by causa";
	$res=ExQuery($cons);?>
    <tr>
	    <td align="left" bgcolor="#e5e5e5" style="font-weight:bold">Causa Externa</td>
  		<td>	
    	    <select name="CausaExterna"><?
				while($fila=ExFetch($res)){
		    	    if($CausaExterna==$fila[1]){
    	    			echo "<option value='$fila[1]' selected>$fila[0]</option>";
	        	  	}
					else{
						echo "<option value='$fila[1]'>$fila[0]</option>";
					}			
				}
    	?>	</select>
       	</td>
    </tr>
    <tr>
	<? 	$cons="Select identificacion,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom) as Nombre,cuotamoderadora  from Central.Terceros where Tipo='Asegurador' and Compania='$Compania[0]' order by primape";		
		$res=ExQuery($cons);echo ExError();	?>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Entidad</td>
        <td colspan="3"><select name="Entidad" onChange="document.FORMA.submit()"><option></option>
     <?	while($fila=ExFetch($res))
		{		
			if($fila[0]==$Entidad){echo "<option selected value='$fila[0]'>$fila[1]</option>";
			}
			else{
			echo "<option value='$fila[0]'>$fila[1]</option>";
			}						
	}?>
        </select></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Contrato</td>
        <td><select name="Contrato" onChange="document.FORMA.submit()">
    <?	$cons="select contrato from contratacionsalud.contratos where compania='$Compania[0]' and Entidad='$Entidad' and estado='AC'
		and ambitocontrato='Recuperacion' Group By Contrato"; 
		$res=ExQuery($cons);
		$banContrato=0;
		while($fila=ExFetch($res))
		{	
			if($Contrato==$fila[0]){
					echo "<option selected value='$fila[0]'>$fila[0]</option>";$Aux=$fila[0];
				}
				else{
					echo "<option value='$fila[0]'>$fila[0]</option>";
				}
		}
		if($Aux!=''){$Contra=$Aux;}
		?> 
        </select></td>    
        <? 
	 	if(!$Contra){
	 		$cons="select contrato from contratacionsalud.contratos where compania='$Compania[0]' and Entidad='$Entidad' and estado='AC' 
			and ambitocontrato='Recuperacion' Group By Contrato"; 
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$Contra=$fila[0];
	 	}
		
        $cons="select numero from contratacionsalud.contratos where compania='$Compania[0]' and Entidad='$Entidad' and estado='AC' and  Contrato='$Contra'
		and estado='AC'	and fechaini<='$ND[year]-$ND[mon]-$ND[mday]' and (fechafin>='$ND[year]-$ND[mon]-$ND[mday]' or fechafin is null)
		and ambitocontrato='Recuperacion'"; ?>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">No Contrato</td>
        <td><select name="NoContrato">
   <?	$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			if($NoContrato==$fila[0]){
					echo "<option selected value='$fila[0]'>$fila[0]</option>";
				}
				else{
					echo "<option value='$fila[0]'>$fila[0]</option>";
				}
		}?>
        </select></td>
    </tr>
    <tr>
   	 <? $cons3="select tipousu,nivelusu,nocarnet from central.terceros where compania='$Compania[0]' and identificacion='$Paciente[1]'";	
	 	$res3=ExQuery($cons3);$fila3=ExFetch($res3);
		if(!$Tipousua){$Tipousua=$fila3[0];}
		if(!$Nivelusu){$Nivelusu=$fila3[1];}
		if(!$Nocarnet){$Nocarnet=$fila3[2];}?>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Tipo Usuario</td>
        <td><select name="Tipousua" onChange="document.FORMA.submit()">
        <?	$cons="select * from salud.tiposusuarios"; 
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			if($fila[0]==$Tipousua){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
			else{echo "<option value='$fila[0]'>$fila[0]</option>";}
		}?>
        </select></td>    
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Nivel Usuario</td>
        <td><select name="Nivelusu">
    <?	$cons="select * from salud.nivelesusu"; 
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			if($fila[0]==$Nivelusu){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
			else{echo "<option value='$fila[0]'>$fila[0]</option>";}
		}?>
        </select></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">No. Carnet</td>
        <td><input type="text" name="Nocarnet" value="<? echo $Nocarnet?>" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"></td>    
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Autorizacion 1</td>
        <td><input type="text" name="Autorizac1" value="<? echo $Autorizac1?>" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Autorizacion 2</td>
        <td><input type="text" name="Autorizac2" value="<? echo $Autorizac2?>" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"></td>
        <td bgcolor="#e5e5e5" style="font-weight:bold">Autorizacion 3</td>
        <td><input type="text" name="Autorizac3" value="<? echo $Autorizac3?>" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"></td>
    </tr> -->
    <tr>
    	<td colspan="4" align="center"><input type="submit" name="Guardar" value="Guardar"><input type="button" value="Cancelar" onClick="location.href='NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>'"></td>
    </tr>
</table>
<input type="hidden" name="IdEscritura" value="<? echo $IdEscritura?>">
<input type="hidden" name="Ban" value="<? echo $Ban?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<iframe scrolling="yes" id="FrameOpener2" name="FrameOpener2" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>    
<script language="javascript">
frames.FrameOpener.location.href='CamasHospitalizacion.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>&UnidadHosp='+document.FORMA.UnidadHosp.value
</script>

</body>
</html>
