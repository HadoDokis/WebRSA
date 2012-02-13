<?php
	echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );

	$this->pageTitle = 'Relance';

	echo $this->element( 'dossier_menu', array( 'id' => $dossier_id ) );

	if( $this->action == 'add' ) {
		$this->pageTitle = 'Ajout relance';
	}
	else {
		$this->pageTitle = 'Édition relance';
	}

?>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php
			echo $ajax->remoteFunction(
				array(
					'update' => 'PieceaprePieceapre',
					'url' => Router::url( array( 'action' => 'ajaxpiece', Set::extract( $this->data, 'Relanceapre.apre_id' ) ), true )
				)
			);
		?>
	});
</script>

<div class="with_treemenu">
	<h1><?php echo $this->pageTitle;?></h1>
	<?php
		if( $this->action == 'add' ) {
			echo $form->create( 'Relanceapre', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
			echo '<div>';
			echo $form->input( 'Relanceapre.apre_id', array( 'type' => 'hidden', 'value' => Set::classicExtract( $apre, 'Apre.id' ) ) );
			echo '</div>';
		}
		else {
			echo $form->create( 'Relanceapre', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
			echo '<div>';
			echo $form->input( 'Relanceapre.id', array( 'type' => 'hidden' ) );
			echo $form->input( 'Relanceapre.apre_id', array( 'type' => 'hidden' ) );
			echo '</div>';
		}
	?>

	<div class="aere">
		<fieldset>
			<?php
				echo $xform->input( 'Relanceapre.daterelance', array( 'domain' => 'apre', 'dateFormat' => 'DMY' ) );
				echo $xform->input( 'Relanceapre.commentairerelance', array( 'domain' => 'apre' ) );
			?>
		</fieldset>
		<fieldset>
			<legend>Pièces jointes manquantes</legend>
			<?php
				$piecesManquantesAides = Set::classicExtract( $apre, "Apre.Piece.Manquante" );
				$listeParAides = '';
				foreach( $piecesManquantesAides as $model => $pieces ) {
					if( !empty( $pieces ) ) {
						echo $xhtml->tag( 'h2', __d( 'apre', $model, true ) ).'<ul><li>'.implode( '</li><li>', $pieces ).'</li></ul>';
						$listeParAides .= $xhtml->tag( 'h2', __d( 'apre', $model, true ) ).'<ul><li>'.implode( '</li><li>', $pieces ).'</li></ul>';
					}
				}

				echo $xform->input( 'Relanceapre.listepiecemanquante', array( 'domain' => 'apre', 'type' => 'hidden', 'value' => $listeParAides ) );
			?>
		</fieldset>
	</div>

	<div class="submit">
		<?php echo $form->submit( 'Enregistrer', array( 'div' => false ) );?>
		<?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
	</div>
<?php echo $form->end();?>
</div>
<div class="clearer"><hr /></div>