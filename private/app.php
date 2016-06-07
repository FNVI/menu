<?php

/*
 * THIS FILE NEEDS EDITING
 * 
 * This file is a config file.
 * We're mainly storing database credentials and collection names to keep them
 * all in one place. This also helps should the database be moved or renamed in future
 *  
 */

/*
 * This overrides the php.ini setting for displaying errors. Useful for debugging.
 * If the page won't load uncomment this line
 */
//ini_set('display_errors', '1');

/******************************************************************
 * Step 1:
 * 
 * Put your database connection credentials into the fields below
 * 
 * These are the mongo connection details.
 * The connection is usually passed as one large string
 * However here the parts have been separated out for ease of use,
 * these constants will be used later for creating that string
 ******************************************************************/
define("DB_SERVER","cloud2.plenary-group.com");

define("DB_NAME", "josh");

define("DB_USER","josh");

define("DB_PASS","password");

define("DB_PORT","27019");

/**
 * Collections inside the database will be named by constants too.
 * This isn't a neccessity but rather useful should you want to change a collection name
 * 
 * In this example we will only be interested in the todo collection.
 */

define("TODO_COLLECTION","todo");

define("FOOD_COLLECTION","food");


/*
 *  DO NOT EDIT BEYOND THIS POINT
 */

/**
 * remove any existing autoloads
 */
spl_autoload_register(null,false);

/*
 * Declare any file types that can be used
 */
spl_autoload_extensions('.php');

/**
 * This function allows class files to be loaded automatically.
 * 
 * 
 * Whenever a class is referenced/called this function gets ran. 
 * It includes the file with the same name as the class if it isn't already included
 * 
 * @param String $class The class name
 * @return boolean
 */
function classLoader($class)
{
    $file = __DIR__."/$class.php";
    if(!file_exists($file))
    {
        echo "$file not found";
        return false;
    }
    include $file;
}
/**
 * register the class loader function
 */
spl_autoload_register('classLoader');
