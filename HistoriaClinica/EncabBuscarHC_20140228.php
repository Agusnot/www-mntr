<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	
	function listarAmbitos($compania){
		$consProc = "SELECT ambito FROM Salud.ambitos WHERE compania = '$compania' ORDER BY ambito ASC";
		$resProc = ExQuery($consProc);
		
		return  $resProc;	
	}
	
	function listarPabellones($ambito,$compania){
			if ($ambito != "false"){
				$condServ = " AND ambito = '$ambito'";
			} 
			else{
				$condServ = "";
			}
		$consServ = "SELECT pabellon FROM Salud.Pabellones WHERE compania = '$compania' $condServ ORDER BY pabellon ASC";
		$resServ = ExQuery($consServ);
		
		return  $resServ;	
	}
	
	function listarMdTratantes($compania){
		$consMdTrat = "select nombre,usuarios.usuario from salud.medicos,central.usuarios,salud.cargos  WHERE medicos.compania='$compania' and cargos.compania='$compania' and medicos.cargo=cargos.cargos and usuarios.usuario=medicos.usuario and cargos.tratante=1 and medicos.estadomed='Activo'  order by nombre";
		$resMdTrat = ExQuery($consMdTrat);
		
		return  $resMdTrat;	
	}
	
	function listarAseguradoras($compania){
		$consAseg = "SELECT primape,identificacion FROM central.terceros WHERE compania='$compania' AND tipo='Asegurador' ORDER BY primape ASC";
		$resAseg = ExQuery($consAseg);
		
		return  $resAseg;	
	}
	
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	
	
	
	function enviarForm(){	
			document.getElementById("FORMA").submit();
	}
</script>	
</head>

		<body background="/Imgs/Fondo.jpg">
			<form id ="FORMA" method="post" target="Abajo" action="ResultBuscarHC.php" >
				<table border="1" bordercolor="white" bgcolor="#e5e5e5"  style="font-family:Tahoma;font-size:13">
					<tr style="text-align:center;">
						<td>Identificaci&oacute;n</td>
						<td colspan="2">Apellidos</td>
						<td colspan="2">Nombres</td>
						
						<td> Proceso</td>						
						<td> Servicio </td>
						<td>Md Tratante</td>
						<td>Aseguradora</td>
					</tr>
					<tr>
						<td><input type="Text" name="Cedula" style="width:90px;" onChange="enviarForm()" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" onFocus="Ocultar()"></td>
						<td><input type="Text" name="PrimApe" style="width:90px;" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" onFocus="Ocultar()"></td>
						<td><input type="Text" name="SegApe" style="width:90px;"  onkeydown="xLetra(this)" onKeyUp="xLetra(this)" onFocus="Ocultar()"></td>
						<td><input type="Text" name="PrimNom" style="width:90px;" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" onFocus="Ocultar()"></td>
						<td><input type="Text" name="SegNom" style="width:90px;" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" onFocus="Ocultar()"></td>
						
						
						<td style="width:90px;"> <?php $resAmb = listarAmbitos($Compania[0]); 
								
								echo "<select name='Ambito' onChange='enviarForm()' style='width:90px;'>  ";
								echo "<option value=''> Seleccionar </option>";
									
									while($filaAmb = ExFetchArray($resAmb)){
										echo "<option value='$filaAmb[ambito]'>".$filaAmb['ambito']."</option>";
									}
								echo "</select>";	
							?>
						</td>
						<td style="width:90px;"><?php 
								if (isset($_POST['Ambito'])){
									$resPab = listarPabellones($_POST['Ambito'],$Compania[0]); 
								} else {
									$resPab = listarPabellones("false",$Compania[0]); 
								}
								
								//echo "<select name='Pabellon' onChange='enviarForm()' style='width:90px;'>";
								?> <select name='Pabellon' onChange="enviarForm()" style='width:90px;'><?php
								echo "<option value=''> Seleccionar </option>";
									
									while($filaPab = ExFetchArray($resPab)){
										echo "<option value='$filaPab[pabellon]'>".$filaPab['pabellon']."</option>";
									}
								echo "</select>";	
							?>
							
						</td>    
						
						<td style="width:90px;"><?php 
								$resMdTrat = listarMdTratantes($Compania[0]); 							
								echo "<select name='Medicotte' onChange='enviarForm()' style='width:90px;'>";
								echo "<option value=''> Seleccionar </option>";
									
									while($filaTrat = ExFetchArray($resMdTrat)){
										echo "<option value='$filaTrat[usuario]'>".$filaTrat['nombre']."</option>";
									}
								echo "</select>";	
							?>
							
						</td> 
						<td style="width:90px;">
							<?php 
								$resAseg = listarAseguradoras($Compania[0]); 							
								echo "<select name='Enttidad' onChange='enviarForm()' style='width:90px;'>";
								echo "<option value=''> Seleccionar </option>";
									
									while($filaAseg = ExFetchArray($resAseg)){
										echo "<option value='$filaAseg[primape]'>".$filaAseg['primape']."</option>";
									}
								echo "</select>";	
							?>
						</td>
						
						
						<?
						$cons="select clinica from salud.clinicashc where compania='$Compania[0]' order by clinica";
						$res=ExQuery($cons);?>
						 <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
						<td><input type="Submit" name="Buscar" value="Buscar"></td>
					</tr>
				</table>
			</form>
		</body>
</html>