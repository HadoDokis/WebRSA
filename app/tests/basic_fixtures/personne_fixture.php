<?php

	class PersonneFixture extends CakeTestFixture {
		var $name = 'Personne';
		var $table = 'personnes';
		var $import = array( 'table' => 'personnes', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>