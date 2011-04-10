<?php
    echo $xhtml->tag(
        'h1',
        $this->pageTitle = __d( 'courrierpdo', "Courrierspdos::{$this->action}", true )
    )
?>
<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    echo $default->form(
        array(
            'Courrierpdo.name' => array( 'type' => 'text' ),
            'Courrierpdo.modeleodt' => array( 'type' => 'text' )
        ),
        array(
            'actions' => array(
                'courrierspdos::save',
                'courrierspdos::cancel'
            )
        )
    );

?>