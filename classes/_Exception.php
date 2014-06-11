<?php
// ============================================================================================================================
// =  @Copyright (c) 2011 Julián Baquero (segquerenquer@gmail.com)
// =  Provides julián Baquero. 
// =  License (CDDL) and the GNU General Public Licence version 2.
// =  File:         _Exception.class This file is part of Project MENTOR.
// =  Description:  this file set if you run an errorof project.  
// =  Created:      09-02-2012
// ============================================================================================================================
require_once "File.php";

class _Exception{
      private $file;
	  private static  $instance;
            
      private function __construct(){  
					   //$this->file = File::getInstance();
                       }//End __construct
      
      public  function __destruct(){
                       $instance;
					   $this->file;
                       }//End __destruct
                       
      public  static  function getInstance(){
                               if(!(self::$instance instanceof self))
                                  self::$instance = new self();
                               return self::$instance; 
                               }//End getInstance 
      
      public function message($Data=null,$message=null){  
                      try{
                         if(!$Data)
                            throw new Exception($message);
	                    }//End try
	                    catch(Exception $ex){
	                          ?><?=("Error ".$ex->getMessage());
							  //$this->file->addData("file/logger.dat",date("d-m-Y H:i:s.")." Error ".$ex->getMessage()." ".$ex->getTraceAsString()."\n");
	                          }//End catch
                      }//End message method   
}//End _Exception class
?>