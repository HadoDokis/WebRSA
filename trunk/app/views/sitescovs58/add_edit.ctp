<?php
    echo $xhtml->tag(
        'h1',
        $this->pageTitle = __d( 'sitecov58', "Sitescovs58::{$this->action}", true )
    )
?>
<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    echo $default->form(
        array(
            'Sitecov58.name' => array( 'type' => 'text' )
        ),
        array(
            'actions' => array(
                'sitescovs58::save',
                'sitescovs58::cancel'
            )
        )
    );

    echo $default->button(
        'back',
        array(
            'controller' => 'sitescovs58',
            'action'     => 'index'
        ),
        array(
            'id' => 'Back'
        )
    );
?>