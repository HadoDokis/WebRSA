<?php
	echo $this->Xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'entretien', "Entretiens::{$this->action}" )
	);
?>
<br />
	<div id="tabbedWrapper" class="tabs">

		<div id="entretiens">
			<h2 class="title">Entretiens</h2>
				<?php if( $this->Permissions->checkDossier( 'entretiens', 'add', $dossierMenu ) ):?>
					<ul class="actionMenu">
						<?php
							echo '<li>'.
								$this->Xhtml->addLink(
									'Ajouter un entretien',
									array( 'controller' => 'entretiens', 'action' => 'add', $personne_id )
								).
							' </li>';
						?>
					</ul>
				<?php endif;?>
				<?php if( isset( $entretiens ) ):?>
					<?php if( empty( $entretiens ) ):?>
						<?php $message = 'Aucun entretien n\'a été trouvé.';?>
						<p class="notice"><?php echo $message;?></p>
					<?php else:?>

					<?php $pagination = $this->Xpaginator->paginationBlock( 'Entretien', $this->passedArgs ); ?>
					<?php echo $pagination;?>
					<table id="searchResults" class="tooltips">
						<thead>
							<tr>
								<th>Date de l'entretien</th>
								<th>Structure référente</th>
								<th>Nom du prescripteur</th>
								<th>Type d'entretien</th>
								<th>Objet de l'entretien</th>
								<th>A revoir le</th>
								<th class="action" colspan="5">Action</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach( $entretiens as $index => $entretien ):?>
							<?php
								$nbFichiersLies = 0;
								$nbFichiersLies = ( isset( $entretien['Fichiermodule'] ) ? count( $entretien['Fichiermodule'] ) : 0 );

								echo $this->Xhtml->tableCells(
										array(
											h( date_short(  $entretien['Entretien']['dateentretien'] ) ),
											h( $entretien['Structurereferente']['lib_struc'] ),
											h( $entretien['Referent']['nom_complet'] ),
											h( Set::enum( $entretien['Entretien']['typeentretien'], $options['Entretien']['typeentretien'] ) ),
											h( $entretien['Objetentretien']['name'] ),
											h( $this->Locale->date( 'Date::miniLettre', $entretien['Entretien']['arevoirle'] ) ),
											$this->Xhtml->viewLink(
												'Voir le contrat',
												array( 'controller' => 'entretiens', 'action' => 'view', $entretien['Entretien']['id'] ),
												$this->Permissions->checkDossier( 'entretiens', 'index', $dossierMenu )
											),
											$this->Xhtml->editLink(
												'Editer l\'orientation',
												array( 'controller' => 'entretiens', 'action' => 'edit', $entretien['Entretien']['id'] ),
												$this->Permissions->checkDossier( 'entretiens', 'edit', $dossierMenu )
											),
											$this->Xhtml->deleteLink(
												'Supprimer l\'entretien',
												array( 'controller' => 'entretiens', 'action' => 'delete', $entretien['Entretien']['id'] ),
												$this->Permissions->checkDossier( 'entretiens', 'delete', $dossierMenu )
											),
											$this->Xhtml->fileLink(
												'Fichiers liés',
												array( 'controller' => 'entretiens', 'action' => 'filelink', $entretien['Entretien']['id'] ),
												$this->Permissions->checkDossier( 'entretiens', 'filelink', $dossierMenu )
											),
											h( '('.$nbFichiersLies.')' )
										),
										array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
										array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
									);
								?>
							<?php endforeach;?>
						</tbody>
					</table>
					<?php endif?>
				<?php endif?>

		</div><!-- Fin de div entretiens -->

<!-- INFO : Fin de l'affichage des Entretiens -->
<? if( false ):?>
	<div id="dsporigine">
		<h2 class="title">DSP d'origine</h2>
		<?php if( !empty( $dsps ) ):?>
			<?php
				echo $this->Form->input(
					'Dsp.hideempty',
					array(
						'type' => 'checkbox',
						'label' => 'Cacher les questions sans réponse',
						'onclick' => 'if( $( \'DspHideempty\' ).checked ) {
							$$( \'.empty\' ).each( function( elmt ) { elmt.hide() } );
						} else { $$( \'.empty\' ).each( function( elmt ) { elmt.show() } ); }'
					)
				);

				echo $this->Default->view(
					$dsps,
					array(
						'Dsp.sitpersdemrsa',
						'Dsp.topisogroouenf',
						'Dsp.topdrorsarmiant',
						'Dsp.drorsarmianta2',
						'Dsp.topcouvsoc',
						'Dsp.accosocfam',
						'Dsp.libcooraccosocfam',
						'Dsp.accosocindi',
						'Dsp.libcooraccosocindi',
						'Dsp.soutdemarsoc',
						'Dsp.nivetu',
						'Dsp.nivdipmaxobt',
						'Dsp.annobtnivdipmax',
						'Dsp.topqualipro',
						'Dsp.libautrqualipro',
						'Dsp.topcompeextrapro',
						'Dsp.libcompeextrapro',
						'Dsp.topengdemarechemploi',
						'Dsp.hispro',
						'Dsp.libderact',
						'Dsp.libsecactderact',
						'Dsp.cessderact',
						'Dsp.topdomideract',
						'Dsp.libactdomi',
						'Dsp.libsecactdomi',
						'Dsp.duractdomi',
						'Dsp.inscdememploi',
						'Dsp.topisogrorechemploi',
						'Dsp.accoemploi',
						'Dsp.libcooraccoemploi',
						'Dsp.topprojpro',
						'Dsp.libemploirech',
						'Dsp.libsecactrech',
						'Dsp.topcreareprientre',
						'Dsp.concoformqualiemploi',
						'Dsp.topmoyloco',
						'Dsp.toppermicondub',
						'Dsp.topautrpermicondu',
						'Dsp.libautrpermicondu',
						'Dsp.natlog',
						'Dsp.demarlog'
					),
					array(
						'options' => $options
					)
				);
			?>
			<?php else:?>
				<ul class="actionMenu">
					<?php
						echo '<li>'.$this->Xhtml->addLink(
							'Ajouter des Dsps',
							array( 'controller' => 'dsps', 'action' => 'add', $personne_id )
						).' </li>';

					?>
				</ul>
				<p class="notice">Cette personne ne possède pas encore de données socio-professionnelles.</p>
		<?php endif;?>
	</div>

<!-- INFO : Fin de l'affichage des DSP d'Origine -->

	<div id="dspcg">
		<h2 class="title">DSP CG</h2>
			<ul class="actionMenu">
				<?php
					echo '<li>'.$this->Xhtml->addLink(
						'Ajouter des Dsps',
						array( 'controller' => 'dsps', 'action' => 'add', $personne_id )
					).' </li>';
				?>
			</ul>
			<?php if( !empty( $dsps ) ):?>
			<?php
				echo $this->Default->view(
					$dsps,
					array(
						'Dsp.nivetu',
						'Dsp.duractdomi'
					),
					array(
						'options' => $options
					)
				);
			?>
			<?php else:?>
				<p class="notice">Cette personne ne possède pas encore de données CG.</p>
		<?php endif;?>
	</div>
	<? endif;?>
</div> <!-- Fin de div tabbedWrapper -->

<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->script( 'prototype.livepipe.js' );
		echo $this->Html->script( 'prototype.tabs.js' );
	}
?>

<script type="text/javascript">
	makeTabbed( 'tabbedWrapper', 2 );
</script>