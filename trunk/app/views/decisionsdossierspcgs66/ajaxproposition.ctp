<?php
	if( $calculpossible ) {
		if( !empty( $questionspcg ) ) {
			echo $xform->input( 'Decisiondossierpcg66.decisionpcg66_id', array( 'label' => __d( 'decisiondossierpcg66', 'Decisiondossierpcg66.decisionpcg66_id', true ),'type' => 'select', 'options' => $questionspcg, 'empty' => true ) );
		}
		else {
			echo '<p class="notice">Aucune proposition ne peut être déduite des questions.</p>';
		}
	}
?>