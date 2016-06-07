<?php

/*
 * THIS FILE WILL NEED EDITING
 * 
 * Methods will need to be added for getting all todo items that are done, not done and for certain dates
 */

/**
 * Represents a group of Todo items in the Todo collection that match a query
 * 
 * An object of this type is essentially a query to the Todo collection. However there 
 * will be functions we frequently perform on a group of Todos such as mark all done
 * remove all and so on. Putting the code here prevents us writing it again on most pages
 * and most importantly if something needs changing in future we can update this function 
 * here to have the rest of the site updated
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class TodoCursor extends Cursor{
    
    /**
     * Takes a query (optional) and sets the collection for the object
     * @param array $qry
     */
    public function __construct($qry = array()) {
        $this->qry = $qry;
        parent::__construct(TODO_COLLECTION);
    }
    
    /**
     * Returns the document the cursor is currently pointing to
     * @return \Todo
     */
    public function current() {
        $output = new Todo();
        $output->document = parent::current();
        return $output;
    }
    
    /**
     * Marks all items as done
     * @return array Values to show if the update was successful
     */
    public function markAllDone(){
        $update_object = array(
            '$set'=>array(
                "done"=>true
            )
        );
        return $this->subset(array("done"=>false))->updateAll($update_object);
    }
    
    
    /**
     * Gets all done Todo documents from the collection
     * @return TodoCursor
     */
    public function getDoneItems(){
        return $this->subset(array("done"=>true));
    }
    
    /**
     * Gets all outstanding Todo documents from the collection
     * @return TodoCursor
     */
    public function getNotDoneItems(){
        return $this->subset(array("done"=>false));
    }
    
    public function total(){
        $pipeline = array();
        
        $pipeline[]['$group'] = array('_id'=>0, 'total'=>array('$sum'=>'$price'));
        
        $output = $this->collection->aggregate($pipeline);
        return $output["result"][0]["total"];
    }
    
    public function SendOrder(){
        $pipeline = array();
        
        $pipeline[]['$group'] = array('_id'=>'$description', 'count'=>array('$sum'=>1));
        
        $output = $this->collection->aggregateCursor($pipeline);
        
        //var_dump($output);
        
       $message = array();
        foreach ($output as $item){
            $message[] = $item["count"]." x ".$item["_id"];           
        }
                
        $to = 'josh@fnvi.co.uk';
        $subject = 'tester';
        
        mail($to,$subject, implode("\r\n", $message));
    }
    
}
