<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}

	echo $this->Default3->titleForLayout();

	echo $this->Default3->actions(
		array(
			'/Sanctionseps58/cohorte_radiespe/#toggleform' => array(
				'onclick' => '$(\'Nonorientationsproscovs58CohorteSearchForm\').toggle(); return false;',
				'class' => 'search'
			)
		)
	);
?>

<?php echo $this->Form->create( null, array( 'type' => 'post', 'url' => array( 'action' => $this->request->action ), 'id' => 'Nonorientationsproscovs58CohorteSearchForm', 'class' => ( !empty( $this->request->params['named'] ) ? 'folded' : 'unfolded' ) ) );?>
	<?php
		echo $this->Html->tag(
			'fieldset',
			$this->Html->tag( 'legend', __d( $this->request->params['controller'], 'Search.Contratinsertion.df_ci' ) )
			.$this->Xform->input( 'Search.Contratinsertion.df_ci', array( 'type' => 'hidden', 'value' => true ) )
			.$this->Xform->input( 'Search.Contratinsertion.df_ci_from', array( 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => 2009, 'domain' => $this->request->params['controller'] ) )
			.$this->Xform->input( 'Search.Contratinsertion.df_ci_to', array( 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => 2009, 'domain' => $this->request->params['controller'] ) )
		);

		echo $this->Allocataires->blocDossier(
			array(
				'prefix' => 'Search',
				'options' => $options,
				'skip' => array(
					'Search.Dossier.dernier',
					'Search.Situationdossierrsa.etatdosrsa'
				)
			)
		);
		echo $this->Allocataires->blocAdresse( array( 'prefix' => 'Search', 'options' => $options ) );
		echo $this->Allocataires->blocAllocataire(
			array(
				'prefix' => 'Search',
				'options' => $options,
				'skip' => array(
					'Search.Calculdroitrsa.toppersdrodevorsa'
				)
			)
		);
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
	echo $this->Observer->disableFormOnSubmit('Nonorientationsproscovs58CohorteSearchForm');
?>

<?php
	if( isset( $results ) ) {
		echo $this->Html->tag( 'h2', 'Résultats de la recherche' );

		echo $this->Xform->create( null,
			array(
				'id' => 'Nonorientationsproscovs58CohorteCohorte',
//				'url' => Router::url( array( 'controller' => $controller, 'action' => $action ), true )
			)
		);

		echo $this->Default3->configuredCohorte( $results, $configuredCohorteParams	);

		echo $this->Xform->end( 'Save' );
		echo $this->Observer->disableFormOnSubmit('Nonorientationsproscovs58CohorteCohorte');

		echo $this->element( 'search_footer', array( 'modelName' => 'Orientstruct', 'url' => array( 'action' => 'exportcsv' ) ) );

		echo $this->Form->button( 'Tout cocher', array( 'onclick' => 'return toutCocher();' ) );
		echo $this->Form->button( 'Tout décocher', array( 'onclick' => 'return toutDecocher();' ) );
	}
?>