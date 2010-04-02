<?php
    class Parteprempl extends Partep
    {
        var $name = 'Parteprempl';
        var $useTable = 'partseps';

		var $virtualFields = array(
			'nom_complet' => array(
				'type'		=> 'string',
				'postgres'	=> '( "Parteprempl"."nom" || \' \' || "Parteprempl"."prenom" )' // FIXME
			),
		);
    }
?>