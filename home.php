<?php /*if($DatNameSID){session_name("$DatNameSID");}*/ 
	session_start();  
	require_once "classes/_Var.php"; 
	$Var=_Var::getInstance(); 
	$Var->__autoload("Text"); 
	$Text       = Text::getInstance();  
	include("Funciones.php"); 
	$ND=getdate();  
	/*---------------*/ 
	include "xajax/xajax_core/xajax.inc.php"; 
	$xajax= new xajax(); 
	function autocompleta($input)           { 
		$respuesta = new xajaxResponse(); 
		$con= "SELECT  codigo ,detalle FROM facturacion.codmotivoglosa WHERE  codigo  LIKE '".$input."%' OR  detalle LIKE '".$input."%' LIMIT 10 "; 
		$res45 = ExQuery($con); 
		$num = ExNumRows($res45); 
		if ($input == "") { 
			$respuesta->Assign("divSugerencias", "innerHTML", ""); 
			return $respuesta; 
		} if ($num == 0) { 
			$output = "<font color='red'>No existe</font>"; 
		} else if ($num == 1)  { 
			$row = ExFetch($res45); 
			if (strcasecmp($input, $row[0]) == 0) { 
				$output = "";
			} else { 
				$output = "  <div id='divLista' class='ui-widget-content'> <table > <tr style='cursor:pointer'> <td onClick=\"xajax_seleccion('".$row[0]."');xajax_autocompleta('".$row[0]."')\">".$row [0]." - ".$row[1]."</td> </tr> </div> </table>"; 
			}
		} else { 
			$output .= "<div id='divLista' class='ui-widget-content'> <table  cellpadding='0'cellspacing='0'>"; 
			while ($row = ExFetch($res45)) { 
				$output .= "<tr style='cursor:pointer'><td onClick=\"xajax_seleccion('".$row[0]."');xajax_autocompleta('".$row[0]."')\">".$row [0]." - ".$row[1]."</td></tr>"; 
			}
			$output .= "</div></table>"; 
		} 
		$respuesta->Assign("divSugerencias", "innerHTML", $output); 
		return $respuesta;                       
	} 
	/*----*/ 
	function seleccion($pais){ 
		$respuesta = new xajaxResponse(); 
		$respuesta->Assign("TipoGlosa", "value", $pais); 
		return $respuesta;       
	} 
	$xajax->registerFunction('autocompleta'); 
	$xajax->registerFunction('seleccion'); 
	$xajax->processRequest(); 
	/*---------------*/  
	$cons = "Select InstruccionSQL,MsjAlerta,Estado,Archivo,Id from Alertas.AlertasProgramadas where Compania='$Compania' and estado='Activo'  and bloqueante='Si'"; 
	/*echo $cons;*/ 
	$res = ExQuery($cons); 
	while ($fila=ExFetch($res)) { 
		$cons3="select usuario from alertas.usuariosxalertas where compania='$Compania' and idalerta=$fila[4]"; 
		$res3=ExQuery($cons3); 
		if(ExNumRows($res3)>0){ 
			$BanUsus=1; 
			$cons4="select usuario from alertas.usuariosxalertas where compania='$Compania' and idalerta=$fila[4] and usuario='$usuario'"; 
			$res4=ExQuery($cons4); 
			if(ExNumRows($res4)>0){ 
				$BanUsuSi=1; 
			} 
		} 
		$cons2="SELECT Id from Alertas.AlertasxModulos,Central.UsuariosxModulos where AlertasxModulos.Modulo=UsuariosxModulos.Modulo and AlertasxModulos.Id=$fila[4] and UsuariosxModulos.Usuario='$usuario' and Alertas.AlertasxModulos.Compania='$Compania' and UsuariosxModulos.Compania='$Compania'";  
		/*echo $cons2;*/ 
		$res2=ExQuery($cons2); 
		if(ExNumRows($res2)>0) { 
			/*echo $cons1."<br>";*/ 
			$cons1=str_replace("|","'",$fila[0]); 
			$cons1=str_replace("[COMPANIA]","$Compania[0]",$cons1); 
			$cons1=str_replace("[FEC_ACTUAL]","$ND[year]-$ND[mon]-$ND[mday]",$cons1); 
			$cons1=str_replace("[USU]","$usuario",$cons1); 
			/*echo $cons1."<br>";*/ 
			$cons1=str_replace("+","||",$cons1); 
			$res1=ExQuery($cons1); 
			if(ExNumRows($res1)>0){ 
				if($BanUsus==1){ 
					if($BanUsuSi==1){
						$BanMsj=1;
					} 
				} else{ 
					$BanMsj=1; 
				} 
			} 
		} 
	} 
	$cons="Select Perfil from Central.AccesoxModulos,Central.UsuariosxModulos where Modulo=Perfil and Nivel=0 and Usuario='$usuario' and Compania='$Compania' AND perfil='$home'"; 
	$res=ExQuery($cons); 
	$NumRows=ExNumRows($res);  
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml"> 
	<head> 
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
		<title>Mentor</title> 
		<!-- include jquery support --> 
		<script src="js/jquery-1.7.1.min.js" type="text/javascript"></script> 
		<script src="js/jquery-ui-1.8.17.custom.min.js"  type="text/javascript"></script> 
		<script src="js/jquery.maskMoney.0.2.js" type="text/javascript"></script> 
		<script src="js/jquery.PrintArea.js" type="text/javascript"></script 
		<!-- css support--> 
		<style type="text/css" title="currentStyle"> 
			@import "css/blitzer/jquery-ui-1.8.17.custom.css"; 
			/*css dataTable support*/ 
			@import "dataTable/media/css/demo_page.css"; 
			@import "dataTable/media/css/demo_table.css"; 
			@import "css/blitzer/styles.css"; 
		</style> 
		<!-- include dataTable support --> 
		<script type="text/javascript" language="javascript" src="dataTable/media/js/jquery.dataTables.js"></script>  
		<script type="text/javascript" language="javascript" src="dataTables/media/js/jquery.dataTables.js"></script>  
		<!-- Include the datepicker support --> 
		<script src="development-bundle/ui/i18n/jquery.ui.datepicker-es.js" type="text/javascript"></script>  
		<!-- Include script support --> 
		<script type="text/javascript" src="js/Request.php"></script> 
		<script type="text/javascript" src="js/Data.php"></script> 
		<script type="text/javascript" src="js/Text.php"></script> 
		<script type="text/javascript" src="js/Utility.php"></script> 
		<script type="text/javascript" src="js/Filing.php"></script> 
		<script type="text/javascript" src="js/ClaimComplaint.php"></script> 
		<script type="text/javascript" src="js/jquery.validate-1.9-0.min.js"></script> 
		<script language="javascript"> 
			Utility.easyJQ("accordion","accordion"); 
			Utility.config();
			Utility.right();
		</script> 
		<?php 
			$xajax->printJavascript("xajax/"); 
		?> 
	</head> 
	<body> 
		<div id="wrapper">
			<div id="header" class="ui-state-default ui-corner-all"> 
				<span style="float: left; margin-right: 0em; margin-top:0em" class="ui-icon2 ui-icon-home"> 
					<img src="Imgs/logo.png" width="160" height="100" border="0" alt="Granada" title="Granada"/>
				</span> 
				<span style="float: left; font-size:2em; margin-top:2em">ORDEN HOSPITALARIA SAN JUAN DE DIOS</span> 
				<span style="float: right; margin-right:10px; margin-top:6em">
					<a href="#" id="close" class="ui-button ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false"  onClick="parent.parent.location.href='Principal.php?DatNameSID=<?=$DatNameSID;?>'">
						<span class="ui-button-text" >Cerrar</span>
					</a> 
				</span> 
			</div> 
			<div id="superWrapperContent"> 
				<div id="menu"> 
					<div id="accordion"> 
						<?
							$fila=""; 
							while($fila=ExFetch($res)){ 
								$cons1="Select Perfil,Ruta,Frame from Central.AccesoxModulos,Central.UsuariosxModulos where Modulo=Perfil and AccesoxModulos.Madre='$fila[0]' and UsuariosxModulos.Madre='$fila[0]' and Usuario='$usuario' and Compania='$Compania' and ModuloGr='$fila[0]' Order By Id"; 
								$res1=ExQuery($cons1); 
								$NumRows2=ExNumRows($res1); 
								echo '<h3><a href="#" class="ui-state-default" color="0098D8">'.$fila[0].' </a></h3>'; 
							}
							/*End while*/ 
						?>
						<div class="opciones ui-state-highlight">
							<? 
								while($fila1=ExFetch($res1)){ 
									$cons2="Select Perfil,Ruta,Frame from Central.AccesoxModulos,Central.UsuariosxModulos where Modulo=Perfil and AccesoxModulos.Madre='$fila1[0]' and UsuariosxModulos.Madre='$fila[0]' and Usuario='$usuario' and ModuloGr='$fila[0]' and Compania='$Compania' Order By Id"; 
									$res2=ExQuery($cons2); 
									$NumRows3=ExNumRows($res2); 
									if(substr($fila1[1],strlen($fila1[1])-4,strlen($fila1[1]))==".php"){
										$Separa="?";
									}else{
										$Separa="&";
									} 
									$Ruta=$root.$fila1[1].$Separa."DatNameSID=$DatNameSID";
									$Target=$fila1[2];
									if($fila1[0]!="Configuracion") echo '<span style="background:url(images/clientes.gif) no-repeat scroll 5px center;"  onclick="Utility.loadPage(\'content\',\''.$Ruta.'\',\''.$DatNameSID.'\')">'.$fila1[0].'</span>'; 
								}
								/*End while*/
							?>
						</div> 
					</div> 
				</div> 
			</div> 
			<div id="contentWrapper"> 
				<div id="content"></div> 
			</div> 
		</div> 
		<script>Utility.gui();</script> 
		<label id="notify"></label> 
		<label id="dialog-message" title="<span style='font-size:8pt'><?=$Text->setLabel("TLT_BOX");?></span>" ></label> 
		<label id="dialog-loading" title="<img src='Imgs/loader.gif' width='215' height='20' border='0'/>" style="float: left; margin-right: -10.0em; margin-top: -10em;"></label> 
		<label id="dialog-confirm" title="<span style='font-size:8pt;'><?=$Text->setLabel("TLT_BOX");?></span>" style="float: left; margin-right: 0.3em; margin-top: 1em;" >
			<label id="#dialog:ui-dialogConfirm" style="float: left; margin-right: 0.3em; margin-top: 1em;" ></label>
		</label> 
	</body> 
</html> 
<?php 
	$Var->release($Text);
?>