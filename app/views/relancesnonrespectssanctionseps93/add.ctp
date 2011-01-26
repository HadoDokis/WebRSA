<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id) );
?>

<div class="with_treemenu">
	<h1><?php echo $this->pageTitle = 'Ajout d\'une relance';?></h1>

	<?php
		echo $xform->create();

		echo $xform->input( 'Nonrespectsanctionep93.id', array( 'type' => 'hidden' ) );
		echo $xform->input( 'Nonrespectsanctionep93.origine', array( 'domain' => 'nonrespectsanctionep93', 'type' => 'radio', 'options' => array( 'orientstruct' => 'Orientation non contractualisée', 'contratinsertion' => 'Non renouvellement du CER' ), 'value' => $origine ) );

		echo $xform->input( 'Relancenonrespectsanctionep93.id', array( 'type' => 'hidden' ) );
		echo $xform->input( 'Relancenonrespectsanctionep93.numrelance', array( 'domain' => 'relancenonrespectsanctionep93', 'type' => 'radio', 'options' => array( 1 => 'Première relance', 2 => 'Seconde relance', 3 => 'Troisième relance' ), 'value' => $numrelance ) );
		echo $xform->input( 'Relancenonrespectsanctionep93.daterelance', array( 'domain' => 'relancenonrespectsanctionep93', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 1, 'empty' => true ) );

		echo $xform->end( 'Enregistrer' );
	?>

	<script type="text/javascript">
		document.observe( "dom:loaded", function() {
			<?php if( $origine == 'orientstruct' ):?>
				$( 'Nonrespectsanctionep93OrigineContratinsertion' ).disable();
			<?php elseif( $origine == 'contratinsertion' ):?>
				$( 'Nonrespectsanctionep93OrigineOrientstruct' ).disable();
			<?php endif;?>

			<?php for( $i = 1 ; $i <= ( ( $origine == 'orientstruct' ) ? 3 : 2 ) ; $i++ ):?>
				<?php if( $i != $numrelance ):?>
					$( 'Relancenonrespectsanctionep93Numrelance<?php echo $i;?>' ).disable();
				<?php endif;?>
			<?php endfor;?>
		} );
	</script>
</div>
<div class="clearer"><hr /></div>