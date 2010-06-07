<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'descriptionpdo', "Descriptionspdos::{$this->action}", true )
    )
?>
<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php

    echo $default->form(
        array(
            'Descriptionpdo.name',
            'Descriptionpdo.modelenotification',
            'Descriptionpdo.sensibilite' => array( 'type' => 'radio',  'value' => 'N'  )
        ),
        array(
            'actions' => array(
                'Descriptionpdo.save',
                'Descriptionpdo.cancel'
            ),
            'options' => $options
        )
    );
?>
