<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id) );
?>

<div class="with_treemenu">
	<h1><?php echo $this->pageTitle = 'Ajout d\'une relance';?></h1>

	<?php
		echo $this->Xform->create();

		echo $this->Xform->input( 'Nonrespectsanctionep93.id', array( 'type' => 'hidden' ) );
		echo $this->Xform->input( 'Nonrespectsanctionep93.origine', array( 'domain' => 'nonrespectsanctionep93', 'type' => 'radio', 'options' => array( 'orientstruct' => 'Orientation non contractualisée', 'contratinsertion' => 'Non renouvellement du CER' ), 'value' => $origine ) );

		echo $this->Xform->input( 'Relancenonrespectsanctionep93.id', array( 'type' => 'hidden' ) );
		echo $this->Xform->input( 'Relancenonrespectsanctionep93.numrelance', array( 'domain' => 'relancenonrespectsanctionep93', 'type' => 'radio', 'options' => array( 1 => 'Première relance', 2 => 'Seconde relance', 3 => 'Confirmation passage en EP'/*'Troisième relance'*/ ), 'value' => $numrelance ) );
		echo '<div class="input select"><span class="label">Date de relance minimale</span><span class="input">'.date_short( $daterelance_min ).'</span></div>';
		echo $this->Xform->input( 'Relancenonrespectsanctionep93.daterelance', array( 'domain' => 'relancenonrespectsanctionep93', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 1, 'empty' => true ) );

// 		echo $this->Xform->end( 'Enregistrer' );
// 		echo $this->Xform->button( 'Annuler', array( 'type' => 'submit', 'name' => 'Cancel' ) )
// 		
		
		echo $this->Html->tag(
			'div',
			$this->Xform->button( 'Enregistrer', array( 'type' => 'submit' ) )
			.$this->Xform->button( 'Annuler', array( 'type' => 'submit', 'name' => 'Cancel' ) ),
			array( 'class' => 'submit noprint' )
		);
		
		echo $this->Xform->end();
	?>

	<script type="text/javascript">
		document.observe( "dom:loaded", function() {
			<?php if( $origine == 'orientstruct' ):?>
				$( 'Nonrespectsanctionep93OrigineContratinsertion' ).disable();
			<?php elseif( $origine == 'contratinsertion' ):?>
				$( 'Nonrespectsanctionep93OrigineOrientstruct' ).disable();
			<?php endif;?>

			<?php for( $i = 1 ; $i <= 3/*( ( $origine == 'orientstruct' ) ? 3 : 2 )*/ ; $i++ ):?>
				<?php if( $i != $numrelance ):?>
					$( 'Relancenonrespectsanctionep93Numrelance<?php echo $i;?>' ).disable();
				<?php endif;?>
			<?php endfor;?>
		} );
	</script>
</div>
<div class="clearer"><hr /></div>