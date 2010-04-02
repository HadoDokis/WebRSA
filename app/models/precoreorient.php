<?php
	class Precoreorient extends AppModel
	{
		public $name =  'Precoreorient';

		public $actsAs = array(
			'Autovalidate',
			'Formattable' => array(
				'unsetOnNull' => 'id',
				'suffix' => array( 'structurereferente_id', 'referent_id' ),
			)
		);

		public $validate = array(
			'rolereorient' => array(
				array( 'rule' => 'notEmpty' )
			),
			'typeorient_id' => array(
				array( 'rule' => 'notEmpty' )
			),
			'structurereferente_id' => array(
				array( 'rule' => 'notEmpty' )
			),
			'referent_id' => array(
				array( 'rule' => 'notEmpty' )
			),
		);

		public $belongsTo = array(
			'Demandereorient',
			'Typeorient',
			'Structurereferente',
			'Referent',
// 			'Orientstruct',
		);

		/**
		* TODO: créer une nouvelle orientstruct / mettre à jour l'orientstruct liée à cette réorientation
		* FIXME: c'est bon si on fait en cohorte ou entrée par entrée ?
		* FIXME: si on fait une mise à jour où on retire l'accord
		*/

		public function afterSave( $created ) {
			$success = true;

			/// Si on en est à l'étape du conseil et qu'il a marqué son accord...
			if( ( $this->data[$this->alias]['rolereorient'] == 'conseil' ) && ( !empty( $this->data[$this->alias]['accord'] ) ) ) {
				$demandereorient = $this->Demandereorient->findById( $this->data[$this->alias]['demandereorient_id'], null, null, 1 );

				/// Nouvelle orientstruct
				$orientstruct = array( 'Orientstruct' => array( ) );
				$orientstruct_id = Set::classicExtract( $demandereorient, 'Demandereorient.orientstruct_id' );
				if( !empty( $orientstruct_id ) ) {
					$this->Orientstruct = ClassRegistry::init( 'Orientstruct' );
					$orientstruct = $this->Orientstruct->findById( $orientstruct_id, null, null, -1 );
				}

				$orientstruct['Orientstruct']['personne_id'] = Set::classicExtract( $demandereorient, 'Demandereorient.personne_id' );
				foreach( array( 'typeorient_id', 'structurereferente_id' ) as $field ) {
					$orientstruct['Orientstruct'][$field] = Set::classicExtract( $this->data, "{$this->alias}.{$field}" );
				}
				$orientstruct['Orientstruct']['statut_orient'] = 'Orienté';
				$orientstruct['Orientstruct']['valid_cg'] = true;
				$orientstruct['Orientstruct']['date_propo'] = $orientstruct['Orientstruct']['date_valid'] = date( 'Y-m-d' );

				$this->Demandereorient->Orientstruct->create( $orientstruct );
				$success = $this->Demandereorient->Orientstruct->save() && $success;

				/// Mise à jour de la demande
				if( $success ) {
					$demandereorient['Demandereorient']['orientstruct_id'] = $this->Demandereorient->Orientstruct->id;
					$this->Demandereorient->create( $demandereorient );
					$success = $this->Demandereorient->save() && $success;
				}

				/// Ajout du nouveau référent - FIXME mise à jour dfdesignation de l'ancien
				/// FIXME: on n'est pas certain de désigner le nouveay référent.
				$personne_referent = array(
					'PersonneReferent' => array(
						'personne_id' => $demandereorient['Demandereorient']['personne_id'],
						'referent_id' => $this->data['Precoreorient']['referent_id'],
						'dddesignation' => date( 'Y-m-d' ),
						'structurereferente_id' => $orientstruct['Orientstruct']['structurereferente_id'],
					)
				);
				$this->Referent->PersonneReferent->create( $personne_referent );
				$success = $this->Referent->PersonneReferent->save() && $success;
			}

			return $success;
		}
	}
?>