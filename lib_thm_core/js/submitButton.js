/**
 * Contains the normal form function that is executed when a form is submitted
 */
jQuery( document ).ready(function() {
    Joomla.submitbutton = function (task) {
        var match = task.match(/\.cancel$/);
        if (match !== null || document.formvalidator.isValid(document.id('item-form'))) {
            Joomla.submitform(task, document.getElementById('item-form'));
        }
    }
});