		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			header("Cache-Control: no-store, no-cache, must-revalidate");		
			if($QL==1&&empty($Guardar))
			{	
				$cons="Update HistoriaClinica.Formatos set LogoAdicional=''	where Formato='$NewFormato' and TipoFormato='$TF' and Compania='$Compania[0]'";
				$res=ExQuery($cons);		
				unlink($_SERVER['DOCUMENT_ROOT'].$LogoAnt);
				$LogoAnt="";$QL="";
				unset($LogoAnt);		
				unset($QL);	
				?><script language="javascript"></script><?			
			}
			if($Guardar){			
				//echo "$Logo --> ".$_FILES['Logo']['name'];
				if($Agrupar=='on' || $Agrupar=="Si"){$Agrupar='Si';}else{$Agrupar='No';}
				if($FormatoExterno=='on' || $FormatoExterno=="Si"){$FormatoExterno='Si';}else{$FormatoExterno='No';}
				if($CierreVoluntario==""){$CierreVoluntario="No";}else{$CierreVountario="Si";}
				if($Seleccionable==""){$Seleccionable="No";}else{$Seleccionable="Si";}
				if($Epicrisis==""){$Epicrisis="No";}else{$Epicrisis="Si";}
				if($PacienteSeguro==""){$PacienteSeguro="No";}else{$PacienteSeguro="Si";}
				//echo "$CierrarFormatoNoPos<br>";
				if($CierrarFormatoNoPos=='on'||$CierrarFormatoNoPos=="Si"){$CierrarFormatoNoPos="Si";}else{$CierrarFormatoNoPos="No";}
				//if($XML==""){$XML="No";}else{$XML="Si";}
				$Aux2=str_replace('\\','/',$AuxLogo);	
				if(!$Edit)
				{
					$cons="Select tblformat from HistoriaClinica.Formatos order by tblformat desc";
					$res=ExQuery($cons);
					$fila=ExFetch($res);
					//echo $fila[0];
					$NumReg=$fila[0];
					if(!$NumReg){$NumReg="tbl00000";}
					$NumReg++;
					//echo " $NumReg";
					//$NumReg=ExNumRows($res);
					//$NumReg=substr("00000",1,5-strlen($NumReg)).$NumReg;
					$Rr="/Imgs/HistoriaClinica/Formatos/$TipoFormato/";
					if (is_uploaded_file($_FILES['Logo']['tmp_name'])) 
					{
						$serv=$_SERVER['DOCUMENT_ROOT'];											
						if(is_dir($serv.$Rr))
						{
							chmod($serv.$Rr,0777);	
						}	
						else
						{
							mkdir($serv.$Rr,0777,true);	
						}															
						copy($_FILES['Logo']['tmp_name'], str_replace(" ","","$NewFormato$Extension")); 				
						copy("$serv/HistoriaClinica/Administracion/".str_replace(" ","","$NewFormato$Extension"),"$serv".$Rr.str_replace(" ","","$NewFormato$Extension"));										
						unlink("$serv/HistoriaClinica/Administracion/".str_replace(" ","","$NewFormato$Extension"));	
						$PartLogoCons=",LogoAdicional,AltoLogo,AnchoLogo";									
						$PartLogoIns=",'$Rr".str_replace(" ","","$NewFormato$Extension")."',50,50";
					}
					elseif($_FILES['Logo']['tmp_name'])
					{
						?><script language="javascript">alert("No se pudo subir el Logo Adicional!!!");</script><?
						$PartLogoCons="";								
						$PartLogoIns="";	
					}
					//---- 			
					if(!$Paguinacion){$Paguinacion="0";}
					$cons="Insert 
					into HistoriaClinica.Formatos 		
					(Formato,Ajuste,TipoFormato,UsuarioCre,Alineacion,AgruparxHospi,CierreVoluntario,Compania,tblformat,formatoesterno,paguinacion,laboratorio,rutaformatant,seleccionable
					,NoPos,Epicrisis,formatoxml,Acudientes,ReqAmbito,ambitoformato,CierraFormatoNoPos, incluirsignosvitales,pacienteseguro,firmapaciente,
					confimpdiagnostico, impcoddiagnostico, impnomdiagnostico $PartLogoCons) 
					values 										   
					('$NewFormato','$Ajuste','$TipoFormato','$usuario[0]','$Alineacion','$AgruparxHospi','$CierreVoluntario','$Compania[0]','$NumReg','$FormatoExterno',$Paguinacion
					,'$Laboratorio','$RutaFormatAnt','$Seleccionable','$NoPos','$Epicrisis',$FormatoXML,'$Acudientes','$ReqAmbito','$AmbitoFormato','$CierrarFormatoNoPos', '$IncluirSignosVitales','$PacienteSeguro','$FirmaPacienteF', '$ConfigDiagnostico', '$CodDiagnostico', '$NomDiagnostico' $PartLogoIns)";
					//echo $cons;
					/*$cons="Insert into HistoriaClinica.Formatos 			
					(Formato,Ajuste,TipoFormato,UsuarioCre,Alineacion,AgruparxHospi,CierreVoluntario,Compania,tblformat) values
					('$NewFormato','$Ajuste','$TipoFormato','$usuario[0]','$Alineacion','$AgruparxHospi','$CierreVoluntario','$Compania[0]','tbl$NumReg')";*/
					$res=ExQuery($cons);
					
					$Edit=1;
					$cons2="
						CREATE TABLE histoclinicafrms.$NumReg
						(
							formato character varying(150) NOT NULL,
							tipoformato character varying(150) NOT NULL,
							id_historia integer NOT NULL DEFAULT 0,
							usuario character varying(150) NOT NULL,
							cargo character varying(80) NOT NULL,
							fecha date,
							hora time without time zone,
							cedula character varying(15) NOT NULL,
							ambito character varying(150),
							unidadhosp character varying(150),
							numservicio integer,
							compania character varying(60) NOT NULL,
							cerrado integer,
							noliquidacion integer DEFAULT 0,
							finalidadconsult character varying(5),
							causaexterna character varying(5),					  
							dx1 character varying(6),
							dx2 character varying(6),
							dx3 character varying(6),
							dx4 character varying(6),
							dx5 character varying(6),
							tipodx character varying(1),
							numproced integer,
							usuarioajuste character varying(30),
							fechaajuste date,	
							padretipoformato character varying(150),
							padreformato character varying(150),
							id_historia_origen integer,				

								CONSTRAINT PkHCtbl$NumReg PRIMARY KEY (formato, tipoformato, id_historia, cedula, compania),

								CONSTRAINT fkambtbl$NumReg FOREIGN KEY (ambito, compania)
								REFERENCES salud.ambitos (ambito, compania) MATCH SIMPLE
								ON UPDATE CASCADE ON DELETE RESTRICT,					

								CONSTRAINT fkitemsxtbl$NumReg FOREIGN KEY (formato, tipoformato, compania)
								REFERENCES historiaclinica.formatos (formato, tipoformato,  compania) MATCH SIMPLE
								ON UPDATE CASCADE ON DELETE RESTRICT,

								CONSTRAINT fkmedxtbl$NumReg FOREIGN KEY (usuario, cargo, compania)
								REFERENCES salud.medicos (usuario, cargo, compania) MATCH SIMPLE
								ON UPDATE CASCADE ON DELETE RESTRICT,

								CONSTRAINT fkpabxtbl$NumReg FOREIGN KEY (unidadhosp, compania, ambito)
								REFERENCES salud.pabellones (pabellon, compania, ambito) MATCH SIMPLE
								ON UPDATE CASCADE ON DELETE RESTRICT,

								CONSTRAINT fktercxtbl$NumReg FOREIGN KEY (cedula, compania)
								REFERENCES central.terceros (identificacion, compania) MATCH SIMPLE
								ON UPDATE CASCADE ON DELETE RESTRICT
						)
							";
						//echo $cons2;
						$res=ExQuery($cons2);
				}
				else
				{			
					$Rr="/Imgs/HistoriaClinica/Formatos/$TipoFormato/";
					if (is_uploaded_file($_FILES['Logo']['tmp_name'])) 
					{
						$serv=$_SERVER['DOCUMENT_ROOT'];											
						if(is_dir($serv.$Rr))
						{
							chmod($serv.$Rr,0777);	
						}	
						else
						{
							mkdir($serv.$Rr,0777,true);	
						}	
						if(is_file($_SERVER['DOCUMENT_ROOT']."$LogoAnt"))
						{
							unlink($_SERVER['DOCUMENT_ROOT']."$LogoAnt");														
						}
						copy($_FILES['Logo']['tmp_name'], str_replace(" ","","$NewFormato$Extension")); 				
						copy("$serv/HistoriaClinica/Administracion/".str_replace(" ","","$NewFormato$Extension"),"$serv".$Rr.str_replace(" ","","$NewFormato$Extension"));										
						unlink("$serv/HistoriaClinica/Administracion/".str_replace(" ","","$NewFormato$Extension"));	
						$ccons="Select LogoAdicional from HistoriaClinica.Formatos where Compania='$Compania[0]' and Formato='$NewFormato'
						and TipoFormato='$TipoFormato'";
						$rres=ExQuery($ccons);
						$ffila=ExFetch($rres);
						if($ffila[0])
						{
							$PartLogo=",LogoAdicional='$Rr".str_replace(" ","","$NewFormato$Extension")."'";									
						}
						else
						{
							$PartLogo=",LogoAdicional='$Rr".str_replace(" ","","$NewFormato$Extension")."', Anchologo=60, AltoLogo=60";									
						}
					}
					elseif($_FILES['Logo']['tmp_name'])
					{
						?><script language="javascript">alert("No se pudo subir el Logo Adicional!!!");</script><?
						$PartLogo="";									
					}
					//---- 
					//echo "$serv/Imgs/HistoriaClinica/Formatos/$TipoFormato$NewFormato$Extension";
					if($Paguinacion==''){$Paguinacion="0";}
					$cons="Update HistoriaClinica.Formatos set Ajuste='$Ajuste',Alineacion='$Alineacion',AgruparxHospi='$Agrupar',CierreVoluntario='$CierreVoluntario',
					paguinacion=$Paguinacion,formatoesterno='$FormatoExterno',laboratorio='$Laboratorio',rutaformatant='$RutaFormatAnt',seleccionable='$Seleccionable',NoPos='$NoPos'
					,epicrisis='$Epicrisis',formatoxml=$FormatoXML,Acudientes='$Acudientes',ReqAmbito='$ReqAmbito',ambitoformato='$AmbitoFormato' 
					,cierraformatonopos='$CierrarFormatoNoPos', incluirsignosvitales='$IncluirSignosVitales',pacienteseguro='$PacienteSeguro',firmapaciente='$FirmaPacienteF', confimpdiagnostico='$ConfigDiagnostico', impcoddiagnostico='$CodDiagnostico', impnomdiagnostico='$NomDiagnostico' $PartLogo
					where Formato='$NewFormato' and TipoFormato='$TipoFormato' and Compania='$Compania[0]'";
					$res=ExQuery($cons);
					//echo $cons;
				}			
			}	
			if($Edit){
				if($Ban==0){
					$cons="Select * from HistoriaClinica.Formatos where Formato='$NewFormato' and TipoFormato='$TF' 
					and Compania='$Compania[0]'";
					//echo $cons;
					$res=ExQuery($cons,$conex);
					$fila3=ExFetchArray($res);
					$TblFormat=$fila3['tblformat'];
					$Laboratorio=$fila3['laboratorio'];
					$Paguinacion=$fila3['paguinacion'];
					$AmbitoFormato=$fila3['ambitoformato'];	
					if(!$IncluirSignosVitales){$IncluirSignosVitales=$fila3['incluirsignosvitales'];}
					if(!$FirmaPacienteF){$FirmaPacienteF=$fila3['firmapaciente'];}	
					if(!$ConfigDiagnostico){$ConfigDiagnostico=$fila3['confimpdiagnostico'];}
					if(!$CodDiagnostico){$CodDiagnostico=$fila3['impcoddiagnostico'];}
					if(!$NomDiagnostico){$NomDiagnostico=$fila3['impnomdiagnostico'];}	
					$Ban=1;
				}
				if($Guardar)
				{
					$consalt="select column_name from information_schema.columns where table_name = '$TblFormat' and (column_name='usuarioajuste' or column_name='fechaajuste');";
					$resalt=ExQuery($consalt);
					if(ExNumRows($resalt)==0)
					{
						$consalt="ALTER TABLE histoclinicafrms.$TblFormat ADD COLUMN usuarioajuste character varying(30), ADD COLUMN fechaajuste date";	
						$resalt=ExQuery($consalt);
					}
					$consalt="select column_name from information_schema.columns where table_name = '$TblFormat' and (column_name='padretipoformato' or  column_name='padreformato' or column_name='id_historia_origen');";			
					$resalt=ExQuery($consalt);
					if(ExNumRows($resalt)==0)
					{
						$consalt="ALTER TABLE histoclinicafrms.$TblFormat ADD COLUMN padretipoformato character varying(150), ADD COLUMN padreformato character varying(150), ADD COLUMN id_historia_origen integer";	
						$resalt=ExQuery($consalt);
					}
				}
			}
			//--acudientes
			$cons="SELECT formato, tipoformato, tblformat, acudientes FROM historiaclinica.formatos where Compania='$Compania[0]' and Acudientes='Si'";
			$res=ExQuery($cons);
			if(ExNumRows($res)>0)
			{
				$fila=ExFetch($res);
				$FormatoAcu=$fila[0];$TipoFormatoAcu=$fila[1];$TablaAcu=$fila[2];
			}	
		?>
		
		<html>
			<head>
				<?php echo $codificacionMentor; ?>
				<?php echo $autorMentor; ?>
				<?php echo $titleMentor; ?>
				<?php echo $iconMentor; ?>
				<?php echo $shortcutIconMentor; ?>
				<link rel="stylesheet" type="text/css" href="../../General/Estilos/estilos.css">
				
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<meta http-equiv="Expires" content="Mon, 26 Jul 1997 05:00:00 GMT">
				<meta http-equiv="Last-Modified" content="Mon, 02 Jun 2014 16:12:09 GMT">
				<meta http-equiv="Cache-Control" content="no-cache, must-revalidate">
				<meta http-equiv="Pragma" content="nocache">
				<style type="text/css">
					@import url("../../SpryAssets/SpryCollapsiblePanel.css");
				</style>
				<!--<script type="text/javascript" src="/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>-->
					<script src="/SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
					<script language="javascript">
					function TamVentana() {  
					  var Tamanyo = [0, 0];  
					  if (typeof window.innerWidth != 'undefined')  
					  {  
						Tamanyo = [  
							window.innerWidth,  
							window.innerHeight  
						];  
					  }  
					  else if (typeof document.documentElement != 'undefined'  
						  && typeof document.documentElement.clientWidth !=  
						  'undefined' && document.documentElement.clientWidth != 0)  
					  {  
					 Tamanyo = [  
							document.documentElement.clientWidth,  
							document.documentElement.clientHeight  
						];  
					  }  
					  else   {  
						Tamanyo = [  
							document.getElementsByTagName('body')[0].clientWidth,  
							document.getElementsByTagName('body')[0].clientHeight  
						];  
					  }  
					  return Tamanyo;  
					}  
					/*window.onload = function() {  
					  var Tam = TamVentana();  
					  alert('La ventana mide: [' + Tam[0] + ', ' + Tam[1] + ']');  
					}*/
					function AbrirImg(Imagen,AnchoLogo,AltoLogo)
					{		
						var Tam = TamVentana(); 	
						//alert(Tam[0]+" , "+Tam[1]);
						var leftOffset = parseInt((Tam[0]/2)) - parseInt((AnchoLogo/2));	
						var topOffset = parseInt((Tam[1]/3)) - parseInt((AltoLogo/2)) ;
						
						<? $_SESSION['Recarga']=1;?>	
						frames.FrameOpener.location.href="VerImagen.php?DatNameSID=<? echo $DatNameSID?>&Recarga=1&Imagen="+Imagen+"&AnchoLogo="+AnchoLogo+"&AltoLogo="+AltoLogo;
						document.getElementById('FrameOpener').style.position='absolute';
						document.getElementById('FrameOpener').style.top=topOffset ;
						document.getElementById('FrameOpener').style.left=leftOffset ;
						document.getElementById('FrameOpener').style.display='';
						document.getElementById('FrameOpener').style.width=AnchoLogo;
						document.getElementById('FrameOpener').style.height=AltoLogo;	
					}
					function CambiarTamañoLogo()
					{	
						frames.TamLogo.location.href="TamanoLogo.php?DatNameSID=<? echo $DatNameSID?>&TipoFormato=<? echo $TF?>&Formato=<? echo $NewFormato?>";
						document.getElementById('TamLogo').style.position='absolute';
						document.getElementById('TamLogo').style.top='25%';
						document.getElementById('TamLogo').style.left='40%';
						document.getElementById('TamLogo').style.display='';
						document.getElementById('TamLogo').style.width='190px';
						document.getElementById('TamLogo').style.height='130px';	
					}
					function ContratoxLogo()
					{	
						frames.ContLogo.location.href="ContratoLogo.php?DatNameSID=<? echo $DatNameSID?>&TipoFormato=<? echo $TF?>&Formato=<? echo $NewFormato?>&PV=1";
						document.getElementById('ContLogo').style.position='absolute';
						document.getElementById('ContLogo').style.top='25%';
						document.getElementById('ContLogo').style.left='15%';
						document.getElementById('ContLogo').style.display='';
						document.getElementById('ContLogo').style.width='70%';
						document.getElementById('ContLogo').style.height='200px';	
					}
					function CerrarImg(Imagen)
					{
						frames.FrameOpener.location.href="";	
						document.getElementById('FrameOpener').style.display='';
					}
					function Info(Evento,Dato,Div,AnchoLogo,AltoLogo) 
					{
						<!-- Due to different browser naming of certain key global variables, we need to do three different tests to determine their values -->
						//alert(Div+"Imagen");
						// Determine how much the visitor had scrolled	
						var PosMouseX,PosMouseY;
						PosMouseX=Evento.clientX;
						PosMouseY=Evento.clientY;
						//--
						var ajusteX, ajusteY;
						if( self.pageYOffset ) 
						{
							ajusteX = self.pageXOffset;
							ajusteY = self.pageYOffset;
						}
						else if( document.documentElement && document.documentElement.scrollTop ) 
						{
							ajusteX = document.documentElement.scrollLeft;
							ajusteY = document.documentElement.scrollTop;
						} 
						else if( document.body ) 
						{
							ajusteX = document.body.scrollLeft;
							ajusteY = document.body.scrollTop;
						}
						var leftOffset;
						if(Div="VerLogo"){leftOffset = ajusteX + (PosMouseX )-(parseInt(AnchoLogo/2));}
						else{leftOffset = ajusteX + (PosMouseX );}
						var topOffset = ajusteY + (PosMouseY )-(AltoLogo)-10 ;
						//alert(Div+"Msj");
						var Msjforma=Div+"Imagen";	
						document.getElementById(Msjforma).width=AnchoLogo;
						document.getElementById(Msjforma).height=AltoLogo;
						document.getElementById(Msjforma).value="";
						document.getElementById(Msjforma).src="";	
						document.getElementById(Msjforma).src=Dato;	
						//document.getElementById(Msjforma).dynsrc=Dato;
						document.getElementById(Div).style.width=AnchoLogo;
						document.getElementById(Div).style.height=AltoLogo;
						document.getElementById(Div).style.top = topOffset + "px";
						document.getElementById(Div).style.left = leftOffset + "px";
						document.getElementById(Div).style.display = "block";
						if(Dato==""){document.getElementById(Msjforma).style.background="none";}
					}
						function NoAjuste(){
							if(document.FORMA.CierreVoluntario.checked){
								document.FORMA.Ajuste.value="";
								document.FORMA.Ajuste.disabled=!document.FORMA.Ajuste.disabled;
							}
							else{
								document.FORMA.Ajuste.disabled=!document.FORMA.Ajuste.disabled;
							}
						}
						function DejarChecked(NomChecked)
						{
							if(document.FORMA.XML.checked){}else{document.FORMA.XML.checked = "true"; }
						}
						function Validar(Archivo)
						{		
							//-----
							if(document.FORMA.CodDiagnostico.checked==false&&document.FORMA.NomDiagnostico.checked==false){alert("Debe seleccionar por lo menos una opcion para mostrar el diagnostico en la impresion del formato!!!");return false;}	
							//---
							if(!Archivo)
							{
								return true;
							}
							else
							{					
								var extensiones_permitidas=new Array(".jpg",".png");	
								//recupero la extensión de este nombre de archivo 
								extension = (Archivo.substring(Archivo.lastIndexOf("."))).toLowerCase(); 			
								//alert (extension); 
								//compruebo si la extensión está entre las permitidas 
								permitida = false; 
								for (var i = 0; i < extensiones_permitidas.length; i++)
								{ 
									if (extensiones_permitidas[i] == extension) 
									{ 
										permitida = true; 
										break; 
									} 
								}
							} 
							if (!permitida) 
							{ 
								alert("Comprueba la extensión de la imagen a subir para el Logo Adicional. \nSólo se pueden subir archivos con extensiones: " + extensiones_permitidas.join()); 
								document.FORMA.Extension.value="";
								return false;
							}					
							else
							{
								document.FORMA.Extension.value=extension;	
							}		
						}	
						function VerPanel()
						{
							if(CollapsiblePanel1.contentIsOpen==true){document.FORMA.EstadoPanel.value='Closed';document.getElementById("ItemsxFormatos").height="450";}else{document.FORMA.EstadoPanel.value='Open';document.getElementById("ItemsxFormatos").height="350";}		
						}
					</script>
			</head>
			
			<body <?php echo $backgroundBodyMentor; ?>>
					<?php
						$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
						$rutaarchivo[1] = "CONFIGURACI&Oacute;N";
						$rutaarchivo[2] = "ADMINISTRACI&Oacute;N DE FORMATOS";
						mostrarRutaNavegacionEstatica($rutaarchivo);
					?>
			
					<form name="FORMA" method="post" enctype="multipart/form-data" onSubmit="return Validar(document.FORMA.Logo.value);">
					
					<div id="CollapsiblePanel1" class="CollapsiblePanel"  >
						<div class="CollapsiblePanelTab"  tabindex="0"    onClick="VerPanel()">
							<? echo strtoupper("$NewFormato<br></font><font size=-1>".$TblFormat)?>
						</div>
					  <div class="CollapsiblePanelContent">  
					  <table width="100%" class="tabla3"  <?php echo $borderTabla3Mentor; echo $bordercolorTabla3Mentor; echo $cellspacingTabla3Mentor; echo $cellpaddingTabla3Mentor; ?>>
					   <!--<tr>
						  <td>          
							<strong> <font size="5"><center><? //echo strtoupper("$NewFormato<br></font><font size=-1>".$TblFormat)?></center> </font></strong>
						  </td>
						</tr>   -->
						
						<tr style="background-color:#FFFFFF;">
						  <td style="text-align:center;">     
							<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>                
								<tr>
									<td class="encabezado2VerticalInvertido"> TIPO DE FORMATO</td>
									<td><input type="Text" readonly="yes" value="<?echo $TF?>" name="TipoFormato"> </td>            
									<td class="encabezado2VerticalInvertido">CLASE DE FORMATO</td>
									<td>
										<select name="ClaseFormato" onChange="FORMA.submit()">
											<option value="General">General</option>	             
											<option value="Laboratorio" <? if($ClaseFormato=='Laboratorio'||$fila3['laboratorio']!=NULL){ echo "selected";}?> >Laboratorio</option>	             
										</select>
									
							   <?	if($ClaseFormato=='Laboratorio'||$fila3['laboratorio']!=NULL){
										$consLab="select clasificacion from salud.clasifclabs where compania='$Compania[0]'";
										$resLab=ExQuery($consLab);?>
										<select name="Laboratorio">
										<?	while($filaLab=ExFetch($resLab)){
												if($filaLab[0]==$Laboratorio){
													echo "<option value='$filaLab[0]' selected>$filaLab[0]</option>";
												}		
												else{
													echo "<option value='$filaLab[0]'>$filaLab[0]</option>";
												}
										}?>
										</select>
								<?	}
								 
								 ?>   
								</td>    
								<td class="encabezado2VerticalInvertido">AMBITO DEL FORMATO</td>  
								<td>
									<select name="AmbitoFormato">
										<option value="Recuperacion" <? if($AmbitoFormato=="Recuperacion"){?> selected <? }?>>Recuperacion</option>
										<option value="PyP" <? if($AmbitoFormato=="PyP"){?> selected <? }?>>P y P</option>
									</select>
								</td>
								</tr>       
								<tr>            
								  <td class="encabezado2VerticalInvertido">ALINEACI&Oacute;N</strong>
								 </td>
								 <td>
									<select name="Alineacion" >
									  <?
										$cons="Select * from HistoriaClinica.Alineacion Order By Nombre Desc";
										$res=ExQuery($cons,$conex);
										while($fila=ExFetch($res))
										{
											if($fila[0]==$fila3['alineacion']||$Alineacion==$fila[0])
											{
												echo "<option selected value='$fila[0]'>$fila[0]</option>";
											}
											else
											{
												{echo "<option value='$fila[0]'>$fila[0]</option>";}
											}
													
										}
									?>
								  </select>            
								  <span class="encabezado2VerticalInvertido">ESCRITURA</span> 
									<input type="Button" class="boton2Envio"  value="..." onClick="frames.ItemsxFormatos.location.href='EditPermisos.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>&ClasePermiso=Escritura&NewFormato=<?echo $NewFormato?>&TF=<?echo $TF?>'">
								  </td>
								  <td class="encabezado2VerticalInvertido">IMPRESI&Oacute;N</td>
								  <td><input type="Button" class="boton2Envio" value="..." onClick="frames.ItemsxFormatos.location.href='EditPermisos.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>&ClasePermiso=Impresion&NewFormato=<?echo $NewFormato?>&TF=<?echo $TF?>'"></td>
								  <td class="encabezado2VerticalInvertido">AGRUPAR POR SERVICIO</td>             
								  <td><? if($fila3['agruparxhospi']=='Si'||$Agrupar=='Si')	 { echo "<input type='checkbox' name='Agrupar' value='Si' checked>";}else { echo "<input type='checkbox' name='Agrupar'>";}?></td>
								  </tr>
								<tr>
								  <td class="encabezado2VerticalInvertido">AJUSTE</td>
								  <td><input type="Text" name="Ajuste" style="width:40px;" <? if($fila3['cierrevoluntario']=='Si'){ ?> disabled <? }?>value="<? if($fila3['cierrevoluntario']!='Si'){ if(!$Ajuste){echo $fila3['ajuste'];}else{echo $Ajuste;} } ?>"  >
								  <span class="encabezado2VerticalInvertido" >MINUTOS </span>
									 <input title="Ajuste Permanente" class="boton2Envio" type="Button" class="boton2Envio" value="..." onClick="frames.ItemsxFormatos.location.href='AjustePermanente.php?DatNameSID=<? echo $DatNameSID?>&ClasePermiso=Modificacion&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>'">
								  </td>
								  <td class="encabezado2VerticalInvertido">VISTO BUENO X</td>
								  <td><input type="Button" class="boton2Envio" value="..." onClick="frames.ItemsxFormatos.location.href='VoBueno.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>'">
								  </td>
								  <td class="encabezado2VerticalInvertido">PAGINACI&Oacute;N</td>
								  <?	?>
								  <td>	<select name="Paguinacion"><option></option>
									<?	for($i=20;$i<=100;$i=$i+5){
											if($Paguinacion==$i){
												echo "<option value='$i' selected>$i</option>";
											}
											else{
												echo "<option value='$i'>$i</option>";
											}
										}?>
										</select>
								  </td>
								  </tr>
								<tr>
								  <td class="encabezado2VerticalInvertido">AGENDA INTERNA</td>
								  <td><input type="Button" class="boton2Envio" value="..." onClick="frames.ItemsxFormatos.location.href='SelAgendaInterna.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<?echo $NewFormato?>&TF=<?echo $TF?>'"> 
								  
								  <span class="encabezado2VerticalInvertido">FORMATO EXTERNO</span>              
								  <? 	if($fila3['formatoesterno']=='Si'||$FormatoExterno=='Si')	 { echo "<input type='checkbox' name='FormatoExterno' value='Si' checked>";
										}			  
										else { 						
											echo "<input type='checkbox' name='FormatoExterno' value='No'>";
										}?>
								  </td>
								  <td class="encabezado2VerticalInvertido">TITULOS</td>
								  <td><input type="Button" class="boton2Envio" value="..." onClick="frames.ItemsxFormatos.location.href='AsignarTitulos.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<?echo $NewFormato?>&TF=<?echo $TF?>'">
								  </td>
								  <td class="encabezado2VerticalInvertido">CIERRE VOLUNTARIO</td>
								  <td><? if($fila3['cierrevoluntario']=='Si'||$CierreVoluntario=='Si'){?><input type="checkbox" name="CierreVoluntario" onClick="NoAjuste()" value="Si" checked><? }
										 else{?><input type="checkbox" name="CierreVoluntario" value="Si" onClick="NoAjuste()"><? }?>
								  </td>
								 </tr>
								 <tr>
									<td class="encabezado2VerticalInvertido">RUTA FORMATO ANTERIOR</td>
									<td colspan="3">
										<input type="text" name="RutaFormatAnt" style="width:380" 
											 value="<? if(!$RutaFormatAnt){ echo $fila3['rutaformatant']; }else{ echo $RutaFormatAnt; }?>">
									</td>
									<td class="encabezado2VerticalInvertido">SELECCIONABLE</td>
									<td><input type="checkbox" name="Seleccionable" value="Si" <? if($fila3['seleccionable']=='Si'||$Seleccionable=='Si'){?> checked<? }?>>
									</td>
								</tr>
								<tr>
									<td class="encabezado2VerticalInvertido">PACIENTE SEGURO</td>
								<!--  <td><input type="Button" class="boton2Envio" value="..." 
										onClick="frames.ItemsxFormatos.location.href='PacienteSeg.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>'">
									</td>-->
									<td><input type="checkbox" name="PacienteSeguro" value="Si" <? if($fila3['pacienteseguro']=='Si'||$PacienteSeguro=='Si'){?> checked<? }?>></td>
								<?	$consNoPos="select nopos from historiaclinica.formatos where compania='$Compania[0]' and nopos is not null and estado='AC'";
									//echo $consNoPos;
									$resNoPos=ExQuery($consNoPos);
									while($filaNoPos=ExFetch($resNoPos))
									{					
										if($filaNoPos[0]=="Medicamentos No POS"){$banMedNoPos=1; }
										if($filaNoPos[0]=="CUPS No POS"){$banServNoPos=1;}
									}
									$consNPFormato="select nopos from historiaclinica.formatos where compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF'";
									//echo $consNPFormato;
									$resNPFormato=ExQuery($consNPFormato);
									$filaNPFormato=ExFetch($resNPFormato);	
									$consYaNP="select item,estado from historiaclinica.itemsxformatos where compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF' 
									and (item ilike '%Medicamento No POS%' or item ilike '%CUP No POS%')";
									//echo $consYaNP;
									$resYaNP=ExQuery($consYaNP);
									$filaYaNP=ExFetch($resYaNP);
									//echo "$banMedNoPos xx $banServNoPos xx $filaNPFormato[0]";
									if($banMedNoPos!=1||$banServNoPos!=1||$filaNPFormato[0]){							
										?>
										<td class="encabezado2VerticalInvertido">FORMATO NO POS</td>
										<td>
											<select name="NoPos" <? if($filaYaNP[1]=="AC"){?> onFocus="document.FORMA.CUPS.focus()"<? }?> >
												<option></option>
											<?	if(!$banMedNoPos||$filaNPFormato[0]=="Medicamentos No POS"){?>
													<option value="Medicamentos No POS" <? if($filaNPFormato[0]=="Medicamentos No POS"){?> selected <? }?>>Medicamentos No POS</option>
											<?	}?>
											<?	if(!$banServNoPos||$filaNPFormato[0]=="CUPS No POS"){?>	
													<option value="CUPS No POS" <? if($filaNPFormato[0]=="CUPS No POS"){?> selected <? }?>>CUPS No POS</option>
											<?	}?>
											</select>
										</td>
								<?	}?>
									<td class="encabezado2VerticalInvertido">EPICRISIS</td>
									<td  colspan="<? if($banMedNoPos!=1||$banServNoPos!=1||$filaNPFormato[0]){?> 1 <? }else{?> 3 <? }?>" >
										<input type="checkbox" name="Epicrisis" value="Si" <? if($fila3['epicrisis']=='Si'||$Epicrisis=='Si'){?> checked<? }?> >
									</td>
								</tr>
							<?	if($Edit){
									$consXML="Select item from historiaclinica.itemsxformatos where compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF'";				
									$resXML=ExQuery($consXML);if(ExNumRows($resXML)>0){$BanXML=1;}
								}?>
								<tr>
									<td class="encabezado2VerticalInvertido">FORMATO XML</td>
									<td>                	
									 <?	$consXML1="select item from historiaclinica.itemsxformatos where compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF' 
										and etiqxml is not null and etiqxml!=''";		
										$resXML1=ExQuery($consXML1);										
										if(ExNumRows($resXML1)>0){$BanXMLYa=1;}
										if(!$FormatoXML){$FormatoXML=$fila3['formatoxml'];}
										$consXML="select formatoxml,codigoxml from historiaclinica.formatosxml where compania='$Compania[0]'";		
										$resXML=ExQuery($consXML); ?> 
										<select name="FormatoXML" <? if($BanXMLYa){?> onFocus="document.FORMA.CUPS.focus()"<? }?>>
										<option value="0"></option>
									<?	while($filaXML=ExFetch($resXML))
										{
											if($filaXML[1]==$FormatoXML){echo "<option value='$filaXML[1]' selected>$filaXML[0]</option>";}
											else{ echo "<option value='$filaXML[1]'>$filaXML[0]</option>";}
										}?>
								</select>
									</td>
									<?
									if((!$FormatoAcu)||($FormatoAcu&&$FormatoAcu==$NewFormato&&$TipoFormatoAcu==$TF))
									{?>
									<td class="encabezado2VerticalInvertido">ACUDIENTES</td>
									<td><input type="checkbox" name="Acudientes" value="Si" <? if($fila3['acudientes']=='Si'||$Acudientes=='Si'){?> checked<? }?> title="Especifica éste formato como 'Formato unico de Acudientes'!!!">
									</td>
									<?
									}else
									{?>
										<td colspan="2">&nbsp;</td>
									<?
									}?>                
									<td class="encabezado2VerticalInvertido">REQUIERE SERVICIO</td>
									<td><input type="checkbox" name="ReqAmbito" value="Si" <? if($fila3['reqambito']=='Si'||$ReqAmbito=='Si'){?> checked<? }?>>              	</td>
								</tr>
							 <?	if(!$banMedNoPos||$filaNPFormato[0]=="Medicamentos No POS"){?>
									<tr>
										<td class="encabezado2VerticalInvertido"> PERMITIR CERRAR FORMATO</td>
										<td><input type="checkbox" name="CierrarFormatoNoPos" value="Si" 
											<? if($fila3['cierraformatonopos']=='Si'||$CierrarFormatoNoPos=='Si'){?> checked<? }?>>
										</td>
									</tr>
							<? 	}
								$consLogo="select LogoAdicional,AnchoLogo,AltoLogo from historiaclinica.formatos where compania='$Compania[0]' and TipoFormato='$TF' and Formato='$NewFormato' and estado='AC'";
								$resLogo=ExQuery($consLogo);$filaLogo=ExFetch($resLogo);$LogoAnt=$filaLogo[0];$AnchoLogo=$filaLogo[1];$AltoLogo=$filaLogo[2];
								if($LogoAnt){$cols=2;$tittle="Reemplazar Logo Adicional!!!";}else{$cols=3;$tittle="Agregar Logo Adicional";}
								?>
								<tr  >            
								<td class="encabezado2VerticalInvertido" <? if($LogoAnt){?>onMouseOver="Info(event,'<? echo $LogoAnt?>','VerLogo','<? echo $AnchoLogo?>','<? echo $AltoLogo?>')" onMouseOut="document.getElementById('VerLogo').style.display='none'" <? }?>>LOGO ADICIONAL</td>           
								<td colspan="<? echo $cols?>" <? if($LogoAnt){?>onMouseOver="Info(event,'<? echo $LogoAnt?>','VerLogo','<? echo $AnchoLogo?>','<? echo $AltoLogo?>')" onMouseOut="document.getElementById('VerLogo').style.display='none'" <? }?>>            	
									<input type="file" name="Logo" id="Logo" style="width:100%" title="<? echo $tittle?>" >
									<input type="hidden" name="AuxLogo">  
									<input type="hidden" name="Extension">
									<input type="hidden" name="LogoAnt" value="<? echo $LogoAnt?>">
								</td>
									<?
									if($LogoAnt)
									{?>
									<td>
									<button name="TamanoLogo" style="cursor:hand" onClick="CambiarTamañoLogo();" onMouseOver="Info(event,'<? echo $LogoAnt?>','VerLogo','<? echo $AnchoLogo?>','<? echo $AltoLogo?>')" onMouseOut="document.getElementById('VerLogo').style.display='none'" title="Cambiar Tamaño del Logo"><img src="/Imgs/tamano7.png" width="16px" height="16px"></button>
									<button name="ContratoLogo" style="cursor:hand" onClick="ContratoxLogo();" onMouseOver="Info(event,'<? echo $LogoAnt?>','VerLogo','<? echo $AnchoLogo?>','<? echo $AltoLogo?>')" onMouseOut="document.getElementById('VerLogo').style.display='none'" title="Configurar Contrato Logo"><img src="/Imgs/s_vars.png" width="16px" height="16px"></button>
									<button name="QuitarLogo" style="cursor:hand" onClick="if(confirm('Desea Eliminiar el Logo Adicional anteriormente configurado?')){location.href='EditFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>&LogoAnt=<? echo $LogoAnt?>&Edit=1&QL=1'}" onMouseOver="Info(event,'<? echo $LogoAnt?>','VerLogo','<? echo $AnchoLogo?>','<? echo $AltoLogo?>')" onMouseOut="document.getElementById('VerLogo').style.display='none'" title="Quitar Logo Adicional"><img src="/Imgs/b_drop.png"></button>
									</td>
									<?
									}?>  
								<td class="encabezado2VerticalInvertido">INCLUIR SIGNOS VITALES</td>
								<td ><input type="checkbox" name="IncluirSignosVitales" value="1" <? if($IncluirSignosVitales){echo "checked";}?>></td>          
								</tr>
								<tr>
								<td class="encabezado2VerticalInvertido">FIRMA DEL PACIENTE</td>
								<td class="encabezado2VerticalInvertido"><input type="checkbox" name="FirmaPacienteF" value="1" <? if($FirmaPacienteF){echo "checked";}?> title="Firma Paciente Impresion de Formato">&nbsp;
								CONFIGURA DIAGN&Oacute;STICO</td>  
								<td  style="font-weight:bold"><input type="checkbox" name="ConfigDiagnostico" value="1" <? if($ConfigDiagnostico){echo "checked";}?> title="Configurar como se muestra Diagnostico al imprimir" onClick="if(!document.FORMA.ConfigDiagnostico.checked){document.FORMA.CodDiagnostico.checked=true;document.FORMA.CodDiagnostico.disabled=true;document.FORMA.NomDiagnostico.checked=true;document.FORMA.NomDiagnostico.disabled=true;}else{document.FORMA.CodDiagnostico.disabled=false;document.FORMA.NomDiagnostico.disabled=false;}"></td>          
								<td  class="encabezado2VerticalInvertido">COD. DIAGN&Oacute;STICO</td>
								<td  class="encabezado2VerticalInvertido"><input type="checkbox" name="CodDiagnostico" value="1" <? if($CodDiagnostico){echo "checked";}?> title="Mostrar Codigo del Diagnostico">&nbsp; NOM. DIAGN&Oacute;STICO</td>
								<td  style="font-weight:bold"><input type="checkbox" name="NomDiagnostico" value="1" <? if($NomDiagnostico){echo "checked";}?> title="Mostrar Nombre del Diagnostico"></td>
								</tr>          	                  
								<tr>
									<td class="encabezado2VerticalInvertido">CAUSA EXTERNA</td>
									<td><input type="Button" class="boton2Envio" value="..." onClick="frames.ItemsxFormatos.location.href='CausasExternaxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<?echo $NewFormato?>&TF=<?echo $TF?>'"> 
									</td>
									<td class="encabezado2VerticalInvertido">FINALIDAD</td>
									<td><input type="Button" class="boton2Envio" value="..." onClick="frames.ItemsxFormatos.location.href='FinalidadxFormatoFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<?echo $NewFormato?>&TF=<?echo $TF?>'"> 
									</td>
									<td class="encabezado2VerticalInvertido">DIAGN&Oacute;STICO FORMATO</strong></td>
									<td><input type="Button" class="boton2Envio" value="..." onClick="frames.ItemsxFormatos.location.href='DiagnosticoxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<?echo $NewFormato?>&TF=<?echo $TF?>'"> 
									</td>                
								 </tr>
								</table>       
						 </td>
						 </tr>     
					   </table>
						 </div>
					</div> 
					
					
					 <table width="100%" class="tabla3"  <?php echo $borderTabla3Mentor; echo $bordercolorTabla3Mentor; echo $cellspacingTabla3Mentor; echo $cellpaddingTabla3Mentor; ?>>
						 <tr>
							<td colspan="6" style="text-align:center;background-color:E5E5E5;">
						   <?	if($TblFormat){	
									$consCup="select cup from historiaclinica.cupsxformatos where compania='$Compania[0]' and formato='$NewFormato' 
									and tipoformato='$TF'";
									//echo $consCup;
									$resCup=ExQuery($consCup); $filaCup=ExFetch($resCup);
									if($filaCup){						
											$HayCups=1;
											//echo "hay cups";
									}	
									else
									{ 
										$consDx="select cie10 from historiaclinica.dxformatos where compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF'";							
										$resDx=ExQuery($consDx);					
										if(ExNumRows($resDx)>0){
											$HayCups=1;															
										}
										else{
											$HayCups=0;
										}						
									}	
									?>
								<input type="button"  name="CUPS" class="boton2Envio" value="Cups" onClick="<? 	if($HayCups==0){?> alert('Se debe agregar un diagnostico para poder agregar Cups');
									frames.ItemsxFormatos.location.href='DxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>'<? }else{?> frames.ItemsxFormatos.location.href='CupsXFormatos.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>' <? 	}?>">
							<? }?>
							 <input type="button" name="Indicador" class="boton2Envio" value="Indicador" onClick="frames.ItemsxFormatos.location.href='IndicadoresxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>&TablaFormato=<? echo $TblFormat?>'">
							 <input name="Disponibilidad" class="boton2Envio" type="button" onClick="frames.ItemsxFormatos.location.href='Disponibilidad.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>'" value="Disponibilidad">
					   <?	$consCarga="Select Formato from HistoriaClinica.Formatos where Formato='$NewFormato' and Compania='$Compania[0]' and tipoformato='$TF'";
							$resCarga=ExQuery($consCarga);
							if(ExNumRows($resCarga)>0){?>
								<input type="button" class="boton2Envio" value="Copiar Items de" onClick="frames.ItemsxFormatos.location.href='TraerItemsDe.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>'">
								<input type="button" class="boton2Envio" value="Vista Previa" onClick="frames.ItemsxFormatos.location.href='SelecPagVistaPrev.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>'">
								<input type="button" class="boton2Envio" value="CUPS Laboratorio" onClick="if(document.FORMA.Laboratorio.value!=''){frames.ItemsxFormatos.location.href='CupsLabs.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>';}">
						<?	}?>
							 <input type="Submit" class="boton2Envio" value="Guardar Registro" name="Guardar">
							 <input type="button" class="boton2Envio" value="Cerrar" onClick="location.href='/HistoriaClinica/AdminFormatos.php?DatNameSID=<? echo $DatNameSID?>&TF=<? echo $TF?>'"> 
						   </td>
						 </tr>     
						 </table> 
					<input type="Hidden" name="NewFormato" value="<? echo $NewFormato?>">
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					<input type="Hidden" name="Edit" value="<? echo $Edit?>">
					<input type="hidden" name="Ban" value="<? echo $Ban?>">
					<input type="Hidden" name="TF" value="<? echo $TF?>">
					<input type="hidden" name="TblFormat" value="<? echo $TblFormat?>">
					<input type="hidden" name="QL">
					<input type="hidden" name="EstadoPanel" value="<? echo $EstadoPanel;?>"/>

					<?
						$busqueda="Select Formato from HistoriaClinica.Formatos where Formato='$NewFormato' and Compania='$Compania[0]' and TipoFormato='$TF' ";
						//echo $busqueda;
						$resbusqueda=ExQuery($busqueda);
						$conteo=ExNumRows($resbusqueda);
						if($conteo==1){?>
						<iframe id="ItemsxFormatos" name="ItemsxFormatos" src="ItemsxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<?echo $NewFormato?>&TF=<?echo $TF?>" width="100%" height="350"></iframe>
						 <? }?>

					<div id='VerLogo' name='VerLogo' style='position: absolute; width:auto; height:auto; display: none; background:#FFFFFF '>
					<input type="image" name="VerLogoImagen" src="/Imgs/Logo.jpg" >
					<!--<img name="VerLogoImagen" src="" border="0" style="width:80px; height:80px">--<
					<!--<input type="text" name="MensajeMsj" value="<? echo $LogoAnt?>"  style=" color:#0066FF; border:thin; background:none; font:normal normal small-caps 14px Tahoma; font-weight:bold; text-align:left; width:430px"/>-->
					</div>
					</form>
					<iframe id="FrameOpener" name="FrameOpener" style="display:none;border:thin ridge" frameborder="0" scrolling="no" ></iframe>
					<iframe id="TamLogo" name="TamLogo" style="display:none;border:thin ridge" frameborder="0" scrolling="no" ></iframe>
					<iframe id="ContLogo" name="ContLogo" style="display:none;border:thin ridge" frameborder="0" scrolling="no" ></iframe>
					<?
					if($Guardar&&$LogoAnt)
					{?>
					<script language="javascript">AbrirImg("<? echo $LogoAnt?>","<? echo $AnchoLogo?>","<? echo $AltoLogo?>");</script>
					<?
					}
					?>
					<script language="javascript">
					var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1");
					if(!document.FORMA.ConfigDiagnostico.checked){document.FORMA.CodDiagnostico.checked=true;document.FORMA.CodDiagnostico.disabled=true;document.FORMA.NomDiagnostico.checked=true;document.FORMA.NomDiagnostico.disabled=true;}else{document.FORMA.CodDiagnostico.disabled=false;document.FORMA.NomDiagnostico.disabled=false;}</script>
					<?
					//--panel
					if(empty($EstadoPanel)||$EstadoPanel=="Open")
					{?>
					<script language="javascript">
					CollapsiblePanel1.open();
					</script>
					<?	
					}
					else
					{?>
					<script language="javascript">
					CollapsiblePanel1.close();
					</script>
					<?	
					}
					//--
					?>
					<script language="javascript">
					if(CollapsiblePanel1.contentIsOpen==true){document.FORMA.EstadoPanel.value='Open';document.getElementById("ItemsxFormatos").height="350";}else{document.FORMA.EstadoPanel.value='Closed';document.getElementById("ItemsxFormatos").height="450";}
					</script>
			</body>
