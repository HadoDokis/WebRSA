<?php
	App::import( 'Sanitize' );

	class Statistiquesministerielle extends AppModel
	{
		var $name = 'Statistiqueministerielle';
		var $useTable = false;

	
		function indicateursOrganismes($args)
		{
			// Nombre de personnes dans le champ des Droits et Devoirs (L262-28) au 31 décembre de l'année
			// ET qui ont un référent.
			$sql = 'SELECT count(*), typesorients.id, typesorients.lib_type_orient
						FROM personnes
						LEFT JOIN calculsdroitsrsa ON ( calculsdroitsrsa.personne_id = personnes.id )
						LEFT JOIN personnes_referents ON (personnes_referents.personne_id = personnes.id)
						LEFT JOIN structuresreferentes ON ( structuresreferentes.id = personnes_referents.structurereferente_id )
						LEFT JOIN typesorients ON ( typesorients.id = structuresreferentes.typeorient_id  )
						WHERE calculsdroitsrsa.toppersdrodevorsa = \'1\'
						GROUP BY typesorients.id, typesorients.lib_type_orient
						ORDER BY typesorients.id;';
			$sqlFound = $this->query( $sql );
			$results = array();
			$results['DroitsEtDevoirs'] = 0; 
			$results['Autres'] = 0;
			foreach($sqlFound as $row)
			{
				if( empty($row[0]['id']) ) continue;
				$results['DroitsEtDevoirs'] += $row[0]['count'];
				switch( $row[0]['id']){
					case 1: //Socioprofessionnelle
						$results['SP'] = $row[0]['count'];//SP : Socio Professionelle
						break;
					case 2: //Social
						$results['SSD'] = $row[0]['count'];//SSD : Service Social du Département
						break;
					case 3: //Emploi
						$results['PE'] = $row[0]['count'];
						break;
					default: // Autres
						$results['Autres'] += $row[0]['count'];	
				}
			}
			// 
//			$sql = '';
//			$result[''] = $this->query( $sql );
			return $results;
		}


	}
?>