<?php

	class ImportcsvcoderomedspShell extends Shell {

		public $uses = array( 'Coderomesecteurdsp66', 'Coderomemetierdsp66' );

		public function main() {
			if ( ( $this->args && $this->args[0] == '?' ) || ( count( $this->args ) < 2 ) ) {
				return $this->out('Usage: ./cake importcsvcoderomedsp fichier_secteur.csv fichier_metier.csv');
			}

			if ( ( fopen( $this->args[0], 'r' ) ) === false ) {
				return $this->out('Le fichier des codes secteurs ne peut être lu');
			}
			elseif ( ( fopen( $this->args[1], 'r' ) ) === false ) {
				return $this->out('Le fichier des codes métiers ne peut être lu');
			}
			else {
				$this->{$this->modelClass}->begin();
				$fileError = false;
				$success = true;

				if ( ( $csv = fopen( $this->args[0], 'r' ) ) !== false ) {
					$this->out(sprintf('-> fichier %s ouvert', $this->args[0]));
					$first = true;
					while ( ( $data = fgetcsv( $csv, 4096, ";" ) ) !== false && !$fileError ) {
						if ( $first ) {
							$first = false;
							if ( !isset( $data[1] ) ) {
								$fileError = true;
							}
						}
						else {
							$code = $data[0];
							$name = $data[1];

							$findsecteur = $this->Coderomesecteurdsp66->find(
								'first',
								array(
									'conditions' => array(
										'code' => $code
									),
									'contain' => false
								)
							);
							if ( empty( $findsecteur ) ) {
								$coderomesecteurdsp66['Coderomesecteurdsp66']['code'] = $code;
								$coderomesecteurdsp66['Coderomesecteurdsp66']['name'] = $name;
								$this->Coderomesecteurdsp66->create($coderomesecteurdsp66);
								$success = $this->Coderomesecteurdsp66->save() && $success;
							}
						}
					}
					fclose($csv);
				}
				else {
					$fileError = true;
				}

				if ( ( $csv = fopen( $this->args[1], 'r' ) ) !== false ) {
					$this->out(sprintf('-> fichier %s ouvert', $this->args[1]));
					$first = true;
					while ( ( $data = fgetcsv( $csv, 4096, ";" ) ) !== false && !$fileError ) {
						if ( $first ) {
							$first = false;
							if ( !isset( $data[2] ) ) {
								$fileError = true;
							}
						}
						else {
							$codeSecteur = $data[0];
							$code = $data[1];
							$name = $data[2];

							$secteur = $this->Coderomesecteurdsp66->find(
								'first',
								array(
									'conditions' => array(
										'code' => $codeSecteur
									),
									'contain' => false
								)
							);

							$coderomemetierdsp66['Coderomemetierdsp66']['coderomesecteurdsp66_id'] = $secteur['Coderomesecteurdsp66']['id'];
							$coderomemetierdsp66['Coderomemetierdsp66']['code'] = $code;
							$coderomemetierdsp66['Coderomemetierdsp66']['name'] = $name;
							$this->Coderomemetierdsp66->create($coderomemetierdsp66);
							$success = $this->Coderomemetierdsp66->save() && $success;
						}
					}
					fclose($csv);
				}
				else {
					$fileError = true;
				}

				if ( !$fileError && $success ) {
					$this->out( "Script terminé avec succès" );
					$this->{$this->modelClass}->commit();
				}
				else {
					$this->out( "Script terminé avec erreurs" );
					$this->{$this->modelClass}->rollback();
				}
			}
		}
	}

?>