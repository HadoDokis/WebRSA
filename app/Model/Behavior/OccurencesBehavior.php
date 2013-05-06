<?php
	/**
	 * Code source de la classe OccurencesBehavior.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model.Behavior
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe OccurencesBehavior ...
	 *
	 * @package app.Model.Behavior
	 */
	class OccurencesBehavior extends ModelBehavior
	{
		/**
		* Retourne un tableau dont la clé est l'id du champ du model choisi et la valeur
		* le nombre d'occurences trouvées dans les tables liées en hasOne, hasMany et
		* hasAndBelongsToMany avec possibilité d'envoyer des conditions supplémentaires
		*/

		public function occurences( Model $model, $conditions = array() ) {
			$counts = array();
			$joins = array();
			$dbo = $model->getDataSource( $model->useDbConfig );
			$sq = $dbo->startQuote;
			$eq = $dbo->endQuote;

			// remplissage des variables pour faire les jointure et le count sur les tables en hasOne et en hasMany
			foreach( array( 'hasOne', 'hasMany' ) as $assocType ) {
				if( !empty( $model->{$assocType} ) ) {
					foreach( $model->{$assocType} as $alias => $assoc ) {
						$joins[] = array(
							'table'      => $dbo->fullTableName( $model->{$alias}, false, false ),
							'alias'      => $alias,
							'type'       => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array( "{$alias}.{$assoc['foreignKey']} = {$model->alias}.{$model->primaryKey}" )
						);

						$counts[] = "COUNT({$sq}{$alias}{$eq}.{$sq}id{$eq})";
					}
				}
			}

			// remplissage des variables pour faire les jointure et le count sur les tables en hasAndBelongsToMany
			if( !empty( $model->hasAndBelongsToMany ) ) {
				foreach( $model->hasAndBelongsToMany as $alias => $assoc ) {
					$joins[] = array(
						'table'      => $dbo->fullTableName( $model->{$assoc['with']}, false, false ),
						'alias'      => $assoc['with'],
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( "{$assoc['with']}.{$assoc['foreignKey']} = {$model->alias}.{$model->primaryKey}" )
					);

					$counts[] = "COUNT({$sq}{$assoc['with']}{$eq}.{$sq}id{$eq})";
				}
			}

			if( !empty( $counts ) ) {
				$implodeCounts = implode( $counts, ' + ' );
			}
			else {
				$implodeCounts = '0';
			}

			// création du queryData
			$queryData = array(
				'fields' => array(
					"{$model->alias}.{$model->primaryKey}",
					"{$implodeCounts} AS {$sq}{$model->alias}__occurences{$eq}",
				),
				'joins' => $joins,
				'recursive' => -1,
				'conditions' => $conditions,
				'group' => array( "{$model->alias}.{$model->primaryKey}" ),
				'order' => array( "{$model->alias}.{$model->primaryKey}" )
			);

			$results = $model->find( 'all', $queryData );

			return Set::combine( $results, "{n}.{$model->alias}.{$model->primaryKey}", "{n}.{$model->alias}.occurences" );
		}

		/**
		* Retourne un tableau dont la clé est l'id du champ du model choisi et la valeur
		* le fait qu'il existe au moins une occurence dans une des tables liées en hasOne,
		* hasMany et hasAndBelongsToMany avec possibilité d'envoyer des conditions supplémentaires
		*/

		public function occurencesExists( Model $model, $conditions = array(), $blacklist = array() ) {
			$exists = array();
			$dbo = $model->getDataSource( $model->useDbConfig );
			$sq = $dbo->startQuote;
			$eq = $dbo->endQuote;

			// remplissage des variables pour faire les jointure et le count sur les tables en hasOne et en hasMany
			foreach( array( 'hasOne', 'hasMany' ) as $assocType ) {
				if( !empty( $model->{$assocType} ) ) {
					foreach( $model->{$assocType} as $alias => $assoc ) {
						if( !in_array( $alias, $blacklist ) ) {
							$table = $dbo->fullTableName( $model->{$alias}, false, false );
							$exists[] = "EXISTS( SELECT {$table}.{$assoc['foreignKey']} FROM {$table} WHERE {$table}.{$assoc['foreignKey']} = {$sq}{$model->alias}{$eq}.{$sq}{$model->primaryKey}{$eq} )";
						}
					}
				}
			}

			// remplissage des variables pour faire les jointure et le count sur les tables en hasAndBelongsToMany
			if( !empty( $model->hasAndBelongsToMany ) ) {
				foreach( $model->hasAndBelongsToMany as $alias => $assoc ) {
					if( !in_array( $alias, $blacklist ) ) {
						$table = $dbo->fullTableName( $model->{$assoc['with']}, false, false );
						$exists[] = "EXISTS( SELECT {$table}.{$assoc['foreignKey']} FROM {$table} WHERE {$table}.{$assoc['foreignKey']} = {$sq}{$model->alias}{$eq}.{$sq}{$model->primaryKey}{$eq} )";
					}
				}
			}

			if( !empty( $exists ) ) {
				$implodeExists = implode( $exists, ' OR ' );
			}
			else {
				$implodeExists = 'false';
			}

			// création du queryData
			$queryData = array(
				'fields' => array(
					"{$model->alias}.{$model->primaryKey}",
					"{$implodeExists} AS {$sq}{$model->alias}__occurences{$eq}",
				),
				'recursive' => -1,
				'conditions' => $conditions,
				'group' => array( "{$model->alias}.{$model->primaryKey}" ),
				'order' => array( "{$model->alias}.{$model->primaryKey}" )
			);

			$results = $model->find( 'all', $queryData );

			return Set::combine( $results, "{n}.{$model->alias}.{$model->primaryKey}", "{n}.{$model->alias}.occurences" );
		}
	}
?>