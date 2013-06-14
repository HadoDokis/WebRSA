<?php
	$this->Xls->addRow( $headers );

	foreach( $data as $row ) {
		$this->Xls->addRow( array_values( $row ) );
	}
	echo $this->Xls->render();
?>