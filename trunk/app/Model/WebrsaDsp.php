<?php
	/**
	 * Code source de la classe WebrsaDsp.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	App::uses('WebrsaAbstractLogic', 'Model');

	/**
	 * La classe WebrsaDsp possède la logique métier web-rsa
	 *
	 * @todo WebrsaLogicDsp ?
	 *
	 * @package app.Model
	 */
	class WebrsaDsp extends WebrsaAbstractLogic
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaDsp';

		/**
		 * Les modèles qui seront utilisés par ce modèle.
		 *
		 * @var array
		 */
		public $uses = array('Dsp', 'DspRev');

		/**
		 * Ajoute les virtuals fields pour permettre le controle de l'accès à une action
		 * 
		 * @param array $query
		 * @return type
		 */
		public function completeVirtualFieldsForAccess(array $query = array()) {
			return $query;
		}
		
		/**
		 * Permet d'obtenir le nécéssaire pour calculer les droits d'accès métier à une action
		 * 
		 * @param array $conditions
		 * @return array
		 */
		public function getDataForAccess(array $conditions) {
			$query = array(
				'fields' => array(
					'DspRev.id',
					'DspRev.personne_id',
				),
				'conditions' => $conditions,
				'contain' => false
			);
			$bases = $this->DspRev->find('all', array('contain' => false, 'conditions' => $conditions));
			
			$datas = array();
			foreach ($bases as $value) {
				$datas[Hash::get($value, 'DspRev.personne_id')][] = Hash::get($value, 'DspRev.id');
			}
			$ids = (array)Hash::extract($bases, '{n}.DspRev.id');
			$personne_ids = array_unique((array)Hash::extract($bases, '{n}.DspRev.personne_id'));
			
			$results = array();
			foreach ($personne_ids as $personne_id) {
				$query = $this->completeVirtualFieldsForAccess($this->getViewQuery());
				$query['conditions'] = array('DspRev.personne_id' => $personne_id);
				$query['order'] = array('DspRev.created DESC', 'DspRev.id DESC');

				$histos = $this->DspRev->find('all', $query);

				$count = count($histos);
				$histos[$count-1]['diff'] = 0;
				$prev = $histos[$count-1];

				for ($i = $count-2 ; $i >= 0 ; $i--) {
					$delta = $this->getDiffs($prev, $histos[$i]);
					$diff = count(Hash::flatten($delta));
					$prev = $histos[$i];
					$histos[$i]['diff'] = $diff;
				}
				
				foreach ($histos as $histo) {
					if (in_array(Hash::get($histo, 'DspRev.id'), $ids)) {
						$results[] = $histo;
					}
				}
			}
			
			return $results;
		}
		
		/**
		 * Retourne un querydata contenant tous les champs et les associations à
		 * utiliser dans les pages de visualisation d'une DspRev, dans la page
		 * d'historique des DspRev, dans la page de différences entre deux versions
		 * des DspRev.
		 *
		 * @return array
		 */
		public function getViewQuery() {
			$cacheKey = Inflector::underscore($this->DspRev->useDbConfig).'_'.Inflector::underscore($this->DspRev->alias).'_'.Inflector::underscore(__FUNCTION__);
			$query = Cache::read($cacheKey);

			if ($query === false) {
				$query = array(
					'fields' => $this->DspRev->fields(),
					'contain' => array(
						'Personne',
						'DetaildifsocRev',
						'DetailaccosocfamRev',
						'DetailaccosocindiRev',
						'DetaildifdispRev',
						'DetailnatmobRev',
						'DetaildiflogRev',
						'DetailmoytransRev',
						'DetaildifsocproRev',
						'DetailprojproRev',
						'DetailfreinformRev',
						'DetailconfortRev',
						'Fichiermodule'
					),
					'joins' => array()
				);

				foreach (array_keys($this->DspRev->belongsTo) as $alias) {
					if (in_array($alias, $query['contain'])) {
						$query['fields'] = array_merge($query['fields'], $this->DspRev->{$alias}->fields());
					}
					// Codes ROME V2
					elseif (preg_match('/66(Metier|Secteur)/', $alias)) {
						$key = array_search("{$this->DspRev->alias}.{$this->DspRev->belongsTo[$alias]['foreignKey']}", $query['fields']);
						if ($key !== -1) {
							unset($query['fields'][$key]);
						}

						$field = $this->DspRev->{$alias}->getVirtualField('intitule');
						$query['fields'][] = "({$field}) \"{$alias}__intitule\"";
						$query['joins'][] = $this->DspRev->join($alias, array('type' => 'LEFT OUTER'));
					}
				}

				if (Configure::read('Romev3.enabled')) {
					foreach ($this->Dsp->romev3LinkedModels as $alias) {
						$aliasRev = "{$alias}Rev";
						$replacements = array();

						$query['joins'][] = $this->DspRev->join($aliasRev);

						$fields = array();
						foreach ($this->Dsp->suffixesRomev3 as $suffix) {
							$prefix = preg_replace('/^(.*)romev3Rev$/', "\\1", $aliasRev);

							$linked = Inflector::camelize("{$suffix}romev3");
							$linkedAlias = "{$prefix}{$suffix}romev3Rev";
							$replacements[$linked] = $linkedAlias;

							$query['joins'][] = array_words_replace($this->DspRev->{$aliasRev}->join($linked), $replacements);

							switch($suffix) {
								case 'famille':
									$fields[] = "(\"{$linkedAlias}\".\"code\" || ' - ' || \"{$linkedAlias}\".\"name\") AS \"{$linkedAlias}__name\"";
									break;
								case 'domaine':
									$fields[] = "(\"{$prefix}familleromev3Rev\".\"code\" || \"{$linkedAlias}\".\"code\" || ' - ' || \"{$linkedAlias}\".\"name\") AS \"{$linkedAlias}__name\"";
									break;
								case 'metier':
									$fields[] = "(\"{$prefix}familleromev3Rev\".\"code\" || \"{$prefix}domaineromev3Rev\".\"code\" || \"{$linkedAlias}\".\"code\" || ' - ' || \"{$linkedAlias}\".\"name\") AS \"{$linkedAlias}__name\"";
									break;
								case 'appellation':
									$fields[] = "{$linkedAlias}.name";
									break;
							}
						}
						$query['fields'] = Hash::merge($query['fields'], $fields);
					}
				}

				Cache::write($cacheKey, $query);
			}

			return $query;
		}

		/**
		 * Permet d'obtenir les différences entre deux versions des DspRev obtenues
		 * grâce au query se trouvant dans la méthode getViewQuery().
		 *
		 * @param array $old
		 * @param array $new
		 * @return array
		 */
		public function getDiffs($old, $new) {
			$return = array();

			// Suppression des champs de clés primaires et étrangères des résultats des Dsps actuelles
			foreach ($new as $Model => $values) {
				if ($Model != 'DspRev' && preg_match('/Rev$/', $Model)) {
					foreach ($new[$Model] as $key1 => $value1) {
						if (is_array($new[$Model][$key1])) {
							$new[$Model][$key1] = Hash::remove($new[$Model][$key1], "id");
							$new[$Model][$key1] = Hash::remove($new[$Model][$key1], "dsp_rev_id");
						}
					}
				}
			}

			// Suppression des champs de clés primaires et étrangères des résultats des Dsps précédentes
			foreach ($old as $Model => $values) {
				if ($Model != 'DspRev' && preg_match('/Rev$/', $Model)) {
					foreach ($old[$Model] as $key2 => $value2) {
						if (is_array($old[$Model][$key2])) {
							$old[$Model][$key2] = Hash::remove($old[$Model][$key2], "id");
							$old[$Model][$key2] = Hash::remove($old[$Model][$key2], "dsp_rev_id");
						}
					}
				}
			}

			// Suppression des champs de clés primaires et étrangères des codes ROME V3 liés
			if (Configure::read('Romev3.enabled')) {
				foreach ($this->Dsp->romev3LinkedModels as $alias) {
					$foreignKey = Inflector::underscore($alias).'_id';
					unset($old["DspRev"][$foreignKey]);
					unset($new["DspRev"][$foreignKey]);

					foreach (array_keys($this->Dsp->Deractromev3->schema()) as $fieldName) {
						unset($old["{$alias}Rev"][$fieldName]);
						unset($new["{$alias}Rev"][$fieldName]);
					}
				}
			}

			// -----------------------------------------------------------------

			foreach ($new as $Model => $values) {
				$return[$Model] = Set::diff($new[$Model], $old[$Model]);
				unset($return[$Model]['id']);
				unset($return[$Model]['created']);
				unset($return[$Model]['modified']);

				if ($Model != 'DspRev' && !empty($new[$Model]) && !empty($return[$Model]) && preg_match('/Rev$/', $Model)) {
					foreach ($new[$Model] as $key1 => $value1) {
						foreach ($old[$Model] as $key2 => $value2) {
							$compare = Set::diff($value1, $value2);
							if (empty($compare) && ($key1 != $key2)) {
								$return[$Model] = Hash::remove($return[$Model], $key1);
							}
						}
					}
				}

				if (empty($return[$Model])) {
					$return = Hash::remove($return, $Model);
				}
			}

			// Suppression des fausses différences trouvées au niveau des libellés vides
			foreach ($this->Dsp->getCheckboxes() as $alias => $params) {
				if ($params['text'] !== false) {
					$alias = "{$alias}Rev";
					$path = "{$alias}.{n}.{$params['text']}";

					if (Hash::extract($return, $path) === array(null)) {
						$return = Hash::remove($return, $path);
					}
				}
				if (empty($return[$Model])) {
					$return = Hash::remove($return, $Model);
				}
			}

			return $return;
		}
		
		/**
		 * Exécute les différentes méthods du modèle permettant la mise en cache.
		 * Utilisé au préchargement de l'application (/prechargements/index).
		 *
		 * @return boolean true en cas de succès, false en cas d'erreur,
		 * 	null pour les méthodes qui ne font rien.
		 */
		public function prechargement() {
			$results = $this->getViewQuery();
			$success = !empty($results);

			return $success;
		}
	}