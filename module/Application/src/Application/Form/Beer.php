<?php

namespace Application\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class Beer extends Form
{
    public function __construct()
    {
        parent::__construct('post');
        $this->setAttribute('method', 'post');        
        $this->setAttribute('action', '/beer/save');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'name',
            'options' => [
                'label' => 'Beer name',
            ],
            'type'  => 'Text',
        ]);
        $this->add([
            'name' => 'style',
            'options' => [
                'label' => 'Beer style',
            ],
            'type'  => 'Text',
        ]);
        $this->add([
            'name' => 'img',
            'options' => [
                'label' => 'Beer image',
            ],
            'type'  => 'Text',
        ]);

        $this->add([
            'name' => 'send',
            'type'  => 'Submit',
            'attributes' => [
                'value' => 'Salvar',
            ],
        ]);
    }

}
