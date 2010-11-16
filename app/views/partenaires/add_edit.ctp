<?php
    echo $xhtml->tag(
        'h1',
        $this->pageTitle = __d( 'partenaire', "Partenaires::{$this->action}", true )
    )
?>
<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    echo $default->form(
        array(
            'Partenaire.libstruc' => array( 'required' => true ),
            'Partenaire.numvoie' => array( 'required' => true ),
            'Partenaire.typevoie' => array( 'required' => true ),
            'Partenaire.nomvoie' => array( 'required' => true ),
            'Partenaire.compladr' => array( 'required' => true ),
            'Partenaire.numtel',
            'Partenaire.numfax',
            'Partenaire.email',
            'Partenaire.codepostal' => array( 'required' => true ),
            'Partenaire.ville' => array( 'required' => true )
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
