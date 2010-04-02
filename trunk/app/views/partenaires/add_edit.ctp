<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'partenaire', "Partenaires::{$this->action}", true )
    )
?>
<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    echo $default->form(
        array(
            'Partenaire.libstruc',
            'Partenaire.numvoie',
            'Partenaire.typevoie',
            'Partenaire.nomvoie',
            'Partenaire.compladr',
            'Partenaire.numtel',
            'Partenaire.numfax',
            'Partenaire.email',
            'Partenaire.codepostal',
            'Partenaire.ville'
        ),
        array(
            /*'actions' => array( /// FIXME: à faire par christian
                'Partenaire.save',
                'Partenaire.cancel'
            ),*/
            'options' => $options
       )
    );
?>
