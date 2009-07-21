<?php
    class RefreshShell extends Shell
    {
        var $uses = array( 'Foyer' );

        function main() {
            $this_start = microtime( true );
            echo "Démarrage: ".date( 'Y-m-d H:i:s' )."\n";

            //-----------------------------------------------------------------

            $this->Foyer->begin();
            $saved = true;

            //*****************************************************************

            // rgadr sur deux chiffres -> Réparation des données du flux CAF
//             $adressesFoyers = $this->Foyer->Adressefoyer->find( 'list', array( 'fields' => array( 'Adressefoyer.id', 'Adressefoyer.rgadr' ) ) );
//             foreach( $adressesFoyers as $id => $rgadr ) {
//                 $rgadr = trim( $rgadr );
//                 $rgadr = ( ( strlen( $rgadr ) == 2 ) ? $rgadr : '0'.$rgadr );
//                 $this->Foyer->Adressefoyer->create( array( 'Adressefoyer' => array( 'id' => $id, 'rgadr' => $rgadr ) ) );
//                 $saved = $this->Foyer->Adressefoyer->save() && $saved;
//             }

            //-----------------------------------------------------------------

            // FIXME: calculer et sauvegarder les mtpersressmenrsa
            // FIXME: ajouter une entrée dans la table orientsstructs ?

            //-----------------------------------------------------------------

            $foyers = $this->Foyer->find( 'list', array( 'fields' => array( 'Foyer.id', 'Foyer.id' ), 'order' => 'Foyer.id ASC' ) );
            foreach( $foyers as $foyer_id ) {
                $saved = $this->Foyer->refreshSoumisADroitsEtDevoirs( $foyer_id ) && $saved;
            }

            //-----------------------------------------------------------------

            if( $saved ) {
                $this->Foyer->commit();
                echo "Succès\n";
            }
            else {
                $this->Foyer->rollback();
                echo "Erreur\n";
            }

            //-----------------------------------------------------------------

            echo "Terminé: ".date( 'Y-m-d H:i:s' )."\n";
            echo number_format( microtime( true ) - $this_start, 2 )."\n";
        }
    }
?>