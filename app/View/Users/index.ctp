<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}

	echo $this->Default3->titleForLayout( array(), array( 'msgid' => __m( '/Users/index/:heading' ) ) );

	$searchFormId = 'UserIndexForm';
	$actions =  array(
		'/Users/add' => array(
		),
		'/Users/index/#toggleform' => array(
			'title' => 'Visibilité formulaire',
			'text' => 'Formulaire',
			'class' => 'search',
			'onclick' => "$( '{$searchFormId}' ).toggle(); return false;"
		)
	);
	echo $this->Default3->actions( $actions );

	$jetonsEnabled = ( Configure::read( 'Jetons2.disabled' ) ? '0' : '1' );
	$jetonsfonctionsEnabled = ( Configure::read( 'Jetonsfonctions2.disabled' ) ? '0' : '1' );

	$search = array(
		'Search.User.search' => array( 'type' => 'hidden', 'value' => true, 'label' => false ),
		'Search.User.username' => array( 'required' => false ),
		'Search.User.nom' => array( 'required' => false ),
		'Search.User.prenom' => array( 'type' => 'text', 'required' => false ),
		'Search.User.group_id' => array( 'options' => $options['Groups'], 'empty' => true, 'required' => false ),
		'Search.User.serviceinstructeur_id' => array( 'options' => $options['Serviceinstructeur'], 'empty' => true, 'required' => false ),
		'Search.User.type' => array( 'options' => $options['User']['type'], 'empty' => true, 'required' => false ),
		'Search.User.communautesr_id' => array( 'options' => $options['communautessrs'], 'empty' => true, 'required' => false ),
		'Search.User.structurereferente_id' => array( 'empty' => true, 'required' => false ),
		'Search.User.referent_id' => array( 'empty' => true, 'required' => false ),
		'Search.User.has_connections' => array( 'empty' => true, 'required' => false )
	);

	if( $jetonsEnabled ) {
		$search['Search.User.has_jetons'] = array( 'empty' => true, 'required' => false );
	}

	if( $jetonsfonctionsEnabled ) {
		$search['Search.User.has_jetonsfonctions'] = array( 'empty' => true, 'required' => false );
	}

	echo $this->Default3->form(
		$this->Translator->normalize(
			$search
		),
		array(
			'id' => $searchFormId,
			'options' => array( 'Search' => $options ),
			'buttons' => array( 'Search', 'Reset' => array( 'type' => 'reset' ) )
		)
	);
	echo $this->Observer->disableFormOnSubmit( $searchFormId );
	echo $this->Observer->dependantSelect(
		array(
			'Search.User.structurereferente_id' => 'Search.User.referent_id'
		)
	);
	echo $this->Observer->disableFieldsOnValue(
		'Search.User.communautesr_id',
		array( 'Search.User.structurereferente_id', 'Search.User.referent_id' ),
		array( '', null ),
		false
	);

	echo $this->Observer->disableFieldsOnValue(
		'Search.User.structurereferente_id',
		'Search.User.communautesr_id',
		array( '', null ),
		false
	);

	if( isset( $results ) ) {
		$this->Default3->DefaultPaginator->options(
			array( 'url' => Hash::flatten( (array)$this->request->data, '__' ) )
		);

		$connectedUserId = $this->Session->read( 'Auth.User.id' );

		echo $this->Default3->index(
			$results,
			$this->Translator->normalize(
				array(
					'User.nom',
					'User.prenom',
					'User.username',
					'User.date_deb_hab',
					'User.date_fin_hab',
					'User.type',
					'Group.name',
					'Serviceinstructeur.lib_service',
					'User.has_connections' => array( 'type' => 'boolean' ),
					'User.has_jetons' => array(
						'condition' => $jetonsEnabled,
						'condition_group' => 'jetons',
						'type' => 'boolean'
					),
					'User.has_jetonsfonctions' => array(
						'condition' => $jetonsfonctionsEnabled,
						'condition_group' => 'jetonsfonctions',
						'type' => 'boolean'
					),
					'/Users/edit/#User.id#' => array(
						'title' => false
					),
					'/Users/delete_jetons/#User.id#' => array(
						'condition' => $jetonsEnabled,
						'condition_group' => 'jetons',
						'title' => false,
						'confirm' => true,
						'disabled' => '0 == "#User.has_jetons#"'
					),
					'/Users/delete_jetonsfonctions/#User.id#' => array(
						'condition' => $jetonsfonctionsEnabled,
						'condition_group' => 'jetonsfonctions',
						'title' => false,
						'confirm' => true,
						'disabled' => '0 == "#User.has_jetonsfonctions#"'
					),
					'/Users/force_logout/#User.id#' => array(
						'title' => false,
						'confirm' => true,
						'disabled' => '( 0 == "#User.has_connections#" || "'.$connectedUserId.'" == "#User.id#" )'
					),
					'/Users/delete/#User.id#' => array(
						'title' => false,
						'confirm' => true,
						'disabled' => '( 0 != "#User.has_linkedrecords#" || "'.$connectedUserId.'" == "#User.id#" )'
					)
				)
			),
			array(
				'options' => $options,
				'format' => $this->element( 'pagination_format', array( 'modelName' => 'User' ) ),
				'innerTable' => $this->Translator->normalize(
					array(
						'User.date_naissance',
						'User.numtel'
					)
				)
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