<?php
	function array_avg( $array ) {
		$avg = 0;
		if( !is_array( $array ) || count( $array ) == 0 ) {
			return false;
		}

		return ( array_sum( $array ) / count( $array ) );
	}

	//**************************************************************************

	$types = array(
		'nbrDossiersInstruits'				=> array( 'type' => 'int', 'result' => 'sum' ),
		'nbrDossiersRejetesCaf'				=> array( 'type' => 'int', 'result' => 'sum' ),
		'nbrOuverturesDroits'				=> array( 'type' => 'int', 'result' => 'sum' ),
		'nbrAllocatairesDroitsEtDevoirs'	=> array( 'type' => 'int', 'result' => 'sum' ),
		'nbrPreorientationsEmploi'			=> array( 'type' => 'int', 'result' => 'sum' ),
		'delaiOuvertureNotification'		=> array( 'type' => 'float', 'result' => 'avg' ),
		'delaiNotificationSignature'		=> array( 'type' => 'float', 'result' => 'avg' ),
		'montantsIndus'						=> array( 'type' => 'int', 'result' => 'sum' ),
		'nbrCiNouveauxEntrantsCg'			=> array( 'type' => 'int', 'result' => 'sum' ),
		'nbrSuspensionsDroits'				=> array( 'type' => 'int', 'result' => 'sum' )
	);

	//**************************************************************************

	$rows = array();
	foreach( $indicateurs as $key => $indicateur ) {
		$row = array( __( 'Indicateurmensuel.'.$key, true ) );
		$type = Set::extract( $types, $key.'.type' );
		$result = Set::extract( $types, $key.'.result' );
		for( $i = 1 ; $i <= 12 ; $i++ ) {
			$value = ( ( isset( $indicateur[$i] ) ? $indicateur[$i] : null ) );
			$row[] = $locale->number( $value, ( ( $type == 'int' ) ? 0 : 2 ) );
		}
		$value = ( ( $type == 'int' ) ? array_sum( $indicateur ) : array_avg( $indicateur ) );
		$row[] = $locale->number( $value, ( ( $type == 'int' ) ? 0 : 2 ) );
		$rows[] = $row;
	}

	//**************************************************************************

	$headers = array( null );
	for( $i = 1 ; $i <= 12 ; $i++ ) {
		$headers[] = ucfirst( $locale->date( '%b %Y', '2009'.( ( $i < 10 ) ? '0'.$i : $i ).'01' ) );
	}
	$headers[] = 'Total / Moyenne';

	$thead = $html->tag(
		'thead',
		$html->tableHeaders( $headers )
	);

	$tbody = $html->tag(
		'tbody',
		$html->tableCells( $rows, array( 'class' => 'odd' ), array( 'class' => 'even' ) )
	);

	echo $html->tag( 'table', $thead.$tbody );
?>

<?php /*debug( $indicateurs );*/?>