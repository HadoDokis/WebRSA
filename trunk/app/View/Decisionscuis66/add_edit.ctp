<?php
	$this->pageTitle = __d( 'decisioncui66', "Decisioncui66::{$this->action}" );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>
<?php
	echo $this->Xhtml->tag( 'h1', $this->pageTitle );

	echo $this->Form->create( 'Decisioncui66',array() );
?>

<fieldset><legend>Avis techniques</legend>
	<?php if( !empty( $proposdecisionscuis66 ) ):?>
		<table class="aere">
			<thead>
				<tr>
					<th>Avis MNE</th>
					<th>Date avis MNE</th>
					<th>Observation MNE</th>
					<th>Avis élu</th>
					<th>Date avis élu</th>
					<th>Observation élu</th>
					<th>Avis référent</th>
					<th>Date avis référent</th>
					<th>Observation référent</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $proposdecisionscuis66 as $i => $propodecision ):?>
					<?php
						$datepropositioncui = date_short( $propodecision['Propodecisioncui66']['datepropositioncui'] );
						$datepropositioncuielu = date_short( $propodecision['Propodecisioncui66']['datepropositioncuielu'] );
						$datepropositioncuireferent = date_short( $propodecision['Propodecisioncui66']['datepropositioncuireferent'] );

						$propositioncui = Set::enum( $propodecision['Propodecisioncui66']['propositioncui'], $options['Propodecisioncui66']['propositioncui'] );
						$propositioncuielu = Set::enum( $propodecision['Propodecisioncui66']['propositioncuielu'], $options['Propodecisioncui66']['propositioncui'] );
						$propositioncuireferent = Set::enum( $propodecision['Propodecisioncui66']['propositioncuireferent'], $options['Propodecisioncui66']['propositioncui'] );

						$observcui = $propodecision['Propodecisioncui66']['observcui'];
						$observcuielu = $propodecision['Propodecisioncui66']['observcuielu'];
						$observcuireferent = $propodecision['Propodecisioncui66']['observcuireferent'];

						echo $this->Xhtml->tableCells(
							array(
								h( $propositioncui ),
								h( $observcui ),
								h( $datepropositioncui ),
								h( $propositioncuielu ),
								h( $observcuielu ),
								h( $datepropositioncuielu ),
								h( $propositioncuireferent ),
								h( $observcuireferent ),
								h( $datepropositioncuireferent )
							),
							array( 'class' => 'odd' ),
							array( 'class' => 'even' )
						);
				?>
			<?php endforeach;?>
		</tbody>
	</table>
<?php else:?>
	<p class="notice">Aucune proposition passée n'a encore été émise par le technicien.</p>
<?php  endif;?>
</fieldset>
<fieldset>
	<legend></legend>
		<?php
			echo $this->Xform->create( 'Decisioncui66', array( 'id' => 'decisioncui66form' ) );
			if( Set::check( $this->request->data, 'Decisioncui66.id' ) ){
				echo $this->Xform->input( 'Decisioncui66.id', array( 'type' => 'hidden' ) );
			}

			echo $this->Form->input( 'Decisioncui66.cui_id', array( 'type' => 'hidden', 'value' => $cui_id ) );
			echo $this->Xform->input( 'Decisioncui66.user_id', array( 'type' => 'hidden', 'value' => $userConnected ) );

			echo $this->Xform->input( 'Decisioncui66.observdecisioncui', array( 'label' => __d( 'decisioncui66', 'Decisioncui66.observdecisioncui' ), 'type' => 'textarea', 'rows' => 6)  );
			echo $this->Xform->input( 'Decisioncui66.decisioncui', array( 'label' => __d( 'decisioncui66', 'Decisioncui66.decisioncui' ), 'type' => 'select', 'options' => $options['Decisioncui66']['decisioncui'], 'empty' => true ) );
			echo $this->Xform->input( 'Decisioncui66.datedecisioncui', array( 'label' => __d( 'decisioncui66', 'Decisioncui66.datedecisioncui' ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+2, 'minYear'=>date('Y')-2 , 'empty' => true)  );
		?>
</fieldset>

<div class="submit">
<?php echo $this->Form->submit( 'Enregistrer', array( 'div' => false ) );?>
<?php echo $this->Form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
</div>
<?php echo $this->Form->end();?>