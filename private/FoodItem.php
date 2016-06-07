<?php

/*
 * THIS FILE NEEDS EDITING
 * 
 */

/**
 * Description of Todo
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class FoodItem extends Document{
    
    /**
     * 
     * @param String $var
     */
    public function __construct($var = null) {
        parent::__construct(FOOD_COLLECTION);
        $this->loadTodo($var);
    }
    
    /**
     * 
     * @param type $var
     */
    public function loadTodo($var){
        if(MongoId::isValid($var))
        {
            $this->loadDocument($var);
        }
        else
        {
            $this->loadDocument($var, "some field");
        }
    }
    
    /**
     * Sets the instruction fo the todo item
     * @param type $text
     */
    public function setDescription($text){
        $this->document["description"] = $text;
    }
    
    public function addToMenu(){
        $variable = new Todo();
        $variable->document = $this->document;
        $variable->document["_id"] = new MongoId();
        $variable->store();
    }
    
    public function createMenuItem($description){
        $this->setDescription($description);
        $this->store();
    }
}
