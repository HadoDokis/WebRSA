<?php
	class Dsp extends AppModel
	{
		var $name = 'Dsp';

		var $actsAs = array( 'Enumerable' );

		var $belongsTo = array( 'Personne' );

		var $enumFields = array(
			'sitpersdemrsa',
			'topisogroouenf',
			'topdrorsarmiant',
			'drorsarmianta2',
			'topcouvsoc',
			'accosocfam',
			'accosocindi',
			'soutdemarsoc',
			'nivetu',
			'nivdipmaxobt',
			'topqualipro',
			'topcompeextrapro',
			'topengdemarechemploi',
			'hispro',
			'cessderact',
			'topdomideract',
			'duractdomi',
			'inscdememploi',
			'topisogrorechemploi',
			'accoemploi',
			'topprojpro',
			'topcreareprientre',
			'concoformqualiemploi',
			'topmoyloco',
			'toppermicondub',
			'topautrpermicondu',
			'natlog',
			'demarlog'
		);
	}
?>