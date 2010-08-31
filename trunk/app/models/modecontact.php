<?php
    class Modecontact extends AppModel
    {
        public $name = 'Modecontact';
        public $useTable = 'modescontact';

        public $belongsTo = array(
            'Foyer' => array(
                'classname'     => 'Foyer',
                'foreignKey'    => 'foyer_id'
            )
        );

        //*********************************************************************

        public function dossierId( $modecontact_id ) {
            $modecontact = $this->findById( $modecontact_id, null, null, 0 );
            if( !empty( $modecontact ) ) {
                return $modecontact['Foyer']['dossier_rsa_id'];
            }
            else {
                return null;
            }
        }

        //*********************************************************************

        public $validate = array(
            // Role personne
            'numtel' => array(
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 ),
//                 array(
//                     'rule' => 'isUnique',
//                     'message' => 'Ce numéro est déjà utilisé'
//                 ),
                array(
                    'rule' => array( 'between', 10, 14 ),
                    'message' => 'Le numéro de téléphone est composé de 10 chiffres',
                    'allowEmpty' => true
                )
            ),
            'numposte' => array(
                array(
                    'allowEmpty' => true
                ),

                array(
                    'rule' => array( 'between', 4, 4 ),
                    'message' => 'Le numéro de poste est composé de 4 chiffres'
                )
            ),
//             'nattel' => array(
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 )
//             ),
//             'matetel' => array(
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 )
//             ),
//             'autorutitel' => array(
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 )
//             ),
//             'adrelec' => array(
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 )
//             ),
//             'autorutiadrelec' => array(
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 )
//             )
        );
        
        public function sqDerniere($field) {
        	$dbo = $this->getDataSource( $this->useDbConfig );
        	$table = $dbo->fullTableName( $this, false );
        	return "
		    	SELECT {$table}.id
					FROM {$table}
					WHERE
						{$table}.foyer_id = ".$field."
					ORDER BY {$table}.foyer_id DESC
					LIMIT 1
        	";
        }
    }
?>
