<?php
	$this->pageTitle = 'Contrats d\'insertion';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>
<?php
	if( $this->action == 'add' ) {
		$this->pageTitle = 'Ajout d\'actions';
	}
	else {
		$this->pageTitle = 'Édition d\'actions';
	}
?>
<script type="text/javascript">
	function cbDpdt( master, slave ) {
		if( !$( master ).attr( 'checked' ) ) {
			$( slave ).attr( 'disabled', true );
			$( slave ).parents( 'div.input' ).addClass( 'disabled' );
		}
		else {
			$( slave ).attr( 'disabled', false );
			$( slave ).parents( 'div.input' ).removeClass( 'disabled' );
		}
	}

	function selDpdt( master, slave, enabled ) {
		if( !enabled ) {
			$( slave ).attr( 'disabled', true );
			$( slave ).parents( 'div.input' ).addClass( 'disabled' );
		}
		else {
			$( slave ).attr( 'disabled', false );
			$( slave ).parents( 'div.input' ).removeClass( 'disabled' );
		}
	}

	$( document ).ready(
		function() {
			// Au chargement
			selDpdt( $( '#ActioninsertionLibActionAide' ), $( '#AidedirecteLibAide' ) );
			selDpdt( $( '#ActioninsertionLibActionAide' ), $( '#AidesLieeDateAideDay' ) );
			selDpdt( $( '#ActioninsertionLibActionAide' ), $( '#AidesLieeDateAideMonth' ) );
			selDpdt( $( '#ActioninsertionLibActionAide' ), $( '#AidesLieeDateAideYear' ) );
			selDpdt( $( '#ActioninsertionLibActionPrest' ), $( '#PrestformLibPresta' ) );
			selDpdt( $( '#ActioninsertionLibActionPrest' ), $( '#RefprestaNomDay' ) );
			selDpdt( $( '#ActioninsertionLibActionPrest' ), $( '#RefprestaNomMonth' ) );
			selDpdt( $( '#ActioninsertionLibActionPrest' ), $( '#RefprestaNomYear' ) );

			//Evénements on Checkbox
			$( '#ActioninsertionLibActionAide' ).click( function( i ) {
					selDpdt( $( this ), $( '#AidedirecteLibAide' ), true );
					selDpdt( $( this ), $( '#AidesLieeDateAideDay' ), true );
					selDpdt( $( this ), $( '#AidesLieeDateAideMonth' ), true );
					selDpdt( $( this ), $( '#AidesLieeDateAideYear' ), true );
					selDpdt( $( this ), $( '#PrestformLibPresta' ), false  );
					selDpdt( $( this ), $( '#RefprestaNomDay' ), false  );
					selDpdt( $( this ), $( '#RefprestaNomMonth' ), false  );
					selDpdt( $( this ), $( '#RefprestaNomYear' ), false  );
			} );

			$( '#ActioninsertionLibActionPrest' ).click( function( i ) {
					selDpdt( $( this ), $( '#AidedirecteLibAide' ), false );
					selDpdt( $( this ), $( '#AidesLieeDateAideDay' ), false );
					selDpdt( $( this ), $( '#AidesLieeDateAideMonth' ), false );
					selDpdt( $( this ), $( '#AidesLieeDateAideYear' ), false );
					selDpdt( $( this ), $( '#PrestformLibPresta' ), true  );
					selDpdt( $( this ), $( '#RefprestaNomDay' ), true  );
					selDpdt( $( this ), $( '#RefprestaNomMonth' ), true  );
					selDpdt( $( this ), $( '#RefprestaNomYear' ), true  );
			} );

	} );
</script>

<div class="with_treemenu">
	<h1><?php echo $this->pageTitle;?></h1>

	<?php
		if( $this->action == 'add' ) {
			echo $form->create( 'Actioninsertion', array( 'type' => 'post', 'url' => Router::url( null, true ) ));
			echo '<div>';
			echo $form->input( 'Actioninsertion.id', array( 'type' => 'hidden' ) );

			echo $form->input( 'Aidedirecte.id', array( 'type' => 'hidden' ) );
			echo $form->input( 'Aidedirecte.actioninsertion_id', array( 'type' => 'hidden'));//, 'value' => $actioninsertion_id ) );
			echo $form->input( 'Prestform.id', array( 'type' => 'hidden') );
			echo $form->input( 'Prestform.actioninsertion_id', array( 'type' => 'hidden' ) );
			echo $form->input( 'Refpresta.id', array( 'type' => 'hidden' ) );
			echo '</div>';
		}
		else {
			echo $form->create( 'Actioninsertion', array( 'type' => 'post', 'url' => Router::url( null, true ) ));
			echo '<div>';
			echo $form->input( 'Actioninsertion.id', array( 'type' => 'hidden' ) );
			echo $form->input( 'Aidedirecte.id', array( 'type' => 'hidden' ) );
			echo $form->input( 'Prestform.id', array( 'type' => 'hidden') );
			echo $form->input( 'Prestform.actioninsertion_id', array( 'type' => 'hidden' ) );
			echo $form->input( 'Refpresta.id', array( 'type' => 'hidden' ) );
			echo $form->input( 'Actioninsertion.personne_id', array( 'type' => 'hidden' ) );
			echo '</div>';
		}
	?>

	<fieldset>
			<?php echo $form->input( 'Actioninsertion.id', array( 'type' => 'hidden' ) );?>
			<?php echo $form->input( 'Actioninsertion.lib_action', array( 'label' =>  __d( 'action', 'Action.lib_action', true ) , 'type' => 'radio', 'options' => $lib_action ) );?>
	</fieldset>
	<fieldset>
			<?php echo $form->input( 'Aidedirecte.id', array( 'type' => 'hidden' )  ); ?>
			<?php echo $form->input( 'Aidedirecte.actioninsertion_id', array( 'type' => 'hidden' )  ); ?>

			<?php echo $form->input( 'Aidedirecte.typo_aide', array( 'label' => __d( 'action', 'Action.typo_aide', true ), 'type' => 'select', 'options' => $typo_aide, 'empty' => true )  ); ?>
			<?php echo $form->input( 'Aidedirecte.lib_aide', array( 'label' => __d( 'action', 'Action.lib_aide', true ), 'type' => 'select', 'options' => $actions, 'empty' => true )  ); ?>
			<?php echo $form->input( 'Aidedirecte.date_aide', array( 'label' => __d( 'action', 'Action.date_aide', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y'), 'minYear'=>date('Y')-80 , 'empty' => true)  ); ?>
	</fieldset>
	<fieldset>
			<?php echo $form->input( 'Prestform.id', array( 'type' => 'hidden' )  ); ?>
			<?php echo $form->input( 'Prestform.actioninsertion_id', array( 'type' => 'hidden') ); ?>
			<!-- <?php echo $form->input( 'Refpresta.id', array( 'type' => 'hidden' )); ?> -->
			<?php echo $form->input( 'Prestform.lib_presta', array( 'label' => __d( 'action', 'Action.lib_presta', true ), 'type' => 'select', 'options' => $actions, 'empty' => true )  ); ?>
			<?php echo $form->input( 'Refpresta.nomrefpresta', array( 'label' => __d( 'personne', 'Personne.nom', true ), 'type' => 'text')); ?>
	</fieldset>

	<?php echo $form->submit( 'Enregistrer' );?>
	<?php echo $form->end();?>
</div>

<div class="clearer"><hr /></div>