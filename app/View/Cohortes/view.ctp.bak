<?php
	$this->Csv->addRow( $headers );

	foreach( $data as $row ){
		$this->Csv->addRow( array_values( $row ) );
	}
	echo $this->Csv->render();
?>