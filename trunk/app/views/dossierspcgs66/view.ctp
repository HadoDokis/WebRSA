<?php
	$this->pageTitle =  __d( 'dossierpcg66', "Dossierspcgs66::{$this->action}", true );

	echo $this->element( 'dossier_menu', array( 'foyer_id' => $foyer_id ) );
?>

<div class="with_treemenu">
	<?php
		echo $xhtml->tag( 'h1', $this->pageTitle );
		echo $form->create( 'Dossierpcg66', array( 'type' => 'post', 'id' => 'dossierpcg66form', 'url' => Router::url( null, true ) ) );

		echo $default2->view(
			$dossierpcg66,
			array(
				'Dossierpcg66.datereceptionpdo',
				'Typepdo.libelle',
				'Originepdo.libelle',
				'Dossierpcg66.orgpayeur',
				'Serviceinstructeur.lib_service',
				'Dossierpcg66.iscomplet',
				'Dossierpcg66.user_id' => array( 'value' => '#User.nom# #User.prenom#' ),
				'Dossierpcg66.etatdossierpcg',
			),
			array(
				'class' => 'aere',
				'options' => $options
			)
		);

		echo "<h2>Pièces jointes</h2>";
		echo $fileuploader->results( Set::classicExtract( $dossierpcg66, 'Fichiermodule' ) );
	?>
</div>
	<div class="submit">
		<?php
			echo $form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>
	<?php echo $form->end();?>
<div class="clearer"><hr /></div>