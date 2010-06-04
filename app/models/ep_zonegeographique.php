<?php
	class EpZonegeographique extends AppModel
	{
		public $name = 'EpZonegeographique';

		public $actsAs = array(
			'Autovalidate'
		);

		public $belongsTo = array(
			'Ep',
			'Zonegeographique'
		);
	}
?>