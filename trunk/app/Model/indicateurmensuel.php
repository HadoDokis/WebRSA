<?php
	App::import( 'Sanitize' );

	class Indicateurmensuel extends AppModel
	{
		public $name = 'Indicateurmensuel';
		public $useTable = false;

		/**
		*
		*/

		protected function _query( $sql ) {
			$results = $this->query( $sql );
			return Set::combine( $results, '{n}.0.mois', '{n}.0.indicateur' );
		}

		/**
		*
		*/

		protected function _nbrDossiersInstruits( $annee ) {
			$sql = 'SELECT EXTRACT(MONTH FROM dossiers.dtdemrsa) AS mois, EXTRACT(YEAR FROM dossiers.dtdemrsa) AS annee, COUNT(dossiers.*) AS indicateur
						FROM dossiers
						WHERE EXTRACT(YEAR FROM dossiers.dtdemrsa) = '.Sanitize::clean( $annee ).'
						GROUP BY annee, mois
						ORDER BY annee, mois;';
			return $this->_query( $sql );
		}

		/**
		*
		*/

		protected function _nbrDossiersRejetesCaf( $annee ) {
			$sql = 'SELECT EXTRACT(MONTH FROM dossiers.dtdemrsa) AS mois, EXTRACT(YEAR FROM dossiers.dtdemrsa) AS annee, COUNT(dossiers.*) AS indicateur
						FROM dossiers
							INNER JOIN situationsdossiersrsa ON situationsdossiersrsa.dossier_id = dossiers.id
						WHERE situationsdossiersrsa.etatdosrsa = \'1\'
							AND EXTRACT(YEAR FROM dossiers.dtdemrsa) = '.Sanitize::clean( $annee ).'
						GROUP BY annee, mois
						ORDER BY annee, mois;';
			return $this->_query( $sql );
		}

		/**
		*
		*/

		protected function _nbrOuverturesDroits( $annee ) {
			$sql = 'SELECT EXTRACT(MONTH FROM dossiers.dtdemrsa) AS mois, EXTRACT(YEAR FROM dossiers.dtdemrsa) AS annee, COUNT(dossiers.*) AS indicateur
						FROM dossiers
							INNER JOIN situationsdossiersrsa ON situationsdossiersrsa.dossier_id = dossiers.id
						WHERE situationsdossiersrsa.etatdosrsa IN ( \'2\', \'3\', \'4\' )
							AND EXTRACT(YEAR FROM dossiers.dtdemrsa) = '.Sanitize::clean( $annee ).'
						GROUP BY annee, mois
						ORDER BY annee, mois;';
			return $this->_query( $sql );
		}

		/**
		*
		*/

		protected function _nbrAllocatairesDroitsEtDevoirs( $annee ) {
			$sql = 'SELECT EXTRACT(MONTH FROM dossiers.dtdemrsa) AS mois, EXTRACT(YEAR FROM dossiers.dtdemrsa) AS annee, COUNT(prestations.*) AS indicateur
						FROM prestations
							INNER JOIN personnes
								ON  prestations.personne_id = personnes.id
							INNER JOIN calculsdroitsrsa
								ON  calculsdroitsrsa.personne_id = personnes.id
							INNER JOIN foyers
								ON  personnes.foyer_id = foyers.id
							INNER JOIN dossiers
								ON  foyers.dossier_id = dossiers.id
						WHERE calculsdroitsrsa.toppersdrodevorsa = \'1\'
							AND prestations.natprest = \'RSA\'
							AND prestations.rolepers IN ( \'DEM\', \'CJT\' )
							AND EXTRACT(YEAR FROM dossiers.dtdemrsa) = '.Sanitize::clean( $annee ).'
						GROUP BY annee, mois
						ORDER BY annee, mois;';
			return $this->_query( $sql );
		}

		/**
		*
		*/

		protected function _nbrPreorientations( $annee, $type ) {
			$sql = 'SELECT EXTRACT(MONTH FROM orientsstructs.date_propo) AS mois, EXTRACT(YEAR FROM orientsstructs.date_propo) AS annee, COUNT(orientsstructs.*) AS indicateur
						FROM orientsstructs
						WHERE orientsstructs.statut_orient = \'Orienté\'
							AND orientsstructs.propo_algo IN
								( SELECT typesorients.id
									FROM typesorients
										WHERE typesorients.lib_type_orient = \''.$type.'\'
								)
							AND EXTRACT(YEAR FROM orientsstructs.date_propo) = '.Sanitize::clean( $annee ).'
						GROUP BY annee, mois
						ORDER BY annee, mois;';
			return $this->_query( $sql );
		}

		/**
		*
		*/

		protected function _delaiOuvertureNotification( $annee ) {
			$sql = 'SELECT EXTRACT(MONTH FROM dossiers.dtdemrsa) AS mois, EXTRACT(YEAR FROM dossiers.dtdemrsa) AS annee, AVG( ABS(orientsstructs.date_impression - dossiers.dtdemrsa ) ) AS indicateur
						FROM orientsstructs
							INNER JOIN personnes ON orientsstructs.personne_id = personnes.id
							INNER JOIN foyers ON personnes.foyer_id = foyers.id
							INNER JOIN dossiers ON foyers.dossier_id = dossiers.id
						WHERE orientsstructs.statut_orient = \'Orienté\'
							AND orientsstructs.date_impression IS NOT NULL
							AND dossiers.dtdemrsa IS NOT NULL
							AND EXTRACT(YEAR FROM dossiers.dtdemrsa) = '.Sanitize::clean( $annee ).'
						GROUP BY annee, mois
						ORDER BY annee, mois;';
			return $this->_query( $sql );
		}

		/**
		*
		*/

		protected function _delaiNotificationSignature( $annee ) {
			$sql = 'SELECT EXTRACT(MONTH FROM orientsstructs.date_impression) AS mois, EXTRACT(YEAR FROM orientsstructs.date_impression) AS annee, AVG( ABS( contratsinsertion.date_saisi_ci - orientsstructs.date_impression ) ) AS indicateur
						FROM orientsstructs
							INNER JOIN contratsinsertion ON contratsinsertion.personne_id = orientsstructs.personne_id
						WHERE EXTRACT(YEAR FROM orientsstructs.date_impression) = '.Sanitize::clean( $annee ).'
							AND orientsstructs.date_impression IS NOT NULL
							AND contratsinsertion.date_saisi_ci IS NOT NULL
						GROUP BY annee, mois
						ORDER BY annee, mois;';
			return $this->_query( $sql );
		}

		/**
		*
		*/

		protected function _montantsIndusConstates( $annee ) {
			$sql = 'SELECT EXTRACT(MONTH FROM infosfinancieres.moismoucompta) AS mois, EXTRACT(YEAR FROM infosfinancieres.moismoucompta) AS annee, SUM(infosfinancieres.mtmoucompta) AS indicateur
						FROM infosfinancieres
						WHERE infosfinancieres.type_allocation = \'IndusConstates\'
							AND EXTRACT(YEAR FROM infosfinancieres.moismoucompta) = '.Sanitize::clean( $annee ).'
							AND infosfinancieres.moismoucompta IS NOT NULL
						GROUP BY annee, mois
						ORDER BY annee, mois;';
			return $this->_query( $sql );
		}

		/**
		*
		*/

        protected function _montantsIndusTransferes( $annee ) {
            $sql = 'SELECT EXTRACT(MONTH FROM infosfinancieres.moismoucompta) AS mois, EXTRACT(YEAR FROM infosfinancieres.moismoucompta) AS annee, SUM(infosfinancieres.mtmoucompta) AS indicateur
                        FROM infosfinancieres
                        WHERE infosfinancieres.type_allocation = \'IndusTransferesCG\'
                            AND EXTRACT(YEAR FROM infosfinancieres.moismoucompta) = '.Sanitize::clean( $annee ).'
                            AND infosfinancieres.moismoucompta IS NOT NULL
                        GROUP BY annee, mois
                        ORDER BY annee, mois;';
            return $this->_query( $sql );
        }

		/**
		*
		*/

		protected function _nbrCiNouveauxEntrantsCg( $annee ) {
			$sql = 'SELECT EXTRACT(MONTH FROM contratsinsertion.date_saisi_ci) AS mois, EXTRACT(YEAR FROM contratsinsertion.date_saisi_ci) AS annee, COUNT(contratsinsertion.*) AS indicateur
						FROM contratsinsertion
							INNER JOIN personnes ON personnes.id = contratsinsertion.personne_id
							INNER JOIN foyers ON foyers.id = personnes.foyer_id
							INNER JOIN dossiers ON dossiers.id = foyers.dossier_id
						WHERE ( AGE( contratsinsertion.date_saisi_ci, dossiers.dtdemrsa ) <= INTERVAL \'2 months\' )
							AND contratsinsertion.num_contrat = \'PRE\'
							AND contratsinsertion.rg_ci = 1
							AND contratsinsertion.date_saisi_ci IS NOT NULL
							AND EXTRACT(YEAR FROM contratsinsertion.date_saisi_ci) = '.Sanitize::clean( $annee ).'
						GROUP BY annee, mois
						ORDER BY annee, mois;';
			return $this->_query( $sql );
		}

		/**
		*
		*/

		protected function _nbrSuspensionsDroits( $annee ) {
			$sql = 'SELECT EXTRACT(MONTH FROM suspensionsdroits.ddsusdrorsa) AS mois, EXTRACT(YEAR FROM suspensionsdroits.ddsusdrorsa) AS annee, COUNT(suspensionsdroits.*) AS indicateur
						FROM suspensionsdroits
						WHERE EXTRACT(YEAR FROM suspensionsdroits.ddsusdrorsa) = '.Sanitize::clean( $annee ).'
							AND suspensionsdroits.ddsusdrorsa IS NOT NULL
						GROUP BY annee, mois
						ORDER BY annee, mois;';
			return $this->_query( $sql );
		}

		/**
		*
		*/

		public function liste( $annee ) {
			$results['nbrDossiersInstruits'] = $this->_nbrDossiersInstruits( $annee );
			$results['nbrDossiersRejetesCaf'] = $this->_nbrDossiersRejetesCaf( $annee );
			$results['nbrOuverturesDroits'] = $this->_nbrOuverturesDroits( $annee );
			$results['nbrAllocatairesDroitsEtDevoirs'] = $this->_nbrAllocatairesDroitsEtDevoirs( $annee );
			$results['nbrPreorientationsEmploi'] = $this->_nbrPreorientations( $annee, 'Emploi' );
			$results['nbrPreorientationsSocial'] = $this->_nbrPreorientations( $annee, 'Social' );
			$results['nbrPreorientationsSocioprofessionnelle'] = $this->_nbrPreorientations( $annee, 'Socioprofessionnelle' );

			$results['delaiOuvertureNotification'] = $this->_delaiOuvertureNotification( $annee );
			$results['delaiNotificationSignature'] = $this->_delaiNotificationSignature( $annee );

			$results['montantsIndusConstates'] = $this->_montantsIndusConstates( $annee );
			$results['montantsIndusTransferes'] = $this->_montantsIndusTransferes( $annee );

			$results['nbrCiNouveauxEntrantsCg'] = $this->_nbrCiNouveauxEntrantsCg( $annee );
			$results['nbrSuspensionsDroits'] = $this->_nbrSuspensionsDroits( $annee );

			return $results;
		}
	}
?>