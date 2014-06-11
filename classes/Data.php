<?php
// ============================================================================================================================
// =  @Copyright (c) 2011 Julián Baquero (segquerenquer@gmail.com)
// =  Provides Julián Baquero.
// =  License (CDDL) and the GNU General Public Licence version 2.
// =  File:         Data.class This file is part of Project MENTOR.
// =  Description:  this file contains configuration parameters of project.
// =  Created:      09-02-2012
// ============================================================================================================================
require_once "_Exception.php";

class Data{
      private $_exception,
              $vDataAll,
			  $vUtil,
              $data,
			  $_var;
      private static  $instance;

      private function __construct(){
                       $this->_exception = _Exception::getInstance();
					   $this->_var       = _Var::getInstance();
					   $this->vDataAll   = array();
					   $this->vUtil      = array();
                       $this->data       = null;                                                 
                       
                       //PARAMS//*****************************************************************************
                       $this->setElement("PARAM_USER"                       , "postgres");
					   $this->setElement("PARAM_PORT"                       , "5432");
					   $this->setElement("PARAM_DB"                         , "sistema");
                       $this->setElement("PARAM_HOST"                       , "localhost");
                       $this->setElement("PARAM_PASS"                       , "Server*1982");
                       //************************************************************************************* 
                       
                       //GUI//********************************************************************************
                       $this->setElement("GUI"                    , 'style="margin-top:0px; margin-left:auto; margin-right:auto; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px;"');
                       $this->setElement("GUI_CONTROL"            , 'style="color:#8B0000; font-size:12px;"');
                       $this->setElement("GUI_VALIDATE"           , '<table><tr><td style="color:#990000; font-size:8pt;">%s</td></tr></table>');
					   $this->setElement("GUI_TOP_RIGHT"          , 'style="position: fixed; bottom: 300px; right: 5px;"');
					   $this->setElement("GUI_BUTTOM_RIGHT"       , 'style="position: fixed; bottom: 60px; right: 5px;"');
					   $this->setElement("GUI_BUTTOM_CENTER"      , 'style="position: fixed; bottom: 5px; right: 120px;"');
					   $this->setElement("GUI_BUTTOM"             , 'style="position: fixed; bottom: 5px; right: 5px;"');
					   $this->setElement("GUI_FORM"               , 'style="background-color: #fffaf0;"');
                       //*************************************************************************************
					   
					   //PARTICULAR//*************************************************************************
                       $this->setElement("CLR_MAX"                , 'fafafa');
                       $this->setElement("CLR_MIN"                , 'fff');
                       $this->setElement("CLR_TIME"               , '500');
					   $this->setElement("DEFAULT_TIME"           , '1000');
					   $this->setElement("DEFAULT_TIME_CONTINUOUS", '3000');
                       //*************************************************************************************
                       }//End __construct

      public  function __destruct(){
                       $instance;
					   $this->_exception;
					   $this->vDataAll;
                       $this->data;
					   $this->_var;
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
					  
	  public function setDate(){
					  date_default_timezone_set('America/Bogota');
                      $this->argument = func_get_args();
                      if(isset($this->argument[0])&&isset($this->argument[1])&&isset($this->argument[2])&&isset($this->argument[3])&&isset($this->argument[4])&&
                         isset($this->argument[5])):return date(''.$this->argument[0].''.$this->argument[1].''.$this->argument[2].''.$this->argument[3].''.$this->argument[4].''.$this->argument[5].'');endif;//Y,m,d,h,i,s
                      if(isset($this->argument[0])&&!isset($this->argument[1])&&!isset($this->argument[2])&&!isset($this->argument[3])&&!isset($this->argument[4])&&
                         !isset($this->argument[5])):return date(''.$this->argument[0].'');endif;
                      if(isset($this->argument[0])&&isset($this->argument[1])&&isset($this->argument[2])&&!isset($this->argument[3])&&!isset($this->argument[4])&&!isset($this->argument[5])):
                         return date(''.$this->argument[0].'-'.$this->argument[1].'-'.$this->argument[2].'');
                         endif;
                      }//End setDate method

      public function setTime(){
					  date_default_timezone_set('America/Bogota');
                      return strftime("%Y-%m-%d %H:%M:%S",time());
                      }//End setTime method					  

      public function setDifferenceDays($initiationDate=null,$endDate=null){
					  $initiation=strtotime($this->_var->processExplode(" ",$initiationDate,0));
					  $end=strtotime($endDate);
					  $difference=($end - $initiation);
					  return $this->_var->processExplode(".",((($difference/60)/60)/24),0);
					  }//End setDifferenceDays method	
		
      public function getDecimal($receive=null){ 
	                  return number_format($receive,2,",",".");
                      }//End getDecimal method

     public function getThousand($receive=null){ 
	                  return number_format($receive,0,",",".");
                      }//End getThousand method						  
		
	  public function setParameter($Data=null, $DATA=null){
                      for($filter=0;
                          $filter<sizeof($this->VDataAll);
                          isset($this->VDataAll[$filter][$Data])? $this->receive=($this->VDataAll[$filter][$Data]=$DATA): null,
                          $filter++);
                      if(!$this->receive)$this->receive=null;
                      return $this->receive;
                      }//End setText method

      public function getParameter($Data=null){
                      for($filter=0;$filter<sizeof($this->VDataAll);$filter++):
                          if(isset($this->VDataAll[$filter][$Data])):
                             $this->data=$this->VDataAll[$filter][$Data];
                             endif;
                          endfor;
					  if(!$this->data)
                         $this->_exception->message(false,$Data." no exist...");
                      return $this->data;	
                      }//End getParameter method
					  
	  public function setElementAtList($Data=null){
                      array_push($this->vUtil,$Data);
                      }//End setElementAtList method
                      
      public function getElementAtList($Data=null){
                      if(isset($this->vUtil[$Data]))
                         return $this->vUtil[$Data];
                      }//End getElementAtList method
                      
      public function getSize($Data=null){
                      return sizeof($Data);
                      }//End getSize method
                      
      public function getSizeList(){
                      return $this->vUtil;
                      }//End getSizeList method      				  
}//End Data class
?>
