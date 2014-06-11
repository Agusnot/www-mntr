<?php
// ============================================================================================================================
// =  @Copyright (c) 2011 Julián Baquero (segquerenquer@gmail.com)
// =  Provides Julián Baquero. 
// =  License (CDDL) and the GNU General Public Licence version 2.
// =  File:         Script.class This file is part of Project MENTOR.
// =  Description:  this file contains Script configuration.  
// =  Created:      13-02-2012
// ============================================================================================================================
require_once '../classes/Text.php';
require_once '_Var.php';

class Script{
      private $attribute,
              $object,
              $text,
              $_var;
      private static  $instance;
            
      private function __construct(){ 
					   $this->text      =Text::getInstance();
                       $this->_var      =_Var::getinstance(); 
                       $this->attribute = array();
                       $this->object    =null;    
                       }//End Script __construct
      
      public  function __destruct(){
                       $this->text;
                       $this->_var;
                       $this->attribute;
                       $this->object;
                       $instance;
                       }//End Script __destruct
                       
      public  static  function getInstance(){
                               if(!(self::$instance instanceof self))
                                  self::$instance = new self();
                               return self::$instance; 
                               }//End getInstance                 
      
      public function object($name=null){
                      $this->object=$name;
                      $this->text->setBuffer('var '.$name.' = {');
                      }//End object method
                      
      public function objectLBJS($name=null){
                      $this->object=$name;
                      $this->text->setBuffer('<script>var '.$name.' = {');
                      }//End objectLBJS method                
      
      public function attribute($name=null){
                      $this->text->setBuffer($this->text->cleanBuffer($name));
                      $this->attribute[]=$name;
                      }//End attribute method
                      
      public function destroyAttribute(){      
                      $this->text->setBuffer('destroy:function(){ ');
                      for($filter=0;
                          $filter<sizeof($this->attribute);
                          $this->text->setBuffer('delete this.'.$this->_var->processExplode(':',$this->attribute[$filter],0).';'),
                          $filter++);
                      $this->text->setBuffer(' },/*End destroyAttribute method*/');
                      $this->attribute=array();
                      }//End destroyAttribute method
                      
      public function initialize(){      
                      $this->text->setBuffer('initialize:function(){ ');
                      for($filter=0;
                          $filter<sizeof($this->attribute);
                          $this->text->setBuffer('this.'.$this->_var->processExplode(':',$this->attribute[$filter],0).'='.$this->_var->processExplode(',',$this->_var->processExplode(':',$this->attribute[$filter],1),0).';'),
                          $filter++);
                      $this->text->setBuffer(' },/*End initialize method*/');
                      }//End initialize method                
                         
      public function method($name=null,$params=null,$operations=null,$continue=null){  
                      $this->text->setBuffer(''.$name.':function('.$params.'){');
                      $this->text->setBuffer(''.$this->text->cleanBuffer($operations).''); 
                      $this->text->setBuffer('}/*End '.$name.' method*/');if($continue)$this->text->setBuffer(",");
                      }//End method method
        
      public function send($name=null,$params=null,$operations=null,$type=null,$url=null,$request=null,$success=null,$continue=null){ 
                      $this->text->setBuffer(''.$name.':function('.$params.'){');
                      $this->text->setBuffer(''.$this->text->cleanBuffer($operations).'');        
                      $this->text->setBuffer($this->text->cleanBuffer('$.ajax({type: "'.$type.'",'.
                                                                                      'url: "'.$url.'",'.
                                                                                      'data: '.$request.''));
                                      $this->text->setBuffer($this->text->cleanBuffer('success: function(html){'.
                                                                                                ''.$success.''.
                                                                                      '}/*End success*/'.
                                                                             '});/*End $.ajax*/')); 
                      $this->text->setBuffer('}/*End '.$name.' method*/');if($continue)$this->text->setBuffer(","); 
                      }//End send method
                      
      public function endObject(){                
                      $this->text->setBuffer('};/*End '.$this->object.' class*/');
                      $this->object=null;   
                      }//End endObject method
                      
      public function endObjectLBJS(){                
                      $this->text->setBuffer('};/*End '.$this->object.' class*/</script>');
                      $this->object=null;   
                      }//End endObjectLBJS method       
}//End Script class
?>