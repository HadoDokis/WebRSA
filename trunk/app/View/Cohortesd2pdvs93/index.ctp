<?php
	echo $this->Default3->titleForLayout();

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	$searchFormOptions = array( 'domain' => 'search_plugin' );

	echo $this->Default3->actions(
		array(
			'/Cohortesd2pdvs93/index/#toggleform' => array(
				'onclick' => '$(\'Cohortesd2pdvs93IndexForm\').toggle(); return false;'
			),
		)
	);

	if( isset( $this->request->data['Search'] ) && !empty( $this->request->params['named'] ) ) {
		$out = "document.observe( 'dom:loaded', function() { \$('Cohortesd2pdvs93IndexForm').hide(); } );";
		echo $this->Html->scriptBlock( $out );
	}

	echo $this->Xform->create( null, array( 'id' => 'Cohortesd2pdvs93IndexForm' ) );

	// Filtres concernant le dossier
	echo $this->Search->blocDossier( $options['etatdosrsa'], 'Search' );

	echo $this->Search->blocAdresse( $options['mesCodesInsee'], $options['cantons'], 'Search' );

	// Filtres concernant l'allocataire
	echo '<fieldset>';
	echo sprintf( '<legend>%s</legend>', __d( 'cohortesd2pdvs93', 'Search.Personne' ) );
	echo $this->Xform->input( 'Search.Dossier.dernier', array( 'type' => 'checkbox', 'domain' => 'cohortesd2pdvs93' ) );
	echo $this->Search->blocAllocataire( array(), 'Search' );
	echo $this->Search->toppersdrodevorsa( $options['Calculdroitrsa']['toppersdrodevorsa'], 'Search.Calculdroitrsa.toppersdrodevorsa' );
//	echo $this->SearchForm->dependantDateRange( 'Search.Personne.dtnai', $searchFormOptions );
	echo '</fieldset>';

	// Filtres concernant l'accompagnement
	echo '<fieldset>';
	echo sprintf( '<legend>%s</legend>', __d( 'cohortesd2pdvs93', 'Search.Questionnaired2pdv93' ) );
	echo $this->Xform->input( 'Search.Questionnaired1pdv93.annee', array( 'options' => $options['Questionnaired1']['annee'], 'domain' => 'cohortesd2pdvs93' ) );
	echo $this->Xform->input( 'Search.Rendezvous.structurereferente_id', array( 'options' => $options['Rendezvous']['structurereferente_id'], 'empty' => true, 'domain' => 'cohortesd2pdvs93' ) );
	echo $this->Xform->input( 'Search.Questionnaired2pdv93.exists', array( 'type' => 'checkbox', 'domain' => 'cohortesd2pdvs93' ) );
	echo '</fieldset>';

	echo $this->Search->paginationNombretotal( 'Search.Pagination.nombre_total' );

	echo $this->Xform->end( 'Search' );


	if( isset( $results ) ) {
		$this->Default3->DefaultPaginator->options(
			array( 'url' => Hash::flatten( (array)$this->request->data, '__' ) )
		);

		echo $this->Default3->index(
			$results,
			array(
				'Personne.nir',
				'Personne.nom',
				'Personne.prenom',
				'Personne.numfixe',
				'Personne.numport',
				'Rendezvous.daterdv',
				'Structurereferente.lib_struc',
				'Questionnaired2pdv93.created' => array( 'type' => 'date' ),
				// 'Dossier.locked' => array( 'type' => 'boolean', 'sort' => false ),
				'/Cohortesd2pdvs93/ajaxadd/#Personne.id#' => array( 'onclick' => 'ajax#Personne.id#();return false;', 'class' => 'ajax' ),
				'/Questionnairesd2pdvs93/index/#Personne.id#' => array( 'class' => 'external' ),
			),
			array(
				'options' => $options
			)
		);
	}
?>
<?php if( isset( $results ) ): ?>
	<?php
		echo $this->element(
			'modalbox',
			array(
				'modalid' => 'Questionnaired2pdv93ModalForm',
				'modalcontent' => null,
				'modalmessage' => null,
				'modalclose' => false
			)
		);
	?>
	<script type="text/javascript">
	<?php
		foreach( $results as $result ) {
			if( empty( $result['Questionnaired2pdv93']['id'] ) ) {
				$url = array( 'controller' => 'questionnairesd2pdvs93', 'action' => 'add', $result['Personne']['id'] );
			}
			else {
				$url = array( 'controller' => 'questionnairesd2pdvs93', 'action' => 'edit', $result['Questionnaired2pdv93']['id'] );
			}

			$remoteFunction = $this->Ajax->remoteFunction(
				array(
					'url' => $url,
					'update' => 'popup-content1',
					'evalScripts' => true
				)
			);

			echo "function ajax{$result['Personne']['id']}() { {$remoteFunction}; $( 'Questionnaired2pdv93ModalForm' ).show(); }\n";
		}
	?>
	</script>
<?php endif; ?>