<?php
// ============================================================================================================================
// =  @Copyright (c) 2011 Julián Baquero (segquerenquer@gmail.com)
// =  Provides Julián Baquero. 
// =  License (CDDL) and the GNU General Public Licence version 2.
// =  File:         Connection.class This file is part of Project MENTOR.
// =  Description:  this file contains connection configuration. 
// =  Created:      09-02-2012
// ============================================================================================================================
require_once "Text.php";
require_once "Data.php";

class Connection{
      private $_exception,
              $text,
              $data,
              $connection,
              $receive;
      private static  $instance;
                
      private function __construct(){    
                       $this->_exception = _Exception::getInstance();             
                       $this->text       = Text::getInstance();
                       $this->data       = Data::getInstance();
                       $this->connection = 
                       $this->receive    = false;       
                       }//End __construct
      
      public  function __destruct(){
                       $instance;
                       $this->_exception;
                       $this->text;
                       $this->data;
                       $this->connection;
                       $this->receive;       
                       }//End __destruct
     
      public  static  function getInstance(){
                               if(!(self::$instance instanceof self))
                                  self::$instance = new self();
                               return self::$instance; 
                               }//End getInstance 
     
      public function connect(){ 
					  if(!($this->connection = @pg_connect("host=".$this->data->getParameter("PARAM_HOST").
					                                      " port=".$this->data->getParameter("PARAM_PORT").
														  " dbname=".$this->data->getParameter("PARAM_DB").
														  " user=".$this->data->getParameter("PARAM_USER").
                                                          " password='".$this->data->getParameter("PARAM_PASS")."'")))
                         $this->_exception->message(false,$this->text->setLabel("MSG_CONNECT"));
					  }//End connect method
                                 
      public function get(){
                      return $this->connection; 
                      }//End get method
               
      public function close(){
                      if(!($this->receive = @pg_close($this->get())))
                         $this->_exception->message(false,$this->text->setLabel("MSG_CONNECT_CLOSE"));
                      }//End close method			  
}//End Connection class
?>