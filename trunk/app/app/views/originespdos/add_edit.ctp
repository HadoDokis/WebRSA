<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'originepdo', "Originespdos::{$this->action}", true )
    )
?>
<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    echo $default->form(
        array(
            'Originepdo.libelle'
        ),
        array(
            'actions' => array(
                'Originepdo.save',
                'Originepdo.cancel'
            )
        )
    );
?>
