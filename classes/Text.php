<?php
// ============================================================================================================================
// =  @Copyright (c) 2011 Julián Baquero (segquerenquer@gmail.com)
// =  Provides Julián Baquero.
// =  License (CDDL) and the GNU General Public Licence version 2.
// =  File:         Text.class This file is part of Project MENTOR.
// =  Description:  this file contains configuration labels of project.
// =  Created:      09-02-2012
// ============================================================================================================================
require_once "_Exception.php";

class Text{
      private $_exception,
              $vDataAll,
              $buffer,
              $text;
      private static  $instance;

      private function __construct(){
                       $this->_exception = _Exception::getInstance();
                       $this->vDataAll   =
                       $this->buffer     =
                       $this->text       = null;
                       
                       //CONECTION//**************************************************************************
                       $this->setElement("MSG_CONNECT"							, "No fué posible establecer la conexión.");
					   $this->setElement("MSG_CONNECT_CLOSE"            		, "No fué posible cerrar el flujo de conexión.");
                       $this->setElement("MSG_DB_SELECTION"             		, "No fué posible seleccionar la base de datos.");
                       $this->setElement("MSG_HOST_CONNECTION"          		, "No fué posible establecer la conexión con el host.");
                       $this->setElement("MSG_LOADING_CURSOR"           		, "No fué posible cargar el cursor.");
                       //*************************************************************************************

                       //PERSONAL PARTICULARS LBL//***********************************************************
                       $this->setElement("LBL_SELECT"        		          , "-- Seleccionar --");
                       $this->setElement("TLT_BOX"                      	  , "Mensaje del Sistema");
					   //*************************************************************************************
                       
                       //VALIDATE//***************************************************************************
                       $this->setElement("MSG_VALIDATE_REQUIRED"       , "Este Campo es requerido.");
                       $this->setElement("MSG_VALIDATE_DATE"           , "Digite una Fecha v&aacute;lida.");
                       $this->setElement("MSG_VALIDATE_EMAIL"          , "Direcci&oacute;n de correo incorrecta.");
                       $this->setElement("MSG_VALIDATE_DIGITS"         , "Digite s&oacute;lo n&uacute;meros.");
					   //*************************************************************************************

					   //PERSONAL PARTICULARS MSG FILING MODULE//*********************************************
                       $this->setElement("MSG_THERE_NOT"        	   , "No Hay %s Durante Este Periodo.");
					   $this->setElement("MSG_GREATER_OR_EQUAL_TO"	   , "<strong>%s</strong> debe ser mayor o igual a <strong>%s</strong>.");
					   $this->setElement("MSG_LESS_TO"	               , "<strong>%s</strong> debe ser menor a <strong>%s</strong>.");
					   $this->setElement("MSG_GREATER"	               , "<strong>%s</strong> debe ser mayor <strong>%s</strong>.");
					   $this->setElement("MSG_LESS_OR_EQUAL_TO"	       , "<strong>%s</strong> debe ser menor o igual a <strong>%s</strong>.");
					   $this->setElement("MSG_SELECTED"     	       , "No se ha detectado ninguna selecci&oacute;n.");
					   $this->setElement("MSG_CONFIRM"     	           , "&iquest;Est&aacute; seguro que desea confirmar %s?");
					   $this->setElement("MSG_DATA_SAVE"     	       , "La Informaci&oacute;n ha sido guardada con &eacute;xito.");
					   $this->setElement("MSG_BEGING_PROCESS"     	   , "Iniciar proceso de %s.");
					   $this->setElement("MSG_SELECT"     	           , "&iexcl;Debe seleccionar <strong>%s</strong> diferente!.");
					   $this->setElement("MSG_DELETE"                  , "&iexcl;Registro Eliminado!"); 
					   $this->setElement("MSG_FILL_OUT"                , "&iexcl;Diligencie la Informaci&oacute;n!");
                       $this->setElement("MSG_TYPING"     	           , "&iexcl;Debe digitar <strong>%s</strong>!.");
					   //*************************************************************************************
					   
					   //PERSONAL PARTICULARS MSG CLAIM MODULE//*********************************************
					   $this->setElement("MSG_NOT_HAVE_BEEN_RECORDED"  , "No se han registrado todas las solicitudes!!! No puede realizar una apertura de buz&oacute;n hasta que se registren todas las solicitudes de la &uacute;ltima apertura.");
					   $this->setElement("MSG_MUST"                    , "Se debe realizar %s.");
					   $this->setElement("MSG_ADD"                     , "Agregar %s");
					   $this->setElement("MSG_EVALU_ARGUM"             , "Evaluar y Argumentar como %s");
					   $this->setElement("MSG_LIST"                    , "Listado de %s"); 
					   //*************************************************************************************
					   
					   //CODING////***************************************************************************
					   $this->setElement("UTF_8"     	               , 1);
					   $this->setElement("ASCII"     	               , 2);
					   $this->setElement("ISO_8859_1"     	           , 3); 
					   //*************************************************************************************
                       }//End __construct

      public  function __destruct(){
                       $this->_exception;
                       $this->vDataAll;
                       $this->buffer;
                       $this->text;
                       $instance;
                       }//End __destruct

      public  static  function getInstance(){
                               if(!(self::$instance instanceof self))
                                  self::$instance = new self();
                               return self::$instance;
                               }//End getInstance  
                     
      private function setElement($DATA=null, $Data=null){
                      $this->VDataAll[][$DATA]=$Data;
                      }//End setElement method
					  
	  public function setBuffer($Data=null){
                      ?><?=$this->buffer=$Data;
                      }//End setBuffer method

      public function getBuffer(){
                      return $this->buffer;
                      }//End getBuffer method

	  public function cleanBuffer($string=null){ 
                      $clean    = ""; 
                      $parts    = array(); 
					  $parts = preg_split("/[ ]/",$string); 
                      foreach($parts as $subString){ 
                              $subString = trim($subString); 
							  if($subString!="")
                                 $clean .= $subString." "; 
                              }//End foreach 
                      $clean = trim($clean); 
                      return $clean; 
                      }//End cleanBuffer method
					  
      public function setLabel($Data=null){
                      for($filter=0;
                          $filter<sizeof($this->VDataAll);
                          isset($this->VDataAll[$filter][$Data])? $this->text=($this->VDataAll[$filter][$Data]): null,
                          $filter++);
                      if(!$this->text)
                         $this->_exception->message(false,$Data." no exist...");
                      return $this->text;
                      }//End setLabel method
					  
	  public function coding($text){ 
					  return ($this->fixCoding($text)==ISO_8859_1) ? $text : utf8_decode($text); 
					  }//End coding method 
 
	  public function fixCoding($text){ 
					  $c = 0;$ascii = true; 
					  for($i=0;$i<strlen($text);$i++){ 
						  $byte = ord($text[$i]); 
						  if($c>0){ 
							 if(($byte>>6)!=0x2)return $this->setLabel("ISO_8859_1"); 
							    else$c--; 
						     }elseif($byte&0x80){ 
							         $ascii=false; 
							         if(($byte>>5)==0x6)$c=1; 
							            elseif(($byte>>4)==0xE)$c = 2; 
							                   elseif(($byte>>3)==0x14)$c = 3;
											          else return $this->setLabel("ISO_8859_1"); 		 
						            }//End if 
					     }//End for 
					 return ($ascii) ? $this->setLabel("ASCII") : $this->setLabel("UTF_8"); 
				     }//End fixCoding method

      public function replace($script=null,$string=null,$text=null){           
                      return preg_replace('/'.$string.'/', $text, $script);
					  }//End search method

      public function capitalized($string=null){
                      return strtoupper($string);
                      }//End capitalized method					  
}//End Text class
?>