<?php
// ============================================================================================================================
// =  @Copyright (c) 2011 Julián Baquero (segquerenquer@gmail.com)
// =  Provides Julián Baquero. 
// =  License (CDDL) and the GNU General Public Licence version 2.
// =  File:         Cursor.class This file is part of Project MENTOR.
// =  Description:  this file contains control structure connection.
// =  Created:      09-02-2012
// ============================================================================================================================
require_once "Text.php";
require_once "Sql.php";
require_once "Connection.php";

class Cursor{
      private $_exception,
              $text,
			  $sql,
			  $connect,
              $cursor,
              $result,
              $argument;
      private static  $instance;
      
      private function __construct(){    
                       $this->_exception  = _Exception::getInstance();
                       $this->text        = Text::getInstance();
                       $this->sql         = Sql::getInstance();
                       $this->connect     = Connection::getInstance();
                       $this->cursor      = false;
                       $this->result      =
                       $this->argument    = null;
                       }//End __construct
      
      public  function __destruct(){
                       $instance;
                       $this->_exception;
                       $this->text;
                       $this->cursor;
					   $this->sql;
                       $this->connect;					   
                       $this->result;
                       $this->argument;
                       }//End __destruct
      
      public  static  function getInstance(){
                               if(!(self::$instance instanceof self))
                                  self::$instance = new self();
                               return self::$instance; 
                               }//End getInstance 
                             
      public function consultExecute(){
                      $this->argument = func_get_args();
                      if(!($this->cursor = @pg_query($this->connect->get(),$this->sql->getSentence())))
                         $this->_exception->message(false,$this->text->setLabel("MSG_LOADING_CURSOR"));
					  }//End consultExecute method

      public function execute($DATA=null,$Data=null){$this->_exception=_Exception::getInstance();$this->text=Text::getInstance();
	                  if(!($this->cursor = @pg_query($DATA,$Data)))
                         $this->_exception->message(false,$this->text->setLabel("MSG_LOADING_CURSOR"));
					  }//End execute method						  
               
      public function get(){
                      return $this->cursor; 
                      }//End get method
      
      public function next($receive=null){
                      if(($this->result = @pg_fetch_object($receive)))
                          return true;
                      }//End next method
                      
      public function getParameter($receive=null){ 
	                  //$this->_exception  = _Exception::getInstance();
                      //if(!@$this->result->$receive)$this->_exception->message(false,$receive." no exists...");
                      return $this->text->coding(@$this->result->$receive);
                      }//End getParameter method
					  
	  public function getDecimal($receive=null){ 
	                  return number_format(@$this->result->$receive,2,",",".");
                      }//End getDecimal method

      public function getThousand($receive=null){ 
	                  return number_format(@$this->result->$receive,0,",",".");
                      }//End getThousand method					  

      public function freeResult(){
                      @pg_free_result($this->cursor);
                      }//End freeResult method					  
}//End Cursor class
?>