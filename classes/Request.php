<?php
// ============================================================================================================================
// =  @Copyright (c) 2011 Julián Baquero (segquerenquer@gmail.com)
// =  Provides Julián Baquero. 
// =  License (CDDL) and the GNU General Public Licence version 2.
// =  File:         Request.class This file is part of Project MENTOR.
// =  Description:  this file contains request form of project.
// =  Created:      09-02-2012
// ============================================================================================================================
require_once "_Exception.php";
require_once "Security.php";

class Request{
      private $_exception,
			  $security,
		      $vDataAll,
			  $request;
              
      private static  $instance;
                
      private function __construct(){
                       $this->_exception = _Exception::getInstance();
					   $this->security   = Security::getInstance();
                       $this->vDataAll   = array();
					   $this->request    = null;
                       }//End _Request __construct
      
      public  function __destruct(){
                       $this->_exception;
					   $this->security;
                       $this->vDataAll;
					   $this->request;
                       $instance;
                       }//End _Request __destruct
      
      public  static  function getInstance(){
                               if(!(self::$instance instanceof self))
                                  self::$instance = new self();
                               return self::$instance;  
                               }//End getInstance 
                      
      private function setElement($DATA=null, $Data=null){
                       $this->VDataAll[][$DATA]=$Data;
                       }//End setElement method
	    
      public function setVar($DATA=null, $Data=null){
				      if(@$_POST[$Data])
					     $this->setElement($DATA, $this->security->verify($this->security->repair($_POST[$Data])));
						 else
							 $this->setElement($DATA, $this->security->verify($this->security->repair(@$_GET[$Data])));
                      }//End setVar method
                      
      public function getVar($Data=null){
                      $filter=0;for(;;):
                          if(isset($this->VDataAll[$filter][$Data])):
                             $this->request= $this->VDataAll[$filter][$Data];
                             endif;
                          if($filter>sizeof($this->VDataAll))break;
                             $filter++;
                         endfor;
				      //if(!$this->request)
                         //$this->_exception->message(false,$Data." no exist...");
                      return $this->request;
                      }//End getVar method                  
}//End Request class
?>