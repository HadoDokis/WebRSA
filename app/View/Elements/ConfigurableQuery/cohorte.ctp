<?php
	// $actions
	$actions = isset( $actions ) ? (array)$actions : array();
	// $searchFormId
	$searchFormId = isset( $searchFormId ) ? $searchFormId : Inflector::camelize( "{$this->request->params['controller']}_{$this->request->params['action']}_form" );
	// $custom
	$custom = isset( $custom ) ? $custom : '';
	// $custom_beginning
	$custom_beginning = isset( $custom_beginning ) ? $custom_beginning : '';
	// $url
	// $exportcsv
	$exportcsv = isset( $exportcsv ) ? $exportcsv : array( 'controller' => $this->request->params['controller'], 'action' => 'exportcsv' );
	// $css
	// $scripts
	// $modelName
	$modelName = isset( $modelName ) ? $modelName : Inflector::classify( $this->request->params['controller'] );

	// $cohorteFormId
	$cohorteFormId = isset( $cohorteFormId ) ? $cohorteFormId : Inflector::camelize( "{$this->request->params['controller']}_{$this->request->params['action']}_cohorte" );

	// $tableClass
	$configuredCohorteParams = isset($configuredCohorteParams) ? $configuredCohorteParams : array();
	$tableClass = isset( $tableClass ) ? $tableClass : Hash::get($configuredCohorteParams, 'class');
	$configuredCohorteParams['class'] = $tableClass;

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js', 'cake.prototype.js' ) );
	}

	echo $this->Default3->titleForLayout();

	$actions['/'.Inflector::camelize( $this->request->params['controller'] ).'/'.$this->request->params['action'].'/#toggleform'] =  array(
		'class' => 'search',
		'onclick' => "$( '{$searchFormId}' ).toggle(); return false;"
	);
	echo $this->Default3->actions( $actions );

	echo $this->Form->create( null, array( 'type' => 'post', 'url' => array( 'controller' => $this->request->params['controller'], 'action' => $this->request->action ), 'id' => $searchFormId, 'class' => ( isset( $results ) ? 'folded' : 'unfolded' ) ) );

	echo $custom_beginning;
	echo $this->Allocataires->blocDossier( array( 'prefix' => 'Search', 'options' => $options ) );
	echo $this->Allocataires->blocAdresse( array( 'prefix' => 'Search', 'options' => $options ) );
	echo $this->Allocataires->blocAllocataire( array( 'prefix' => 'Search', 'options' => $options ) );
	echo $custom;
	echo $this->Allocataires->blocHave( array( 'prefix' => 'Search', 'options' => $options ) );
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

		echo $this->Xform->create( null, array( 'id' => $cohorteFormId ) );

		echo $this->Default3->configuredCohorte(
			$results,
			$configuredCohorteParams
		);

		echo $this->Xform->end( 'Save' );
		echo $this->Observer->disableFormOnSubmit( $cohorteFormId );

		echo $this->element( 'search_footer', array( 'modelName' => $modelName, 'url' => $exportcsv ) );
	}
?>
<script type="text/javascript">
//<![CDATA[
	Event.observe( window, 'load', function() {
		Event.observe( '<?php echo $searchFormId;?>', 'submit', Cake.Search.onSubmit );
	} );
//]]>
</script>