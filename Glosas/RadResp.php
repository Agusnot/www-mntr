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
<table width="100%" <?=$Data->getParameter("GUI");?> class="ui-state-highlight ui-corner-all" align="center">  
	       <tr align="center">	
            <td class="ui-state-default ui-corner-all" colspan="2" height="40px">RADICADO DE LA RESPUESTA DE LA GLOSA</td>       
	       </tr>
         <tr>
             <td><form id="filedResponseGlosses">
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
                    <td class="ui-state-default" align="right" height="5px" style="vertical-align:sub;">* Entidad:</td>
					<td>
					    <select class="required" name="Entidad" id="Entidad" <?=$Data->getParameter("GUI_CONTROL");?> onChange="Data.search('Contrato', 'Entidad', 'contratacionsalud.contratos', 'entidad', 'contrato', 'contrato');">
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
            	       <td class="ui-state-default" align="right" height="5px" style="vertical-align:sub;">* Contrato:</td>
                     <td>                                      
                	     <select class="required" name="Contrato" id="Contrato" <?=$Data->getParameter("GUI_CONTROL");?> onChange="Data.search('NoContrato', 'Contrato', 'contratacionsalud.contratos', 'contrato', 'numero', 'numero');">
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
                     <td><input type="text" class="digits" id="FacI"  name="FacI" onKeyUp="Data.copy('FacI','FacF')" size="10" value=""></td>
					</tr>
					<tr>
					 <td class="ui-state-default" align="right">Factura Final:</td>
					 <td><input type="text" class="digits" id="FacF" name="FacF" size="10" value=""></td>
					</tr>
					<tr align="center"> 
        		           <td class="ui-state-default" colspan="2"><a href="#" id="fillingFiledResponseGlossesView" class="ui-button ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false" onClick="Filing.filedResponseGlossesView(true);"><span class="ui-button-text" >Ver</span></a></td>
         	        </tr>
         	       </table>
				   <input type="hidden" id="Compania" name="Compania" value="<? echo $Compania[0]?>">
                   <input type="hidden" id="Usuario" name="Usuario" value="<? echo $usuario[1]?>">
				   </form>
         	   </td>
              </tr>
              <tr>
                <td colspan="2">
                 <div id="filedResponseGlossesView"></div>   	    
                </td>
              </tr>			  
     </table>
</td></tr></table>
</body>
</html> 
<?php $Var->release($Data,$Text,$Connection,$Cursor,$Sql,$Var);?>
