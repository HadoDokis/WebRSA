<?php
	App::import( 'Helper', 'Html' );

	class XhtmlHelper extends HtmlHelper
	{
		/**
		* Ajout d'un paramètre "enabled" dans $htmlAttributes qui permet de marquer
		* un lien comme "désactivé" (texte grisé) lorsque "enabled" vaut "false".
		*/

		public function link( $title, $url = null, $htmlAttributes = array(), $confirmMessage = false, $escapeTitle = true ) {
			if( isset( $htmlAttributes['enabled'] ) && $htmlAttributes['enabled'] == false ) {
				if( $escapeTitle ) {
					$title = h( $title );
				}
				$htmlAttributes['class'] = ( isset( $htmlAttributes['class'] ) ? "{$htmlAttributes['class']} disabled" : "disabled" );

				return "<span class=\"{$htmlAttributes['class']}\">{$title}</span>";
			}
			else {
                unset( $htmlAttributes['enabled'] );
				return parent::link( $title, $url, $htmlAttributes, $confirmMessage, $escapeTitle );
			}
		}

		public function tag( $name, $text = null, $attributes = array(), $escape = false ) {
			if( is_null( $text ) || strlen( $text ) == 0 ) {
				$text = ' ';
			}
			return parent::tag( $name, $text, $attributes, $escape );
		}

		/**
		*
		*/

		public function details( $rows = array(), $options = array(), $oddOptions = array( 'class' => 'odd'), $evenOptions = array( 'class' => 'even') ) {
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
					if( $allowEmpty || ( !empty( $row[1] ) || valid_int( $row[1] ) ) ) {
						// TODO ?
						$currentOptions = ( ( $class == 'even' ) ? $evenOptions : $oddOptions );

						if( ( empty( $row[1] ) && !valid_int( $row[1] ) ) ) {
							$currentOptions = $this->addClass( $currentOptions, 'empty' );
						}

						$classes = Set::classicExtract( $currentOptions, 'class' );
						if( ( !empty( $row[1] ) || valid_int( $row[1] ) ) ) {
							$currentOptions['class'] = implode( ' ', Set::merge( $classes, array( 'answered' ) ) );
						}

						$question = $row[0];
						$answer = ( ( !empty( $row[1] ) || valid_int( $row[1] ) ) ? $row[1] : ' ' );

						if( $type == 'table' ) {
							$return .= $this->tag(
								'tr',
								$this->tag( 'th', $question ).$this->tag( 'td', $answer ),
								$currentOptions
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
							'table',
							$this->tag(
								'tbody',
								$return
							),
							$options
						);
					}
					else if( $type == 'list' ) {
						$return = $this->tag(
							'dl',
							$return,
							$options
						);
					}
				}
			}

			return $return;
		}

		/**
		*
		*/

		public function addLink( $title, $url, $enabled = true ) {
			$content = $this->image(
					'icons/add.png',
					array( 'alt' => '' )
				).' Ajouter';

			if( $enabled ) {
				return $this->link(
					$content,
					$url,
					array( 'escape' => false, 'title' => $title )
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}

		/**
		* Bouton ajouter pour les nouveaux comités
		*/

		public function addComiteLink( $title, $url, $enabled = true ) {
			$content = $this->image(
					'icons/add.png',
					array( 'alt' => '' )
				).' Ajouter un nouveau Comité';

			if( $enabled ) {
				return $this->link(
					$content,
					$url,
					array( 'escape' => false, 'title' => $title )
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}

		/**
		* Boutons à utiliser pour les Equipes pluridisciplinaires
		*/

		public function addEquipeLink( $title, $url, $enabled = true ) {
			$content = $this->image(
					'icons/add.png',
					array( 'alt' => '' )
				).' Ajouter une nouvelle Equipe';

			if( $enabled ) {
				return $this->link(
					$content,
					$url,
					array( 'escape' => false, 'title' => $title )
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}

		/**
		*
		*/

		public function addSimpleLink( $title, $url ) {
			return $this->link(
				$this->image(
					'icons/add.png',
					array( 'alt' => '' )
				).' Ajouter une préconisation d\'orientation',
				$url,
				array( 'escape' => false, 'title' => $title )
			);
		}

		/**
		*
		*/

		public function addPieceLink( $title, $url ) {
			return $this->link(
				$this->image(
					'icons/add.png',
					array( 'alt' => '' )
				).' Ajouter une pièce',
				$url,
				array( 'escape' => false, 'title' => $title )
			);
		}

        /**
        *
        */

        public function cancelLink( $title, $url, $enabled = true ) {
            $content = $this->image(
                    'icons/cancel.png',
                    array( 'alt' => '' )
                ).' Annuler';
                if( $enabled ) {
                    return $this->link(
                        $content,
                        $url,
                        array( 'escape' => false, 'title' => $title ),
                        $title.' ?'
                    );
                }
                else{
                    return '<span class="disabled">'.$content.'</span>';
                }
        }

		/**
		*
		*/

		public function editLink( $title, $url, $enabled = true ) {
			$content = $this->image(
				'icons/pencil.png',
				array( 'alt' => '' )
			).' Modifier';

			if( $enabled ) {
				return $this->link(
					$content,
					$url,
					array( 'escape' => false, 'title' => $title )
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}

        /**
        *
        */

        public function fileLink( $title, $url, $enabled = true ) {
            $content = $this->image(
                'icons/attach.png',
                array( 'alt' => '' )
            ).' Fichiers liés';

            if( $enabled ) {
                return $this->link(
                    $content,
                    $url,
                    array( 'escape' => false, 'title' => $title )
                );
            }
            else {
                return '<span class="disabled">'.$content.'</span>';
            }
        }
		/**
		*
		*/

		public function validateLink( $title, $url, $enabled = true ) {
			$content = $this->image(
				'icons/tick.png',
				array( 'alt' => '' )
			).' Valider';

			if( $enabled ) {
				return $this->link(
					$content,
					$url,
					array( 'escape' => false, 'title' => $title )
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}

		/**
		*
		*/

		public function deleteLink( $title, $url, $enabled = true ) {
			$content = $this->image(
					'icons/delete.png',
					array( 'alt' => '' )
				).' Supprimer';
				if( $enabled ) {
					return $this->link(
						$content,
						$url,
						array( 'escape' => false, 'title' => $title ),
						$title.' ?'
					);
				}
				else{
					return '<span class="disabled">'.$content.'</span>';
				}
		}

		/**
		*
		*/

		public function viewLink( $title, $url, $enabled = true, $external = false ) {
			$content = $this->image(
				'icons/zoom.png',
				array( 'alt' => '' )
			).' Voir';

			if( $enabled ) {
				return $this->link(
					$content,
					$url,
					array( 'escape' => false, 'title' => $title, 'class' => ( $external ? 'external' : 'internal' ) )
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}

		/**
		*
		*/

		public function actionsLink( $title, $url, $enabled = true ) {
			$content = $this->image(
				'icons/lightning.png',
				array( 'alt' => '' )
			).' Actions';

			if( $enabled ) {
				return $this->link(
					$content,
					$url,
					array( 'escape' => false, 'title' => $title )
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}

		/**
		*
		*/

		public function aidesLink( $title, $url ) {
			return $this->link(
				$this->image(
					'icons/ruby.png',
					array( 'alt' => '' )
				).' Aides',
				$url,
				array( 'escape' => false, 'title' => $title )
			);
		}

		public function ajoutcomiteLink( $title, $url ) {
			return $this->link(
				$this->image(
					'icons/add.png',
					array( 'alt' => '' )
				).' Ajout comité',
				$url,
				array( 'escape' => false, 'title' => $title )
			);
		}

		/**
		*
		*/

		public function attachLink( $title, $url, $enabled = true ){
			$content = $this->image(
				'icons/attach.png',
				array( 'alt' => '' )
			).' Visualiser';

			if( $enabled ) {
				return $this->link(
					$content,
					$url,
					array( 'escape' => false, 'title' => $title )
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}

		/**
		* Boutons à utiliser pour les Equipes pluridisciplinaires
		*/

		public function conseilLink( $title, $url, $enabled = true ) {
			$content = $this->image(
				'icons/door_out.png',
				array( 'alt' => '' )
			).' Traitement par CG';

			if( $enabled ) {
				return $this->link(
					$content,
					$url,
					array( 'escape' => false, 'title' => $title , 'class' => 'internal' )
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}
        /**
        * Boutons à utiliser pour les courriers à envoyer aux allocataires
        */

        public function courrierLink( $title, $url, $enabled = true ) {
            $content = $this->image(
                'icons/page_white_text.png',
                array( 'alt' => '' )
            ).' Courrier d\'information';

            if( $enabled ) {
                return $this->link(
                    $content,
                    $url,
                    array( 'escape' => false, 'title' => $title , 'class' => 'internal' )
                );
            }
            else {
                return '<span class="disabled">'.$content.'</span>';
            }
        }

		/**
		* Boutons à utiliser pour les Equipes pluridisciplinaires
		*/

		public function decisionLink( $title, $url, $enabled = true ) {
			$content = $this->image(
				'icons/user_comment.png',
				array( 'alt' => '' )
			).' Décisions';

			if( $enabled ) {
				return $this->link(
					$content,
					$url,
					array( 'escape' => false, 'title' => $title , 'class' => 'internal' )
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}

		/**
		* Boutons à utiliser pour les Equipes pluridisciplinaires
		*/

		public function equipeLink( $title, $url, $enabled = true ) {
			$content = $this->image(
				'icons/door_out.png',
				array( 'alt' => '' )
			).' Traitement par équipe';

			if( $enabled ) {
				return $this->link(
					$content,
					$url,
					array( 'escape' => false, 'title' => $title , 'class' => 'internal' )
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}

		/**
		*
		*/

		public function printLink( $title, $url, $enabled = true ) {
			$content = $this->image(
				'icons/printer.png',
				array( 'alt' => '' )
			).' Imprimer';

			if( $enabled ) {
				return $this->link(
					$content,
					$url,
					array( 'escape' => false, 'title' => $title )
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}

		/**
		*
		*/

		public function printListLink( $title, $url, $enabled = true ) {
			$content = $this->image(
				'icons/printer.png',
				array( 'alt' => '' )
			).' Version imprimable';

			if( $enabled ) {
				return $this->link(
					$content,
					$url,
					array( 'escape' => false, 'title' => $title , 'class' => 'external' )
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}

		/**
		*
		*/

		public function printCohorteLink( $title, $url, $enabled = true, $confirmMessage = 'Etes-vous sûr de vouloir imprimer la cohorte ?' ) {
			$content = $this->image(
				'icons/printer.png',
				array( 'alt' => '' )
			).' Imprimer la cohorte';

			if( $enabled ) {
				$View = ClassRegistry::getObject('view');
				return $View->element( 'popup' ).$this->link(
					$content,
					$url,
					array(
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
		*/

		public function printLinkJs( $title, $htmlAttributes = array(), $enabled = true ) {
			$content = $this->image(
				'icons/printer.png',
				array( 'alt' => '' )
			).' '.$title;

			if( $enabled ) {
				return $this->link(
					$content,
					'#',
					Set::merge( array( 'escape' => false ), $htmlAttributes )
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}

		/**
		*
		*/

		public function exportLink( $title, $url, $enabled = true ) {
			$content = $this->image(
				'icons/page_white_get.png',
				array( 'alt' => '' )
			).' Télécharger le tableau';

			if( $enabled ) {
				return $this->link(
					$content,
					$url,
					array( 'escape' => false, 'title' => $title , 'class' => 'external' )
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}

		/**
		*  Liens nécessaires pour les décisions et notification de l'APRE
		*/

		public function notificationsApreLink( $title, $url, $enabled = true ){
			$content = $this->image(
				'icons/application_view_list.png',
				array( 'alt' => '' )
			).' Notifications';

			if( $enabled ) {
				return $this->link(
					$content,
					$url,
					array( 'escape' => false, 'title' => $title )
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}

		/**
		*  Liens nécessaires pour les décisions et notification de l'APRE
		*/

		public function notificationsCer66Link( $title, $url, $enabled = true ){
			$content = $this->image(
				'icons/application_view_list.png',
				array( 'alt' => '' )
			).' Notifications OP';

			if( $enabled ) {
				return $this->link(
					$content,
					$url,
					array( 'escape' => false, 'title' => $title )
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}

		/**
		* Boutons à utiliser pour les Equipes pluridisciplinaires
		*/

		public function ordreLink( $title, $url, $enabled = true ) {
			$content = $this->image(
				'icons/book_open.png',
				array( 'alt' => '' )
			).' Ordre du jour';

			if( $enabled ) {
				return $this->link(
					$content,
					$url,
					array( 'escape' => false, 'title' => $title , 'class' => 'internal' )
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}

		/**
		* Boutons à utiliser pour les périodes d'immersion des CUIs
		*/

		public function periodeImmersionLink( $title, $url, $enabled = true ) {
			$content = $this->image(
				'icons/page_attach.png',
				array( 'alt' => '' )
			).' Périodes d\'immersion';

			if( $enabled ) {
				return $this->link(
					$content,
					$url,
					array( 'escape' => false, 'title' => $title , 'class' => 'internal' )
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}

		/**
		* Boutons à utiliser pour les périodes d'immersion des CUIs
		*/

		public function rapportLink( $title, $url, $enabled = true ){
			$content = $this->image(
				'icons/page_attach.png',
				array( 'alt' => '' )
			).' Rapport';

			if( $enabled ) {
				return $this->link(
					$content,
					$url,
					array( 'escape' => false, 'title' => $title )
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}
		/**
		* Boutons à utiliser pour les détails des indus
		*/

		public function remiseLink( $title, $url, $enabled = true ) {
			$content = $this->image(
				'icons/money.png',
				array( 'alt' => '' )
			).' Enregistrer les remises';

			if( $enabled ) {
				return $this->link(
					$content,
					$url,
					array( 'escape' => false, 'title' => $title , 'class' => 'internal' )
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}

		/**
		*
		*/

		public function recgraLink( $title, $url, $enabled = true ) {
			$content = $this->image(
				'icons/money_add.png',
				array( 'alt' => '' )
			).' Recours gracieux';

			if( $enabled ) {
				return $this->link(
					$content,
					$url,
					array( 'escape' => false, 'title' => $title , 'class' => 'internal' )
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}

		/**
		*
		*/

		public function recconLink( $title, $url, $enabled = true ) {
			$content = $this->image(
				'icons/money_delete.png',
				array( 'alt' => '' )
			).' Recours contentieux';

			if( $enabled ) {
				return $this->link(
					$content,
					$url,
					array( 'escape' => false, 'title' => $title , 'class' => 'internal' )
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}

		/**
		*
		*/

		public function relanceLink( $title, $url, $enabled = true ) {
			$content = $this->image(
				'icons/hourglass.png',
				array( 'alt' => '' )
			).' Relancer';

			if( $enabled ) {
				return $this->link(
					$content,
					$url,
					array( 'escape' => false, 'title' => $title , 'class' => 'internal' )
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}

		/**
		* Bouton traitement utilisé pour les PDOs d'une personne
		*/

		public function treatmentLink( $title, $url, $enabled = true ){
			$content = $this->image(
				'icons/page_attach.png',
				array( 'alt' => '' )
			).' Traitements';

			if( $enabled ) {
				return $this->link(
					$content,
					$url,
					array( 'escape' => false, 'title' => $title )
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}

		/**
		* Bouton traitement utilisé pour les PDOs d'une personne
		*/

		public function reorientLink( $title, $url, $enabled = true ){
			$content = $this->image(
				'icons/door_out.png',
				array( 'alt' => '' )
			).' Réorientation';

			if( $enabled ) {
				return $this->link(
					$content,
					$url,
					array( 'escape' => false, 'title' => $title )
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}

		/**
		*
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
			else if( $boolean === false ){
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
		*/

		function revertToLink( $title, $url, $enabled = true ){
			$content = $this->image(
				'icons/arrow_undo.png',
				array( 'alt' => '' )
			).' Revenir à cette version';

			if( $enabled ) {
				return $this->link(
					$content,
					$url,
					array( 'escape' => false, 'title' => $title )
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}

		/**
		*
		*/

		function saisineEpLink( $title, $url, $enabled = true ) {
			$content = $this->image(
				'icons/folder_table.png',
				array( 'alt' => '' )
			).' '.$title;

			if( $enabled ) {
				return $this->link(
					$content,
					$url,
					array( 'escape' => false, 'title' => $title )
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}

			/**
		*
		*/

		function presenceLink( $title, $url, $enabled = true ) {
			$content = $this->image(
				'icons/pencil.png',
				array( 'alt' => '' )
			).' 4. Présences';

			if( $enabled ) {
				return $this->link(
					$content,
					$url,
					array( 'escape' => false, 'title' => $title )
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}

            /**
        *
        */

        function reponseLink( $title, $url, $enabled = true ) {
            $content = $this->image(
                'icons/pencil.png',
                array( 'alt' => '' )
            ).' Réponses';

            if( $enabled ) {
                return $this->link(
                    $content,
                    $url,
                    array( 'escape' => false, 'title' => $title )
                );
            }
            else {
                return '<span class="disabled">'.$content.'</span>';
            }
        }

		/**
		*
		*/

		public function affecteLink( $title, $url, $enabled = true ) {
			$content = $this->image(
				'icons/pencil.png',
				array( 'alt' => '' )
			).' Affecter les dossiers';

			if( $enabled ) {
				return $this->link(
					$content,
					$url,
					array( 'escape' => false, 'title' => $title )
				);
			}
			else {
				return '<span class="disabled">'.$content.'</span>';
			}
		}

	}
?>