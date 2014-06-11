		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			session_register("ExcluyeComprobantes");
			$ExcluyeComprobantes="";
			include("Informes.php");
			include_once("General/Configuracion/Configuracion.php");
			
			$ND=getdate();
			$AnioAc=$ND[year];
			$cons="Select Comprobante,Numero from Contabilidad.ExcluyeComprobantes where Compania='$Compania[0]'";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				$ExcluyeComprobantes=$ExcluyeComprobantes."(Comprobante || Numero !='$fila[0]$fila[1]')";
				if($n>1){$ExcluyeComprobantes=$ExcluyeComprobantes." and ";}
			}
			if(ExNumRows($res)==0){$ExcluyeComprobantes="1=1";}
			

			if(!$PerFin){$PerFin="$ND[year]-$ND[mon]-$ND[mday]";}
			$cons="Select Anio from Central.Anios where Compania='$Compania[0]' Order By Anio";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				if(!$AnioInc){$AnioInc=$fila[0];}
				$AnioAf=$fila[0];
			}
			$AnioAf++;
		?>

		<html>
			<head>
				<?php echo $codificacionMentor; ?>
				<?php echo $autorMentor; ?>
				<?php echo $titleMentor; ?>
				<?php echo $iconMentor; ?>
				<?php echo $shortcutIconMentor; ?>
				<link rel="stylesheet" type="text/css" href="../../../General/Estilos/estilos.css">
			</head>
			<body  onFocus="Ocultar()">
				<?php
					$rutaarchivo[0] = "CONTABILIDAD";
					$rutaarchivo[1] = "REPORTES";	
						if(!empty($_GET['Seleccion'])){
								$rutaarchivo[2] = $_GET['Seleccion'];
						}
					mostrarRutaNavegacionEstatica($rutaarchivo);
					
					
				?>
			<div align="center">
					<table>
					<tr><td>
					<table class="tabla2" style="text-align:left;"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>  onFocus="Ocultar()">
						<tr>
							<td>
								<table border="0" cellpadding="0px" cellspacing="0">
									<tr>
										<td class="encabezado2Horizontal">TIPO DE REPORTE</td>
									</tr>
									<tr>
										<td>
											<select name="Seleccion" onChange="location.href='EncabReportes.php?DatNameSID=<? echo $DatNameSID?>&Seleccion=' + this.value+'&Tipo=<?echo $Tipo?>'">
												<option value=""></option>
												<?
													$cons="Select Nombre from Central.Reportes where Clase='$Tipo' and Modulo='Contabilidad' Order By Id";echo $cons;
													$res=ExQuery($cons);
													while($fila=ExFetch($res))
													{
														if($Seleccion==$fila[0]){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
														else{echo "<option value='$fila[0]'>$fila[0]</option>";}
													}
												?>
											</select>
										</td>
									</tr>
								</table>	
							</td>
							</td>
							<td>
					<script language="javascript">
						function Mostrar()
						{
							parent(1).location.href="about:blank"; // AQUI SE DEJA EN BLANCO EL CUADRO DE ABAJO
							parent.document.getElementById('Reporteador').rows="340,*"; // AQUI, LLAMO AL FRAME PRINCIPAL Y LO REDIMENSIONO
							document.getElementById('Busquedas').style.display=''; // AQUI VUELVO VISIBLE EL FRAME DE ABAJO LLAMADO BUSQUEDAS
						}
						function Ocultar()
						{
							parent.document.getElementById('Reporteador').rows="150,*"; // SE VUELVE EL FRAME PRINCIPAL AL TAMAÑO NORMAL
							document.getElementById('Busquedas').style.display='none'; // SE VUELVE INVISIBLE BUSQUEDAS
						}
						function BuscarCuenta(Objeto,Cuenta) // CON ESTA FUNCION REALIZO LA BUSQUEDA DE LOS DATOS. EN ESTE CASO EL TERCERO RECIBIENDO EL OBJETO YLA CUENTA
						{
							document.getElementById('Busquedas').style.width="550px";							
							
							frames.Busquedas.location.href='/Contabilidad/Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanCuentasTodas&Reporteador=1&Objeto='+Objeto+'&Anio='+document.FORMA.Anio.value+'&Cuenta='+Cuenta;
						}

						function BuscarCC(Objeto,Cuenta) // CON ESTA FUNCION REALIZO LA BUSQUEDA DE LOS DATOS. EN ESTE CASO EL TERCERO RECIBIENDO EL OBJETO YLA CUENTA
						{
							document.getElementById('Busquedas').style.width="550px";							
							frames.Busquedas.location.href='/Contabilidad/Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=CCG&Reporteador=1&Objeto='+Objeto+'&Anio='+document.FORMA.Anio.value+'&CC='+Cuenta;
						}

						function BuscarTercero(Objeto,Valor)
						{
							document.getElementById('Busquedas').style.width="700px";							
							frames.Busquedas.location.href='/Contabilidad/Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=TercerosxReportes&Reporteador=1&Tercero='+Valor+'&Objeto='+Objeto;
						}
						function BuscarComprobante(Objeto,Valor)
						{
							document.getElementById('Busquedas').style.width="550px";							
							frames.Busquedas.location.href='/Contabilidad/Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Comprobante&Reporteador=1&Tercero='+Valor+'&ObjComprobante='+Objeto;
						}
						function BuscarCuentaBancos(Objeto,Cuenta)
						{
							document.getElementById('Busquedas').style.width="550px";							
							frames.Busquedas.location.href='/Contabilidad/Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanCuentasTodas&Bancos=1&Reporteador=1&Objeto='+Objeto+'&Anio='+document.FORMA.Anio.value+'&Cuenta='+Cuenta;
						}
					</script>
					<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
					<?
						if($Seleccion){
						$cons="Select Tipo,Archivo from Central.Reportes where Nombre='$Seleccion'  and UPPER(Modulo)='CONTABILIDAD'";
						$res=ExQuery($cons);
						$fila=ExFetch($res);
						$Tipo=$fila[0];
						$NomArchivo=$fila[1];
						$cons2="Select sum(NoCaracteres) from Contabilidad.EstructuraPuc where Compania='$Compania[0]' and Anio=$AnioAc";
						$res2=ExQuery($cons2);
						$fila2=ExFetch($res2);
						$NoDigitos=$fila2[0];
						if(!$NoDigitos){$NoDigitos=0;}
						
						
						
						if($Tipo==1){
							echo "<form name='FORMA' action='$NomArchivo' target='Abajo'>";
								echo "<tr>";
									echo "<td class='encabezado2Horizontal' colspan=2><center>PERIODO</td>";
									echo "<td class='encabezado2Horizontal'>CEROS</td>";
									echo "<td class='encabezado2Horizontal'>DIGITOS</td>";
									echo "<td class='encabezado2Horizontal'>CUENTA INICIAL</td>";
									echo "<td class='encabezado2Horizontal'>CUENTA FINAL</td>";
									echo "<td class='encabezado2Horizontal'>CC</td>";
									echo "<td class='encabezado2Horizontal'>PDF</td>";?>
							<tr>
							<td>
								<table cellpadding="0" cellspacing="0" border="0">
									<tr>
										<td>
											<select name="Anio" onFocus="Ocultar()">
												<?
												for($i=$AnioInc;$i<$AnioAf;$i++){
													if($i==$AnioAc){
														echo "<option selected value=$i>$i</option>";
													}
													else{
														echo "<option value=$i>$i</option>";
													}
												}	
												?>
											</select>
										</td>
										<td>
											<select name="MesIni" onFocus="Ocultar()">
												<? 
												for($i=1;$i<=12;$i++){
													if($ND[mon]==$i){
														echo "<option selected value='$i'>$NombreMesC[$i]</option>";
													}
													else{
														echo "<option value='$i'>$NombreMesC[$i]</option>";
													}
												}
												?>
											</select>
										</td>
										<td>
											<input type='Text' name='DiaIni' style='width:20px;' maxlength="2" value='01' onFocus="Ocultar()">
										</td>
									</tr>
								</table>	
							</td>
							<td>
								<table cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td>
											<select name="MesFin" onFocus="Ocultar()">
												<?
												for($i=1;$i<=12;$i++){
													if($ND[mon]==$i){
														echo "<option selected value='$i'>$NombreMesC[$i]</option>";
													}
													else{
														echo "<option value='$i'>$NombreMesC[$i]</option>";
													}
												}
												?>
											</select>
										</td>
										<td>										
											<input type='Text' name='DiaFin' style='width:20px;' maxlength="2" value='<?echo $ND[mday]?>' onFocus="Ocultar()">
										</td>
									</tr>
								</table>
							</td>	
					<?		echo "<td><input  type='Checkbox' name='IncluyeCeros' onFocus='Ocultar()'></td>";
							echo "<td><input type='Text' name='NoDigitos' style='width:20px;' onFocus='Ocultar()' value=$NoDigitos></td>";?>
							<td><input type="Text" name="CuentaIni" style="width:70px;" 
							onFocus="Mostrar();BuscarCuenta(this.name,this.value)" onKeyDown="BuscarCuenta(this.name,this.value)" onKeyUp="BuscarCuenta(this.name,this.value)" onBlur="CuentaFin.value=CuentaIni.value" value="<? echo $CuentaIni?>">
							</td>
							<td><input type="Text" name="CuentaFin" style="width:70px;" value="<? echo $CuentaFin?>" onFocus="Mostrar();BuscarCuenta(this.name,this.value)" onKeyDown="BuscarCuenta(this.name,this.value)" onKeyUp="BuscarCuenta(this.name,this.value)">

							<td><input type="Text" name="CC" style="width:60px;" value="<? echo $CC?>" onFocus="Mostrar();BuscarCC(this.name,this.value)" onKeyDown="BuscarCC(this.name,this.value)" onKeyUp="BuscarCC(this.name,this.value)">

							<td><input type="checkbox" name="PDF" value="1"></td>
					<?
						}

						if($Tipo==2){
							echo "<form name='FORMA' action='$NomArchivo' target='Abajo'>";
							echo "<tr>";
								echo "<td class='encabezado2Horizontal'>CORTE</td>";
								echo "<td class='encabezado2Horizontal'>CEROS</td>";
								echo "<td class='encabezado2Horizontal'>No. DIGITOS</td>";
								echo "<td class='encabezado2Horizontal'>CUENTA INICIAL</td>";
								echo "<td class='encabezado2Horizontal'>CUENTA FINAL </td>";
								echo "<td class='encabezado2Horizontal'>PDF</td>";?>
							
							<tr><td>
							<select name="Anio" onFocus="Ocultar()"><?
							for($i=$AnioInc;$i<$AnioAf;$i++)
							if($i==$AnioAc){echo "<option selected value=$i>$i</option>";}
							else{echo "<option value=$i>$i</option>";}
							?></select>
							<select name="MesFin" onFocus="Ocultar()">
							<?for($i=1;$i<=12;$i++)
							{
								if($ND[mon]==$i){echo "<option selected value='$i'>$NombreMesC[$i]</option>";}
								else{echo "<option value='$i'>$NombreMesC[$i]</option>";}
							}
							?>
							</select>
							<input type='Text' name='DiaFin' style='width:20px;' maxlength="2" value='<?echo $ND[mday]?>' onFocus="Ocultar()">
					<?		echo "<td><input  type='Checkbox' name='IncluyeCeros' onFocus='Ocultar()'></td>";
							echo "<td><input type='Text' name='NoDigitos' style='width:20px;' onFocus='Ocultar()' value=$NoDigitos></td>";?>
							<td><input type='Text' name='CuentaIni' style='width:70px;' value="<? echo $CuentaIni?>"
							onFocus="Mostrar();BuscarCuenta(this.name,this.value)" onKeyDown="BuscarCuenta(this.name,this.value)" onKeyUp="BuscarCuenta(this.name,this.value)" onBlur="CuentaFin.value=CuentaIni.value"></td>
							<td><input type='Text' name='CuentaFin' style='width:70px;' value="<? echo $CuentaFin?>"
							onFocus="Mostrar();BuscarCuenta(this.name,this.value)" onKeyDown="BuscarCuenta(this.name,this.value)" onKeyUp="BuscarCuenta(this.name,this.value)">
					<?
							echo "<td><input type='Checkbox' name='PDF' onFocus='Ocultar()'></td>";
						}


						if($Tipo==3){
							echo "<form name='FORMA' action='$NomArchivo' target='Abajo'>";
							echo "<tr>";
								echo "<td class='encabezado2Horizontal' colspan=2>PERIODO</td>";
								echo "<td class='encabezado2Horizontal'> DIGITOS</td>";
								echo "<td class='encabezado2Horizontal'>CUENTA INICIAL</td>";
								echo "<td class='encabezado2Horizontal'>CUENTA FINAL</td>";
								echo "<td class='encabezado2Horizontal'> TERCERO</td>";
								echo "<td class='encabezado2Horizontal'>Comprobante</td>";
								echo "<td class='encabezado2Horizontal'>CC</td>";
								echo "<td class='encabezado2Horizontal'>PDF</td>";
							?>
							<tr>
								<td>
									<table cellspacing="0" cellpadding="0" border="0">
										<tr>
											<td>
												<select name="Anio" onFocus='Ocultar()'>
													<?
													for($i=$AnioInc;$i<$AnioAf;$i++) {
														if($i==$AnioAc){
															echo "<option selected value=$i>$i</option>";
														}
														else{
															echo "<option value=$i>$i</option>";
														}
													}	
													?>
												</select>
											</td>
											<td>						
												<select name="MesIni" onFocus='Ocultar()'>
													<?
													for($i=1;$i<=12;$i++)	{
														if($ND[mon]==$i){
															echo "<option selected value='$i'>$NombreMesC[$i]</option>";
														}
														else{
															echo "<option value='$i'>$NombreMesC[$i]</option>";
														}
													}
													?>
												</select>
											</td>	
											<td>
												<input type='Text' name='DiaIni' style='width:20px;' maxlength="2" value='01' onFocus='Ocultar()'>
											</td>
										</tr>
									</table>
								</td>	
							</td>
							<td>
								<table cellpadding="0" cellspacing="0" border="0">
									<tr>
										<td>
											<select name="AnioFin" onFocus='Ocultar()'>
												<?
													for($i=$AnioInc;$i<$AnioAf;$i++) {
														if($i==$AnioAc){
															echo "<option selected value=$i>$i</option>";
														}
														else{
															echo "<option value=$i>$i</option>";
														}
													}
												?>
											</select>
										</td>
										<td>											
											<select name="MesFin" onFocus='Ocultar()'>
												<?
												for($i=1;$i<=12;$i++){
													if($ND[mon]==$i){
														echo "<option selected value='$i'>$NombreMesC[$i]</option>";
													}
													else{
														echo "<option value='$i'>$NombreMesC[$i]</option>";
													}
												}
												?>
											</select>
										</td>
										<td>
											<input type='Text' name='DiaFin' style='width:20px;' onFocus='Ocultar()' maxlength="2" value='<?echo $ND[mday]?>'>
										</td>
									</tr>
								</table>
							</td>	
							<?
							echo "<td><input type='Text' name='NoDigitos' style='width:20px;' value=$NoDigitos onFocus='Ocultar()'></td>";?>
							<td><input type='Text' name='CuentaIni' style='width:50px;' value="<? echo $CuentaIni?>" 
							onFocus="Mostrar();BuscarCuenta(this.name,this.value)" onKeyDown="BuscarCuenta(this.name,this.value)" onKeyUp="BuscarCuenta(this.name,this.value)" onBlur="CuentaFin.value=CuentaIni.value"></td>
							<td><input type='Text' name='CuentaFin' style='width:50px;' value="<? echo $CuentaFin?>" 
							onFocus="Mostrar();BuscarCuenta(this.name,this.value)" onKeyDown="BuscarCuenta(this.name,this.value)" onKeyUp="BuscarCuenta(this.name,this.value)"></td>
							<td><input type='Text' name='Tercero' style='width:60px;' value="<? echo $Tercero?>" onFocus="Mostrar();BuscarTercero(this.name,this.value)" onKeyUp="BuscarTercero(this.name,this.value)" onKeyDown="BuscarTercero(this.name,this.value)">
							<td><input type='Text' name='Comprobante' style='width:70px;' value="<? echo $Comprobante?>" onFocus="Mostrar();BuscarComprobante(this.name,this.value)" onKeyUp="BuscarComprobante(this.name,this.value)" onKeyDown="BuscarComprobante(this.name,this.value)">
							<td><input type="Text" name="CC" style="width:60px;" value="<? echo $CC?>" onFocus="Mostrar();BuscarCC(this.name,this.value)" onKeyDown="BuscarCC(this.name,this.value)" onKeyUp="BuscarCC(this.name,this.value)">

					<?
							echo "<td><input type='Checkbox' name='PDF' onFocus='Ocultar()'></td>";
						}
						if($Tipo==4){
							echo "<form name='FORMA' action='$NomArchivo' target='Abajo'>";
							echo "<tr>";
							echo "<td class='encabezado2Horizontal'>CORTE</td>";
							echo "<td class='encabezado2Horizontal'>DIGITOS</td>";?>
							<tr><td>
							<select name="Anio" onFocus='Ocultar()'><?
							for($i=$AnioInc;$i<$AnioAf;$i++)
							if($i==$AnioAc){echo "<option selected value=$i>$i</option>";}
							else{echo "<option value=$i>$i</option>";}
							?></select>
							<select name="MesFin" onFocus='Ocultar()'>
							<?for($i=1;$i<=12;$i++)
							{
								if($ND[mon]==$i){echo "<option selected value='$i'>$NombreMesC[$i]</option>";}
								else{echo "<option value='$i'>$NombreMesC[$i]</option>";}
							}
							?>
							</select>
							<input type='Text' name='DiaFin' style='width:20px;' maxlength="2" onFocus='Ocultar()' value='<?echo $ND[mday]?>'>

							</td>
					<?		echo "<td><input type='Text' name='NoDigitos' onFocus='Ocultar()' style='width:70px;' value=$NoDigitos></td>";
						}
						
						
						if($Tipo==5){
							echo "<form name='FORMA' action='$NomArchivo' target='Abajo'>";
								echo "<tr>";
								echo "<td class='encabezado2Horizontal'>MES</td>";
								echo "<td class='encabezado2Horizontal'>A&Ntilde;O</td>";
								echo "<td class='encabezado2Horizontal'>BANCO</td>";
							echo "<tr><td>
							<select name='Mes' onFocus='Ocultar()'>";
							$cons="Select * from Central.Meses Order By Numero";
							$res=ExQuery($cons);
							while($fila=ExFetch($res))
							{
								if($fila[1]==$ND[mon]){echo "<option selected value='$fila[1]'>$fila[0]</option>";}
								else{echo "<option value='$fila[1]'>$fila[0]</option>";}
							}
							echo "</select>
							</td>
							<td>
							<select name='Anio' onFocus='Ocultar()'>";

							for($i=$AnioInc;$i<$AnioAf;$i++)
							if($i==$AnioAc){echo "<option selected value=$i>$i</option>";}
							else{echo "<option value=$i>$i</option>";}

							echo "</select>
							</td>
							";?>
							<td><input type='Text' name='Banco' style='width:160px;' value="<? echo $Banco?>" 
							onFocus="Mostrar();BuscarCuentaBancos(this.name,this.value)" onKeyDown="BuscarCuentaBancos(this.name,this.value)" onKeyUp="BuscarCuentaBancos(this.name,this.value)"></td>
					<?	}
						if($Tipo==6)
						{
							echo "<form name='FORMA' action='$NomArchivo' target='Abajo'>";
								echo "<tr>";
									echo "<td class='encabezado2Horizontal'>PERIODO INICIAL</td>";
									echo "<td class='encabezado2Horizontal'>PERIODO FINAL</td>";
							?>
							<tr><td>
							<select name="Anio" onFocus='Ocultar()'><?
							for($i=$AnioInc;$i<$AnioAf;$i++)
							if($i==$AnioAc){echo "<option selected value=$i>$i</option>";}
							else{echo "<option value=$i>$i</option>";}
							?></select>
							<select name="MesIni" onFocus='Ocultar()'>
							<?for($i=1;$i<=12;$i++)
							{
								if($ND[mon]==$i){echo "<option selected value='$i'>$NombreMesC[$i]</option>";}
								else{echo "<option value='$i'>$NombreMesC[$i]</option>";}
							}
							?>
							</select>
							<input type='Text' onFocus='Ocultar()' name='DiaIni' style='width:20px;' maxlength="2" value='01'>

							</td>
							<td>
							<select name="MesFin" onFocus='Ocultar()'>
							<?for($i=1;$i<=12;$i++)
							{
								if($ND[mon]==$i){echo "<option selected value='$i'>$NombreMesC[$i]</option>";}
								else{echo "<option value='$i'>$NombreMesC[$i]</option>";}
							}
							?>
							</select>
							<input type='Text' onFocus='Ocultar()' name='DiaFin' style='width:20px;' maxlength="2" value='<?echo $ND[mday]?>'>
							<?
						}
						
						
						if($Tipo==7){
							echo "<form name='FORMA' action='$NomArchivo' target='Abajo'>";
								echo "<tr>";
									echo "<td class='encabezado2Horizontal' colspan=2>CORTE</td>";
									echo "<td class='encabezado2Horizontal'>CUENTA INICIAL</td>";
									echo "<td class='encabezado2Horizontal'>CUENTA FINAL</td>";
									echo "<td class='encabezado2Horizontal'>TERCERO</td>";
									echo "<td class='encabezado2Horizontal'>DOCUMENTOS</td>";
							?><tr><td>
							<select name="Anio" onFocus='Ocultar()'><?
							for($i=$AnioInc;$i<$AnioAf;$i++)
							if($i==$AnioAc){echo "<option selected value=$i>$i</option>";}
							else{echo "<option value=$i>$i</option>";}
							?></select>
							<td>
							<select name="MesFin" onFocus='Ocultar()'>
							<?for($i=1;$i<=12;$i++)
							{
								if($ND[mon]==$i){echo "<option selected value='$i'>$NombreMesC[$i]</option>";}
								else{echo "<option value='$i'>$NombreMesC[$i]</option>";}
							}
							?>
							</select>
							<input type='Text' name='DiaFin' style='width:20px;' maxlength="2" onFocus='Ocultar()' value='<?echo $ND[mday]?>'>
							<td><input type='Text' name='CuentaIni' style='width:100%;' value="<? echo $CuentaIni?>" 
							onFocus="Mostrar();BuscarCuenta(this.name,this.value)" onKeyDown="BuscarCuenta(this.name,this.value)" onKeyUp="BuscarCuenta(this.name,this.value)" onBlur="CuentaFin.value=CuentaIni.value"></td>
							<td><input type='Text' name='CuentaFin' style='width:100%;' value="<? echo $CuentaFin?>" 
							onFocus="Mostrar();BuscarCuenta(this.name,this.value)" onKeyDown="BuscarCuenta(this.name,this.value)" onKeyUp="BuscarCuenta(this.name,this.value)"></td>
							<td><input type='Text' name='Tercero' style='width:100%;' value="<? echo $Tercero?>" onFocus="Mostrar();BuscarTercero(this.name,this.value)" onKeyUp="BuscarTercero(this.name,this.value)" onKeyDown="BuscarTercero(this.name,this.value)">
					<?
							echo "<td><input type='checkbox' name='MostrarDocs'><input type='Text' name='NoDoc' style='width:60px;'></td>";
						
						}

						if($Tipo==8){
							echo "<form name='FORMA' action='$NomArchivo' target='Abajo'>";
								echo "<tr>";
									echo "<td class='encabezado2Horizontal' colspan=2>PERIODO</td>";
									echo "<td class='encabezado2Horizontal'>DIGITOS</td>";
									echo "<td class='encabezado2Horizontal'>CUENTA INICIAL</td>";
									echo "<td class='encabezado2Horizontal'>CUENTA FINAL</td>";
									echo "<td class='encabezado2Horizontal'>AGRUPADO X</td>";
							echo "<tr><td><input type='Text' name='PerIni' style='width:70px;' value='$PerIni' onFocus='Ocultar()'></td>";
							echo "<td><input type='Text' name='PerFin' style='width:70px;' value='$PerFin' onFocus='Ocultar()'></td>";
							echo "<td><input type='Text' name='NoDigitos' style='width:70px;' value=$NoDigitos onFocus='Ocultar()'></td>";?>
							<td><input type='Text' name='CuentaIni' style='width:50px;' value="<? echo $CuentaIni?>" 
							onFocus="Mostrar();BuscarCuenta(this.name,this.value)" onKeyDown="BuscarCuenta(this.name,this.value)" onKeyUp="BuscarCuenta(this.name,this.value)" onBlur="CuentaFin.value=CuentaIni.value"></td>
							<td><input type='Text' name='CuentaFin' style='width:50px;' value="<? echo $CuentaFin?>" 
							onFocus="Mostrar();BuscarCuenta(this.name,this.value)" onKeyDown="BuscarCuenta(this.name,this.value)" onKeyUp="BuscarCuenta(this.name,this.value)"></td>
							<input type='Button' value='...' onClick="open('/Contabilidad/BusquedaxOtros.php?Tipo=Cuentas&Campo=CuentaFin','','width=600,height=400')"></td>
					<?
							echo "<td><select name='Agruparx' onFocus='Ocultar()'>
							<option value='Cuenta'>Cuenta</option>
							<option value='Comprob'>Comprob</option>
							</select>
							</td>";
						}
						if($Tipo==9)	{
							echo "<form name='FORMA' action='$NomArchivo' target='Abajo'>";
								echo "<tr>";
									echo "<td class='encabezado2Horizontal' colspan=2>VIGENCIA</td>";?>
								<tr><td>
							<select name="Anio" onFocus='Ocultar()'><?
							for($i=$AnioInc;$i<$AnioAf;$i++)
							if($i==$AnioAc){echo "<option selected value=$i>$i</option>";}
							else{echo "<option value=$i>$i</option>";}
							?></select><?
						}
						

						if($Tipo==10){
							echo "<form name='FORMA' action='$NomArchivo' target='Abajo'>";
								echo "<tr>";
									echo "<td  class='encabezado2Horizontal'colspan=2>PERIODO</td>";
									echo "<td class='encabezado2Horizontal'>TERCERO</td>";
									echo "<td class='encabezado2Horizontal'>COMPROBANTE</td>";
							?>
							<tr>
							<td>
							<select name="Anio" onFocus='Ocultar()'><?
							for($i=$AnioInc;$i<$AnioAf;$i++)
							if($i==$AnioAc){echo "<option selected value=$i>$i</option>";}
							else{echo "<option value=$i>$i</option>";}
							?></select>
							<select name="MesIni" onFocus='Ocultar()'>
							<?for($i=1;$i<=12;$i++)
							{
								if($ND[mon]==$i){echo "<option selected value='$i'>$NombreMesC[$i]</option>";}
								else{echo "<option value='$i'>$NombreMesC[$i]</option>";}
							}
							?>
							</select>
							<input type='Text' name='DiaIni' onFocus='Ocultar()' style='width:20px;' maxlength="2" value='01'>

							</td>
							<td>
							<select name="MesFin" onFocus='Ocultar()'>
							<?for($i=1;$i<=12;$i++)
							{
								if($ND[mon]==$i){echo "<option selected value='$i'>$NombreMesC[$i]</option>";}
								else{echo "<option value='$i'>$NombreMesC[$i]</option>";}
							}
							?>
							</select>
							<input type='Text' onFocus='Ocultar()' name='DiaFin' style='width:20px;' maxlength="2" value='<?echo $ND[mday]?>'>
							<td><input type='Text' name='Tercero' style='width:60px;' value="<? echo $Tercero?>" onFocus="Mostrar();BuscarTercero(this.name,this.value)" onKeyUp="BuscarTercero(this.name,this.value)" onKeyDown="BuscarTercero(this.name,this.value)">
							<td><input type='Text' name='Comprobante' style='width:70px;' value="<? echo $Comprobante?>" onFocus="Mostrar();BuscarComprobante(this.name,this.value)" onKeyUp="BuscarComprobante(this.name,this.value)" onKeyDown="BuscarComprobante(this.name,this.value)">
					<?
						}

						}
					?>
					</table>
					</td>
					<td>
					
						<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr>
								<td class='encabezado2Horizontal'>FILAS</td>
							</tr>
							<tr>
								<td><input type="Text" name="Encabezados" style="width:40px;" value="50"></td>
							</tr>
						</table>
					</td>
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					<td><input type="Submit" name="Ver" class="boton2Envio" style="text-align:center;" value="Ver" onFocus="Ocultar()"></td>
				</table>
		
			</form>
					<iframe id="Busquedas" name="Busquedas" style="display:none;" src="/Contabilidad/Busquedas.php?DatNameSID=<? echo $DatNameSID?>&" frameborder="0" height="250" ></iframe>
			</div>
		</body>