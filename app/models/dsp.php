<?php
	class Dsp extends AppModel
	{
		var $name = 'Dsp';

		var $actsAs = array( 'Enumerable' );

		var $belongsTo = array( 'Personne' );
	}
?>