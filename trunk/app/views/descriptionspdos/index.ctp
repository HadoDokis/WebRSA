<?php
    echo $xhtml->tag(
        'h1',
        $this->pageTitle = __d( 'descriptionpdo', "Descriptionspdos::{$this->action}", true )
    )
?>

<?php
    echo $default->index(
        $descriptionspdos,
        array(
            'Descriptionpdo.name',
            'Descriptionpdo.modelenotification',
            'Descriptionpdo.sensibilite'
        ),
        array(
            'cohorte' => false,
            'actions' => array(
                'Descriptionpdo.edit',
                'Descriptionpdo.delete',
            ),
            'add' => 'Descriptionpdo.add',
            'options' => $options
        )
    );

    echo $default->button(
        'back',
        array(
            'controller' => 'pdos',
            'action'     => 'index'
        ),
        array(
            'id' => 'Back'
        )
    );
?>
