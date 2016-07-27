<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}

	echo $this->Default3->titleForLayout( array(), array( 'msgid' => __m( '/Structuresreferentes/index/:heading' ) ) );

	$searchFormId = 'StructurereferenteIndexForm';
	$actions =  array(
		'/Structuresreferentes/add' => array(
		),
		'/Structuresreferentes/index/#toggleform' => array(
			'title' => 'Visibilité formulaire',
			'text' => 'Formulaire',
			'class' => 'search',
			'onclick' => "$( '{$searchFormId}' ).toggle(); return false;"
		)
	);
	echo $this->Default3->actions( $actions );

	echo $this->Default3->form(
		array(
			'Search.Structurereferente.search' => array( 'type' => 'hidden', 'value' => true ),
			'Search.Structurereferente.lib_struc' => array( 'type' => 'text', 'required' => false ),
			'Search.Structurereferente.ville' => array( 'required' => false ),
			'Search.Structurereferente.typeorient_id' => array( 'empty' => true, 'required' => false ),
			'Search.Structurereferente.typestructure' => array( 'empty' => true, 'required' => false ),
			'Search.Structurereferente.actif' => array( 'empty' => true, 'required' => false ),
			'Search.Structurereferente.apre' => array( 'empty' => true, 'required' => false ),
			'Search.Structurereferente.contratengagement' => array( 'empty' => true, 'required' => false ),
			'Search.Structurereferente.cui' => array( 'empty' => true, 'required' => false ),
			'Search.Structurereferente.orientation' => array( 'empty' => true, 'required' => false ),
			'Search.Structurereferente.pdo' => array( 'empty' => true, 'required' => false )
		),
		array(
			'options' => array( 'Search' => $options ),
			'buttons' => array( 'Search', 'Reset' => array( 'type' => 'reset' ) )
		)
	);
	echo $this->Observer->disableFormOnSubmit( $searchFormId );

	if( isset( $results ) ) {
		$this->Default3->DefaultPaginator->options(
			array( 'url' => Hash::flatten( (array)$this->request->data, '__' ) )
		);

		echo $this->Default3->index(
			$results,
			$this->Translator->normalize(
				array(
					'Structurereferente.lib_struc',
					'Structurereferente.num_voie',
					'Structurereferente.type_voie',
					'Structurereferente.nom_voie',
					'Structurereferente.code_postal',
					'Structurereferente.ville',
					'Structurereferente.code_insee',
					'Structurereferente.numtel',
					'Typeorient.lib_type_orient',
					'Structurereferente.actif',
					'Structurereferente.typestructure',
					'/Structuresreferentes/edit/#Structurereferente.id#' => array(
						'title' => false
					),
					'/Structuresreferentes/delete/#Structurereferente.id#' => array(
						'title' => false,
						'confirm' => 'Supprimer la structure référente « #Structurereferente.lib_struc# » ?',
						'disabled' => '0 != "#Structurereferente.has_linkedrecords#"'
					)
				)
			),
			array(
				'options' => $options,
				'format' => $this->element( 'pagination_format', array( 'modelName' => 'Structurereferente' ) )
			)
		);
	}

	echo $this->Default->button(
		'back',
		array(
			'controller' => 'parametrages',
			'action'     => 'index'
		)
	);
?>
<?php if( isset( $results ) ): ?>
<script type="text/javascript">
	//<![CDATA[
	$('<?php echo $searchFormId;?>').toggle();
	//]]>
</script>
<?php endif; ?>