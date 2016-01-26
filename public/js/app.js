(function ($) {

    /**
     * This is an event fired when the modal for removing items is opened.
     * 
     * It passes the id of the item to a hidden input using the data attribute from
     * the button firing the event, and also gets the todo decription and displays it
     * within the modal.
     */
    $('#modalRemove').on('show.bs.modal', function (event) {

        var message = "";
        // get access to the button as a jQuery object
        var button = $(event.relatedTarget);
        // find the ancestor that represents the whole todo item
        var listGroupItem = button.closest(".list-group-item");
        // get the text from the tag that holds the todo description
        var description = listGroupItem.find('.todo-description').text();
        // get a handle to the modal
        var modal = $(this);
        // get the id of the todo from the button
        var _id = button.data("id");

        // put the id into the hidden input
        modal.find('input[name=_id]').val(_id);

        /*
         * 
         * When using this modal to remove all items, the description will be empty.
         * If the _id variable is empty then change the message in the modal to check the user
         * wants to remove all items
         */

        message = '"' + description.trim() + '"';

        // set the text on the modal
        modal.find('.modal-message').text('Are you sure you want to remove ' + message + '?');

    });

    /**
     * This event is fired when the modal to add items is opened
     * 
     * It clears out the form if it is called to add a todo item,
     * but also can be used to edit a todo item.
     * 
     * To edit a todo item the description and id will be taken from
     * the button and list group item as above, but also the action 
     * will be taken from the buttons data attribute and passed to
     * a hidden input. This will allow the server to know what it's 
     * doing with the data (we don't do this in practice but it helps
     * prevent this example from being overcomplicated)
     */
    $('#modalAdd').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget);
        var modal = $(this);
        var description = "";
        var _id = "";
        var action = button.data("action");

        /*
         * If the action variable is "edit" then find the task description
         * in the same way it is found in the remove modal and a
         */
        if (action === "edit")
        {
            // find the ancestor that represents the whole todo item
            var listGroupItem = button.closest(".list-group-item");
            // get the text from the tag that holds the todo description
            description = listGroupItem.find('.todo-description').text();
            // get the id of the todo from the button
            _id = button.data("id");
        }

        modal.find('input[name=description]').val(description.trim());
        modal.find('input[name=_id]').val(_id);
        modal.find('input[name=action]').val(action);

    });
})(jQuery);