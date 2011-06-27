<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Orientations';?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Orientation';
    }
    else {
        $this->pageTitle = 'Édition de l\'orientation';
    }
?>

<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $javascript->link( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}
?>

<script type="text/javascript">
    document.observe("dom:loaded", function() {
        dependantSelect( 'OrientstructStructurereferenteId', 'OrientstructTypeorientId' );
        try { $( 'OrientstructStructurereferenteId' ).onchange(); } catch(id) { }

        dependantSelect( 'OrientstructReferentId', 'OrientstructStructurereferenteId' );
        try { $( 'OrientstructReferentId' ).onchange(); } catch(id) { }
    });
</script>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php
        if( $this->action == 'add' ) {
            echo $form->create( 'Orientstruct', array(  'type' => 'post', 'url' => Router::url( null, true )  ));
            echo '<div>';
            echo $form->input( 'Orientstruct.id', array( 'type' => 'hidden', 'value' => '' ) );
            echo '</div>';
        }
        else {
            echo $form->create( 'Orientstruct', array( 'type' => 'post', 'url' => Router::url( null, true )  ));
            echo '<div>';
            echo $form->input( 'Orientstruct.id', array( 'type' => 'hidden' ) );
            echo '</div>';
        }
		echo '<div>';
		echo $form->input( 'Orientstruct.origine', array( 'type' => 'hidden', 'value' => 'manuelle' ) );
		echo '</div>';
    ?>

<?php include( '_form.ctp' ); ?>

        <div class="submit">
            <?php
                echo $xform->submit( 'Enregistrer', array( 'div' => false ) );
                echo $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
            ?>
        </div>
    <?php echo $form->end();?>
</div>
<div class="clearer"><hr /></div>