<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'statutdecisionpdo', "Statutsdecisionspdos::{$this->action}", true )
    )
?>
<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

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
?>
