<?php
	$rows = array();
	foreach( $indicateurs as $indicateur ) {
		$row = array();
		for( $i = 1 ; $i <= 12 ; $i++ ) {
			$value = ( ( isset( $indicateur[$i] ) ? $indicateur[$i] : null ) );
			$row[$i-1] = $locale->number( $value );
		}
		$rows[] = $row;
	}

	//**************************************************************************

	$headers = array();
	for( $i = 1 ; $i <= 12 ; $i++ ) {
		$headers[] = ucfirst( $locale->date( '%b %Y', '2009'.( ( $i < 10 ) ? '0'.$i : $i ).'01' ) );
	}

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