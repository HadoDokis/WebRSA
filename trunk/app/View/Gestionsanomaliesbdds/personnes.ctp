<h1><?php echo $title_for_layout = __d( 'gestionanomaliebdd', 'Gestionsanomaliesbdds::personnes' );$this->set( 'title_for_layout', $title_for_layout );?></h1>

<?php if( !empty( $fichiersModuleLies ) ): ?>
	<div class="errorslist">
	Impossible de procéder à la fusion des enregistrements liés aux personnes en doublons car des fichiers liés à ces enregistrements existent:
	<ul>
		<?php
			foreach( $fichiersModuleLies as $fichier ) {
				$controller = Inflector::tableize( $fichier['Fichiermodule']['modele'] );
				echo "<li>".$this->Xhtml->link(
					$fichier['Fichiermodule']['modele'],
					array( 'controller' => $controller, 'action' => 'filelink', $fichier['Fichiermodule']['fk_value'] ),
					array( 'class' => 'external' )
				)."</li>";
			}
		?>
	</ul>
	</div>
<?php else: ?>
	<noscript>
		<p class="error">Cette fonctionnalité nécessite l'utilisation de javascript, mais javascript n'est pas activé dans votre navigateur.</p>
	</noscript>

	<?php if( isset( $validationErrors ) && !empty( $validationErrors ) ): ?>
		<div class="errorslist">
		Les erreurs suivantes ont été détectées:
		<ul>
			<?php
				foreach( $validationErrors as $validationError ) {
					echo "<li>{$validationError}</li>";
				}
			?>
		</ul>
		</div>
	<?php endif;?>

	<h2>Informations concernant le foyer</h2>
	<?php
		$informations = array(
			$this->Gestionanomaliebdd->foyerErreursPrestationsAllocataires( $foyer, false ),
			$this->Gestionanomaliebdd->foyerPersonnesSansPrestation( $foyer, false ),
			$this->Gestionanomaliebdd->foyerErreursDoublonsPersonnes( $foyer, false ),
			( $foyer['Dossier']['locked'] ? $this->Xhtml->image( 'icons/lock.png', array( 'alt' => '', 'title' => 'Dossier verrouillé' ) ) : null ),
		);
		$informations = Hash::filter( (array)$informations );

		if( !empty( $informations ) ) {
			echo '<ul>';
			foreach( $informations as $information ) {
				echo "<li>{$information}</li>";
			}
			echo '</ul>';
		}
	?>

	<ul>
		<li><?php echo $this->Xhtml->link( 'Voir', array( 'controller' => 'personnes', 'action' => 'index', $this->request->params['pass'][0] ) );?></li>
		<?php foreach( $methodes as $m ):?>
			<?php $m = strtolower( $m );?>
			<li><?php echo $this->Xhtml->link( "Comparaison {$m}", array( $this->request->params['pass'][0], $this->request->params['pass'][1], 'Gestionanomaliebdd__methode' => $m ) );?></li>
		<?php endforeach;?>
	</ul>

	<h2>Fusion des enregistrements liés à la personne</h2>

	<?php
		if( empty( $personnes ) ) {
			echo $this->Xhtml->tag( 'p', 'Aucune personne à sélectionner', array( 'class' => 'notice' ) );
		}
		else {
			echo $this->Xform->create( null, array( 'id' => 'PersonnesForm' ) );
			echo '<div>'.$this->Xform->input( 'Form.sent', array( 'type' => 'hidden', 'value' => true ) ).'</div>';

			echo '<table id="personnes">';
			echo '<thead>
				<tr>
					<th>Garder ?</th>
					<th>id</th>
					<th>foyer_id</th>
					<th>qual</th>
					<th>nom</th>
					<th>nomnai</th>
					<th>prenom</th>
					<th>prenom2</th>
					<th>prenom3</th>
					<th>dtnai</th>
					<th>sexe</th>
					<th>rgnai</th>
					<th>nir</th>
					<th>natprest</th>
					<th>rolepers</th>
				</tr>
			</thead>';
			echo '<tbody>';

			foreach( $personnes as $i => $personne ) {
				$checked = '';
				if( isset( $this->request->data['Personne']['garder'] ) && $this->request->data['Personne']['garder'] == $personne['Personne']['id'] ) {
					$checked = 'checked="checked"';
				}
				echo $this->Html->tableCells(
					array(
						"<label><input name=\"data[Personne][garder]\" id=\"PersonneGarder{$i}\" value=\"{$personne['Personne']['id']}\" type=\"radio\" {$checked} />Garder</label>",
						h( $personne['Personne']['id'] ),
						h( $personne['Personne']['foyer_id'] ),
						h( $personne['Personne']['qual'] ),
						h( $personne['Personne']['nom'] ),
						h( $personne['Personne']['nomnai'] ),
						h( $personne['Personne']['prenom'] ),
						h( $personne['Personne']['prenom2'] ),
						h( $personne['Personne']['prenom3'] ),
						h( $personne['Personne']['dtnai'] ),
						h( $personne['Personne']['sexe'] ),
						h( $personne['Personne']['rgnai'] ),
						h( $personne['Personne']['nir'] ),
						h( $personne['Prestation']['natprest'] ),
						h( $personne['Prestation']['rolepers'] ),
					),
					array( 'class' => "odd id{$personne['Personne']['id']}" ),
					array( 'class' => "even id{$personne['Personne']['id']}" )
				);
			}
			echo '</tbody>';
			echo '</table>';

			foreach( $donnees as $modelName => $records ) {
				echo "<h3>{$modelName}</h3>";

				$association = $associations[$modelName];

				$modelClass = ClassRegistry::init( $modelName );;
				$modelFields = array_keys( Hash::flatten( array( $modelClass->alias => Set::normalize( array_keys( $modelClass->schema() ) ) ) ) );

				$fields = array(/* "{$modelName}.id", "{$modelName}.personne_id" */);
				foreach( $modelFields as $modelField ) {
					if( !in_array( $modelField, array( "{$modelName}.id", "{$modelName}.personne_id" ) ) && ( $this->Type2->type( $modelField ) != 'binary' ) ) {
						$fields[] = $modelField;
					}
				}

				echo '<table id="'.$modelName.'" class="tableliee tooltips">';
				echo '<thead>';
				echo '<tr>
						<th class="action">Garder ?</th>
						<th>id</th>
						<th>personne_id</th>';
				foreach( $fields as $field ) {
					list( $modelName, $field ) = Xinflector::modelField( $field );
					echo "<th>{$field}</th>";
				}
				echo "<th class=\"innerTableHeader noprint\">Enregistrements liés</th>";
				echo '</tr>';
				echo '</thead>';
				echo '<tbody>';

				foreach( $records as $i => $record ) {
					$checked = '';
					$inputType = ( $association == 'hasMany' ? 'checkbox' : 'radio' );

					if( isset( $this->request->data[$modelName]['id'] ) && in_array( $record[$modelName]['id'], $this->request->data[$modelName]['id'] ) ) {
						$checked = 'checked="checked"';
					}

					$cells = array(
						"<label><input name=\"data[{$modelName}][id][]\" id=\"{$modelName}Id{$i}\" value=\"{$record[$modelName]['id']}\" type=\"{$inputType}\" {$checked} />Garder</label>",
						h( $record[$modelName]['id'] ),
						h( $record['Personne']['id'] ),
					);

					foreach( $fields as $field ) {
						$fieldType = $this->Type2->type( $field );

						if( $fieldType != 'binary' ) {
							if( $fieldType !== 'string' ) {
								$cells[] = $this->Type2->format( $record, $field );
							}
							else {
								$cells[] = h( Set::classicExtract( $record, $field ) );//FIXME: traductions ?
							}
						}
					}

					// Infobulle
					$linkedRecords = array();
					foreach( $record[$modelName] as $k => $v ) {
						if( preg_match( '/^nb_(.*)$/', $k, $matches ) ) {
							$linkedRecords[] = "<tr><th>".h( $matches[1] )."</th><td>".h( $v )."</td></tr>";
						}
					}
					if( empty( $linkedRecords ) ) {
						$innerTbody = "<tr><td>Aucun enregistrement lié</td></tr>";
					}
					else {
						$innerTbody = implode( "", $linkedRecords );
					}
					$cells[] = array( "<table id=\"innerTable{$modelName}{$i}\" class=\"innerTable\"><tbody>{$innerTbody}</tbody></table>", array( 'class' => 'innerTableCell noprint' ) );

					// FIXME: la même avec personne_id dans la table du haut et le javascript
					$class = array( 'class' => '' );
					foreach( $modelFields as $modelField ) {
						list( $m, $f ) = model_field( $modelField );
						if( ( $f == 'id' ) || preg_match( '/_id$/', $f ) ) {
							$class = $this->Html->addClass( $class, $f.Set::classicExtract( $record, $modelField ) );
						}
					}

					echo $this->Html->tableCells( $cells, $class, $class );
				}
				echo '</tbody>';
				echo '</table>';
			}

			echo $this->Xform->end( 'Enregistrer' );
		}
	?>
	<script type="text/javascript">
		// <![CDATA[
		// Cocher les enregistrements dépendants depuis la table personnes
		var v = $( 'PersonnesForm' ).getInputs( 'radio', 'data[Personne][garder]' );
		var currentValue = undefined;
		$( v ).each( function( radio ) {
			$( radio ).observe( 'change', function( event ) {
				toutDecocher( '.tableliee input[type="checkbox"]' );
				toutDecocher( '.tableliee input[type="radio"]' );
				toutCocher( '.tableliee .personne_id' + radio.value + ' input[type=checkbox]' );
				toutCocher( '.tableliee .personne_id' + radio.value + ' input[type=radio]' );
			} );
		} );

		// Cocher les enregistrements dépendants entre tables (ex.: dsps.id, dsps_revs.dsp_id)
		function foo( modelFrom, columnFrom, modelTo, columnTo ) {
			var v = $( 'PersonnesForm' ).getInputs( 'radio', 'data[' + modelTo + '][' + columnTo + '][]' );//FIXME
			var currentValue = undefined;
			$( v ).each( function( radio ) {
				$( radio ).observe( 'change', function( event ) {
					toutDecocher( '#' + modelFrom + ' input[type="checkbox"]' );
					toutDecocher( '#' + modelFrom + ' input[type="radio"]' );
					toutCocher( '#' + modelFrom + ' .' + columnFrom + radio.value + ' input[type=checkbox]' );
					toutCocher( '#' + modelFrom + ' .' + columnFrom + radio.value + ' input[type=radio]' );
				} );
			} );
		}

		<?php if( !empty( $dependencies ) ): ?>
			<?php foreach( $dependencies as $dependency ): ?>
				foo( '<?php echo $dependency['From']['model'];?>', '<?php echo $dependency['From']['column'];?>', '<?php echo $dependency['To']['model'];?>', '<?php echo $dependency['To']['column'];?>' );
			<?php endforeach; ?>
		<?php endif; ?>

		// Mise en évidence à partir de la table #personnes vers les tables liées
		var re = new RegExp( '^.*id([0-9]+).*$', 'g' );
		$$( '#personnes tr' ).each( function( elmt ) {
			// Ajout d'une classe
			$(elmt).observe( 'mouseover', function( event ) {
				var classes = this.classNames().toString();
				var personneId = classes.replace( re, '$1' );
				$(this).addClassName( 'highlight' );
				$$( '.tableliee tr.personne_id' + personneId ).each( function( row ) {
					$(row).addClassName( 'highlight' );
				} );
			} );
			// Suppression d'une classe
			$(elmt).observe( 'mouseout', function( event ) {
				var classes = this.classNames().toString();
				var personneId = classes.replace( re, '$1' );
				$(this).removeClassName( 'highlight' );
				$$( '.tableliee tr.personne_id' + personneId ).each( function( row ) {
					$(row).removeClassName( 'highlight' );
				} );
			} );
		} );

		// Mise en évidence à partir des tables liées vers la table #personnes -> FIXME
		/*var re2 = new RegExp( '^.*personne_id([0-9]+).*$', 'g' );
		$$( '.tableliee tr' ).each( function( elmt ) {
			// Ajout d'une classe
			$(elmt).observe( 'mouseover', function( event ) {
				var classes = this.classNames().toString();
				var personneId = classes.replace( re2, '$1' );
				$(this).addClassName( 'highlight' );
				$$( '#personnes tr.id' + personneId ).each( function( row ) {
					$(row).addClassName( 'highlight' );
				} );
			} );
			// Suppression d'une classe
			$(elmt).observe( 'mouseout', function( event ) {
				var classes = this.classNames().toString();
				var personneId = classes.replace( re2, '$1' );
				$(this).removeClassName( 'highlight' );
				$$( '#personnes tr.id' + personneId ).each( function( row ) {
					$(row).removeClassName( 'highlight' );
				} );
			} );
		} );*/
		// ]]>
	</script>
<?php endif;?>
<?php
	echo $this->Default->button(
		'back',
		array(
			'controller' => $this->request->params['controller'],
			'action'     => 'foyer',
			$this->request->params['pass'][0],
			'Gestionanomaliebdd__methode' => $methode
		),
		array(
			'id' => 'Back'
		)
	);
?>