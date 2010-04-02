<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'Référents liés à la personne';?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<?php /*if( $this->action == 'add' ):*/?>
    <?php echo $javascript->link( 'dependantselect.js' ); ?>
    <script type="text/javascript">
        document.observe("dom:loaded", function() {
            dependantSelect( 'PersonneReferentReferentId', 'StructurereferenteId' );
        });
    </script>
<?php /*endif;*/?>
<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>
<?php 
    if( $this->action == 'add' ) {
        echo $xform->create( 'PersonneReferent', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
    }
    else {
        echo $xform->create( 'PersonneReferent', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
        echo '<div>';
        echo $xform->input( 'PersonneReferent.id', array( 'type' => 'hidden' ) );
        echo '</div>';
    }
?>

    <fieldset>
        <legend>Structures référentes</legend>
        <?php
            echo $xform->input( 'PersonneReferent.personne_id', array( 'type' => 'hidden', 'value' => $personne_id ) );

            if( empty( $orientstruct ) && $this->action == 'add' ) {
                echo $xform->input( 'Structurereferente.id', array( 'label' => required( 'Structure référente' ), 'type' => 'select' , 'options' => $struct, 'empty' => true ) );
            }
            else if( !empty( $orientstruct ) && $this->action == 'add' ) {
                echo $xform->input( 'Structurereferente.id', array( 'label' => required( 'Structure référente' ), 'type' => 'select', 'options' => $struct, 'selected' => $sr, 'empty' => true )  );
            }


            if( $this->action == 'edit' ) {
                echo $xform->input( 'Structurereferente.id', array( 'label' => required( 'Structure référente' ), 'type' => 'select' , 'options' => $struct, 'selected' => $referent['Referent']['structurereferente_id'], 'empty' => true ) );
                echo $xform->input( 'PersonneReferent.referent_id', array( 'label' => required( 'Référents' ), 'type' => 'select' , 'options' => $referents, 'selected' => $referent['Referent']['structurereferente_id'].'_'.$this->data['PersonneReferent']['referent_id']/*, 'empty' => true*/ ) );
            }
            else {
                echo $xform->input( 'PersonneReferent.referent_id', array( 'label' => required( 'Référents' ), 'type' => 'select' , 'options' => $referents, 'empty' => true ) );
            }

            echo $xform->input( 'PersonneReferent.dddesignation', array( 'label' => required( 'Début de désignation' ), 'type' => 'date' , 'dateFormat' => 'DMY' ) );
            echo $xform->input( 'PersonneReferent.dfdesignation', array( 'label' => ( 'Fin de désignation' ), 'type' => 'date' , 'dateFormat' => 'DMY', 'empty' => true ) );
        ?>
    </fieldset>

    <div class="submit">
        <?php echo $form->submit( 'Enregistrer', array( 'div' => false ) );?>
        <?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
    </div>
<?php echo $xform->end();?>
</div>
<div class="clearer"><hr /></div>