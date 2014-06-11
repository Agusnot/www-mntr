		<?	
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND = getdate();
			if($Guardar){	
				if($FechaCierra2){$Datos2=",seg2='$Seg2',responsablecierre2='$RespCerrar2',fechacierre2='$FechaCierra2' ";}
				if($FechaCierra3){$Datos3=",seg3='$Seg3',responsablecierre3='$RespCerrar3',fechacierre3='$FechaCierra3' ";}
				if($FechaCierra4){$Datos4=",seg4='$Seg4',responsablecierre4='$RespCerrar4',fechacierre4='$FechaCierra4' ";}
				if($FechaCierra5){$Datos5=",seg5='$Seg5',responsablecierre5='$RespCerrar5',fechacierre5='$FechaCierra5' ";}
				if($FechaCierra6){$Datos6=",seg6='$Seg6',responsablecierre6='$RespCerrar6',fechacierre6='$FechaCierra6' ";}
				if($FechaCierra7){$Datos7=",seg7='$Seg6',responsablecierre7='$RespCerrar7',fechacierre7='$FechaCierra7' ";}
				$cons="update historiaclinica.regpacienteseg set segyrevicion='1',fechasegyrev='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',seg1='$Seg1' 
				,responsablecierre1='$RespCerrar1',fechacierre1='$FechaCierra1',resultacctomadas=$AccElimCausaFalla,accieliminfalla=$AccElimCausaFalla,accprevrrecufalla=$AccPrevRecurFalla
				$Datos2 $Datos3 $Datos4 $Datos5 $Datos6 $Datos7
				where compania='$Compania[0]' and numrep=$NumRep";
				$res=ExQuery($cons);?>
				<script language="javascript">
					parent.document.FORMA.submit();
				</script>
		<?	}	
		?>	
		
		<html>
				<head>
						<?php echo $codificacionMentor; ?>
						<?php echo $autorMentor; ?>
						<?php echo $titleMentor; ?>
						<?php echo $iconMentor; ?>
						<?php echo $shortcutIconMentor; ?>
						<link rel="stylesheet" type="text/css" href="../../General/Estilos/estilos.css">
						<script language='javascript' src="/calendario/popcalendar.js"></script>
						<script language="javascript" src="/Funciones.js"></script>
						<script language="javascript">
							function CerrarThis()
							{
								parent.document.getElementById('FrameOpener').style.position='absolute';
								parent.document.getElementById('FrameOpener').style.top='1px';
								parent.document.getElementById('FrameOpener').style.left='1px';
								parent.document.getElementById('FrameOpener').style.width='1';
								parent.document.getElementById('FrameOpener').style.height='1';
								parent.document.getElementById('FrameOpener').style.display='none';
								parent.document.FORMA.submit();
							}
							function Validar()
							{
								if(document.FORMA.Seg1.value==""){alert("Debe haber almenos un seguimiento!!!");return false;}
								else{if(document.FORMA.RespCerrar1.value==""){alert("Cada seguimento debe tener su responsable!!!");return false;}
									else{if(document.FORMA.FechaCierra1.value==""){alert("Cada seguimento debe tener su fecha!!!");return false;}}
								}		
								if(document.FORMA.Seg2.value!=""&&document.FORMA.RespCerrar2.value==""){
									alert("Cada seguiemnto debe tener su responsable!!!");return false;
								}
								else{
									if(document.FORMA.Seg2.value!=""&&document.FORMA.RespCerrar2.value!=""&&document.FORMA.FechaCierra2.value==""){
										alert("Cada seguimiento debe tener su fecha!!!");return false;	
									}
								}
								if(document.FORMA.Seg3.value!=""&&document.FORMA.RespCerrar3.value==""){alert("Cada seguiemnto debe tener su responsable!!!");return false;}
								else{if(document.FORMA.Seg3.value!=""&&document.FORMA.RespCerrar3.value!=""&&document.FORMA.FechaCierra3.value==""){
										alert("Cada seguimiento debe tener su fecha!!!");return false;	
									}
								}
								if(document.FORMA.Seg4.value!=""&&document.FORMA.RespCerrar4.value==""){alert("Cada seguiemnto debe tener su responsable!!!");return false;}
								else{if(document.FORMA.Seg4.value!=""&&document.FORMA.RespCerrar4.value!=""&&document.FORMA.FechaCierra4.value==""){
										alert("Cada seguimiento debe tener su fecha!!!");return false;	
									}
								}
								if(document.FORMA.Seg5.value!=""&&document.FORMA.RespCerrar5.value==""){alert("Cada seguiemnto debe tener su responsable!!!");return false;}
								else{if(document.FORMA.Seg5.value!=""&&document.FORMA.RespCerrar5.value!=""&&document.FORMA.FechaCierra5.value==""){
										alert("Cada seguimiento debe tener su fecha!!!");return false;	
									}
								}
								if(document.FORMA.Seg6.value!=""&&document.FORMA.RespCerrar6.value==""){alert("Cada seguiemnto debe tener su responsable!!!");return false;}
								else{if(document.FORMA.Seg6.value!=""&&document.FORMA.RespCerrar6.value!=""&&document.FORMA.FechaCierra6.value==""){
										alert("Cada seguimiento debe tener su fecha!!!");return false;	
									}
								}
								if(document.FORMA.Seg7.value!=""&&document.FORMA.RespCerrar7.value==""){alert("Cada seguiemnto debe tener su responsable!!!");return false;}
								else{if(document.FORMA.Seg7.value!=""&&document.FORMA.RespCerrar7.value!=""&&document.FORMA.FechaCierra7.value==""){
										alert("Cada seguimiento debe tener su fecha!!!");return false;	
									}
								}
							}
						</script>
				</head>

			<body <?php echo $backgroundBodyMentor; ?>>
			
				<div align="center"	>
					<form name="FORMA" method="post" onSubmit="return Validar()">
						<table class="tabla2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr>
								<td class="encabezado2Horizontal" colspan="4">CIERRE DE CASO</td>
							</tr>    
							<tr>
								<td class='encabezado2HorizontalInvertido'>&nbsp;</td>
								<td class='encabezado2HorizontalInvertido'>SEGUIMIENTO</td>
								<td class='encabezado2HorizontalInvertido'>RESPONSABLE</td>
								<td class='encabezado2HorizontalInvertido'>FECHA</td>
							</tr>
							<tr>
								<td><strong>1.</strong></td><td><input type="text" name="Seg1" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:350"></td>
								<td><input type="text" name="RespCerrar1" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:250"></td>
								 <td><input type="text" name="FechaCierra1" readonly onClick="popUpCalendar(this, FORMA.FechaCierra1, 'yyyy-mm-dd')"> </td>
							</tr>
							<tr>
								<td><strong>2.</strong></td><td><input type="text" name="Seg2" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:350"></td>
								<td><input type="text" name="RespCerrar2" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:250"></td>
								 <td><input type="text" name="FechaCierra2" readonly onClick="popUpCalendar(this, FORMA.FechaCierra2, 'yyyy-mm-dd')"> </td>
							</tr>
							<tr>
								<td><strong>3.</strong></td><td><input type="text" name="Seg3" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:350"></td>
								<td><input type="text" name="RespCerrar3" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:250"></td>
								 <td><input type="text" name="FechaCierra3" readonly onClick="popUpCalendar(this, FORMA.FechaCierra3, 'yyyy-mm-dd')"> </td>
							</tr>
							<tr>
								<td><strong>4.</strong></td><td><input type="text" name="Seg4" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:350"></td>
								<td><input type="text" name="RespCerrar4" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:250"></td>
								 <td><input type="text" name="FechaCierra4" readonly onClick="popUpCalendar(this, FORMA.FechaCierra4, 'yyyy-mm-dd')"> </td>
							</tr>
							<tr>
								<td><strong>5.</strong></td><td><input type="text" name="Seg5" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:350"></td>
								<td><input type="text" name="RespCerrar5" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:250"></td>
								 <td><input type="text" name="FechaCierra5" readonly onClick="popUpCalendar(this, FORMA.FechaCierra5, 'yyyy-mm-dd')"> </td>
							</tr>
							<tr>
								<td><strong>6.</strong></td><td><input type="text" name="Seg6" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:350"></td>
								<td><input type="text" name="RespCerrar6" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:250"></td>
								 <td><input type="text" name="FechaCierra6" readonly onClick="popUpCalendar(this, FORMA.FechaCierra6, 'yyyy-mm-dd')"> </td>
							</tr>
							<tr>
								<td><strong>7.</strong></td><td><input type="text" name="Seg7" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:350"></td>
								<td><input type="text" name="RespCerrar7" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:250"></td>
								 <td><input type="text" name="FechaCierra7" readonly onClick="popUpCalendar(this, FORMA.FechaCierra7, 'yyyy-mm-dd')"> </td>
							</tr>
							<tr>
								<td colspan="4">La(s) accion(es) fueron tomadas <select name="AccFueronTomadas"><option value="1">Si</option><option value="0">No</option></select></td>        
							</tr>
							<tr>
								<td colspan="4">La(s) accion(es) eliminaron las causas del la falla<select name="AccElimCausaFalla"><option value="1">Si</option><option value="0">No</option></select></td>        
							</tr>
							<tr>
								<td colspan="4">La(s) accion(es) previenen la recurrencia de la falla<select name="AccPrevRecurFalla"><option value="1">Si</option><option value="0">No</option></select></td>        
							</tr>
							<tr align="center">
								<td colspan="4"><input type="submit" value="Guardar" name="Guardar"/><input type="button" value="Cancelar" onclick="CerrarThis()"/></td>
							</tr>
						</table>
					</form>
				</div>	
			</body>
		</html>
