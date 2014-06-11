 <?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$ND=getdate();
	if($ND[mon]<10){$C1="0";}else{$C1="";}
	if($ND[mday]<10){$C2="0";}else{$C2="";}
	$fechaActual="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";
   	$DatoPaciente=explode("-",$Paciente[1]);
  	$Ruta="http://10.18.176.100:8080/salud/HistoriaClinica/OrdenesMedicas.php?CedPaciente=$DatoPaciente[0]";
	
	$cons="select modulo from salud.ususxordmeds where usuario='$usuario[1]'";
	$res=ExQuery($cons);
	if(ExNumRows($res)==0){$NoNewOrd=1;}
	if($RevJefeOA){//Registro de Visto Bueno de Jefe de Enfermeria
		$cons="update salud.ordenesmedicas set jefeenfermeria='$usuario[1]',fechajefe='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]' 
		where compania='$Compania[0]' and cedula='$Paciente[1]' and idescritura='$IdEscr'";
		$res=ExQuery($cons);
		//echo $cons;
		$RevJefeOA=0;
	}
	if($RevAuxOA){  //Registro de Visto Bueno de Auxiliar de Enfermeria
		$cons="update salud.ordenesmedicas set revisadopor='$usuario[1]',fecharevisado='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]' 
		where compania='$Compania[0]' and cedula='$Paciente[1]' and idescritura='$IdEscr'";
		$res=ExQuery($cons);
		//echo $cons;
		$RevAuxOA=0;
	}
		if($Revfarmacia){//Registro de Visto Bueno de Jefe de Enfermeria
		$cons="update salud.ordenesmedicas set usufarmacia='$usuario[1]',fechafarmacia='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]' 
		where compania='$Compania[0]' and cedula='$Paciente[1]' and idescritura='$IdEscr'";
		$res=ExQuery($cons);
		//echo $cons;
		$Revfarmacia=0;
	}
	if($Eliminar){//Eliminar de ordenes medicas (opcion disponible solo para usuarios super)
		$cons="delete from salud.ordenesmedicas where compania='$Compania[0]' and cedula='$Ced' and numservicio=$NumServ and idescritura=$IdEscri and numorden=$NumOrd";
		//echo $cons;
		$res=ExQuery($cons);
	}
		
	//Matris de medicos
	$cons="select nombre,usuarios.usuario,especialidad,cargo from central.usuarios,salud.medicos 
	where medicos.usuario=usuarios.usuario and medicos.compania='$Compania[0]'";
	$res=ExQuery($cons);
	//echo $cons;
	while($fila=ExFetch($res)){
		$Medicos[$fila[1]]=array($fila[0],$fila[3]);
	}
	//
	$consUpD="Update salud.ordenesmedicas set estado='AN',acarreo=0 where compania='$Compania[0]' and cedula='$Paciente[1]' and 
   (tipoorden='Ingreso' or tipoorden='Procedimiento' or tipoorden='Traslado de Unidad' or tipoorden='Suspencion' or tipoorden='Suspension' or  tipoorden='Nota' or tipoorden='Interprograma' or tipoorden='Medicamento Urgente')";
	$resUpD=ExQuery($consUpD);
$consServ="select numservicio from salud.servicios where compania='$Compania[0]' and cedula='$Paciente[1]' order by numservicio desc";
//echo $consServ;
$resServ=ExQuery($consServ);
$filaServ=ExFetch($resServ);

	$consUpd="select autoidprod,numorden,idescritura from salud.plantillamedicamentos 
	where compania='$Compania[0]' and cedpaciente='$Paciente[1]' and estado='AC' and tipomedicamento!='Medicamento Urgente'
	and fechafin is not null and fechafin<'$fechaActual'";
	$resUpD=ExQuery($consUpd);
	//echo $consUpd;
	while($filaUpD=ExFetch($resUpD))
	{		
		$consUpD2="update salud.plantillamedicamentos set estado='AN' where compania='$Compania[0]' and cedpaciente='$Paciente[1]' and estado='AC' and autoidprod=$filaUpD[0]
		and tipomedicamento!='Medicamento Urgente'";
		$resUpD2=ExQuery($consUpD2);
		$consUpD2="update salud.horacantidadxmedicamento set estado='AN' where compania='$Compania[0]' and paciente='$Paciente[1]' 
		and idescritura=$filaUpD[2] and numorden=$filaUpD[1] and tipo!='U'";
		//echo $cons2."<br>";
		$resUpD2=ExQuery($consUpD2);		
		$consUpdD2="update salud.ordenesmedicas set estado='AN',acarreo=0 where compania='$Compania[0]' and cedula='$Paciente[1]' and numorden=$filaUpD[1] and idescritura=$filaUpD[2]";
		$resUpD2=ExQuery($consUpdD2);
		//echo $consUpdD2;
	}
	$consUpd="select numprocedimiento,detalle,justificacion from salud.plantillaprocedimientos 
	where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC' and fechafin is not null and fechafin<'$fechaActual'";
	$resUpD=ExQuery($consUpd);
	while($filaUpD=ExFetch($resUpD))
	{
		$consUpD2="update salud.plantillaprocedimientos set estado='AN' where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC' and numprocedimiento=$filaUpD[0]";
		$resUpD2=ExQuery($consUpD2);
		$consUpD2="update salud.ordenesmedicas set estado='AN',acarreo=1 where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC' and tipoorden='Medicamento Programado'
		and detalle='$filaUpD[1]'";
		$resUpD2=ExQuery($consUpD2);
	}
	$consUpd="select numprocedimiento,detalle from salud.plantillaprocedimientos 
	where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AN' and externo=1";
	$resUpD=ExQuery($consUpd);
	while($filaUpD=ExFetch($resUpD))
	{	
		$consUpD2="update salud.plantillaprocedimientos set estado='AN' where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC' and numprocedimiento=$filaUpD[0]";
		$resUpD2=ExQuery($consUpD2);
		$consUpD2="update salud.ordenesmedicas set estado='AN',acarreo=1 where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC' and tipoorden='Medicamento Programado'
		and detalle='$filaUpD[1]'";
		$resUpD2=ExQuery($consUpD2);
		//echo "$consUpD2";
	}
	/*
	Activar cuando las notas tenga fecha final
	$consUpd="select nota from salud.plantillanotas 
	where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC' and fechafin is not null and fechafin<'$fechaActual'";
	$resUpD=ExQuery($consUpd);
	while($filaUpD=ExFetch($resUpD))
	{
		$consUpD2="update salud.plantillanotas set estado='AN' where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC' and nota='$filaUpD[0]'";
		$resUpD2=ExQuery($consUpD2);
		$consUpD2="update salud.ordenesmedicas set estado='AN',acarreo=1 where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC' and tipoorden='Nota'
		and detalle='$filaUpD[0]'";
		$resUpD2=ExQuery($consUpD2);
	}*/

	if($EliminaOrden==1)	
	{
		$cons="Delete from salud.OrdenesMedicas where Cedula='$Paciente[1]' and NumServicio='$NumServicio' and NumOrden='$NumOrden' and IdEscritura='$IdEscritura' and Compania='$Compania[0]'
		and Usuario='$Usu'";
		$res=ExQuery($cons);
		
		$cons="Delete from salud.plantillamedicamentos where cedpaciente='$Paciente[1]' and NumServicio='$NumServicio' and numorden='$NumOrden' and IdEscritura='$IdEscritura' and Compania='$Compania[0]'
		and Usuario='$Usu'";
		$res=ExQuery($cons);
		$EliminaOrden=NULL;

		unset($EliminaOrden);
		
	}
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
function OpcsImprimir(e,Radios,IdEsc)
	{
		x = e.clientX; 
		y = e.clientY; 
		st = document.body.scrollTop;
		frames.FrameOpener.location.href="OpcImpOrdenes.php?DatNameSID=<? echo $DatNameSID?>&Radios="+Radios+"&IdEsc="+IdEsc;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=(y)+st;
		document.getElementById('FrameOpener').style.left='5%';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='450px';
		document.getElementById('FrameOpener').style.height='300px';
	}
</script>
</head>
<style>
	a{color:black; text-decoration:none;}
	a:hover{color:blue; text-decoration:underline}
</style>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="RevAuxOA" value="<? echo $RevAuxOA?>">
<input type="hidden" name="RevJefeOA" value="<? echo $RevJefeOA?>">
<?
if($ND[mon]<10){$cero='0';}else{$cero='';}
if($ND[mday]<10){$cero1='0';}else{$cero1='';}
$FechaCompActua="$ND[year]-$cero$ND[mon]-$cero1$ND[mday]";
//if($Paciente[48]!=$FechaCompActua){echo "<em><center><br><br><br><br><br><font size=5 color='BLUE'>La Hoja de Identificacion no se ha guardado!!!";exit;}	

if($Paciente[1]&&$Paciente[21]){
	//--IMPRESION
	$cons="Select usuario,cargo from salud.medicos where compania='$Compania[0]' and usuario='$usuario[1]' order by usuario";
	$res=ExQuery($cons);
	//echo $cons;
	$fila=ExFetch($res);
	if($fila)
	{
		$cons="select perfil from  historiaclinica.permisosxformato where permiso='Impresion' and perfil='$fila[1]'group by perfil order by perfil";	
		$res=ExQuery($cons);		
		$fila=ExFetch($res);
		$PermisoImpresion=$fila[0];
	}
	//---	
	$cons="select super from central.usuarios where usuario='$usuario[1]'";
	$res=ExQuery($cons);$fila=ExFetch($res);
	$Super=$fila[0];
	$subcons2="select vistobuenojefe,vistobuenoaux,vistobuenofarmacia from salud.medicos,salud.cargos 
	where cargos.compania='$Compania[0]' and medicos.compania='$Compania[0]' and usuario='$usuario[1]' and medicos.cargo=cargos.cargos";
	//echo $subcons2;
	$subres2=ExQuery($subcons2);
	$row2=ExFetch($subres2);
	$VoBoJefe=$row2[0];
	$VoBoAux=$row2[1];
	$VoBoFar=$row2[2];
	
	?>
	<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="left" cellpadding="4">
        <tr><td colspan="4" align="center"><a style="color:blue" href="<? echo $Ruta?>">Ver Ordenamiento Medico Anterior</a></td></tr>
  	<?	if($NoNewOrd!=1){?>
        	<tr>
            	<td colspan="4" align="center">
                	<input type="button" name="NuevaOrden" value="Nueva Orden" onClick="location.href='NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>'">
             	</td>
        	</tr>
 	<?	}?>	
        <tr>
            <td align="center" style="font-weight:bold" colspan="3">	
                <input type="radio" name="OpcMostrar" onClick="document.FORMA.submit()" value="ServActivo"
                <? if($OpcMostrar=="ServActivo"||$OpcMostrar==''){ echo " checked ";$Radios="ServActivo";}?>/> Servicio Activo 
                <input type="radio" name="OpcMostrar" onClick="document.FORMA.submit()" value="TodasOrdenes" 
                <? if($OpcMostrar=="TodasOrdenes"){ echo " checked ";$Radios="TodasOrdenes";}?>/> Todas las Ordenes
                <input type="radio" name="OpcMostrar" onClick="document.FORMA.submit()" value="Activos" 
                <? if($OpcMostrar=="Activos"){ echo " checked ";$Radios="Activos";}?>/> Ver Ordenes Activas
            </td>
            <input type="hidden" name="Radios" value="<? echo $Radios?>">
        </tr>			
  	<?	if($Radios=="ServActivo")
		{	
			$consServ="select numservicio from salud.servicios where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC'
			order by numservicio desc";
			//echo $consServ;
			$resServ=ExQuery($consServ);
			$filaServ=ExFetch($resServ);

			if(!$filaServ[0]){?>
            	<tr><td><strong>El Paciente No Tiene Servicios Activos</strong></td></tr>
		<?	}
			else
			{	
				$cons3="select ordenesmedicas.detalle,ordenesmedicas.numservicio,ordenesmedicas.numorden,posologia,viasumin,tipoorden,fechareprog
				,salud.ordenesmedicas.usuario,fecha,revisadopor,fecharevisado,jefeenfermeria,fechajefe,idescritura,substring(cast(fecha as text),1,10),observacionDieta,
				consistenciaDieta,fechafarmacia,usufarmacia,tipodosis from salud.ordenesmedicas	
				--LEFT JOIN salud.plantilladietas ON salud.plantilladietas.cedula=salud.ordenesmedicas.cedula 
				--and salud.plantilladietas.numservicio=salud.ordenesmedicas.numservicio
				--and tipoorden='Dieta' -- salud.plantilladietas.estado='AC'
				where salud.ordenesmedicas.compania='$Compania[0]' and salud.ordenesmedicas.cedula='$Paciente[1]' and salud.ordenesmedicas.numservicio='$filaServ[0]' 
				order by substring(cast(fecha as text),1,10)desc,idescritura desc,numorden asc";
				//echo $cons3;
				$res3=ExQuery($cons3);				
				while($fila3=ExFetch($res3))
				{
					$OrdMeds[$fila3[13]][$fila3[2]]=array($fila3[0],$fila3[1],$fila3[2],$fila3[3],$fila3[4],$fila3[5],$fila3[6],$fila3[7],$fila3[8],$fila3[9],$fila3[10],$fila3[11],$fila3[12],$fila3[13],$fila3[15],$fila3[16],$fila3[17],$fila3[18],$fila3[19]);
					//echo$OrdMeds [$fila3[13]][$fila3[2]][4]."<br>";
					//echo $fila3[1]
					//Ords[Idesc][numord]=detalle,numserv,numord,posolog,viasum,tipoord,fechaprog,usu,fecha,fevisadopo,fecharev,jefe,fechajefe,idesc
					//                   =    0  ,    1  ,   2  ,    3  ,  4   ,   5   ,     6   , 7 ,  8  ,    9     ,  10    , 11 ,   12    ,  13 
 				}   
				
				
				$cons="select idescritura,substring(cast(fecha as text),1,10) from salud.ordenesmedicas 
				where compania='$Compania[0]' and cedula='$Paciente[1]' and numservicio=$filaServ[0] 
				group by idescritura,substring(cast(fecha as text),1,10) order by substring(cast(fecha as text),1,10) desc ,idescritura desc";
				//echo $cons;
				$res=ExQuery($cons);
				while($fila=ExFetch($res))
				{		
					//echo $OrdMeds[$fila[0]][1][5]." --- ".$OrdMeds[$fila[0]][1][0]."<br>";
					
					//$cons2="select usuario,fecha,revisadopor,fecharevisado,jefeenfermeria,fechajefe from salud.ordenesmedicas where compania='$Compania[0]' 
					//and cedula='$Paciente[1]' and numservicio=$filaServ[0] and idescritura=$fila[0] order by numorden desc";
					//$res2=ExQuery($cons2);
					//$fila2=ExFetch($res2);?>                   
					<tr align="left"  bgcolor="#e5e5e5">
                    	<a name="<? echo $fila[0];?>">
                    	<?
						if($PermisoImpresion)
						{
							$colsp='3';
							?>
                            <td colspan="1" valign="bottom" > <button  title="Imprimir" onClick="OpcsImprimir(event,'<? echo $Radios?>','<? echo $fila[0]?>')"><img src="/Imgs/HistoriaClinica/printer.png" width="22px" height="22px" style="border:none"></img></button></td>
							<?
						}
						else
						{
							$colsp='4';							
						}?>                        
                        <td colspan="<? echo $colsp?>">                       
                        <?	if($OrdMeds[$fila[0]][1][11]){?>
								<div align="right"><em>Rev. <? echo $Medicos[$OrdMeds[$fila[0]][1][11]][0]." - ".$Medicos[$OrdMeds[$fila[0]][1][11]][1]?></em></div>
								<div align="right"><em><? echo $OrdMeds[$fila[0]][1][12]?></em></div>
								<br>
						<?	}
							elseif($VoBoJefe){?>
								<a href="OrdenamientoMedico.php?DatNameSID=<? echo $DatNameSID?>&RevJefeOA=1&IdEscr=<? echo $fila[0]?>" title="Revisar">
									<div align="right"><em>Sin Rev. Jefe Enfermeria</em></div>
								</a>
								<br>
						<?	}
							else{?>
								<div align="right"><em>Sin Rev. Jefe Enfermeria</em></div>
							   	<br>
						<?	}
							if($OrdMeds[$fila[0]][1][9]){?>
								<div align="right"><em>Rev. <? echo $Medicos[$OrdMeds[$fila[0]][1][9]][0]." - ".$Medicos[$OrdMeds[$fila[0]][1][9]][1]?></em></div>
								<div align="right"><em><? echo $OrdMeds[$fila[0]][1][10]?></em></div>
								<br>	
						<?	}
							elseif($VoBoAux){?>
								<a href="OrdenamientoMedico.php?DatNameSID=<? echo $DatNameSID?>&RevAuxOA=1&IdEscr=<? echo $fila[0]?>" title="Revisar">
								<div align="right"><em>Sin Rev. Aux Enfermeria</em></div></a>
						<?	}
							else{?>
								<div align="right"><em>Sin Rev. Aux Enfermeria</em></div>
						<?	}
						
						
						if($OrdMeds[$fila[0]][1][16]){
								//echo $OrdMeds [$fila[0]][2][5];
								$numordenes="select numorden from salud.ordenesmedicas where idescritura='$fila[0]' and compania='$Compania[0]' and cedula='$Paciente[1]' and numservicio=$filaServ[0] and (tipoorden='Medicamento Urgente' or tipoorden='Medicamento Programado' or tipoorden='Suspension') order by numorden asc";
								$res88=ExQuery($numordenes);
								$fila88=ExFetch($res88);
								if($OrdMeds [$fila[0]][$fila88[0]][5]=="Medicamento Urgente"||$OrdMeds [$fila[0]][$fila88[0]][5]=="Medicamento Programado"|| $OrdMeds [$fila[0]][$fila88[0]][5]=="Suspension"){
								?>
									<div align="right"><em>Rev. <? echo $Medicos[$OrdMeds[$fila[0]][1][17]][0]." - ".$Medicos[$OrdMeds[$fila[0]][1][17]][1]?></em></div>
									<div align="right"><em><? echo $OrdMeds[$fila[0]][1][16]?></em></div>
									<br>
								
						<?		}
							}
							elseif($VoBoFar){
								
								$numordenes="select numorden from salud.ordenesmedicas where idescritura='$fila[0]' and compania='$Compania[0]' and cedula='$Paciente[1]' and numservicio=$filaServ[0] and (tipoorden='Medicamento Urgente' or tipoorden='Medicamento Programado' or tipoorden='Suspension') order by numorden asc";
								$res88=ExQuery($numordenes);
								$fila88=ExFetch($res88);
								
								if($OrdMeds [$fila[0]][$fila88[0]][5]=="Medicamento Urgente"||$OrdMeds [$fila[0]][$fila88[0]][5]=="Medicamento Programado"|| $OrdMeds [$fila[0]][$fila88[0]][5]=="Suspension"){

									?>
										<a href="OrdenamientoMedico.php?DatNameSID=<? echo $DatNameSID?>&Revfarmacia=1&IdEscr=<? echo $fila[0]?>" title="Revisar">
										<div align="right"><em>Sin Rev. Aux Farmacia</em></div></a>
									
							<?		}
								//}
							}
							else{
								
								$numordenes="select numorden from salud.ordenesmedicas where idescritura='$fila[0]' and compania='$Compania[0]' and cedula='$Paciente[1]' and numservicio=$filaServ[0] and (tipoorden='Medicamento Urgente' or tipoorden='Medicamento Programado' or tipoorden='Suspension') order by numorden asc";
								$res88=ExQuery($numordenes);
								$fila88=ExFetch($res88);
								
								if($OrdMeds [$fila[0]][$fila88[0]][5]=="Medicamento Urgente"||$OrdMeds [$fila[0]][$fila88[0]][5]=="Medicamento Programado"|| $OrdMeds [$fila[0]][$fila88[0]][5]=="Suspension"){
								?>
									<div align="right"><em>Sin Rev. Aux Farmacia</em></div>
						<?	  		}
								//}
							}
						
						?>
                        </td>
                        </a>
                        </tr>
                        <tr>
                        <td colspan="4" align="left"  bgcolor="#e5e5e5">
                        	<strong><? echo $Medicos[$OrdMeds[$fila[0]][1][7]][0]." <br>".$Medicos[$OrdMeds[$fila[0]][1][7]][1]." - ".$OrdMeds[$fila[0]][1][8]?></strong>                       
                        </td></a>
                    </tr>	
				<?	$cont=1;
					foreach($OrdMeds[$fila[0]] as $Ordens)
					{
						$consNota="select notas,justificacion from salud.plantillamedicamentos where numservicio='$Ordens[1]' and idescritura='$Ordens[13]' and fechaformula='$Ordens[8]'";
						$resNota=ExQuery($consNota);
						$filaNota=ExFetch($resNota);
						$consNota2="select detalle from salud.plantillamedicamentos where numservicio='$Ordens[1]' and idescritura='$Ordens[13]' and fechaformula='$Ordens[8]'and detalle like '%AMPOLLA%'";
						$resNota2=ExQuery($consNota2);
						$filaNota2=ExFetch($resNota2);
						?>
						
                        <tr>
                        	<a name="<? echo $Ordens[1];?>">
                        	<td align="right"><strong><? echo $cont;?> </strong></td>
                            <td><i><? echo $Ordens[5];  if($Ordens[6]){ echo " (Reprogramado para el dia siguiente)";}?></i></td>
                            <td><?	if($filaNota2[0]){
										echo "$Ordens[0]"."<br> $filaNota[0] $Ordens[15]"."<br> $Ordens[14] "; 
										if($Ordens[4]){ echo " Via $Ordens[4]   ";
										} 
										if($Ordens[5]=='Medicamento Urgente'){
											echo " $Ordens[18]";
										}
										echo "<br>".$filaNota[1];
									}else{
										echo "$Ordens[0]"."<br> $Ordens[3] $Ordens[15]"."<br> $Ordens[14] "; 
										if($Ordens[4]){ 
											echo " Via $Ordens[4]  $filaNota[0] ";
										}
										if($Ordens[5]=='Medicamento Urgente'){
											echo " $Ordens[18]";
										}
										echo "<br>".$filaNota[1];
									}?></td>
                            </a>
                        </tr>						
				<?		$cont++;
					}
					/*echo "<tr><td colspan='10>aaaa</td></tr>";
					//Ords[Idesc][numord]=detalle,numserv,numord,posolog,viasum,tipoord,fechaprog,usu,fecha,fevisadopo,fecharev,jefe,fechajefe,idesc
					//                   =    0  ,    1  ,   2  ,    3  ,  4   ,   5   ,     6   , 7 ,  8  ,    9     ,  10    , 11 ,   12    ,  13 
					$cons3="select ordenesmedicas.detalle,ordenesmedicas.numservicio,ordenesmedicas.numorden,posologia,viasumin,tipoorden,fechareprog
					from salud.ordenesmedicas	
					where compania='$Compania[0]' and cedula='$Paciente[1]' and numservicio='$filaServ[0]' and idescritura=$fila[0] order by fecha";
					$res3=ExQuery($cons3);					
					$cont=1;
					while($fila3=ExFetch($res3))
					{?>                    	
						<tr>
                        	<a name="<? echo $fila3[1];?>">
                        	<td><strong><? echo $cont;?> </strong></td>
                            <td><i><? echo $fila3[5];  if($fila3[6]){ echo " (Reprogramado)";}?></i></td>
                            <td><?	echo "$fila3[0] $fila3[3]"; if($fila3[4]){ echo " Via $fila3[4]";}?></td>
                            </a>
                        </tr>
				<?		$cont++;
					}*/
				}
			}
		}
		elseif($Radios=='TodasOrdenes')
		{
		$cons="select numservicio,tiposervicio,fechaing,fechaegr from salud.servicios where compania='$Compania[0]' and cedula='$Paciente[1]' order by numservicio desc";
			$res=ExQuery($cons);
			if(ExNumRows($res)>0)
			{			
				while($filaServ=ExFetch($res))
				{  	?>
					<tr bgcolor="#e5e5e5" align="center">
                    	<td colspan="4"><strong>SERVICIO DE <? echo strtoupper($filaServ[1])?> - FECHA INGRESO: <? echo $filaServ[2]?> FECHA EGRESO: <? echo $filaServ[3]?></strong></td>   	
                  	</tr>				
				<?	$cons="select idescritura,substring(cast(fecha as text),1,10) from salud.ordenesmedicas 
					where compania='$Compania[0]' and cedula='$Paciente[1]' 
					group by idescritura,substring(cast(fecha as text),1,10) order by substring(cast(fecha as text),1,10) desc,idescritura desc";
					$res=ExQuery($cons);
					while($fila=ExFetch($res))
					{		
						$consS="select numservicio from salud.ordenesmedicas where idescritura=$fila[0] and compania='$Compania[0]' and cedula='$Paciente[1]'";
						$resS=ExQuery($consS);
						$filaS=ExFetch($resS);
						$filaServ[0]=$filaS[0];
						$cons2="select usuario,fecha,revisadopor,fecharevisado,jefeenfermeria,fechajefe,fechafarmacia,usufarmacia from salud.ordenesmedicas where compania='$Compania[0]' 
						and cedula='$Paciente[1]' and numservicio=$filaServ[0] and idescritura=$fila[0] order by numorden desc";
						$res2=ExQuery($cons2);
						$fila2=ExFetch($res2);?>                   
						<tr align="left"  bgcolor="#e5e5e5">
							 <a name="<? echo $fila[0];?>">
							<?
							if($PermisoImpresion)
							{
								$colsp='3';
								?>
								<td colspan="1" valign="bottom" > <button  title="Imprimir" onClick="OpcsImprimir(event,'<? echo $Radios?>','<? echo $fila[0]?>')"><img src="/Imgs/HistoriaClinica/printer.png" width="22px" height="22px" style="border:none"></img></button></td>
								<?
							}
							else
							{
								$colsp='4';							
							}?>                        
							<td colspan="<? echo $colsp?>">  
							<?	if($fila2[4]){?>
									<div align="right"><em>Rev. <? echo $Medicos[$fila2[4]][0]." - ".$Medicos[$fila2[4]][1]?></em></div>
									<div align="right"><em><? echo $fila2[5]?></em></div>
									<br>
							<?	}
								elseif($VoBoJefe){?>
									<a href="OrdenamientoMedico.php?DatNameSID=<? echo $DatNameSID?>&RevJefeOA=1&IdEscr=<? echo $fila[0]?>" title="Revisar">
										<div align="right"><em>Sin Rev. Jefe Enfermeria</em></div>
									</a>
									<br>
							<?	}
								else{?>
									<div align="right"><em>Sin Rev. Jefe Enfermeria</em></div>
									<br>
							<?	}
								if($fila2[2]){?>
									<div align="right"><em>Rev. <? echo $Medicos[$fila2[2]][0]." - ".$Medicos[$fila2[2]][1]?></em></div>
									<div align="right"><em><? echo $fila2[3]?></em></div>
									<br>	
							<?	}
								elseif($VoBoAux){?>
									<a href="OrdenamientoMedico.php?DatNameSID=<? echo $DatNameSID?>&RevAuxOA=1&IdEscr=<? echo $fila[0]?>" title="Revisar">
									<div align="right"><em>Sin Rev. Aux Enfermeria</em></div></a>
							<?	}
								else{?>
									<div align="right"><em>Sin Rev. Aux Enfermeria</em></div>
							<?	}
							
							if($fila2[7]){?>
									<div align="right"><em>Rev. <? echo $Medicos[$fila2[7]][0]." - ".$Medicos[$fila2[7]][1]?></em></div>
									<div align="right"><em><? echo $fila2[6]?></em></div>
									<br>	
							<?	}
								elseif($VoBoFar){?>
									<a href="OrdenamientoMedico.php?DatNameSID=<? echo $DatNameSID?>&Revfarmacia=1&IdEscr=<? echo $fila[0]?>" title="Revisar">
									<div align="right"><em>Sin Rev. Aux Farmacia</em></div></a>
							<?	}
								else{?>
									<div align="right"><em>Sin Rev. Aux Farmacia</em></div>
							<?	}
							
							?>
                            </td>
                            </a>
                            </tr>
                            <tr>
                            <td colspan="4" align="left"  bgcolor="#e5e5e5">
								<strong><? echo $Medicos[$fila2[0]][0]." <br>".$Medicos[$fila2[0]][1]." - ".$fila2[1]?></strong>
							</td></a>
						</tr>	
					<?	$cons3="select ordenesmedicas.detalle,ordenesmedicas.numservicio,ordenesmedicas.numorden,posologia,viasumin,tipoorden,fechareprog,substring(cast(fecha as text),1,10),observacionDieta,
						consistenciaDieta,ordenesmedicas.idescritura,fecha from salud.ordenesmedicas
						--LEFT JOIN salud.plantilladietas ON salud.plantilladietas.cedula=salud.ordenesmedicas.cedula 
						--and salud.ordenesmedicas.tipoorden='Dieta' and salud.plantilladietas.estado='AC'
						where salud.ordenesmedicas.compania='$Compania[0]' and salud.ordenesmedicas.cedula='$Paciente[1]' and salud.ordenesmedicas.numservicio='$filaServ[0]' and idescritura=$fila[0] 
						group by ordenesmedicas.detalle,ordenesmedicas.numservicio,ordenesmedicas.numorden,posologia,viasumin,tipoorden,fechareprog,substring(cast(fecha as text),1,10),observacionDieta,consistenciaDieta,ordenesmedicas.idescritura,fecha
						order by substring(cast(fecha as text),1,10)";
						
						$res3=ExQuery($cons3);
						$cont=1;
						
						while($fila3=ExFetch($res3))
						{
							$consNota="select notas from salud.plantillamedicamentos where numservicio='$fila3[1]' and idescritura='$fila3[10]' and fechaformula='$fila3[11]' and numorden='$fila3[2]'";
							$resNota=ExQuery($consNota);
							$filaNota=ExFetch($resNota);
							$consNota2="select detalle from salud.plantillamedicamentos where numservicio='$fila3[1]' and idescritura='$fila3[10]' and fechaformula='$fila3[11]'and numorden='$fila3[2]'and detalle like '%AMPOLLA%'";
							$resNota2=ExQuery($consNota2);
							$filaNota2=ExFetch($resNota2);
						?>                    	
							<tr>
								<a name="<? echo $fila3[1];?>">
								<td><strong><? echo $cont?></strong></td>
                                 <td><i><? echo $fila3[5]; if($fila3[6]){ echo " (Reprogramado)";}?></i></td>
                                <td><?
									if($filaNota2[0]){
										echo "$fila3[0] $filaNota[0] $fila3[9] $fila3[8]  "; 
										if($fila3[4]){ echo " Via $fila3[4]   ";
										} 										
									}else{
										echo "$fila3[0] $fila3[3] $fila3[9] $fila3[8]  "; 
										if($fila3[4]){ echo " Via $fila3[4]   ";
										} 									
									}

								//echo "$fila3[0] $fila3[3] $fila3[9] $fila3[8] "; if($fila3[4]){ echo " Via $fila3[4]";}?></td>
                                <?
									//echo $usuario[1]."  ".$fila2[0]." $fila3[7]  $ND[year]-$ND[mon]-$ND[mday] <br>";
									if(($usuario[1]==$fila2[0] && trim($fila3[7])=="$fechaActual") || $Super)
									{
								?>
	                                <td style="cursor:hand" onClick="if(confirm('Eliminar esta orden?')){location.href='OrdenamientoMedico.php?DatNameSID=<? echo $DatNameSID?>&Radios=<? echo $Radios?>&EliminaOrden=1&NumServicio=<? echo $fila3[1]?>&NumOrden=<? echo $fila3[2]?>&IdEscritura=<? echo $fila[0]?>&OpcMostrar=<? echo $OpcMostrar?>&Usu=<? echo $fila2[0]?>'}"><img src="/Imgs/b_drop.png"></td>
                                <?
									}
								?>
								</a>
							</tr>
					<?		$cont++;
						}
					}
				}
			}
			else{?>
				<tr><td><strong>El Paciente No Tiene Servicios Registrados</strong></td></tr>
	<?		}
		}
		elseif($Radios==="Activos")
		{
			//echo "<tr><td colspan='4'>&nbsp;</td></tr>";
			$cons3="select usuario,fecha
			from salud.ordenesmedicas	
			where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC' order by idescritura desc,numorden desc";
			$res3=ExQuery($cons3);
			$fila3=ExFetch($res3);?>
            <tr align="left"  bgcolor="#e5e5e5">
            	<?
				if($PermisoImpresion)
				{
					$colsp='3';
					?>
					<td colspan="1" valign="bottom" > <button  title="Imprimir" onClick="OpcsImprimir(event,'<? echo $Radios?>','<? echo $fila[0]?>')"><img src="/Imgs/HistoriaClinica/printer.png" width="22px" height="22px" style="border:none"></img></button></td>
					<?
				}
				else
				{
					$colsp='4';							
				}?>                        
				<td colspan="<? echo $colsp?>">  
					<strong><? echo $Medicos[$fila3[0]][0]." <br>".$Medicos[$fila3[0]][1]." - ".$fila3[1]?></strong>
              	</td>
			</tr>            
		<?	$cons3="select ordenesmedicas.detalle,ordenesmedicas.numservicio,ordenesmedicas.numorden,posologia,viasumin,tipoorden,fechareprog,observacionDieta, 
			consistenciaDieta, ordenesmedicas.idescritura,fecha from salud.ordenesmedicas	
			--LEFT JOIN salud.plantilladietas ON salud.plantilladietas.cedula=salud.ordenesmedicas.cedula 
			--and salud.ordenesmedicas.tipoorden='Dieta' and salud.plantilladietas.estado='AC'
			where salud.ordenesmedicas.compania='$Compania[0]' and salud.ordenesmedicas.cedula='$Paciente[1]' and salud.ordenesmedicas.estado='AC' order by idescritura desc,numorden desc";
			
			$res3=ExQuery($cons3);
			$cont=1;
			while($fila3=ExFetch($res3))
			{
				$consNota="select notas from salud.plantillamedicamentos where numservicio='$fila3[1]' and idescritura='$fila3[9]' and fechaformula='$fila3[10]'";
				$resNota=ExQuery($consNota);
				$filaNota=ExFetch($resNota);
				$consNota2="select detalle from salud.plantillamedicamentos where numservicio='$fila3[1]' and idescritura='$fila3[9]' and fechaformula='$fila3[10]'and detalle like '%AMPOLLA%'";
				$resNota2=ExQuery($consNota2);
				$filaNota2=ExFetch($resNota2);
			
			?>                    	
				<tr>
					<a name="<? echo $fila3[1];?>">
					<td><strong><? echo $cont?></strong></td>
                 	<td><i><? echo $fila3[5]; if($fila3[6]){ echo " (Reprogramado)";}?></i></td>
                   	<td><?

					if($filaNota2[0]){
						echo "$fila3[0] $filaNota[0] $fila3[8] $fila3[7]  "; 
						if($fila3[4]){ echo " Via $fila3[4]   ";
						} 										
					}else{
						echo "$fila3[0] $fila3[3] $fila3[8] $fila3[7]  "; 
						if($fila3[4]){ echo " Via $fila3[4]   ";
						} 									
					}

					//echo "$fila3[0] $fila3[3] $fila3[8] $fila3[7] "; if($fila3[4]){ echo " Via $fila3[4]";}?></td>
					</a>
				</tr>
		<?		$cont++;
			}
		}?>
	</table><?
}
else{
	echo "<center><font face='Tahoma' color='#0066FF' size='+2' ><b>No hay un paciente seleccionado, Debe guardarse la ficha de identificacion!!! </b></font></center>";
}?>   
	
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<iframe scrolling="no" id="FrameOpener" name="FrameOpener" style="display:none;border:#e5e5e5 ridge" frameborder="0" height="1" ></iframe> 
</body>
</html>