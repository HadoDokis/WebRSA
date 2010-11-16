<?php
    echo $xhtml->tag(
        'h1',
        $this->pageTitle = __d( 'actioncandidat_partenaire', "ActionscandidatsPartenaires::{$this->action}", true )
    )
?>
<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    echo $default->form(
        array(
            'ActioncandidatPartenaire.actioncandidat_id' => array( 'type' => 'select', 'empty' => true, 'required' => true ),
            'ActioncandidatPartenaire.partenaire_id' => array( 'type' => 'select', 'empty' => true, 'required' => true )
        ),
        array(
            'actions' => array(
                'ActioncandidatPartenaire.save',
                'ActioncandidatPartenaire.cancel'
            ),
            'options' => $options
        )
    );
?>
