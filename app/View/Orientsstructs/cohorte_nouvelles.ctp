<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}

	echo $this->Default3->titleForLayout();

	echo $this->Default3->actions(
			array(
				'/Orientsstructs/cohorte_nouvelles/#toggleform' => array(
					'onclick' => '$(\'OrientsstructsCohorteNouvellesSearchForm\').toggle(); return false;',
					'class' => 'search'
				)
			)
	);
?>

<?php echo $this->Form->create( null, array( 'type' => 'post', 'url' => array( 'action' => $this->request->action ), 'id' => 'OrientsstructsCohorteNouvellesSearchForm', 'class' => (!empty( $this->request->params['named'] ) ? 'folded' : 'unfolded' ) ) ); ?>
<?php
	echo $this->Allocataires->blocDossier(
			array(
				'prefix' => 'Search',
				'options' => $options
			)
	);
	echo $this->Allocataires->blocAdresse( array( 'prefix' => 'Search', 'options' => $options ) );
	echo $this->Allocataires->blocAllocataire(
			array(
				'prefix' => 'Search',
				'options' => $options
			)
	);
?>
<fieldset>
	<legend><?php echo __m( 'Search.Parcours' ); ?></legend>
	<?php
		echo $this->Xform->input( 'Search.Personne.has_dsp', array( 'type' => 'select', 'options' => $options['Personne']['has_dsp'], 'empty' => true, 'label' => __m( 'Search.Personne.has_dsp' ) ) );
		echo $this->Xform->input( 'Search.Orientstruct.propo_algo', array( 'type' => 'select', 'options' => $options['Orientstruct']['propo_algo'], 'empty' => true, 'label' => __m( 'Search.Orientstruct.propo_algo' ) ) )
	?>
</fieldset>
<!--<fieldset>
<?php
	/* if( in_array( $this->action, array( 'preconisationscalculables', 'preconisationsnoncalculables' ) ) ) {
	  $enattente = array( 'Non orienté', 'En attente' );
	  echo $this->Form->input( 'Filtre.enattente', array( 'label' => __( 'Statut de l\'orientation' ), 'type' => 'select', 'options' => array_combine( $enattente, $enattente ), 'empty' => true ) );
	  }

	  if( $this->action != 'preconisationsnoncalculables' ) {
	  if ( Configure::read( 'Cg.departement' ) == 93 && ( in_array( $this->action, array( 'nouvelles', 'enattente', 'preconisationscalculables' ) ) ) ) {
	  echo $this->Form->input( 'Filtre.propo_algo', array( 'label' => __( 'Type de préOrientation' ), 'type' => 'select', 'options' => $modeles, 'empty' => true ) );
	  }
	  else {
	  echo $this->Form->input( 'Filtre.typeorient', array( 'label' => __( 'Type d\'orientation' ), 'type' => 'select', 'options' => $modeles, 'empty' => true ) );
	  if( Configure::read( 'Cg.departement' ) == 93 ) {
	  echo $this->Form->input( 'Filtre.origine', array( 'label' => __d( 'orientstruct', 'Orientstruct.origine' ), 'type' => 'select', 'options' => $options['Orientstruct']['origine'], 'empty' => true ) );
	  }
	  }
	  } */
?>
</fieldset>-->
<?php
	// TODO: Type de préOrientation

	echo $this->Allocataires->blocReferentparcours( array( 'prefix' => 'Search', 'options' => $options ) );
	echo $this->Allocataires->blocPagination( array( 'prefix' => 'Search', 'options' => $options ) );
	echo $this->Allocataires->blocScript( array( 'prefix' => 'Search', 'options' => $options ) );
?>
<div class="submit noprint">
	<?php echo $this->Form->button( 'Rechercher', array( 'type' => 'submit' ) ); ?>
	<?php echo $this->Form->button( 'Réinitialiser', array( 'type' => 'reset' ) ); ?>
</div>
<?php
	echo $this->Form->end();
	echo $this->Observer->disableFormOnSubmit( 'OrientsstructsCohorteNouvellesSearchForm' );
?>

<?php
	if( isset( $results ) ) {
		echo $this->Html->tag( 'h2', 'Résultats de la recherche' );

		// "Pagination" un peu spéciale: on veut simplement le nombre de résultats, pas passer de page en page
		$paging = Hash::get( $this->request->params, 'paging.Personne' );
		$format = 'Nombre de pages: %s - Nombre de résultats: %s.';
		if( Hash::get( $this->request->data, 'Search.Pagination.nombre_total' ) != '1' ) {
			$page = Hash::get( $paging, 'page' );
			$count = Hash::get( $paging, 'count' );
			$limit = Hash::get( $paging, 'limit' );
			if( ( $count > ( $limit * $page ) ) ) {
				$format = 'Nombre de pages: au moins %s - Nombre de résultats: au moins %s.';
			}
		}

		$pagination = $this->Html->tag(
				'p', sprintf(
						$format, $this->Locale->number( $paging['pageCount'] ), $this->Locale->number( $paging['count'] )
				), array( 'class' => 'pagination counter' )
		);

		echo $pagination;
		echo $this->Xform->create( null, array(
			'id' => 'OrientsstructsCohorteNouvellesCohorteForm',
//				'url' => Router::url( array( 'controller' => $controller, 'action' => $action ), true )
				)
		);

		echo $this->Default3->configuredCohorte(
				$results, array( 'paginate' => false ) + $configuredCohorteParams
		);

		echo $this->Xform->end( 'Save' );
		echo $this->Observer->disableFormOnSubmit( 'OrientsstructsCohorteNouvellesCohorteForm' );
		echo $pagination;

		if( !empty( $results ) ) {
			echo $this->Form->button( 'Tout valider', array( 'onclick' => "return toutChoisir( $( 'OrientsstructsCohorteNouvellesCohorteForm' ).getInputs( 'radio' ), 'Orienté', true );" ) );
			echo $this->Form->button( 'Tout mettre en attente', array( 'onclick' => "return toutChoisir( $( 'OrientsstructsCohorteNouvellesCohorteForm' ).getInputs( 'radio' ), 'En attente', true );" ) );
		}

		//echo $this->element( 'search_footer', array( 'modelName' => 'Personne', 'url' => array( 'action' => 'exportcsv' ) ) );
	}
?>
<?php if( isset( $results ) ): ?>
		<script type="text/javascript">
			var structAuto = new Array();
		<?php foreach( $options['structuresAutomatiques'] as $typeId => $structureAutomatique ): ?>
				if (structAuto["<?php echo $typeId; ?>"] == undefined) {
					structAuto["<?php echo $typeId; ?>"] = new Array();
				}
			<?php foreach( $structureAutomatique as $codeInsee => $structure ): ?>
					structAuto["<?php echo $typeId; ?>"]["<?php echo $codeInsee; ?>"] = "<?php echo $structure; ?>";
			<?php endforeach; ?>
		<?php endforeach; ?>

			function selectStructure(index) {
				var typeOrient = $F('Cohorte' + index + 'OrientstructTypeorientId');
				var codeinsee = $F('Cohorte' + index + 'AdresseNumcom');
				if ((structAuto[typeOrient] != undefined) && (structAuto[typeOrient][codeinsee] != undefined)) {
					$('Cohorte' + index + 'OrientstructStructurereferenteId').value = structAuto[typeOrient][codeinsee];
				}
			}

			document.observe("dom:loaded", function () {
				var indexes = new Array(<?php echo "'".implode( "', '", array_keys( $results ) )."'"; ?>);

				indexes.each(function (index) {
					/* Dépendance des deux champs "select" */
					dependantSelect(
							'Cohorte' + index + 'OrientstructStructurereferenteId',
							'Cohorte' + index + 'OrientstructTypeorientId'
							);

					/* Structures automatiques suivant le code Insée */
					// Initialisation
					if ($F('Cohorte' + index + 'OrientstructStructurereferenteId') == '') {
						selectStructure(index);
					}

					// Traquer les changements
					Event.observe($('Cohorte' + index + 'OrientstructTypeorientId'), 'change', function () {
						selectStructure(index);
					});
				});
			});
		</script>
	<?php endif; ?>