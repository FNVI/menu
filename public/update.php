<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php include '../private/app.php'; ?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <?php include 'templates/includes.php'; ?>
        <?php
        
        // Declare the post variables we will be looking for
        $args = array(
            "_id"=>FILTER_SANITIZE_STRING,
            "description"=>FILTER_SANITIZE_STRING
        );
        
        // Get our post variables using the arguments above (this method is much safer than $_POST)
        $post_vars = filter_input_array(INPUT_POST, $args, false);
        
        // Get the action veriable from our post data
        $action = filter_input(INPUT_POST, "action", FILTER_SANITIZE_STRING);
        
        // Create our Todo object. If the _id sent in the post data isn't set then the Todo is blank
        $todo = new Todo($post_vars["_id"]);        
        $foodMenu = new FoodItem($post_vars["_id"]);
        
        
        // Typical switch statement calling our functions from the Todo object
        switch($action)
        {
            case "remove": $todo->delete(); break;
            case "add": $todo->createTodo($post_vars["description"]); break;
            case "edit": $todo->setDescription($post_vars["description"]); $todo->store(); break;
            case "done": $todo->done(); $todo->store(); break;
            case "createMenuItem": $foodMenu->createMenuItem($post_vars["description"]); break;
        }
        
        // This creates a cursor of all the Todo items in our database
        $todoCursor = new TodoCursor();
        $foodCursor = new FoodCursor();
        
        ?>
    </head>
    <body>
        <!--The header is included in another script, this is vary basic templating-->
        <?php include 'templates/header.php'; ?>
        
        <!--The main section of the page, bootstrap layouts are divided into rows and columns using classes-->
        <section class="container">
            <div class="row">
                
<!--            This div is a container for our content.
                Classes will need adding here to allow the content to be full width
                when displayed on a mobile device or small screen, but smaller and centred for
                medium and large screens-->

                <div class="col-xs-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
<!--                Content can be separated into panels using bootstrap.
                    For further info read the documentation on the bootstrap web site-->
                    <article class="panel panel-default">
                        <header class="panel-heading clearfix">
                            <h3 class="panel-title pull-left">Menu</h3>

                            <div class="dropdown pull-right">
                                <button class="btn btn-default dropdown-toggle " type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    Food Categories
                                    <span class="caret"></span>
                                </button>
                                <ul id="menuDropdown" class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                    <?php foreach ($foodCursor->values("type")as $type) { ?>
                                        <li id="<?php echo $type ?>"><a><?php echo $type ?></a></li>

                                    <?php } ?>
                                </ul>
                            </div>


                            <!--                        This button is used to trigger a modal. The data-target and data-toggle attributes
                                                        are used in bootstrap to add this functionality. for a full explanation read the bootstrap 
                                                        documentation on their web site -->

                                                       <button class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target="#modalAdd" data-action="add">
                                                            <i class="fa fa-plus"></i> Add
                                                        </button>
                        </header>
                        <section class="panel-body">
                            <form method="post">
                                <input type="hidden" name="action" value="add">
                                <ul class="list-group">
                                    <!--                                We are using a for each to iterate through the todo cursor as if it were a key value array.
                                                                        PHP blocks can be used with html between them removing the requirement to put the below html
                                                                        into a string variable
                                                                        If you require more info on foreach or php blocks check the online php manual -->
                                    <?php foreach ($foodCursor as $id => $todoItem) { ?>
                                        <?php
                                        /*
                                         * If a to-do item is marked as done then we need to put a line through that item
                                         * This shorthand version of an if statement. 
                                         * For more info lookup the ternary oprator in the php online manual
                                         */
                                        $class = $todoItem["type"] ? $todoItem["type"] : "";
                                        ?>
                                        <li class="list-group-item clearfix menu-item <?php echo $class; ?>">
                                            <span class="todo-description">
                                                <!--Output the to-do description-->
                                                <?php echo $todoItem["description"]; ?>
                                            </span>
                                            <!--More modal buttons, but this time using bootstraps button group-->
                                            <div class="btn-group btn-group-sm pull-right">
                                                <button name="_id" value="<?php echo $todoItem["_id"]; ?>" class="btn btn-danger pull-right"> £<?php echo $todoItem["price"]; ?></button>                                                
                                            </div>
                                        </li>
                                        <!--                                The closing braces to the foreach statement above-->
                                    <?php } ?>
                                </ul>
                            </form>
                        </section>
                        <!--In the footer we will add buttons to mark all done/not done and remove all-->
                        <footer class="panel-footer">

                        </footer>
                    </article>
                </div>
            </div>
        </section>
        
        <!--Modal to add item to the to-do list. For more info on modals read the bootstrap online documentation-->
        <div id="modalAdd" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!--As our modal is used for submitting data, we will use a form on the modal-->
                    <form method="post">
                        <!--Hidden inputs for data the server needs with the request-->
                        <input type="hidden" name="_id" value="">
                        <input type="hidden" name="action" value="createMenuItem">
                        <header class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Add Todo</h4>
                        </header>
                        <section class="modal-body">
                            <div class="form">
                                <div class="form-group">
                                    <label for="todo-description" class="control-label">
                                        Description:
                                    </label>
                                    <input type="text" class="form-control" name="description">
                                </div>
                            </div>
                        </section>
                        <footer class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" >Save</button>
                        </footer>
                    </form>
                </div>
            </div>
        </div>
        
        
        <!--Modal to confirm removal of item-->
        <div id="modalRemove" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post">
                        <input type="hidden" name="action" value="remove">
                        <input type="hidden" name="_id">
                        <header class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Remove Todo</h4>
                        </header>
                        <section class="modal-body">
                            <span class="modal-message"></span>
                        </section>
                        <footer class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                            <button type="submit" class="btn btn-danger">Yes</button>
                        </footer>
                    </form>
                </div>
            </div>
        </div>
        <!-- Our own javascript is included at the bottom as it needs to run after the above html is loaded-->
        <script src="js/app.js" type="text/javascript"></script>
    </body>
</html>
