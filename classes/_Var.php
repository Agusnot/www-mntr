<?php
// ============================================================================================================================
// =  @Copyright (c) 2011 Julián Baquero (segquerenquer@gmail.com)
// =  Provides Julián Baquero. 
// =  License (CDDL) and the GNU General Public Licence version 2.
// =  File:         _Var.inc This file is part of Project UNIQUE MANAGEMENT REPORT.
// =  Description:  this file contains handling variablesf project.  
// =  Created:      09-02-2012
// ============================================================================================================================
require_once "_Exception.php";

class _Var{
      private $_exception,
	            $vDataAll,   
              $vUtil,
              $argument,
              $_var,
              $format;
      private static  $instance;
  
      private function __construct(){
                       $this->_exception = _Exception::getInstance();
					             $this->vUtil      =
                       $this->vDataAll   =array();
                       $this->argument   = 
                       $this->_var       =
                       $this->format     =null;
                       }//End __construct
                       
      public  function __destruct(){
                       $this->_exception;
					             $this->vUtil;
                       $this->vDataAll;
                       $this->argument;
                       $this->_var;
                       $this->format;
                       $instance;
                       }//End __destruct
                       
      public  static  function getInstance(){
                               if(!(self::$instance instanceof self))
                                  self::$instance = new self();
                               return self::$instance; 
                               }//End getInstance
                       
      private function setElement($DATA=null,$Data=null){
                       $this->VDataAll[][$DATA]=$Data;
                       }//End setElement method
                       
      public function __autoload(){
                      $this->argument = func_get_args();
                      for($filter=0;
                          $filter<sizeof($this->argument);
                          $this->argument[$filter]?require_once $this->argument[$filter].'.php':null,
                          $filter++);  
                      }//End __autoload method		
		 
      public function format(){
                      $this->argument = func_get_args();
                      for($this->counter=0;
                          $this->counter<sizeof($this->argument);
                          $this->format=(@utf8_encode(@sprintf($this->argument[0],
                          $this->argument[1],$this->argument[2],$this->argument[3],$this->argument[4],$this->argument[5],
                          $this->argument[6],$this->argument[7],$this->argument[8],$this->argument[9],$this->argument[10],
                          $this->argument[11],$this->argument[12],$this->argument[13],$this->argument[14],$this->argument[15],
                          $this->argument[16],$this->argument[17],$this->argument[18],$this->argument[19],$this->argument[20]))),
                          $this->counter++);
                      return $this->format;
                      }//End format method
                      
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
      
	  public function getSubtract($String=null,$DATA=null,$Data=null){
                      if($DATA)
					     $receive=substr($String,$DATA);
                      if($DATA&&$Data)
					     $receive=substr($String,$DATA,$Data);
					  return $receive;
                      }//End getSubtract method
	  
	  public function getExplode($Separator=null,$String=null){
                      return explode($Separator,$String);
                      }//End getExplode method
	  
      public function processExplode($Separator,$String,$Position){
                      $this->get=$this->getExplode($Separator,$String);
                      return @$this->get[$Position];
                      }//End processExplode method      
	  
      public function release(){
                      $this->argument = func_get_args();
					  for($this->counter=1;
                          $this->counter<sizeof($this->argument);
                          $this->counter++):
                          unset($this->argument[$this->counter]);
                          endfor;
                      }//End release method
					  
	    public function set($DATA=null,$Data=null){
                      $this->setElement($DATA, $Data);
                      }//End set method
                      
      public function get($Data=null){
                      for($filter=0;$filter<sizeof($this->VDataAll);$filter++):
                          if(isset($this->VDataAll[$filter][$Data])):
                             /*$this->_var=*/return ($this->VDataAll[$filter][$Data]);
                             endif;
                          endfor;
					            //if($this->_var)
                         //$this->_exception->message(false,$Data." no exist...");
                      //return $this->_var;
                      }//End get method
                      
      public function increment($Data=null){
                      for($filter=0;$filter<sizeof($this->VDataAll);@$this->VDataAll[$filter][$Data]? $this->_var=($this->VDataAll[$filter][$Data]++): null,$filter++);
                      return $this->_var;
                      }//End increment method

      public function decrease($Data=null){
                      for($filter=0;$filter<sizeof($this->VDataAll);$this->VDataAll[$filter][$Data]? $this->_var=($this->VDataAll[$filter][$Data]--): null,$filter++);
                      return $this->_var;
                      }//End decrease method
					  
	  public function reset($Data=null){
	                  for($this->_var;$this->_var>=sizeof($this->VDataAll);$this->decrease($Data),$this->_var--);
                      }//End reset method				  
}//End PublicVar class
?>