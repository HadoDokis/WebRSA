<?php
	class Decisionparcours extends AppModel
	{
		var $name = 'Decisionparcours';

		var $actsAs = array(
			'Autovalidate',
			'Formattable' => array(
				'suffix' => array( 'structurereferente_id', 'referent_id' ),
			)
		);

		var $hasOne = array(
			'Parcoursdetecte',
// 			'Referent',
		);

        var $belongsTo = array(
            'Orientstruct'
        );

		var $validate = array(
			'maintien' => array(
				array( 'rule' => array( 'dependantNotEmpty', true, array( 'typeorient_id', 'structurereferente_id', 'referent_id' ) ) )
			),
		);

		/**
		*
		*/

		function beforeValidate( $options = array() ) {
			if( Set::check( $this->data, "{$this->alias}.maintien" ) ) {
				if( Set::classicExtract( $this->data, "{$this->alias}.maintien" ) ) {
					foreach( array( 'typeorient_id', 'structurereferente_id', 'referent_id' ) as $field ) {
						$this->data = Set::insert( $this->data, "{$this->alias}.{$field}", null );
					}
				}
			}
		}

		/**
		* TODO: créer une nouvelle orientstruct / mettre à jour l'orientstruct liée à cette réorientation
		* FIXME: c'est bon si on fait en cohorte ou entrée par entrée ?
		* FIXME: si on fait une mise à jour où on retire l'accord
		*/

		public function afterSave( $created ) {
			$success = true;

			/// Si on en est à l'étape du conseil et qu'il a marqué son accord...
			if( ( $this->data[$this->alias]['roleparcours'] == 'conseil' ) && ( empty( $this->data[$this->alias]['maintien'] ) ) ) {
				$parcoursdetecte = $this->Parcoursdetecte->findById( $this->data[$this->alias]['parcoursdetecte_id'], null, null, 1 );

				/// Nouvelle orientstruct
				$orientstruct = array( 'Orientstruct' => array( ) );
				$orientstruct_id = Set::classicExtract( $parcoursdetecte, 'Parcoursdetecte.osnv_id' );
				if( !empty( $orientstruct_id ) ) {
					$orientstruct = $this->Orientstruct->findById( $orientstruct_id, null, null, -1 );
				}

				$orientstruct['Orientstruct']['personne_id'] = Set::classicExtract( $parcoursdetecte, 'Orientstruct.personne_id' );
				foreach( array( 'typeorient_id', 'structurereferente_id' ) as $field ) {
					$orientstruct['Orientstruct'][$field] = Set::classicExtract( $this->data, "{$this->alias}.{$field}" );
				}
				$orientstruct['Orientstruct']['statut_orient'] = 'Orienté';
				$orientstruct['Orientstruct']['valid_cg'] = true;
				$orientstruct['Orientstruct']['date_propo'] = $orientstruct['Orientstruct']['date_valid'] = date( 'Y-m-d' );

				$this->Parcoursdetecte->Orientstruct->create( $orientstruct );
				$success = $this->Parcoursdetecte->Orientstruct->save() && $success;

				/// Mise à jour de la demande
				if( $success ) {
					$parcoursdetecte['Parcoursdetecte']['osnv_id'] = $this->Parcoursdetecte->Orientstruct->id;
					$this->Parcoursdetecte->create( $parcoursdetecte );
					$success = $this->Parcoursdetecte->save() && $success;
				}

				/// Ajout du nouveau référent - FIXME mise à jour dfdesignation de l'ancien
				/// FIXME: on n'est pas certain de désigner le nouveay référent.
				$personne_referent = array(
					'PersonneReferent' => array(
						'personne_id' => Set::classicExtract( $parcoursdetecte, 'Orientstruct.personne_id' ),
						'referent_id' => $this->data[$this->alias]['referent_id'],
						'dddesignation' => date( 'Y-m-d' ),
						'structurereferente_id' => $orientstruct['Orientstruct']['structurereferente_id'],
					)
				);

                $Referent = ClassRegistry::init( 'referent' );
				$Referent->PersonneReferent->create( $personne_referent );
				$success = $Referent->PersonneReferent->save() && $success;
			}

			return $success;
		}
	}
?>