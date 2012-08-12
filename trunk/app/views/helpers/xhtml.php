<?php
	App::import( 'Helper', 'Html' );
	class XhtmlHelper extends HtmlHelper
	{

		/**
		 * Ajout d'un paramètre "enabled" dans $htmlAttributes qui permet de marquer un lien comme
		 * "désactivé" (texte grisé) lorsque "enabled" vaut "false".
		 *
		 * Gère le paramètre escape/escapeTitle pour CakePHP 1.2, 1.3 et 2.x.
		 *
		 * @param string $title
		 * @param mixed $url
		 * @param array $htmlAttributes
		 * @param string $confirmMessage
		 * @param boolean $escapeTitle
		 * @return string
		 */
		public function link( $title, $url = null, $htmlAttributes = array( ), $confirmMessage = false, $escapeTitle = true ) {
			if( isset( $htmlAttributes['escape'] ) ) {
				$escapeTitle = $htmlAttributes['escape'];
			}

			if( isset( $htmlAttributes['enabled'] ) && $htmlAttributes['enabled'] == false ) {
				if( $escapeTitle ) {
					$title = h( $title );
				}
				$htmlAttributes['class'] = ( isset( $htmlAttributes['class'] ) ? "{$htmlAttributes['class']} disabled" : "disabled" );

				return "<span class=\"{$htmlAttributes['class']}\">{$title}</span>";
			}
			else {
				unset( $htmlAttributes['enabled'] );

				if( CAKE_BRANCH == '1.2' ) {
					return parent::link( $title, $url, $htmlAttributes, $confirmMessage, $escapeTitle );
				}
				else {
					$htmlAttributes['escape'] = $escapeTitle;
					return parent::link( $title, $url, $htmlAttributes, $confirmMessage );
				}
			}
		}

		/**
		 * Gère le paramètre $escape et la clé 'escape' de $attributes pour CakePHP 1.2, 1.3 et 2.x.
		 *
		 * @param string $name
		 * @param string $text
		 * @param array $attributes
		 * @param boolean $escape
		 * @return string
		 */
		public function tag( $name, $text = null, $attributes = array( ), $escape = false ) {
			if( isset( $attributes['escape'] ) ) {
				$escape = $attributes['escape'];
			}

			if( is_null( $text ) || strlen( $text ) == 0 ) {
				$text = ' ';
			}

			if( CAKE_BRANCH == '1.2' ) {
				return parent::tag( $name, $text, $attributes, $escape );
			}
			else {
				$attributes['escape'] = $escape;
				return parent::tag( $name, $text, $attributes );
			}
		}

		/**
		 *
		 */
		public function details( $rows = array( ), $options = array( ), $oddOptions = array( 'class' => 'odd' ), $evenOptions = array( 'class' => 'even' ) ) {
			$default = array(
				'type' => 'table',
				'empty' => true
			);

			$options = Set::merge( $default, $options );

			$type = Set::classicExtract( $options, 'type' );
			$allowEmpty = Set::classicExtract( $options, 'empty' );

			if( !in_array( $type, array( 'list', 'table' ) ) ) {
				trigger_error( sprintf( __( 'Type type "%s" not supported in XhtmlHelper::freu.', true ), $type ), E_USER_WARNING );
				return;
			}

			$return = null;
			if( count( $rows ) > 0 ) {
				$class = 'odd';
				foreach( $rows as $row ) {
					if( $allowEmpty || (!empty( $row[1] ) || valid_int( $row[1] ) ) ) {
						// TODO ?
						$currentOptions = ( ( $class == 'even' ) ? $evenOptions : $oddOptions );

						if( ( empty( $row[1] ) && !valid_int( $row[1] ) ) ) {
							$currentOptions = $this->addClass( $currentOptions, 'empty' );
						}

						$classes = Set::classicExtract( $currentOptions, 'class' );
						if( (!empty( $row[1] ) || valid_int( $row[1] ) ) ) {
							$currentOptions['class'] = implode( ' ', Set::merge( $classes, array( 'answered' ) ) );
						}

						$question = $row[0];
						$answer = ( (!empty( $row[1] ) || valid_int( $row[1] ) ) ? $row[1] : ' ' );

						if( $type == 'table' ) {
							$return .= $this->tag(
									'tr', $this->tag( 'th', $question ).$this->tag( 'td', $answer ), $currentOptions
							);
						}
						else if( $type == 'list' ) {
							$return .= $this->tag( 'dt', $question, $currentOptions );
							$return .= $this->tag( 'dd', $answer, $currentOptions );
						}

						$class = ( ( $class == 'odd' ) ? 'even' : 'odd' );
					}
				}

				if( !empty( $return ) ) {
					foreach( array( 'type', 'empty' ) as $key ) {
						unset( $options[$key] );
					}
					if( $type == 'table' ) {
						$return = $this->tag(
								'table', $this->tag(
										'tbody', $return
								), $options
						);
					}
					else if( $type == 'list' ) {
						$return = $this->tag(
								'dl', $return, $options
						);
					}
				}
			}

			return $return;
		}

		/**
		 *
		 * @param type $imagePath
		 * @param type $label
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		protected function _buttonLink( $imagePath, $label, $title, $url, $enabled = true, $attributes = array( ), $confirmMessage = false ) {
			$settings = array( 'escape' => false, 'title' => $title, 'enabled' => $enabled );

			if( is_array( $attributes ) ) {
				$options = array_merge( $settings, $attributes );
			}

			$content = $this->image( $imagePath, array( 'alt' => '' ) ).' '.$label;
			return $this->link( $content, $url, $options, $confirmMessage );
		}

		/**
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function addLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/add.png', 'Ajouter', $title, $url, $enabled );
		}

		/**
		 * Bouton ajouter pour les nouveaux comités
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function addComiteLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/add.png', 'Ajouter un nouveau Comité', $title, $url, $enabled );
		}

		/**
		 * Boutons à utiliser pour les Equipes pluridisciplinaires
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function addEquipeLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/add.png', 'Ajouter une nouvelle Equipe', $title, $url, $enabled );
		}

		/**
		 *
		 * @param type $title
		 * @param type $url
		 * @return type
		 */
		public function addSimpleLink( $title, $url ) {
			return $this->_buttonLink( 'icons/add.png', 'Ajouter une préconisation d\'orientation', $title, $url );
		}

		/**
		 *
		 * @param type $title
		 * @param type $url
		 * @return type
		 */
		public function addPieceLink( $title, $url ) {
			return $this->_buttonLink( 'icons/add.png', 'jouter une pièce', $title, $url );
		}

		/**
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function cancelLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/cancel.png', 'Annuler', $title, $url, $enabled );
		}

		/**
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function editLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/pencil.png', 'Modifier', $title, $url, $enabled );
		}

		/**
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @param type $external
		 * @return type
		 */
		public function fileLink( $title, $url, $enabled = true, $external = false ) {
			return $this->_buttonLink( 'icons/attach.png', 'Fichiers liés', $title, $url, $enabled, array( 'class' => $external ? 'external' : 'internal' ) );
		}

		/**
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function validateLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/tick.png', 'Valider', $title, $url, $enabled );
		}

		/**
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function deleteLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/delete.png', 'Supprimer', $title, $url, $enabled );
		}

		/**
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @param type $external
		 * @return type
		 */
		public function viewLink( $title, $url, $enabled = true, $external = false ) {
			return $this->_buttonLink( 'icons/zoom.png', 'Voir', $title, $url, $enabled, array( 'class' => $external ? 'external' : 'internal' ) );
		}

		/**
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function actionsLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/lightning.png', 'Actions', $title, $url, $enabled );
		}

		/**
		 *
		 * @param type $title
		 * @param type $url
		 * @return type
		 */
		public function aidesLink( $title, $url ) {
			return $this->_buttonLink( 'icons/ruby.png', 'Aides', $title, $url );
		}

		/**
		 *
		 * @param type $title
		 * @param type $url
		 * @return type
		 */
		public function ajoutcomiteLink( $title, $url ) {
			return $this->_buttonLink( 'icons/add.png', 'Ajout comité', $title, $url );
		}

		/**
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function attachLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/attach.png', 'Visualiser', $title, $url, $enabled );
		}

		/**
		 * Boutons à utiliser pour les Equipes pluridisciplinaires
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function conseilLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/door_out.png', 'Traitement par CG', $title, $url, $enabled, array( 'class' => 'internal' ) );
		}

		/**
		 * Boutons à utiliser pour les courriers à envoyer aux allocataires
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function courrierLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/page_white_text.png', 'Courrier d\'information', $title, $url, $enabled, array( 'class' => 'internal' ) );
		}

		/**
		 * Boutons à utiliser pour les Equipes pluridisciplinaires
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function decisionLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/user_comment.png', 'Décisions', $title, $url, $enabled, array( 'class' => 'internal' ) );
		}

		/**
		 * Boutons à utiliser pour les Equipes pluridisciplinaires
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function equipeLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/door_out.png', 'Traitement par équipe', $title, $url, $enabled, array( 'class' => 'internal' ) );
		}

		/**
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function printLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/printer.png', 'Imprimer', $title, $url, $enabled );
		}

		/**
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function printListLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/printer.png', 'Version imprimable', $title, $url, $enabled, array( 'class' => 'external' ) );
		}

		/**
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @param type $confirmMessage
		 * @return type
		 */
		public function printCohorteLink( $title, $url, $enabled = true, $confirmMessage = 'Etes-vous sûr de vouloir imprimer la cohorte ?' ) {
			$content = $this->image(
							'icons/printer.png', array( 'alt' => '' )
					).' Imprimer la cohorte';

			if( $enabled ) {
				$View = ClassRegistry::getObject( 'view' );
				return $View->element( 'popup' ).$this->link(
								$content, $url, array(
							'escape' => false,
							'title' => $title,
							'onclick' => "var conf = confirm( '".str_replace( "'", "\\'", $confirmMessage )."' ); if( conf ) { impressionCohorte( this ); } return conf;"
								)
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}

		/**
		 *
		 * @param type $title
		 * @param type $htmlAttributes
		 * @param type $enabled
		 * @return type
		 */
		public function printLinkJs( $title, $htmlAttributes = array( ), $enabled = true ) {
			return $this->_buttonLink( 'icons/printer.png', $title, $title, '#', $enabled, Set::merge( array( 'escape' => false ), $htmlAttributes ) );
		}

		/**
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function exportLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/page_white_get.png', 'Télécharger le tableau', $title, $url, $enabled, array( 'class' => 'external' ) );
		}

		/**
		 *  Liens nécessaires pour les décisions et notification de l'APRE
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function notificationsApreLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/application_view_list.png', 'Notifications', $title, $url, $enabled );
		}

		/**
		 *  Liens nécessaires pour les décisions et notification de l'APRE
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function notificationsCer66Link( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/application_view_list.png', 'Notifications OP', $title, $url, $enabled );
		}

		/**
		 * Boutons à utiliser pour les Equipes pluridisciplinaires
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function ordreLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/book_open.png', 'Ordre du jour', $title, $url, $enabled, array( 'class' => 'internal' ) );
		}

		/**
		 * Boutons à utiliser pour les périodes d'immersion des CUIs
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function periodeImmersionLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/page_attach.png', 'Périodes d\'immersion', $title, $url, $enabled, array( 'class' => 'internal' ) );
		}

		/**
		 * Boutons à utiliser pour les périodes d'immersion des CUIs
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function rapportLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/page_attach.png', 'Rapport', $title, $url, $enabled );
		}

		/**
		 * Boutons à utiliser pour les détails des indus
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function remiseLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/money.png', 'Enregistrer les remises', $title, $url, $enabled, array( 'class' => 'internal' ) );
		}

		/**
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function recgraLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/money_add.png', 'Recours gracieux', $title, $url, $enabled, array( 'class' => 'internal' ) );
		}

		/**
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function recconLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/money_delete.png', 'Recours contentieux', $title, $url, $enabled, array( 'class' => 'internal' ) );
		}

		/**
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function relanceLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/hourglass.png', 'Relancer', $title, $url, $enabled, array( 'class' => 'internal' ) );
		}

		/**
		 * Bouton traitement utilisé pour les PDOs d'une personne
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function treatmentLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/page_attach.png', 'Traitements', $title, $url, $enabled );
		}

		/**
		 * Bouton traitement utilisé pour les PDOs d'une personne
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function reorientLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/door_out.png', 'Réorientation', $title, $url, $enabled );
		}

		/**
		 *
		 * @param type $boolean
		 * @param type $showIcon
		 * @return string
		 */
		public function boolean( $boolean, $showIcon = true ) {
			// TODO: avec 1 et 0
			if( is_string( $boolean ) ) {
				if( in_array( $boolean, array( 'O', 'N' ) ) ) {
					$boolean = Set::enum( $boolean, array( 'O' => true, 'N' => false ) );
				}
				else if( in_array( $boolean, array( '1', '0' ) ) ) {
					$boolean = Set::enum( $boolean, array( '1' => true, '0' => false ) );
				}
			}

			if( $boolean === true ) {
				$image = 'icons/accept.png';
				$alt = 'Oui';
			}
			else if( $boolean === false ) {
				$image = 'icons/stop.png';
				$alt = 'Non';
			}
			else {
				return;
			}

			if( $showIcon ) {
				return $this->image( $image, array( 'alt' => '' ) ).' '.$alt;
			}
			else {
				return $alt;
			}
		}

		/**
		 * Bouton traitement utilisé pour les DSPs CG
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function revertToLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/arrow_undo.png', 'Revenir à cette version', $title, $url, $enabled );
		}

		/**
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function saisineEpLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/folder_table.png', $title, $title, $url, $enabled );
		}

		/**
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function presenceLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/pencil.png', 'Présences', $title, $url, $enabled );
		}

		/**
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function reponseLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/pencil.png', 'Réponses', $title, $url, $enabled );
		}

		/**
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function affecteLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/pencil.png', 'Affecter les dossiers', $title, $url, $enabled );
		}

		/**
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function avenantLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/add.png', 'Avenant', $title, $url, $enabled );
		}

		/**
		 * Boutons à utiliser pour les propositions de décisions des PDOs 66
		 *
		 * @param type $title
		 * @param type $url
		 * @param type $enabled
		 * @return type
		 */
		public function propositionDecisionLink( $title, $url, $enabled = true ) {
			return $this->_buttonLink( 'icons/user_comment.png', 'Propositions de décision', $title, $url, $enabled, array( 'class' => 'internal' ) );
		}

	}
?>