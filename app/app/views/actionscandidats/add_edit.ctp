<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'actioncandidat', "Actionscandidats::{$this->action}", true )
    )
?>
<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    echo $default->form(
        array(
            'Actioncandidat.intitule' => array( 'domain' => 'actioncandidat' ),
            'Actioncandidat.code' => array( 'domain' => 'actioncandidat' ),
        ),
        array(
            'actions' => array(
                'Actioncandidat.save',
                'Actioncandidat.cancel'
            )
        )
    );
?>
