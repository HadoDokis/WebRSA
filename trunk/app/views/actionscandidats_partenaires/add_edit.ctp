<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'actioncandidat_partenaire', "ActionscandidatsPartenaires::{$this->action}", true )
    )
?>
<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    echo $default->form(
        array(
            'ActioncandidatPartenaire.actioncandidat_id',
            'ActioncandidatPartenaire.partenaire_id'
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
