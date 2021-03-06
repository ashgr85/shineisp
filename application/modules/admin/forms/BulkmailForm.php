<?php
class Admin_Form_BulkmailForm extends Zend_Form
{
    
    public function init()
    {
        // Set the custom decorator
        $this->addElementPrefixPath('Shineisp_Decorator', 'Shineisp/Decorator/', 'decorator');
        
        $this->addElement('text', 'subject', array(
            'filters'     => array('StringTrim'),
            'required'    => false,
            'decorators'  => array('Composite'),
            'label'       => 'Subject',
            'class'       => 'text-input medium-input'
        ));
        
        $this->addElement('textarea', 'body', array(
            'filters'     => array('StringTrim'),
            'required'    => true,
            'label'       => 'Body',
            'description' => 'Write here the email message to send to all your customers.',
            'class'       => 'textarea wysiwyg'
        ));
        
        $this->addElement('submit', 'send', array(
            'required' => false,
            'label'    => 'Save and Send Message',
            'decorators' => array('Composite'),
            'class'    => 'button'
        ));
                
        $this->addElement('hidden', 'mail_id');

    }
    
}