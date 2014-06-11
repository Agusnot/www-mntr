<?php
// ============================================================================================================================
// =  @Copyright (c) 2011 Julián Baquero (segquerenquer@gmail.com)
// =  Provides Julián Baquero. 
// =  License (CDDL) and the GNU General Public Licence version 2.
// =  File:         Sql.class This file is part of Project MENTOR.
// =  Description:  this file contains configuration SQl sentences of project.  
// =  Created:      09-02-2012
// ============================================================================================================================
require_once "_Exception.php";
require_once "Connection.php";
require_once "Cursor.php";

class Sql extends Cursor{      
      private $_exception,
              $vDataAll,
              $argument,
              $sql,
			  $connection;
      private  static  $instance;        
      
      private function __construct(){                        
                       $this->_exception = _Exception::getInstance();
					   $this->connection = Connection::getInstance();
                       $this->vDataAll   = array();
					   $this->argument   =
                       $this->sql        = null;
                       }//End __construct
      
      public  function __destruct(){                       
                       $this->_exception;
                       $this->vDataAll; 
                       $this->argument;                      
                       $instance;
                       $this->sql;
					   $this->connection;
                       }//End __destruct                            
      
      public  static  function getInstance(){                         
                               if(!(self::$instance instanceof self))                                  
                                    self::$instance = new self();                                                                                    
                               return self::$instance;                                
                               }//End getInstance                                     		 
	  
	  private function f($DATA=null,$Data=null){
                       $this->VDataAll2[0][$DATA]=$Data;
                       }//End f method
					   
	  private function setElement($DATA=null){
	                   $this->connection->connect();
					   if($DATA>=00000&&$DATA<10000)$location="sql.radicacion";if($DATA>=10000&&$DATA<20000)$location="sql.facturacion";
                       if($DATA>=20000&&$DATA<30000)$location="sql.standar";   if($DATA>=30000&&$DATA<40000)$location="sql.quejas_reclamos";					
					   $this->execute($this->connection->get(),"SELECT sentence FROM ".$location." WHERE code='".$DATA."'");
					   $this->next($this->get());
					   $this->VDataAll[][$DATA]=$this->getParameter("sentence");
					   $this->freeResult();
                       }//End setElement method
	  
	  public function setSentence(){
                      $this->argument = func_get_args();
					  $this->setElement($this->argument[0]);
					  for($filter=0;
                          $filter<sizeof($this->VDataAll);
                          isset($this->VDataAll[$filter][$this->argument[0]])? @$this->sql=(sprintf($this->VDataAll[$filter][$this->argument[0]],
                          $this->argument[1] ,$this->argument[2] ,$this->argument[3] ,$this->argument[4] ,$this->argument[5],
                          $this->argument[6] ,$this->argument[7] ,$this->argument[8] ,$this->argument[9] ,$this->argument[10],
                          $this->argument[11],$this->argument[12],$this->argument[13],$this->argument[14],$this->argument[15])): null,
						  $filter++);
					  if(!$this->sql)
                         $this->_exception->message(false,$this->argument[0]." no exist...");
                      return $this->sql;
                      }//End setSentence method
					  
	  public function getSentence(){
					  return $this->sql;
                      }//End getSentence method				  
}//End Sql class
?>