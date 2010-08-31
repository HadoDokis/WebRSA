<?php
    class Infopoleemploi extends AppModel
    {
        public $name = 'Infopoleemploi';
        public $useTable = 'infospoleemploi';

        //*********************************************************************

        public $belongsTo = array(
            'Personne' => array(
                'classname'     => 'Personne',
                'foreignKey'    => 'personne_id'
            )
        );
        
        /*
        *
        */

        public function sqDerniere($field) {
        	$dbo = $this->getDataSource( $this->useDbConfig );
        	$table = $dbo->fullTableName( $this, false );
        	return "
		    	SELECT {$table}.id
					FROM {$table}
					WHERE
						{$table}.personne_id = ".$field."
					ORDER BY {$table}.dateinscription DESC
					LIMIT 1
        	";
        }
    }
?>
