<?php
    class Suiviinstruction extends AppModel
    {
        public $name = 'Suiviinstruction';
        public $useTable = 'suivisinstruction';
        public $displayField = 'typeserins';

        public $belongsTo = array(
            'Dossier' => array(
                'classname' => 'Dossier',
                'foreignKey' => 'id'
            )
        );


        public $validate = array(
            'suiirsa' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'date_etat_instruction' => array(
                array(
                    'rule' => 'date',
                    'message' => 'Veuillez entrer une date valide'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'nomins' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'numdepins' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'typeserins' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'numcomins' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'numagrins' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            )

        );
        
        public function sqDerniere($field) {
        	$dbo = $this->getDataSource( $this->useDbConfig );
        	$table = $dbo->fullTableName( $this, false );
        	return "
		    	SELECT {$table}.id
					FROM {$table}
					WHERE
						{$table}.dossier_rsa_id = ".$field."
					ORDER BY {$table}.dossier_rsa_id DESC
					LIMIT 1
        	";
        }
    }
?>
