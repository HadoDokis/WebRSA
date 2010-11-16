<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<h1><?php echo $this->pageTile = 'Indicateurs de suivis mensuels';?></h1>

<?php
	echo $form->create( 'Indicateurmensuel', array( 'type' => 'post', 'url' => Router::url( null, true ), 'class' => 'noprint' ) );

	echo $form->input( 'Indicateurmensuel.annee', array( 'label' => __( 'Indicateurmensuel.annee', true ), 'type' => 'select', 'empty' => true, 'options' => array_range( date( 'Y' ), date( 'Y' ) - 20 ) ) );

	echo $form->submit( 'Calculer' );
	echo $form->end();
?>

<?php if( !empty( $this->data ) && isset( $indicateurs ) ) :?>
	<div class="submit noprint">
		<?php echo $form->button( 'Imprimer cette page', array( 'onclick' => 'printit();' ) );?>
	</div>
<?php endif;?>

<?php
	if( !empty( $this->data ) && isset( $indicateurs ) ) {
		$annee = Set::extract( $this->data, 'Indicateurmensuel.annee' );
		$types = array(
			'nbrDossiersInstruits'						=> array( 'type' => 'int', 'result' => 'sum' ),
			'nbrDossiersRejetesCaf'						=> array( 'type' => 'int', 'result' => 'sum' ),
			'nbrOuverturesDroits'						=> array( 'type' => 'int', 'result' => 'sum' ),
			'nbrAllocatairesDroitsEtDevoirs'			=> array( 'type' => 'int', 'result' => 'sum' ),
			'nbrPreorientationsEmploi'					=> array( 'type' => 'int', 'result' => 'sum' ),
			'nbrPreorientationsSocial'					=> array( 'type' => 'int', 'result' => 'sum' ),
			'nbrPreorientationsSocioprofessionnelle'	=> array( 'type' => 'int', 'result' => 'sum' ),
			'delaiOuvertureNotification'				=> array( 'type' => 'float', 'result' => 'avg' ),
			'delaiNotificationSignature'				=> array( 'type' => 'float', 'result' => 'avg' ),
			'montantsIndusConstates'					=> array( 'type' => 'int', 'result' => 'sum' ),
			'montantsIndusTransferes'					=> array( 'type' => 'int', 'result' => 'sum' ),
			'nbrCiNouveauxEntrantsCg'					=> array( 'type' => 'int', 'result' => 'sum' ),
			'nbrSuspensionsDroits'						=> array( 'type' => 'int', 'result' => 'sum' )
		);

		//**************************************************************************

		$rows = array();
		foreach( $indicateurs as $key => $indicateur ) {
			$row = array( '<td>'.__( 'Indicateurmensuel.'.$key, true ).'</td>' );
			$type = Set::extract( $types, $key.'.type' );
			$result = Set::extract( $types, $key.'.result' );
			for( $i = 1 ; $i <= 12 ; $i++ ) {
				$value = ( ( isset( $indicateur[$i] ) ? $indicateur[$i] : null ) );
				$row[] = '<td class="number">'.$locale->number( $value, ( ( $type == 'int' ) ? 0 : 2 ) ).'</td>';
			}
			$value = ( ( $type == 'int' ) ? array_sum( $indicateur ) : array_avg( $indicateur ) );
			$row[] = '<td class="number"><strong>'.$locale->number( $value, ( ( $type == 'int' ) ? 0 : 2 ) ).'</strong></td>';
			$rows[] = '<tr class="'.( ( ( count( $rows ) + 1 ) % 2 ) == 0 ? 'even' : 'odd' ).'">'.implode( '', $row ).'</tr>';
		}

		//**************************************************************************

		$headers = array( null );
		for( $i = 1 ; $i <= 12 ; $i++ ) {
			$headers[] = ucfirst( $locale->date( '%b %Y', $annee.( ( $i < 10 ) ? '0'.$i : $i ).'01' ) );
		}
		$headers[] = 'Total / Moyenne '.$annee;

		$thead = $xhtml->tag( 'thead', $xhtml->tableHeaders( $headers ) );
		$tbody = $xhtml->tag( 'tbody', implode( '', $rows ) );
		echo $xhtml->tag( 'table', $thead.$tbody );
	}
?>