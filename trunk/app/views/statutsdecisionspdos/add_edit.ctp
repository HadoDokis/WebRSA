<?php
    echo $xhtml->tag(
        'h1',
        $this->pageTitle = __d( 'statutdecisionpdo', "Statutsdecisionspdos::{$this->action}", true )
    )
?>
<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    echo $default->form(
        array(
            'Statutdecisionpdo.libelle' => array( 'type' => 'text' )
        ),
        array(
            'actions' => array(
                'Statutdecisionpdo.save',
                'Statutdecisionpdo.cancel'
            )
        )
    );

     echo $default->button(
        'back',
        array(
            'controller' => 'statutsdecisionspdos',
            'action'     => 'index'
        ),
        array(
            'id' => 'Back'
        )
    );
?>
