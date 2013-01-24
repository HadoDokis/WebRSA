<?php
	$this->pageTitle =  __d( 'dossierpcg66', "Dossierspcgs66::{$this->action}" );
?>
<?php
	echo $this->Xhtml->tag( 'h1', $this->pageTitle );
	echo $this->Form->create( 'Dossierpcg66', array( 'type' => 'post', 'id' => 'dossierpcg66form', 'url' => Router::url( null, true ) ) );

	$class = '';
	if( empty( $dossierpcg66['Decisiondossierpcg66'][0]['datetransmissionop'] ) ) {
		$class = 'aere';
	}

	echo $this->Default2->view(
		$dossierpcg66,
		array(
			'Dossierpcg66.datereceptionpdo',
			'Typepdo.libelle',
			'Originepdo.libelle',
			'Dossierpcg66.orgpayeur',
			'Serviceinstructeur.lib_service',
			'Dossierpcg66.iscomplet',
			'Dossierpcg66.user_id' => array( 'value' => '#User.nom# #User.prenom#' ),
			'Dossierpcg66.etatdossierpcg'
		),
		array(
			'class' => $class,
			'options' => $options
		)
	);

	if( !empty( $dossierpcg66['Decisiondossierpcg66'][0]['datetransmissionop'] ) ) {
		echo $this->Default2->view(
			$dossierpcg66,
			array(
				'Decisiondossierpcg66.0.datetransmissionop'
			),
			array(
				'class' => 'aere',
				'options' => $options
			)
		);
	}
?>
<h2>Décisions du dossier</h2>
<?php if( !empty( $dossierpcg66['Decisiondossierpcg66'] ) ):?>
	<table class="tooltips aere">
		<thead>
			<tr>
				<th>Proposition</th>
				<th>Date de la proposition</th>
				<th>Validation</th>
				<th>Date de validation</th>
				<th>Commentaire du technicien</th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach( $dossierpcg66['Decisiondossierpcg66'] as $decision ){
					echo $this->Xhtml->tableCells(
						array(
							h( Set::enum( Set::classicExtract( $decision, 'decisionpdo_id' ), $decisionpdo ) ),
							h( date_short( Set::classicExtract( $decision, 'datepropositiontechnicien' ) ) ),
							h( value( $options['Decisiondossierpcg66']['validationproposition'], Set::classicExtract( $decision, 'validationproposition' ) ) ),
							h( date_short( Set::classicExtract( $decision, 'datevalidation' ) ) ),
							h( Set::classicExtract( $decision, 'commentairetechnicien' ) )
						)
					);
				}
			?>
		</tbody>
	</table>
	<?php else:?>
		<p class="notice">Aucune décision émise pour ce dossier</p>
	<?php endif;?>
	<?php
		echo "<h2>Pièces jointes</h2>";
		echo $this->Fileuploader->results( Set::classicExtract( $dossierpcg66, 'Fichiermodule' ) );
	?>
	<?php if( !empty( $traitementsCourriersEnvoyes ) ):?>
			<h2>Courriers envoyés</h2>
			<table class="tooltips aere">
				<thead>
					<tr>
						<th>Type de traitement</th>
						<th>Date d'envoi</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach( $traitementsCourriersEnvoyes as $courrierEnvoye ){      
							echo $this->Xhtml->tableCells(
								array(
									h( $courrierEnvoye['Situationpdo']['libelle'] ),
									h( date_short( $courrierEnvoye['Traitementpcg66']['dateenvoicourrier'] ) ),
									$this->Xhtml->printLink(
										'Imprimer',
										array( 'controller' => 'traitementspcgs66', 'action'=> 'printModeleCourrier', $courrierEnvoye['Traitementpcg66']['id'] ),
										$this->Permissions->checkDossier( 'traitementspcgs66', 'printModeleCourrier', $dossierMenu )
									)
								)
							);
						}
					?>
				</tbody>
			</table>
		<?php endif;?>
</div>
<div class="submit">
	<?php
		echo $this->Form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
	?>
</div>
<?php echo $this->Form->end();?>