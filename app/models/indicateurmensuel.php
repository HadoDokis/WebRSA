<?php
	App::import( 'Sanitize' );

	class Indicateurmensuel extends AppModel
	{
		var $name = 'Indicateurmensuel';
		var $useTable = false;

		/**
		*
		*/

		function _query( $sql ) {
			$results = $this->query( $sql );
			return Set::combine( $results, '{n}.0.mois', '{n}.0.indicateur' );
		}

		/**
		*
		*/

		function _nbrDossiersInstruits( $annee ) {
			$sql = 'SELECT EXTRACT(MONTH FROM dossiers_rsa.dtdemrsa) AS mois, EXTRACT(YEAR FROM dossiers_rsa.dtdemrsa) AS annee, COUNT(dossiers_rsa.*) AS indicateur
						FROM dossiers_rsa
						WHERE EXTRACT(YEAR FROM dossiers_rsa.dtdemrsa) = '.Sanitize::clean( $annee ).'
						GROUP BY annee, mois
						ORDER BY annee, mois;';
			return $this->_query( $sql );
		}

		/**
		*
		*/

		function _nbrDossiersRejetesCaf( $annee ) {
			$sql = 'SELECT EXTRACT(MONTH FROM dossiers_rsa.dtdemrsa) AS mois, EXTRACT(YEAR FROM dossiers_rsa.dtdemrsa) AS annee, COUNT(dossiers_rsa.*) AS indicateur
						FROM dossiers_rsa
							INNER JOIN situationsdossiersrsa ON situationsdossiersrsa.dossier_rsa_id = dossiers_rsa.id
						WHERE situationsdossiersrsa.etatdosrsa = \'1\'
							AND EXTRACT(YEAR FROM dossiers_rsa.dtdemrsa) = '.Sanitize::clean( $annee ).'
						GROUP BY annee, mois
						ORDER BY annee, mois;';
			return $this->_query( $sql );
		}

		/**
		*
		*/

		function _nbrOuverturesDroits( $annee ) {
			$sql = 'SELECT EXTRACT(MONTH FROM dossiers_rsa.dtdemrsa) AS mois, EXTRACT(YEAR FROM dossiers_rsa.dtdemrsa) AS annee, COUNT(dossiers_rsa.*) AS indicateur
						FROM dossiers_rsa
							INNER JOIN situationsdossiersrsa ON situationsdossiersrsa.dossier_rsa_id = dossiers_rsa.id
						WHERE situationsdossiersrsa.etatdosrsa IN ( \'2\', \'3\', \'4\' )
							AND EXTRACT(YEAR FROM dossiers_rsa.dtdemrsa) = '.Sanitize::clean( $annee ).'
						GROUP BY annee, mois
						ORDER BY annee, mois;';
			return $this->_query( $sql );
		}

		/**
		*
		*/

		function _nbrAllocatairesDroitsEtDevoirs( $annee ) {
			$sql = 'SELECT EXTRACT(MONTH FROM dossiers_rsa.dtdemrsa) AS mois, EXTRACT(YEAR FROM dossiers_rsa.dtdemrsa) AS annee, COUNT(prestations.*) AS indicateur
						FROM prestations
							INNER JOIN personnes
								ON  prestations.personne_id = personnes.id
							INNER JOIN foyers
								ON  personnes.foyer_id = foyers.id
							INNER JOIN dossiers_rsa
								ON  foyers.dossier_rsa_id = dossiers_rsa.id
						WHERE prestations.toppersdrodevorsa = true
							AND prestations.natprest = \'RSA\'
							AND prestations.rolepers IN ( \'DEM\', \'CJT\' )
							AND EXTRACT(YEAR FROM dossiers_rsa.dtdemrsa) = '.Sanitize::clean( $annee ).'
						GROUP BY annee, mois
						ORDER BY annee, mois;';
			return $this->_query( $sql );
		}

		/**
		*
		*/

		function _nbrPreorientationsEmploi( $annee ) {
			$sql = 'SELECT EXTRACT(MONTH FROM orientsstructs.date_propo) AS mois, EXTRACT(YEAR FROM orientsstructs.date_propo) AS annee, COUNT(orientsstructs.*) AS indicateur
						FROM orientsstructs
						WHERE orientsstructs.statut_orient = \'Orienté\'
							AND orientsstructs.propo_algo IN
								( SELECT typesorients.id
									FROM typesorients
										WHERE typesorients.lib_type_orient = \'Emploi\'
								)
							AND EXTRACT(YEAR FROM orientsstructs.date_propo) = '.Sanitize::clean( $annee ).'
						GROUP BY annee, mois
						ORDER BY annee, mois;';
			return $this->_query( $sql );
		}

		/**
		*
		*/

		function _delaiOuvertureNotification( $annee ) {
			$sql = 'SELECT EXTRACT(MONTH FROM dossiers_rsa.dtdemrsa) AS mois, EXTRACT(YEAR FROM dossiers_rsa.dtdemrsa) AS annee, AVG(orientsstructs.date_impression - dossiers_rsa.dtdemrsa ) AS indicateur
						FROM orientsstructs
							INNER JOIN personnes ON orientsstructs.personne_id = personnes.id
							INNER JOIN foyers ON personnes.foyer_id = foyers.id
							INNER JOIN dossiers_rsa ON foyers.dossier_rsa_id = dossiers_rsa.id
						WHERE orientsstructs.statut_orient = \'Orienté\'
							AND orientsstructs.date_impression IS NOT NULL
							AND dossiers_rsa.dtdemrsa IS NOT NULL
							AND EXTRACT(YEAR FROM dossiers_rsa.dtdemrsa) = '.Sanitize::clean( $annee ).'
						GROUP BY annee, mois
						ORDER BY annee, mois;';
			return $this->_query( $sql );
		}

		/**
		*
		*/

		function _delaiNotificationSignature( $annee ) {
			$sql = 'SELECT EXTRACT(MONTH FROM orientsstructs.date_impression) AS mois, EXTRACT(YEAR FROM orientsstructs.date_impression) AS annee, AVG(contratsinsertion.date_saisi_ci - orientsstructs.date_impression ) AS indicateur
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

		function _montantsIndus( $annee ) {
			$sql = 'SELECT EXTRACT(MONTH FROM infosfinancieres.moismoucompta) AS mois, EXTRACT(YEAR FROM infosfinancieres.moismoucompta) AS annee, AVG(infosfinancieres.mtmoucompta) AS indicateur
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

		function _nbrCiNouveauxEntrantsCg( $annee ) {
			$sql = 'SELECT EXTRACT(MONTH FROM contratsinsertion.date_saisi_ci) AS mois, EXTRACT(YEAR FROM contratsinsertion.date_saisi_ci) AS annee, COUNT(contratsinsertion.*) AS indicateur
						FROM contratsinsertion
							INNER JOIN personnes ON personnes.id = contratsinsertion.personne_id
							INNER JOIN foyers ON foyers.id = personnes.foyer_id
							INNER JOIN dossiers_rsa ON dossiers_rsa.id = foyers.dossier_rsa_id
						WHERE ( AGE( contratsinsertion.date_saisi_ci, dossiers_rsa.dtdemrsa ) <= INTERVAL \'2 months\' )
							AND contratsinsertion.typocontrat_id = 1
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

		function _nbrSuspensionsDroits( $annee ) {
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

		function liste( $annee ) {
			$results['nbrDossiersInstruits'] = $this->_nbrDossiersInstruits( $annee );
			$results['nbrDossiersRejetesCaf'] = $this->_nbrDossiersRejetesCaf( $annee );
			$results['nbrOuverturesDroits'] = $this->_nbrOuverturesDroits( $annee );
			$results['nbrAllocatairesDroitsEtDevoirs'] = $this->_nbrAllocatairesDroitsEtDevoirs( $annee );
			$results['nbrPreorientationsEmploi'] = $this->_nbrPreorientationsEmploi( $annee );

			$results['delaiOuvertureNotification'] = $this->_delaiOuvertureNotification( $annee );
			$results['delaiNotificationSignature'] = $this->_delaiNotificationSignature( $annee );

			$results['montantsIndus'] = $this->_montantsIndus( $annee );

			$results['nbrCiNouveauxEntrantsCg'] = $this->_nbrCiNouveauxEntrantsCg( $annee );
			$results['nbrSuspensionsDroits'] = $this->_nbrSuspensionsDroits( $annee );

			return $results;
		}
	}
?>