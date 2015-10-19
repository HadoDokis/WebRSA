<?php
	// $actions
	$actions = isset( $actions ) ? (array)$actions : array();
	// $searchFormId
	$searchFormId = isset( $searchFormId ) ? $searchFormId : Inflector::camelize( "{$this->request->params['controller']}_{$this->request->params['action']}_form" );
	// $custom
	$custom = isset( $custom ) ? $custom : '';
	// $url
	// $exportcsv
	$exportcsv = isset( $exportcsv ) ? $exportcsv : array( 'controller' => $this->request->params['controller'], 'action' => 'exportcsv' );
	// $css
	// $scripts
	// $modelName
	$modelName = isset( $modelName ) ? $modelName : Inflector::classify( $this->request->params['controller'] );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}

	echo $this->Default3->titleForLayout();

	$actions['/'.Inflector::camelize( $this->request->params['controller'] ).'/'.$this->request->params['action'].'/#toggleform'] =  array(
		'class' => 'search',
		'onclick' => "$( '{$searchFormId}' ).toggle(); return false;"
	);
	echo $this->Default3->actions( $actions );

	echo $this->Form->create( null, array( 'type' => 'post', 'url' => array( 'controller' => $this->request->params['controller'], 'action' => $this->request->action ), 'id' => $searchFormId, 'class' => ( isset( $results ) ? 'folded' : 'unfolded' ) ) );

	echo $this->Allocataires->blocDossier( array( 'prefix' => 'Search', 'options' => $options ) );
	echo $this->Allocataires->blocAdresse( array( 'prefix' => 'Search', 'options' => $options ) );
	echo $this->Allocataires->blocAllocataire( array( 'prefix' => 'Search', 'options' => $options ) );
	echo $custom;
	echo $this->Allocataires->blocReferentparcours( array( 'prefix' => 'Search', 'options' => $options ) );
	echo $this->Allocataires->blocPagination( array( 'prefix' => 'Search', 'options' => $options ) );
	echo $this->Allocataires->blocScript( array( 'prefix' => 'Search', 'options' => $options ) );
?>
	<div class="submit noprint">
		<?php echo $this->Form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
		<?php echo $this->Form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>
<?php
	echo $this->Form->end();
	echo $this->Observer->disableFormOnSubmit( $searchFormId );

	if( isset( $results ) ) {
		echo $this->Html->tag( 'h2', 'Résultats de la recherche' );

		echo $this->Default3->configuredindex(
			$results,
			array(
				'format' => $this->element( 'pagination_format', array( 'modelName' => $modelName ) ),
				'options' => $options
			)
		);

		echo $this->element( 'search_footer', array( 'url' => $exportcsv, 'modelName' => $modelName ) );
	}
?>