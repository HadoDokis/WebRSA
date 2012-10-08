<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo '<ul class="actionMenu"><li>'.$xhtml->link(
		$xhtml->image(
			'icons/application_form_magnify.png',
			array( 'alt' => '' )
		).' Formulaire',
		'#',
		array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "var form = $$( 'form' ); form = form[0]; $( form ).toggle(); return false;" )
	).'</li></ul>';
?>
<?php echo $xform->create( null, array( 'type' => 'post', 'action' => $this->action, 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) && isset( $this->data['Search']['active'] ) ) ? 'folded' : 'unfolded' ) ) );?>
	<?php
		echo $xform->input( 'Search.active', array( 'type' => 'hidden', 'value' => true ) );

		if( $this->action == 'affectes' ) {
			echo $form->input( 'Search.PersonneReferent.referent_id', array( 'label' => 'Affectation', 'type' => 'select', 'options' => $options['referents'], 'empty' => true ) );
			echo $search->date( 'Search.PersonneReferent.dddesignation' );
		}

		echo $search->blocAllocataire( array(), 'Search' );
		echo $search->toppersdrodevorsa( $options['toppersdrodevorsa'], 'Search.Calculdroitrsa.toppersdrodevorsa' );
		echo $form->input( 'Search.Dsp.exists', array( 'label' => 'Possède une DSP ?', 'type' => 'select', 'options' => $options['exists'], 'empty' => true ) );
		echo $form->input( 'Search.Contratinsertion.exists', array( 'label' => 'Possède un CER ?', 'type' => 'select', 'options' => $options['exists'], 'empty' => true ) );
		echo $search->date( 'Search.Orientstruct.date_valid' );
		echo $form->input( 'Search.Dossier.dernier', array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox' ) );
		echo $search->blocAdresse( $options['mesCodesInsee'], $options['cantons'], 'Search' );
		echo $search->etatdosrsa( $options['etatdosrsa'], 'Search.Situationdossierrsa.etatdosrsa' );
		echo $search->paginationNombretotal( 'Search.Pagination.nombre_total' );
	?>
	<div class="submit noprint">
		<?php echo $xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
		<?php echo $xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>
<?php echo $xform->end();?>