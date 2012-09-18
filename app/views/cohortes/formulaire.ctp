<h1><?php echo $this->pageTitle = $pageTitle;?></h1>
<?php
	if( !empty( $this->data ) ) {
		echo '<ul class="actionMenu"><li>'.$xhtml->link(
			$xhtml->image(
				'icons/application_form_magnify.png',
				array( 'alt' => '' )
			).' Formulaire',
			'#',
			array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Filtre' ).toggle(); return false;" )
		).'</li></ul>';
	}
?>

<?php if( isset( $cohorte ) ):?>
	<?php
		if( Configure::read( 'debug' ) > 0 ) {
			echo $javascript->link( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
		}
	?>
	<script type="text/javascript">
		var structAuto = new Array();
		<?php foreach( $structuresAutomatiques as $typeId => $structureAutomatique ):?>
				if( structAuto["<?php echo $typeId;?>"] == undefined ) { structAuto["<?php echo $typeId;?>"] = new Array(); }
				<?php foreach( $structureAutomatique as $codeInsee => $structure ):?>
					structAuto["<?php echo $typeId;?>"]["<?php echo $codeInsee;?>"] = "<?php echo $structure;?>";
				<?php endforeach;?>
		<?php endforeach;?>

		function selectStructure( index ) {
			var typeOrient = $F( 'Orientstruct' + index + 'TypeorientId' );
			var codeinsee = $F( 'Orientstruct' + index + 'Codeinsee' );
			if( ( structAuto[typeOrient] != undefined ) && ( structAuto[typeOrient][codeinsee] != undefined ) ) {
				$( 'Orientstruct' + index + 'StructurereferenteId' ).value = structAuto[typeOrient][codeinsee];
			}
		}

		document.observe("dom:loaded", function() {
			var indexes = new Array( <?php echo "'".implode( "', '", array_keys( $cohorte ) )."'";?> );
			indexes.each( function( index ) {
				/* Dépendance des deux champs "select" */
				dependantSelect( 'Orientstruct' + index + 'StructurereferenteId', 'Orientstruct' + index + 'TypeorientId' );

				/* Structures automatiques suivant le code Insée */
				// Initialisation
				if( $F( 'Orientstruct' + index + 'StructurereferenteId' ) == '' ) {
					selectStructure( index );
				}

				// Traquer les changements
				Event.observe( $( 'Orientstruct' + index + 'TypeorientId' ), 'change', function() {
					selectStructure( index );
				} );
			} );
		});
	</script>
<?php endif;?>

<?php require_once( 'filtre.ctp' );?>

<?php if( !empty( $this->data ) && $formSent ):?>
	<?php if( empty( $cohorte ) ):?>
		<p class="notice">Aucune demande dans la cohorte.</p>
	<?php else:?>
		<?php
			$formatPagination = 'Nombre de pages: %s - Nombre de résultats: %s.';
			if( isset( $this->data['Filtre']['paginationNombreTotal'] ) && !$this->data['Filtre']['paginationNombreTotal'] ) {
				$page = Set::classicExtract( $this->params, "paging.Personne.page" );
				$count = Set::classicExtract( $this->params, "paging.Personne.count" );
				$limit = Set::classicExtract( $this->params, "paging.Personne.options.limit" );
				if( ( $count > ( $limit * $page ) ) ) {
					$formatPagination = 'Nombre de pages: au moins %s - Nombre de résultats: au moins %s.';
				}
			}
		?>
		<p><?php echo sprintf( $formatPagination, $locale->number( $this->params['paging']['Personne']['pageCount'] ), $locale->number( $this->params['paging']['Personne']['count'] ) );?></p>
		<?php echo $form->create( 'NouvellesDemandes', array( 'url'=> Router::url( null, true ) ) );?>
		<?php
			foreach( Set::flatten( $filtre ) as $key => $value ) {
				echo '<div>'.$form->input( $key, array( 'type' => 'hidden', 'value' => $value, 'id' => 'FiltreBas'.Inflector::camelize( str_replace( '.', '_', $key ) ) ) ).'</div>';
			}
			$typesorientsNamesToIds = array_flip( $typesOrient );
		?>
		<table class="tooltips">
			<thead>
				<tr>
					<th>Commune</th>
					<th>Date demande</th>
					<th>Présence DSP</th>
					<th>Nom prenom</th>
					<th>Type de service instructeur</th>
					<th>PréOrientation</th>
					<th class="action">Orientation</th>
					<th class="action">Structure</th>
					<th class="action">Décision</th>
					<th>Statut</th>
					<?php if( in_array( $this->action, array( 'nouvelles', 'enattente', 'preconisationscalculables', 'preconisationsnoncalculables' ) ) ):?><th class="action">Détails</th><?php endif;?>
					<th class="innerTableHeader">Informations complémentaires</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $cohorte as $index => $personne ):?>
					<?php
						$innerTable = '<table id="innerTable'.$index.'" class="innerTable">
							<tbody>
								<tr>
									<th>N° de dossier</th>
									<td>'.h( $personne['Dossier']['numdemrsa'] ).'</td>
								</tr>
								<tr>
									<th>Date ouverture de droit</th>
									<td>'.h( date_short( $personne['Dossier']['dtdemrsa'] ) ).'</td>
								</tr>
								<tr>
									<th>Date naissance</th>
									<td>'.h( date_short( $personne['Personne']['dtnai'] ) ).'</td>
								</tr>
								<tr>
									<th>Numéro CAF</th>
									<td>'.h( $personne['Dossier']['matricule'] ).'</td>
								</tr>
								<tr>
									<th>NIR</th>
									<td>'.h( $personne['Personne']['nir'] ).'</td>
								</tr>
								<tr>
									<th>Code postal</th>
									<td>'.h( $personne['Adresse']['codepos'] ).'</td>
								</tr>
								<tr>
									<th>Canton</th>
									<td>'.h( $personne['Adresse']['canton'] ).'</td>
								</tr>
								<tr>
									<th>Date de fin de droit</th>
									<td>'.h( $personne['Situationdossierrsa']['dtclorsa'] ).'</td>
								</tr>
								<tr>
									<th>Motif de fin de droit</th>
									<td>'.h( $personne['Situationdossierrsa']['moticlorsa'] ).'</td>
								</tr>
								<tr>
									<th>Rôle</th>
									<td>'.h( $rolepers[$personne['Prestation']['rolepers']] ).'</td>
								</tr>
								<tr>
									<th>État du dossier</th>
									<td>'.h( value( $etatdosrsa, $personne['Situationdossierrsa']['etatdosrsa'] ) ).'</td>
								</tr>
							</tbody>
						</table>';
						$typeorient_id = Set::extract( $this->data, 'Orientstruct.'.$index.'.typeorient_id' );
						$structurereferente_id = ( !empty( $typeorient_id ) ? $typeorient_id.'_'.preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', Set::extract( $this->data, 'Orientstruct.'.$index.'.structurereferente_id' ) ) : null );
						$statut_orient = Set::extract( $this->data, 'Orientstruct.'.$index.'.statut_orient' );
/*
						if( in_array( $this->action, array( 'nouvelles', 'enattente', 'preconisationscalculables', 'preconisationsnoncalculables' ) ) ) {
							$personne['Orientstruct']['propo_algo'] = @$typesorientsNamesToIds[$personne['Preorientation']['name']];
						}*/

						$tableCells = array(
							h( $personne['Adresse']['locaadr'] ),
							h( date_short( $personne['Dossier']['dtdemrsa'] ) ),
							h( $personne['Dsp']['id'] ? 'Oui' : 'Non' ),
							h( $personne['Personne']['nom'].' '.$personne['Personne']['prenom'] ),
							h( isset( $typeserins[Set::classicExtract( $personne, 'Suiviinstruction.typeserins')] ) ? $typeserins[Set::classicExtract( $personne, 'Suiviinstruction.typeserins')] : '' ),
							h( Set::enum( $personne['Orientstruct']['propo_algo'], $typesOrient ) ).
							$form->input( 'Orientstruct.'.$index.'.propo_algo', array( 'label' => false, 'type' => 'hidden', 'value' => $personne['Orientstruct']['propo_algo'] ) ).
							$form->input( 'Orientstruct.'.$index.'.origine', array( 'label' => false, 'type' => 'hidden', 'value' => 'cohorte' ) ).
							/* FIXME -> id unset ? */
							$form->input( 'Orientstruct.'.$index.'.id', array( 'label' => false, 'type' => 'hidden', 'value' => $personne['Orientstruct']['id'] ) ).
							$form->input( 'Orientstruct.'.$index.'.dossier_id', array( 'label' => false, 'type' => 'hidden', 'value' => $personne['Foyer']['dossier_id'] ) ).
							$form->input( 'Orientstruct.'.$index.'.codeinsee', array( 'label' => false, 'type' => 'hidden', 'value' => $personne['Adresse']['numcomptt'] ) ).
							$form->input( 'Orientstruct.'.$index.'.personne_id', array( 'label' => false, 'type' => 'hidden', 'value' => $personne['Personne']['id'] ) ),
							$form->input( 'Orientstruct.'.$index.'.typeorient_id', array( 'label' => false, 'type' => 'select', 'options' => $typesOrient, 'value' => ( !empty( $typeorient_id ) ? $typeorient_id : $personne['Orientstruct']['propo_algo'] ), 'empty' => true ) ),
							$form->input( 'Orientstruct.'.$index.'.structurereferente_id', array( 'label' => false, 'type' => 'select', 'options' => $structuresReferentes, 'empty' => true, 'value' => ( !empty( $structurereferente_id ) ? $structurereferente_id : $personne['Orientstruct']['structurereferente_id'] ) ) ),
							$form->input( 'Orientstruct.'.$index.'.statut_orient', array( 'label' => false, 'div' => false, 'legend' => false, 'type' => 'radio', 'options' => array( 'Orienté' => 'A valider', 'En attente' => 'En attente' ), 'value' => ( !empty( $statut_orient ) ? $statut_orient : 'Orienté' ) ) ),
							h( $personne['Dossier']['statut'] ),
						);

						if( in_array( $this->action, array( 'nouvelles', 'enattente', 'preconisationscalculables', 'preconisationsnoncalculables' ) ) ) {
							$tableCells[] = array(
								$xhtml->link(
									'Voir',
									array(
										'controller' => 'dossiers',
										'action' => 'view',
										$personne['Dossier']['id']
									),
									array(
										'class' => 'external',
										'title' => "Accéder aux informations de {$personne['Personne']['nom']} {$personne['Personne']['prenom']}"
									)
								),
								array( 'class' => 'action button view' )
							);
						}

						$tableCells[] = array( $innerTable, array( 'class' => 'innerTableCell' ) );

						echo $xhtml->tableCells(
							$tableCells,
							array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
							array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
						);

					?>
				<?php endforeach;?>
			</tbody>
		</table>
		<?php echo $form->submit( 'Validation de la liste' );?>
		<?php echo $form->end();?>
	<?php endif;?>
<?php endif;?>
