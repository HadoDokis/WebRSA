<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'situationpdo', "Situationspdos::{$this->action}", true )
    )
?>
<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    echo $default->form(
        array(
            'Situationpdo.libelle' => array( 'type' => 'text' )
        ),
        array(
            'actions' => array(
                'Situationpdo.save',
                'Situationpdo.cancel'
            )
        )
    );
?>
