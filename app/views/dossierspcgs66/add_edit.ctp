<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $xhtml->css( array( 'fileuploader' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $javascript->link( 'fileuploader.js' );
	}

	$this->pageTitle = 'Dossier PCG';
	$domain = 'dossierpcg66';

	echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	echo $this->element( 'dossier_menu', array( 'foyer_id' => $foyer_id ) );
?>

<?php
	$charge = Set::enum( Set::classicExtract( $this->data, 'Dossierpcg66.user_id' ),  $gestionnaire );

	if( $this->action == 'add' ) {
		$title = $this->pageTitle = 'Ajout d\'un dossier PCG concernant le '.Set::classicExtract( $rolepers, Set::classicExtract( $personneDem, 'Prestation.rolepers' ) ).' : '.Set::classicExtract( $qual, Set::classicExtract( $personneDem, 'Personne.qual' ) ).' '.Set::classicExtract( $personneDem, 'Personne.nom' ).' '.Set::classicExtract( $personneDem, 'Personne.prenom' );
	}
	else {
		if( !empty( $charge ) ) {
			$this->pageTitle = 'Édition du dossier PCG géré par '.$charge;

			$title = 'Édition du dossier PCG concernant le '.Set::classicExtract( $rolepers, Set::classicExtract( $personneDem, 'Prestation.rolepers' ) ).' : '.Set::classicExtract( $qual, Set::classicExtract( $personneDem, 'Personne.qual' ) ).' '.Set::classicExtract( $personneDem, 'Personne.nom' ).' '.Set::classicExtract( $personneDem, 'Personne.prenom' ).'<br />'. 'géré par '.$charge;
		}
		else{
			$this->pageTitle = 'Édition du dossier PCG';

			$title = 'Édition du dossier PCG concernant le '.Set::classicExtract( $rolepers, Set::classicExtract( $personneDem, 'Prestation.rolepers' ) ).' : '.Set::classicExtract( $qual, Set::classicExtract( $personneDem, 'Personne.qual' ) ).' '.Set::classicExtract( $personneDem, 'Personne.nom' ).' '.Set::classicExtract( $personneDem, 'Personne.prenom' );
		}
	}
?>

<div class="with_treemenu">
	<h1><?php echo $title;?></h1>

	<?php
		echo $xform->create( 'Dossierpcg66', array( 'id' => 'dossierpcg66form' ) );
		if( $this->action == 'add' ) {
		}
		else {
			echo '<div>';
			echo $xform->input( 'Dossierpcg66.id', array( 'type' => 'hidden' ) );
			echo '</div>';
		}
		echo '<div>';
		echo $xform->input( 'Dossierpcg66.foyer_id', array( 'type' => 'hidden', 'value' => $foyer_id ) );

		echo '</div>';
	?>

	<div class="aere">

	<fieldset>
		<?php
			echo $default->subform(
				array(
					'Dossierpcg66.etatdossierpcg' => array( 'type' => 'hidden' ),
					'Dossierpcg66.typepdo_id' => array( 'label' => ( __d( 'dossierpcg66', 'Dossierpcg66.typepdo_id', true ) ), 'type' => 'select', 'options' => $typepdo, 'empty' => true ),
					'Dossierpcg66.datereceptionpdo' => array( 'label' =>  ( __d( 'dossierpcg66', 'Dossierpcg66.datereceptionpdo', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => false ),
					'Dossierpcg66.originepdo_id' => array( 'label' =>  ( __d( 'dossierpcg66', 'Dossierpcg66.originepdo_id', true ) ), 'type' => 'select', 'options' => $originepdo, 'empty' => true ),
					'Dossierpcg66.orgpayeur' => array( 'label' =>  __d( 'dossierpcg66', 'Dossierpcg66.orgpayeur', true ), 'type' => 'select', 'options' => $orgpayeur, 'empty' => true ),
					'Dossierpcg66.serviceinstructeur_id' => array( 'label' =>  ( __d( 'dossierpcg66', 'Dossierpcg66.serviceinstructeur_id', true ) ), 'type' => 'select', 'options' => $serviceinstructeur, 'empty' => true )
				),
				array(
					'domain' => $domain,
					'options' => $options
				)
			);
		?>
	</fieldset>
<fieldset>
	<legend><?php echo required( $default2->label( 'Dossierpcg66.haspiecejointe' ) );?></legend>

	<?php echo $form->input( 'Dossierpcg66.haspiecejointe', array( 'type' => 'radio', 'options' => $options['Dossierpcg66']['haspiecejointe'], 'legend' => false, 'fieldset' => false ) );?>
	<fieldset id="filecontainer-piece" class="noborder invisible">
		<?php
			echo $fileuploader->create(
				$fichiers,
				Router::url( array( 'action' => 'ajaxfileupload' ), true )
			);
		?>
	</fieldset>
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
	} );
</script>

	<fieldset>
		<?php
			echo $default->subform(
				array(
					'Dossierpcg66.user_id' => array( 'label' =>  ( __d( 'dossierpcg66', 'Dossierpcg66.user_id', true ) ), 'type' => 'select', 'options' => $gestionnaire ),
// 					'Dossierpcg66.iscomplet' => array( 'legend' => false, 'type' => 'radio', 'options' => $options['Dossierpcg66']['iscomplet'] )
				),
				array(
					'domain' => $domain,
					'options' => $options
				)
			);
		?>
	</fieldset>

	<?php if ($this->action=='edit'):?>
		<fieldset>
			<legend>Personnes concernées</legend>

			<?php if( $permissions->check( 'personnespcgs66', 'add' ) ):?>
				<ul class="actionMenu">
					<?php
						echo '<li class="add">'.$xhtml->addLink(
							'Ajouter une personne',
							array( 'controller' => 'personnespcgs66', 'action' => 'add', $dossierpcg66_id ),
							( $etatdossierpcg != 'attaffect' || $permissions->check( 'personnespcgs66', 'add' ) != "1" )
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
									$differentesSituations .= $xhtml->tag( 'h3', '' ).'<ul><li>'.$situation.'</li></ul>';
								}
							}
// debug($personnepcg66);
							$blockWithoutTraitement = true;
							$nbTraitement = count( Set::extract( $personnepcg66, '/Traitementpcg66/id' ) );
							if( !empty( $nbTraitement ) ){
								$blockWithoutTraitement = true;
							}
							else{
								$blockWithoutTraitement = false;
							}
// 	debug($nbTraitement);
							//Liste des différents statuts de la personne
							$listeStatuts = Set::extract( $personnepcg66, '/Statutpdo/libelle' );
							$differentsStatuts = '';
							foreach( $listeStatuts as $key => $statut ) {
								if( !empty( $statut ) ) {
									$differentsStatuts .= $xhtml->tag( 'h3', '' ).'<ul><li>'.$statut.'</li></ul>';
								}
							}
							echo $xhtml->tableCells(
								array(
									h( Set::classicExtract( $personnepcg66, 'Personne.qual' ).' '.Set::classicExtract( $personnepcg66, 'Personne.nom' ).' '.Set::classicExtract( $personnepcg66, 'Personne.prenom' ) ),
									$differentesSituations,
									$differentsStatuts,
									$xhtml->viewLink(
										'Voir la personne concernée',
										array( 'controller' => 'personnespcgs66', 'action' => 'view', $personnepcg66['Personnepcg66']['id'] ),
										$permissions->check( 'personnespcgs66', 'view' )
									),
									$xhtml->editLink(
										'Editer la personne concernée',
										array( 'controller' => 'personnespcgs66', 'action' => 'edit', $personnepcg66['Personnepcg66']['id'] ),
										$permissions->check( 'personnespcgs66', 'edit' )
									),
									$xhtml->treatmentLink(
										'Traitements pour la personne',
										array( 'controller' => 'traitementspcgs66', 'action' => 'index', $personnepcg66['Personnepcg66']['personne_id'], $personnepcg66['Personnepcg66']['dossierpcg66_id'] ),
										$permissions->check( 'traitementspcgs66', 'index' )
									),
// 									$xhtml->propositionDecisionLink(
// 										'Emettre une proposition de décision',
// 										array( 'controller' => 'decisionspersonnespcgs66', 'action' => 'index', $personnepcg66['Personnepcg66']['id'] ),
// 										( $blockWithoutTraitement && $permissions->check( 'decisionspersonnespcgs66', 'index' ) )
// 									),
									$xhtml->deleteLink(
										'Supprimer la personne',
										array( 'controller' => 'personnespcgs66', 'action' => 'delete', $personnepcg66['Personnepcg66']['id'] ),
										$permissions->check( 'personnespcgs66', 'delete' )
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

// debug($decisionsdossierspcgs66);
			echo $html->tag(
				'fieldset',
				$html->tag(
					'legend',
					'Propositions de décision niveau foyer'
				).
				$default2->index(
					$decisionsdossierspcgs66,
					array(
						'Decisionpdo.libelle'=> array( 'label' =>  'Proposition du technicien : ' ),
						'Decisiondossierpcg66.avistechnique',
// 						'Decisiondossierpcg66.commentaireavistechnique',
						'Decisiondossierpcg66.dateavistechnique',
						'Decisiondossierpcg66.validationproposition',
// 						'Decisiondossierpcg66.commentairevalidation',
						'Decisiondossierpcg66.datevalidation'
					),
					array(
						'actions' => array(
							'Decisionsdossierspcgs66::view',
							'Decisionsdossierspcgs66::edit' => array(
								'disabled' => ( '
									\'#Decisiondossierpcg66.id#\' != '.$lastDecisionId.'
// 									|| (
// 										 \''.$etatdossierpcg.'\' != \'instrencours\'
// 										&& \''.$etatdossierpcg.'\' != \'attval\'
// 										&& \''.$etatdossierpcg.'\' != \'attpj\'
// 										&& \''.$etatdossierpcg.'\' != \'dossiertraite\'
// 									)
									|| '.$permissions->check( 'decisionsdossierspcgs66', 'edit' ).' != \'1\'
								' )
							),///FIXME: à remettre en fonction de ce qu'il y aura à imprimer
							'Decisionsdossierspcgs66::print' => array(
								'label' => 'Imprimer',
								'url' => array( 'controller' => 'decisionsdossierspcgs66', 'action'=>'decisionproposition' ),
								'disabled' => (
									/*(
										'\'#Decisiondossierpcg66.id#\' == '.$lastDecisionId
										&& (
											$etatdossierpcg != 'dossiertraite'
											|| $etatdossierpcg != 'attpj'
										)
									)
									||*/ ( $permissions->check( 'decisionsdossierspcgs66', 'print' ) != "1" )
								)
							),
							'Decisionsdossierspcgs66::transmettreop' => array(
								'label' => 'Transmettre OP',
								'url' => array(  'controller' => 'decisionsdossierspcgs66', 'action' => 'transmitop' ),
								'disabled' =>  '( "'.$permissions->check( 'decisionsdossierspcgs66', 'transmitop' ).'" != "1" ) || ( "#Decisiondossierpcg66.validationproposition#" == "N" )'
							),
							'Decisionsdossierspcgs66::delete' => array(
								'label' => 'Supprimer',
								'url' => array(  'controller' => 'decisionsdossierspcgs66', 'action' => 'delete' ),
								'disabled' =>  '( "'.$permissions->check( 'decisionsdossierspcgs66', 'delete' ).'" != "1" )'
							)
						),
						'add' => array(
							'Decisiondossierpcg66.add' => array( 'controller'=>'decisionsdossierspcgs66', 'action'=>'add', $dossierpcg66_id ),
							'disabled' => ( /*$etatdossierpcg != 'attavistech' || */$permissions->check( 'decisionsdossierspcgs66', 'add' ) != "1" )
						),
						'options' => $options
					)
				)
			);
		?>
	<?php endif;?>

	<fieldset id="Etatpdo" class="invisible"></fieldset>

	</div>
	<div class="submit">
		<?php echo $form->submit( 'Enregistrer', array( 'div' => false ) ); ?>
		<?php echo $form->button( 'Retour', array( 'type' => 'button', 'onclick'=>"location.replace('".Router::url( '/dossierspcgs66/index/'.$foyer_id, true )."')" ) ); ?>
	</div>

	<?php echo $xform->end();?>
</div>

<div class="clearer"><hr /></div>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		[ $('Dossierpcg66TypepdoId'), $('Dossierpcg66UserId')/*, $('Dossierpcg66IscompletCOM'), $('Dossierpcg66IscompletINC')*/ ].each(function(field) {
			field.observe('change', function(element, value) {
				fieldUpdater();
			});
		});

		fieldUpdater();
	});

	function fieldUpdater() {
		new Ajax.Updater(
			'Etatpdo',
			'<?php echo Router::url( array( "action" => "ajaxetatpdo" ), true ) ?>',
			{
				asynchronous:true,
				evalScripts:true,
				parameters:
				{
					'typepdo_id' : $F('Dossierpcg66TypepdoId'),
					'user_id' : $F('Dossierpcg66UserId'),
					'decisionpdo_id' : <?php if ( isset( $this->data['Decisiondossierpcg66']['decisionpdo_id'] ) ) { ?> $F('Decisiondossierpcg66DecisionpdoId') <?php } else { echo 0; } ?>,
// 					'incomplet' : $F('Dossierpcg66IscompletINC'),
					'dossierpcg66_id' : <?php if ( isset( $this->data['Dossierpcg66']['id'] ) ) { ?> $F('Dossierpcg66Id') <?php } else { echo 0; } ?>
				},
				requestHeaders:['X-Update', 'Etatpdo']
			}
		);
	}
</script>