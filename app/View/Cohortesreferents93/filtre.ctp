<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo '<ul class="actionMenu"><li>'.$this->Xhtml->link(
		$this->Xhtml->image(
			'icons/application_form_magnify.png',
			array( 'alt' => '' )
		).' Formulaire',
		'#',
		array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "var form = $$( 'form' ); form = form[0]; $( form ).toggle(); return false;" )
	).'</li></ul>';
?>
<?php echo $this->Xform->create( null, array( 'type' => 'post', 'action' => $this->action, 'id' => 'Search', 'class' => ( ( is_array( $this->request->data ) && !empty( $this->request->data ) && isset( $this->request->data['Search']['active'] ) ) ? 'folded' : 'unfolded' ) ) );?>
	<?php
		echo $this->Xform->input( 'Search.active', array( 'type' => 'hidden', 'value' => true ) );

		if( $this->action == 'affectes' ) {
			echo $this->Form->input( 'Search.PersonneReferent.referent_id', array( 'label' => 'Affectation', 'type' => 'select', 'options' => $options['referents'], 'empty' => true ) );
			echo $this->Search->date( 'Search.PersonneReferent.dddesignation' );
		}

		echo $this->Search->blocAllocataire( array(), 'Search' );
		echo $this->Search->toppersdrodevorsa( $options['toppersdrodevorsa'], 'Search.Calculdroitrsa.toppersdrodevorsa' );
		echo $this->Form->input( 'Search.Dsp.exists', array( 'label' => 'Possède une DSP ?', 'type' => 'select', 'options' => $options['exists'], 'empty' => true ) );
		echo $this->Form->input( 'Search.Contratinsertion.exists', array( 'label' => 'Possède un CER ?', 'type' => 'select', 'options' => $options['exists'], 'empty' => true ) );
		echo $this->Search->date( 'Search.Orientstruct.date_valid' );
		echo $this->Form->input( 'Search.Dossier.dernier', array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox' ) );
		echo $this->Search->blocAdresse( $options['mesCodesInsee'], $options['cantons'], 'Search' );
		echo $this->Search->etatdosrsa( $options['etatdosrsa'], 'Search.Situationdossierrsa.etatdosrsa' );
		echo $this->Search->paginationNombretotal( 'Search.Pagination.nombre_total' );
	?>
	<div class="submit noprint">
		<?php echo $this->Xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
		<?php echo $this->Xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>
<?php echo $this->Xform->end();?>