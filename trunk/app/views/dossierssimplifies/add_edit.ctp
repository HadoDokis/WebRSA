<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>


<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsOnValue( 'Personne0Toppersdrodevorsa', [ 'Typeorient0ParentId', 'Orientstruct0TypeorientId', 'Orientstruct0StructurereferenteId' ], 0, true );
        observeDisableFieldsOnValue( 'Personne1Toppersdrodevorsa', [ 'Typeorient1ParentId', 'Orientstruct1TypeorientId', 'Orientstruct1StructurereferenteId' ], 0, true );
    });
</script>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout d\'une préconisation d\'orientation';
    }
    else {
        echo $this->element( 'dossier_menu', array( 'foyer_id' => $foyer_id ) );

        $this->pageTitle = 'Édition d\'une préconisation d\'orientation';
    }
?>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php 
        if( $this->action == 'add' ) {
            echo $form->create( 'Dossiersimplifie',array( 'url' => Router::url( null, true ) ) );
        }
        else {
            echo $form->create( 'Dossiersimplifie', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
        }


    ?>

<?php include '_form.ctp'; ?>

        <?php echo $form->submit( 'Enregistrer' );?>
    <?php echo $form->end();?>
</div>

<div class="clearer"><hr /></div>