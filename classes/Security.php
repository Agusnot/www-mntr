<?php
// ============================================================================================================================
// =  @Copyright (c) 2011 Julián Baquero (segquerenquer@gmail.com)
// =  Provides Julián Baquero.
// =  License (CDDL) and the GNU General Public Licence version 2.
// =  File:         Security.class This file is part of Project MENTOR.
// =  Description:  this file contains configurations to security of project.
// =  Created:      09-02-2012
// ============================================================================================================================
class Security{
      private static  $instance;

      private function __construct(){
                       }//End __construct

      public  function __destruct(){
                       $instance;
                       }//End __destruct

      public  static  function getInstance(){
                               if(!(self::$instance instanceof self))
                                  self::$instance = new self();
                               return self::$instance;
                               }//End getInstance

      public function repair($str=null) {
                      return str_replace('\'','',str_replace(';','',str_replace('=','',$str)));
                      }//End repair method
					  
	  public function verify($str=null) {
                      return @addslashes(pg_escape_string($str));
                      }//End verify method
}//End Security class
?>