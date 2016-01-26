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
class Todo extends Document{
    
    /**
     * 
     * @param String $var
     */
    public function __construct($var = null) {
        parent::__construct(TODO_COLLECTION);
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
    
    /**
     * Marks a todo as done
     */
    // Add method to mark item as done here
    public function done() {
        $this->document["done"] = true;
    }


    /**
     * Marks a todo as not done
     */
    public function markNotDone(){
        $this->document["done"] = false;
    }
    
    /**
     * Adds a timestamp to the Todo
     */
    private function setTimestamp(){
        $this->document["created"] = new MongoDate();
    }
    
    /**
     * Creates a Todo
     * @param String $description
     */
    public function createTodo($description){
        $this->setDescription($description);
        $this->markNotDone();
        $this->setTimestamp();
        $this->store();
    }
}
