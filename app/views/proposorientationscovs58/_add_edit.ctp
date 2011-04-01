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

<?php echo $javascript->link( 'dependantselect.js' ); ?>
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        dependantSelect( 'Propoorientationcov58StructurereferenteId', 'Propoorientationcov58TypeorientId' );
        try { $( 'Propoorientationcov58StructurereferenteId' ).onchange(); } catch(id) { }

        dependantSelect( 'Propoorientationcov58ReferentId', 'Propoorientationcov58StructurereferenteId' );
        try { $( 'Propoorientationcov58ReferentId' ).onchange(); } catch(id) { }
    });
</script>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php
        if( $this->action == 'add' ) {
            echo $form->create( 'Propoorientationcov58', array(  'type' => 'post', 'url' => Router::url( null, true )  ) );
            echo '<div>';
            echo $form->input( 'Propoorientationcov58.id', array( 'type' => 'hidden', 'value' => '' ) );
            echo '</div>';
        }
        else {
            echo $form->create( 'Propoorientationcov58', array( 'type' => 'post', 'url' => Router::url( null, true )  ) );
            echo '<div>';
            echo $form->input( 'Propoorientationcov58.id', array( 'type' => 'hidden' ) );
            echo $form->input( 'Propoorientationcov58.dossiercov58_id', array( 'type' => 'hidden' ) );
            echo '</div>';
        }
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