<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>
<div class="with_treemenu">
    <?php echo $xform->create( 'Entretien' );?>
    <?php
        echo $xhtml->tag(
            'h1',
            $this->pageTitle = __d( 'entretien', "Entretiens::{$this->action}", true )
        );
    ?>
    <?php
        echo $default->view(
            $entretien,
            array(
                'Entretien.dateentretien',
                'Structurereferente.lib_struc',
                'Referent.nom_complet',
                'Entretien.typeentretien',
                'Entretien.typerdv_id',
                'Entretien.commentaireentretien'
            ),
            array(
                'options' => $options
            )
        );

    ?>
    <div class="submit">
        <?php
            echo $xform->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
        ?>
    </div>
    <?php echo $xform->end();?>
</div>
<div class="clearer"><hr /></div>