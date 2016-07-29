<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}

	echo $this->Default3->titleForLayout();

	$searchFormId = 'ReferentsIndexForm';
	$actions =  array(
		'/Referents/add' => array(),
		'/Referents/index/#toggleform' => array(
			'title' => 'Visibilité formulaire',
			'text' => 'Formulaire',
			'class' => 'search',
			'onclick' => "$( '{$searchFormId}' ).toggle(); return false;"
		)
	);
	echo $this->Default3->actions( $actions );

	echo $this->Form->create( null, array( 'type' => 'post', 'url' => array( 'controller' => $this->request->params['controller'], 'action' => $this->request->action ), 'id' => $searchFormId ) );

	echo $this->Default3->subform(
		$this->Translator->normalize(
			array(
				'Search.Referent.nom' => array('required' => false),
				'Search.Referent.prenom' => array('required' => false),
				'Search.Referent.fonction' => array('required' => false),
				'Search.Referent.structurereferente_id' => array(
					'label' => 'Structure référente liée',
					'required' => false,
					'type' => ( $this->action == 'index' ? 'select': 'hidden' ),
					'empty' => true
				)
			)
		),
		array(
			'options' => array( 'Search' => $options ),
			'fieldset' => true,
		)
	);
	
	echo $this->Allocataires->blocPagination( array( 'prefix' => 'Search', 'options' => $options ) );
	echo $this->Allocataires->blocScript( array( 'prefix' => 'Search', 'options' => $options ) );
?>
	<div class="submit noprint">
		<?php echo $this->Form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
		<?php echo $this->Form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>
<?php
	echo $this->Form->end();

	if( isset( $referents ) ) {
		$this->Default3->DefaultPaginator->options(
			array( 'url' => Hash::flatten( (array)$this->request->data, '__' ) )
		);

		echo $this->Default3->index(
			$referents,
			$this->Translator->normalize(
				array(
					'Referent.qual',
					'Referent.nom',
					'Referent.prenom',
					'Referent.fonction',
					'Referent.numero_poste',
					'Referent.email',
					'Structurereferente.lib_struc',
					'Referent.actif',
					'/referents/cloturer/#Referent.id#' => array(
						'disabled' => "('#Referent.datecloture#' == '' || '#PersonneReferent.nb_referents_lies#' > 0) === false"
					),
					'/referents/edit/#Referent.id#',
					'/referents/delete/#Referent.id#' => array(
						'disabled' => "('#Referent.has_linkedrecords#') == 1"
					),
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
