<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'statutpdo', "Statutspdos::{$this->action}", true )
    )
?>
<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    echo $default->form(
        array(
            'Statutpdo.libelle' => array( 'type' => 'text' )
        ),
        array(
            'actions' => array(
                'Statutpdo.save',
                'Statutpdo.cancel'
            )
        )
    );
?>
