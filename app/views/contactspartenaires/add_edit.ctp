<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'contactpartenaire', "Contactspartenaires::{$this->action}", true )
    )
?>
<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    echo $default->form(
        array(
            'Contactpartenaire.qual' => array( 'options' => $qual, 'empty' => true, 'required' => true ),
            'Contactpartenaire.nom' => array( 'required' => true ),
            'Contactpartenaire.prenom' => array( 'required' => true ),
            'Contactpartenaire.numtel',
            'Contactpartenaire.email',
            'Contactpartenaire.partenaire_id' => array( 'type' => 'select', 'empty' => true, 'required' => true )
        ),
        array(
            'actions' => array(
                'Contactpartenaire.save',
                'Contactpartenaire.cancel',
            ),
            'options' => $options
        )
    );
?>