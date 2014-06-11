<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	
	require_once "../classes/_Var.php";
	$Var=_Var::getInstance();
    $Var->__autoload("Data","Text","Connection","Cursor");
	$Data       = Data::getInstance();
	$Text       = Text::getInstance();
	$Connection = Connection::getInstance();
	$Cursor     = Cursor::getInstance();
	$Sql        = Sql::getInstance();
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body <?=$Data->getParameter("GUI_FORM");?>>
<table id="reports" width="100%" <?=$Data->getParameter("GUI");?> class="ui-state-highlight ui-corner-all" align="center">  
	       <tr align="center">	
            <td class="ui-state-default ui-corner-all" colspan="2" height="40px">INFORMES DE RESPUESTA</td>       
	       </tr>
         <tr>
             <td><form id="reportGlosses">
                 <table width="100%" class="ui-corner-all">
                    <tr>	
                        <td width="20%" class="ui-state-default" align="right">Fecha Inicio:</td>
            	          <td width="50%"><input type="text" id="FechaIni" name="FechaIni" size="10" <?=$Data->getParameter("GUI_CONTROL");?> value='<?=$Data->setDate("Y","m","01");?>'  ><script>$(function(){Utility.date("FechaIni");});</script></td>       
                    </tr>
                    <tr>
                        <td class="ui-state-default" align="right">Fecha Fin:</td>
                        <td><input type="text" id="FechaFin" name="FechaFin" size="10" <?=$Data->getParameter("GUI_CONTROL");?> value='<?=$Data->setDate("Y","m","d");?>' ><script>$(function(){Utility.date("FechaFin");});</script></td>        
                    </tr>
                    <tr>   
                    <td class="ui-state-default" align="right" height="5px" style="vertical-align:sub;">Entidad:</td>
					<td>
					    <select name="Entidad" id="Entidad" <?=$Data->getParameter("GUI_CONTROL");?> onChange="Data.search('Contrato', 'Entidad', 'contratacionsalud.contratos', 'entidad', 'contrato', 'contrato');">
           	            <option value=""><?=$Text->setLabel("LBL_SELECT");?></option>
						<?php
						$Connection->connect();
						$Cursor->consultExecute($Sql->setSentence("00011",$Compania[0],$Compania[0]));
                        while($Cursor->next($Cursor->get()))
                        $Text->setBuffer('<option value="'.$Cursor->getParameter("identificacion").'">'.$Cursor->getParameter("entity").'</option>');
                        $Cursor->freeResult();
						?>
						</select>
				    </td>		
           	        </tr> 
                    <tr>
            	       <td class="ui-state-default" align="right" height="5px" style="vertical-align:sub;">Contrato:</td>
                     <td>                                      
                	     <select name="Contrato" id="Contrato" <?=$Data->getParameter("GUI_CONTROL");?> onChange="Data.search('NoContrato', 'Contrato', 'contratacionsalud.contratos', 'contrato', 'numero', 'numero');">
                         </select>
                     </td>
                    </tr>
                    <tr>
                     <td class="ui-state-default" align="right"> No. de Contrato:</td>
                     <td>
                	       <select id="NoContrato" name="NoContrato" <?=$Data->getParameter("GUI_CONTROL");?></select>
                    </td>
                    </tr>
                    <tr>    
					 <td class="ui-state-default" align="right">Factura Inicial:</td>
                     <td><input type="text" class="digits" id="FacI"  name="FacI" <?=$Data->getParameter("GUI_CONTROL");?> onKeyUp="Data.copy('FacI','FacF')" size="10" value=""></td>
					</tr>
					<tr>
					 <td class="ui-state-default" align="right">Factura Final:</td>
					 <td><input type="text" class="digits" id="FacF" name="FacF" <?=$Data->getParameter("GUI_CONTROL");?> size="10" value=""></td>
					</tr>
					<tr>    
                    <td class="ui-state-default" align="right">* Tipo:</td>    	
                    <td>
                	    <select class="required" name="Report" id="Reporte" <?=$Data->getParameter("GUI_CONTROL");?> onChange="Filing.report('label','control','Reporte')">
           	            <option value=""><?=$Text->setLabel("LBL_SELECT");?></option>
						<?php
						$Connection->connect();
						$Cursor->consultExecute($Sql->setSentence("00040"));
                        while($Cursor->next($Cursor->get()))
                        $Text->setBuffer('<option value="'.$Cursor->getParameter("id").'">'.$Cursor->getParameter("nombre").'</option>');
                        $Cursor->freeResult();
						?>
						</select>
                    </td>
                    </tr>
					<tr id="contenetor"><td class="ui-state-default" align="right" id="label"></td><td id="control"></td></tr>
					<tr id="contenetorContinuous"><td class="ui-state-default" align="right" id="labelContinuous"></td><td id="controlContinuous"></td></tr>
					<tr id="contenetorAlternate"><td class="ui-state-default" align="right" id="labelAlternate"></td><td id="controlAlternate"></td></tr>
					<tr align="center"> 
        		           <td class="ui-state-default" colspan="2"><a href="#" id="fillingReportGlossesView" class="ui-button ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false" onClick='Filing.reportGlossesView();'><span class="ui-button-text" >Ver</span></a></td>
         	        </tr>
         	       </table>
				   <input type="hidden" id="Compania" name="Compania" value="<? echo $Compania[0]?>">
				   <input type="hidden" id="Nit" name="Nit" value="<? echo $Compania[1]?>">
                   <input type="hidden" id="Usuario" name="Usuario" value="<? echo $usuario[1]?>">
				   <span id="bring"></span>
				   </form>
         	   </td>
              </tr>
              <tr>
                <td colspan="2">
                 <div id="reportGlossesView"></div> 				 
                </td>
              </tr>			  
     </table>
</td></tr></table>
</body>
</html> 
<?php $Var->release($Data,$Text,$Connection,$Cursor,$Sql,$Var);?>


























































































































































































































































































































































































































































































































































































































































































































































































































































































































<script>Text.hide("contenetor");Text.hide("contenetorAlternate");Text.hide("contenetorContinuous");</script>