<?php
// ============================================================================================================================
// =  @Copyright (c) 2011 Julián Baquero (segquerenquer@gmail.com)
// =  Provides julián Baquero. 
// =  License (CDDL) and the GNU General Public Licence version 2.
// =  File:         File.class This file is part of Project MENTOR.
// =  Description:  This file contains configurations to format files of project.  
// =  Created:      09-02-2012
// ============================================================================================================================

class File{
      private static  $instance; 
            
      private  function __construct(){
                       }//End __construct
      
      public  function __destruct(){
                       }//End __destruct

      public  static  function getInstance(){ 
                               if(!(self::$instance instanceof self))
                                  self::$instance = new self(); 
                               return self::$instance; 
                               }//End getInstance 

       public function addData($file=null,$data=null){
                       $this->file=fopen($file,'a');
                       fputs($this->file,$data."\r\n");
                       fclose($this->file);
                       }//End addData method					   
}//End File class
?>