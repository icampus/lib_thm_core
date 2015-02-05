/**
 * Contains the normal form function that is executed when a form is submitted
 */
Joomla.submitbutton = function(task)
{
    if (task == '<?php echo $resource; ?>.cancel' || document.formvalidator.isValid(document.id('item-form')))
    {
        Joomla.submitform(task, document.getElementById('item-form'));
    }
}