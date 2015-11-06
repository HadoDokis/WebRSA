<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
        echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}

	$this->pageTitle = 'Dossier PCG';
	$domain = 'dossierpcg66';
?>

<?php
	$charge = Set::enum( Set::classicExtract( $this->request->data, 'Dossierpcg66.user_id' ),  $gestionnaires );

	if( $this->action == 'add' ) {
		$title = $this->pageTitle = 'Ajout d\'un dossier PCG concernant le '.Set::classicExtract( $rolepers, Set::classicExtract( $personneDem, 'Prestation.rolepers' ) ).' : '.Set::classicExtract( $personneDem, 'Personne.nom_complet');
		$this->request->data['Dossierpcg66']['haspiecejointe'] = 0;
	}
	else {
		if( !empty( $charge ) ) {
			$this->pageTitle = 'Édition du dossier PCG géré par '.$charge;

			$title = 'Édition du dossier PCG concernant le '.Set::classicExtract( $rolepers, Set::classicExtract( $personneDem, 'Prestation.rolepers' ) ).' : '.Set::classicExtract( $personneDem, 'Personne.nom_complet').'<br />'. 'géré par '.$charge;
		}
		else{
			$this->pageTitle = 'Édition du dossier PCG';

			$title = 'Édition du dossier PCG concernant le '.Set::classicExtract( $rolepers, Set::classicExtract( $personneDem, 'Prestation.rolepers' ) ).' : '.Set::classicExtract( $personneDem, 'Personne.nom_complet');
		}
	}
?>
<h1><?php echo $title;?></h1>

<?php
	echo $this->Xform->create( 'Dossierpcg66', array( 'id' => 'dossierpcg66form' ) );
	if( $this->action == 'add' ) {
	}
	else {
		echo '<div>';
		echo $this->Xform->input( 'Dossierpcg66.id', array( 'type' => 'hidden' ) );
		echo '</div>';
	}
	echo '<div>';
	echo $this->Xform->input( 'Dossierpcg66.foyer_id', array( 'type' => 'hidden', 'value' => $foyer_id ) );

	echo '</div>';
?>

<div class="aere">

<fieldset>
	<?php
		echo $this->Default->subform(
			array(
				'Dossierpcg66.etatdossierpcg' => array( 'type' => 'hidden' ),
				'Dossierpcg66.typepdo_id' => array( 'label' => ( __d( 'dossierpcg66', 'Dossierpcg66.typepdo_id' ) ), 'type' => 'select', 'options' => $typepdo, 'empty' => true ),
				'Dossierpcg66.datereceptionpdo' => array( 'label' =>  ( __d( 'dossierpcg66', 'Dossierpcg66.datereceptionpdo' ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+1, 'minYear'=> 2009, 'empty' => false ),
				'Dossierpcg66.originepdo_id' => array( 'label' =>  ( __d( 'dossierpcg66', 'Dossierpcg66.originepdo_id' ) ), 'type' => 'select', 'options' => $originepdo, 'empty' => true ),
				'Dossierpcg66.orgpayeur' => array( 'label' =>  __d( 'dossierpcg66', 'Dossierpcg66.orgpayeur' ), 'type' => 'select', 'options' => $orgpayeur, 'empty' => true ),
				'Dossierpcg66.serviceinstructeur_id' => array( 'label' =>  ( __d( 'dossierpcg66', 'Dossierpcg66.serviceinstructeur_id' ) ), 'type' => 'select', 'options' => $serviceinstructeur, 'empty' => true )
			),
			array(
				'domain' => $domain,
				'options' => $options
			)
		);
	?>
</fieldset>
<fieldset>
<legend><?php echo required( $this->Default2->label( 'Dossierpcg66.haspiecejointe' ) );?></legend>

<?php echo $this->Form->input( 'Dossierpcg66.haspiecejointe', array( 'type' => 'radio', 'options' => $options['Dossierpcg66']['haspiecejointe'], 'legend' => false, 'fieldset' => false ) );?>
<fieldset id="filecontainer-piece" class="noborder invisible">
	<?php
		echo $this->Fileuploader->create(
			$fichiers,
			array( 'action' => 'ajaxfileupload' )
		);

		if( !empty( $fichiersEnBase ) ) {
			echo $this->Fileuploader->results(
				$fichiersEnBase
			);
		}
	?>
</fieldset>
</fieldset>
<fieldset>
	<legend><?php echo $this->Default2->label( 'Dossierpcg66.commentairepiecejointe' );?></legend>
		<?php
			echo $this->Default2->subform(
				array(
					'Dossierpcg66.commentairepiecejointe' => array( 'label' =>  false, 'type' => 'textarea' )
				),
				array(
					'domain' => $domain,
					'options' => $options
				)
			);
		?>
</fieldset>

<script type="text/javascript">
document.observe( "dom:loaded", function() {
	observeDisableFieldsetOnRadioValue(
		'dossierpcg66form',
		'data[Dossierpcg66][haspiecejointe]',
		$( 'filecontainer-piece' ),
		'1',
		false,
		true
	);

    dependantSelect( 'Dossierpcg66UserId', 'Dossierpcg66Poledossierpcg66Id' );
} );
</script>

<?php if( $gestionnairemodifiable ):?>
	<fieldset>
		<?php
			echo $this->Default->subform(
				array(
                    'Dossierpcg66.poledossierpcg66_id' => array( 'label' =>  ( __d( 'dossierpcg66', 'Dossierpcg66.poledossierpcg66_id' ) ), 'type' => 'select', 'options' => $polesdossierspcgs66, 'empty' => true ),
					'Dossierpcg66.user_id' => array( 'label' =>  ( __d( 'dossierpcg66', 'Dossierpcg66.user_id' ) ), 'type' => 'select', 'options' => $gestionnaires ),
					'Dossierpcg66.dateaffectation' => array( 'label' =>  ( __d( 'dossierpcg66', 'Dossierpcg66.dateaffectation' ) ), 'type' => 'date', 'dateFormat' => 'DMY', 'empty' => true, 'maxYear' => date( 'Y' ) + 1, 'minYear'=> 2009 ),
				),
				array(
					'domain' => $domain,
					'options' => $options
				)
			);
		?>
	</fieldset>
<?php endif;?>
<?php if( $personnedecisionmodifiable ):?>
	<fieldset>
		<legend>Personnes concernées</legend>

		<?php if( $this->Permissions->checkDossier( 'personnespcgs66', 'add', $dossierMenu ) ):?>
			<ul class="actionMenu">
				<?php
					echo '<li class="add">'.$this->Xhtml->addLink(
						'Ajouter une personne',
						array( 'controller' => 'personnespcgs66', 'action' => 'add', $dossierpcg66_id ),
						( !in_array( $etatdossierpcg, array( 'attaffect', 'transmisop' ) ) || $this->Permissions->checkDossier( 'personnespcgs66', 'add', $dossierMenu ) != "1" )
					).' </li>';
				?>
			</ul>
		<?php endif;?>

		<?php if( empty( $personnespcgs66 ) ):?>
			<p class="notice">Ce dossier ne possède pas de personne liée.</p>
		<?php endif;?>

		<?php if( !empty( $personnespcgs66 ) ):?>
			<table class="tooltips">
				<thead>
					<tr>
						<th>Personne concernée</th>
						<th>Motif(s)</th>
						<th>Statut(s)</th>
						<th>Nb de traitements</th>
						<th colspan="5" class="action">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach( $personnespcgs66 as $personnepcg66 ) {
							//Liste des différentes situations de la personne
							$listeSituations = Set::extract( $personnepcg66, '/Situationpdo/libelle' );
							$differentesSituations = '';
							foreach( $listeSituations as $key => $situation ) {
								if( !empty( $situation ) ) {
									$differentesSituations .= $this->Xhtml->tag( 'h3', '' ).'<ul><li>'.$situation.'</li></ul>';
								}
							}

							$blockWithoutTraitement = true;
							$nbTraitement = count( Set::extract( $personnepcg66, '/Traitementpcg66/id' ) );
							if( !empty( $nbTraitement ) ){
								$blockWithoutTraitement = true;
							}
							else{
								$blockWithoutTraitement = false;
							}

							//Liste des différents statuts de la personne
							$listeStatuts = Set::extract( $personnepcg66, '/Statutpdo/libelle' );
							$differentsStatuts = '';
							foreach( $listeStatuts as $key => $statut ) {
								if( !empty( $statut ) ) {
									$differentsStatuts .= $this->Xhtml->tag( 'h3', '' ).'<ul><li>'.$statut.'</li></ul>';
								}
							}
							echo $this->Xhtml->tableCells(
								array(
									h( Set::classicExtract( $personnepcg66, 'Personne.qual' ).' '.Set::classicExtract( $personnepcg66, 'Personne.nom' ).' '.Set::classicExtract( $personnepcg66, 'Personne.prenom' ) ),
									$differentesSituations,
									$differentsStatuts,
									h( $personnepcg66['Personnepcg66']['nbtraitements'] ),
									$this->Xhtml->viewLink(
										'Voir la personne concernée',
										array( 'controller' => 'personnespcgs66', 'action' => 'view', $personnepcg66['Personnepcg66']['id'] ),
										$this->Permissions->checkDossier( 'personnespcgs66', 'view', $dossierMenu )
									),
									$this->Xhtml->editLink(
										'Editer la personne concernée',
										array( 'controller' => 'personnespcgs66', 'action' => 'edit', $personnepcg66['Personnepcg66']['id'] ),
										$this->Permissions->checkDossier( 'personnespcgs66', 'edit', $dossierMenu )
									),
									$this->Xhtml->treatmentLink(
										'Traitements pour la personne',
										array( 'controller' => 'traitementspcgs66', 'action' => 'index', $personnepcg66['Personnepcg66']['personne_id'], $personnepcg66['Personnepcg66']['dossierpcg66_id'] ),
										$this->Permissions->checkDossier( 'traitementspcgs66', 'index', $dossierMenu )
									),
									$this->Xhtml->deleteLink(
										'Supprimer la personne',
										array( 'controller' => 'personnespcgs66', 'action' => 'delete', $personnepcg66['Personnepcg66']['id'] ),
										$this->Permissions->checkDossier( 'personnespcgs66', 'delete', $dossierMenu )
									),
								),
								array( 'class' => 'odd' ),
								array( 'class' => 'even' )
							);
						}
					?>
				</tbody>
			</table>
		<?php endif;?>
	</fieldset>

	<?php
		$block = true;
		foreach( $decisionsdossierspcgs66 as $i => $decision ){
			if ( isset( $decision ) ) {
				$block = false;
			}
			else {
				$block = true;
			}
		}

		// Droits d'acces aux actions
		$perm['add'] = (
                            $this->Permissions->checkDossier( 'decisionsdossierspcgs66', 'add', $dossierMenu ) != '1'
                            || !in_array( $etatdossierpcg, array( 'attaffect', 'attinstr', 'instrencours', 'attinstrattpiece', 'attinstrdocarrive', 'decisionnonvalid', 'instr', 'arevoir' ) )
                            || empty( $personnespcgs66 )

		);
		$perm['view'] = false;
		$perm['edit'] = ( '		\'#Decisiondossierpcg66.id#\' != '.$lastDecisionId.'
								|| (
									 \''.$etatdossierpcg.'\' == \'transmisop\'
								)
								|| \''.$this->Permissions->checkDossier( 'decisionsdossierspcgs66', 'edit', $dossierMenu ).'\' != \'1\'
								|| ( "#Decisiondossierpcg66.etatdossierpcg#" == "annule" )' 
		);
		$perm['avistechnique'] = ( '\''.$this->Permissions->checkDossier( 'decisionsdossierspcgs66', 'avistechnique', $dossierMenu ). '\' != \'1\'
                                || ( "#Decisiondossierpcg66.etatdossierpcg#" == "annule" )
                                || (
									 \''.$etatdossierpcg.'\' == \'transmisop\'
								)' 
		);
		$perm['validation'] = ( '\''.$this->Permissions->checkDossier( 'decisionsdossierspcgs66', 'validation', $dossierMenu ). '\' != \'1\'
                                || ( "#Decisiondossierpcg66.etatdossierpcg#" == "annule" )
                                || (
									 \''.$etatdossierpcg.'\' == \'transmisop\'
								)' 
		);
		$perm['imprimer'] = ( '( "'.$this->Permissions->checkDossier( 'dossierspcgs66', 'imprimer', $dossierMenu ).'" != "1" )
                                || ( "#Decisiondossierpcg66.etatdossierpcg#" == "annule" )' 
		);
		$perm['transmitop'] = ( '( "'.$this->Permissions->checkDossier( 'decisionsdossierspcgs66', 'transmitop', $dossierMenu ).'" != "1" )
                                || ( "#Decisiondossierpcg66.validationproposition#" == "N" )
                                || ( "#Decisiondossierpcg66.etatdossierpcg#" == "annule" )' 
		);
		$perm['cancel'] = ( '( "'.$this->Permissions->checkDossier( 'decisionsdossierspcgs66', 'cancel', $dossierMenu ).'" != "1" ) 
								|| ( "#Decisiondossierpcg66.etatdossierpcg#" == "annule" )' 
		);
		$perm['delete'] = ( '( "'.$this->Permissions->checkDossier( 'decisionsdossierspcgs66', 'delete', $dossierMenu ).'" != "1" ) 
								|| ( "#Decisiondossierpcg66.etatdossierpcg#" == "annule" )' 
		);
		$perm['filelink'] = ( '( "'.$this->Permissions->checkDossier( 'decisionsdossierspcgs66', 'filelink', $dossierMenu ).'" != "1" )' );


		echo $this->Xhtml->tag(
			'fieldset',
			$this->Xhtml->tag(
				'legend',
				'Propositions de décision niveau foyer'
			).
			$this->Default3->actions(
				array(
					"/Decisionsdossierspcgs66/add/{$dossierpcg66_id}" => array(
						'disabled' => $perm['add']
					),
				)
			).
			$this->Default3->index(
				$decisionsdossierspcgs66,
				array(
					'Decisionpdo.libelle',
					'Decisiondossierpcg66.avistechnique',
					'Decisiondossierpcg66.dateavistechnique',
					'Decisiondossierpcg66.validationproposition',
					'Decisiondossierpcg66.datevalidation',
					// NOTE : conflit en v3.0 dans le domaine de traduction dossierspcgs66
					'Fichiermodule.nb_fichiers_lies' => array( 'label' => __d('decisionsdossierspcgs66', 'Fichiermodule.nb_fichiers_lies')),
					'/Decisionsdossierspcgs66/view/#Decisiondossierpcg66.id#' => array( 'disabled' => $perm['view']	),
					'/Decisionsdossierspcgs66/edit/#Decisiondossierpcg66.id#' => array( 'disabled' => $perm['edit']	),
					'/Decisionsdossierspcgs66/avistechnique/#Decisiondossierpcg66.id#' => array( 'disabled' => $perm['avistechnique']	),
					'/Decisionsdossierspcgs66/validation/#Decisiondossierpcg66.id#' => array( 'disabled' => $perm['validation']	),
					'/Dossierspcgs66/imprimer/#Decisiondossierpcg66.dossierpcg66_id#/#Decisiondossierpcg66.id#' => array( 
						'disabled' => $perm['imprimer'],
						'class' => 'print ActionDecisionproposition'
					),
					'/Decisionsdossierspcgs66/transmitop/#Decisiondossierpcg66.id#' => array( 'disabled' => $perm['transmitop']	),
					'/Decisionsdossierspcgs66/cancel/#Decisiondossierpcg66.id#' => array( 'disabled' => $perm['cancel']	),
					'/Decisionsdossierspcgs66/delete/#Decisiondossierpcg66.id#' => array( 'disabled' => $perm['delete']	),
					'/Decisionsdossierspcgs66/filelink/#Decisiondossierpcg66.id#' => array( 'disabled' => $perm['filelink']	),
				),
				array(
					'options' => $options,
					'tooltip' => array( 'Decisiondossierpcg66.motifannulation' ), // FIXME Ne fonctionne pas sous default3
					'paginate' => false
				)
			)
		);

	?>
<?php endif;?>

<fieldset id="Etatpdo" class="invisible"></fieldset>

</div>
<div class="submit">
	<?php
		echo $this->Form->submit( 'Enregistrer', array( 'div' => false ) );
		echo $this->Form->submit( 'Retour', array( 'div' => false, 'name' => 'Cancel' ) );
	?>
</div>

<?php echo $this->Xform->end();?>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		var impression = $$('a.ActionDecisionproposition').first();
		
		[ $('Dossierpcg66TypepdoId'), $('Dossierpcg66UserId') ].each(function(field) {
			if( field ) {
				field.observe('change', function(element, value) {
					fieldUpdater();
				});
			}
		});

		fieldUpdater();
		
		if (impression) {
			impression.observe('click', function() {
				var etatdossier = $('Etatpdo').select('strong').first();
				etatdossier.innerHTML = 'Calcul de la position...';
				setTimeout(fieldUpdater, 5000);
			});
		}
	});

	function fieldUpdater() {
		new Ajax.Updater(
			'Etatpdo',
			'<?php echo Router::url( array( "action" => "ajax_getetatdossierpcg66", Hash::get($this->request->data, 'Dossierpcg66.id' ) ) ); ?>',
			{
				asynchronous:true,
				evalScripts:true,
				parameters:{},
				requestHeaders:['X-Update', 'Etatpdo']
			}
		);
	}
</script>