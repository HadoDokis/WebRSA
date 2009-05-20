<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Contrats d\'insertion';?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout d\'un contrat d\'insertion';
    }
    else {
        $this->pageTitle = 'Édition d\'un contrat d\'insertion';
    }
?>
<script type="text/javascript">
function cbDpdt( master, slave ) {
    if( !$( master ).attr( 'checked' ) ) {
        $( slave ).attr( 'disabled', true );
        $( slave ).parents( 'div.input' ).addClass( 'disabled' );
    }
    else {
        $( slave ).attr( 'disabled', false );
        $( slave ).parents( 'div.input' ).removeClass( 'disabled' );
    }
}

function selDpdt( master, slave, enabled ) {
    if( !enabled ) {
        $( slave ).attr( 'disabled', true );
        $( slave ).parents( 'div.input' ).addClass( 'disabled' );
    }
    else {
        $( slave ).attr( 'disabled', false );
        $( slave ).parents( 'div.input' ).removeClass( 'disabled' );
    }
}

$( document ).ready(
    function() {
        // Au chargement
        selDpdt( $( '#ContratinsertionTypeCi' ), $( '#ContratinsertionRgCi' ) );
        selDpdt( $( '#ContratinsertionTypeCi' ), $( '#ContratinsertionActionsPrev' ) );
        selDpdt( $( '#ContratinsertionTypeCi' ), $( '#ContratinsertionObstaRenc' ) );
        selDpdt( $( '#ContratinsertionDecisionCi' ), $( '#ContratinsertionDatevalidationCiDay' ) );
        selDpdt( $( '#ContratinsertionDecisionCi' ), $( '#ContratinsertionDatevalidationCiMonth' ) );
        selDpdt( $( '#ContratinsertionDecisionCi' ), $( '#ContratinsertionDatevalidationCiYear' ) );

        $( '#ContratinsertionTypeCi' ).change( function( i ) {
                selDpdt( $( this ), $( '#ContratinsertionRgCi' ), ( $( '#ContratinsertionTypeCi' ).val() != 'pre' ) );
                selDpdt( $( this ), $( '#ContratinsertionActionsPrev' ), ( $( '#ContratinsertionTypeCi' ).val() != 'pre' ) );
                selDpdt( $( this ), $( '#ContratinsertionObstaRenc' ), ( $( '#ContratinsertionTypeCi' ).val() != 'pre' ) );
        } );
        $( '#ContratinsertionDecisionCi' ).change( function( i ) {
                selDpdt( $( this ), $( '#ContratinsertionDatevalidationCiDay' ), ( $( '#ContratinsertionDecisionCi' ).val() == 'v' ) );
                selDpdt( $( this ), $( '#ContratinsertionDatevalidationCiMonth' ), ( $( '#ContratinsertionDecisionCi' ).val() == 'v' ) );
                selDpdt( $( this ), $( '#ContratinsertionDatevalidationCiYear' ), ( $( '#ContratinsertionDecisionCi' ).val() == 'v' ) );
        } );
} );
</script>
<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php 
        if( $this->action == 'add' ) {
            echo $form->create( 'Contratinsertion', array( 'type' => 'post', 'url' => Router::url( null, true ) ) /*array('type' => 'post', 'action' => 'add/'.$personne_id )*/ );
            echo $form->input( 'Contratinsertion.id', array( 'type' => 'hidden', 'value' => '' ) );
        }
        else {
            echo $form->create( 'Contratinsertion', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo $form->input( 'Contratinsertion.id', array( 'type' => 'hidden' ) );

/*            echo $form->input( 'Contratinsertion.typocontrat_id', array( 'type' => 'hidden', 'value' => $typocontrat_id ) );*/
/*            echo $form->input( 'Contratinsertion.referent_id', array( 'type' => 'hidden', 'value' => $referent_id ) );*/
            echo $form->input( 'Contratinsertion.personne_id', array( 'type' => 'hidden', 'value' => $personne_id ) );
        }


    ?>

<?php include '_form.ctp'; ?>

        <?php echo $form->submit( 'Enregistrer' );?>
    <?php echo $form->end();?>
</div>

<div class="clearer"><hr /></div>