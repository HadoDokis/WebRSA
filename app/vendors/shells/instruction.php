<?php
    class InstructionShell extends Shell
    {
        var $uses = array( 'Dossier', 'Foyer', 'Personne' );
        var $_xmlFile;
        var $_xmlParser;
        var $_stack = array();
        var $_cdataElements = array();
        var $_tmp = array();

        //*********************************************************************

        function _open( $file ) {
            $this->_xmlFile = $file;
            $this->_xmlParser = xml_parser_create( '' );
            // http://fr.php.net/manual/fr/function.xml-set-element-handler.php#64064
            xml_set_element_handler( $this->_xmlParser, array( $this, 'startElement' ), array( $this, 'endElement' ) );
            xml_set_character_data_handler( $this->_xmlParser, array( $this, 'cdataElement' ) );
            xml_parser_set_option( $this->_xmlParser, XML_OPTION_TARGET_ENCODING, 'ISO-8859-1' );
            xml_parser_set_option( $this->_xmlParser, XML_OPTION_SKIP_WHITE, 1 );
            if( ! ( $this->_xmlFile = fopen( $file, "r" ) ) ) {
                die("could not open XML input");
            }
        }

        //*********************************************************************

        function startElement( $xmlParser, $currentTagName, $attrs ) {
            array_push( $this->_stack, strtolower( $currentTagName ) );
        }

        //-------------------------------------------------------------------------

        function cdataElement( $xmlParser, $data ) {
            $currentTagName = $this->_stack[count( $this->_stack ) - 1];
            $data = trim( $data );
            if( !empty( $data ) ) {
                $current = Set::insert( array(), implode( '.', $this->_stack ), $data );
                $this->_cdataElements = Set::pushDiff( $current, $this->_cdataElements );
            }
        }

        //-------------------------------------------------------------------------

        function endElement( $xmlParser, $currentTagName ) {
            $currentTagName = strtolower( $currentTagName );

            if( $currentTagName == 'demandersa' ) {
                $demandersa = $this->_cdataElements['racine']['infodemandersa']['identificationrsa']['demandersa'];
                $this->Dossier->create();
                $this->Dossier->save( $demandersa );

                $this->_dossier = $this->Dossier->find(
                    'first',
                    array(
                        'conditions' => array( 'numdemrsa' => $demandersa['numdemrsa'] )
                    )
                );

                //-------------------------------------------------------------

                if( !empty( $this->_cdataElements['racine']['infodemandersa']['donneesadministratives'] ) ) {
                    $foyer = $this->_cdataElements['racine']['infodemandersa']['donneesadministratives']['logement'];
                }

                $this->_foyer = $this->Foyer->find(
                    'first',
                    array(
                        'conditions' => array( 'dossier_rsa_id' => $this->_dossier['Dossier']['id'] )
                    )
                );

                if( !empty( $this->_foyer ) ) {
                    $foyer['id'] = $this->_foyer['Foyer']['id'];
                }

                $foyer['dossier_rsa_id'] = $this->_dossier['Dossier']['id'];
                $this->Foyer->create();
                $this->Foyer->save( $foyer );

                $this->_foyer = $this->Foyer->find(
                    'first',
                    array(
                        'conditions' => array( 'dossier_rsa_id' => $this->_dossier['Dossier']['id'] )
                    )
                );

                //-------------------------------------------------------------
debug( $this->_cdataElements['racine'] );
//                 if( !empty( $this->_cdataElements['racine']['infodemandersa']['donneesadministratives'] ) ) {
//                     $foyer = $this->_cdataElements['racine']['infodemandersa']['donneesadministratives']['logement'];
//                 }

//                 $this->_foyer = $this->Foyer->find(
//                     'first',
//                     array(
//                         'conditions' => array( 'dossier_rsa_id' => $this->_dossier['Dossier']['id'] )
//                     )
//                 );
//
//                 if( !empty( $this->_foyer ) ) {
//                     $foyer['id'] = $this->_foyer['Foyer']['id'];
//                 }
//
//                 $foyer['dossier_rsa_id'] = $this->_dossier['Dossier']['id'];
//                 $this->Foyer->create();
//                 $this->Foyer->save( $foyer );
//
//                 $this->_foyer = $this->Foyer->find(
//                     'first',
//                     array(
//                         'conditions' => array( 'dossier_rsa_id' => $this->_dossier['Dossier']['id'] )
//                     )
//                 );

                //-------------------------------------------------------------

                $this->_cdataElements = array();
            }
//             else if( $currentTagName == 'personne' ) { // Personne/Identification
//                 // FIXME: trouver si la personne  existe  pour faire les modifs
//                 $personne = $this->_cdataElements['racine']['infodemandersa']['personne']['identification'];
//                 $personne['foyer_id'] = $this->_foyer['Foyer']['id'];
//                 $this->Personne->create();
//                 $this->Personne->save( $personne );
//
// //                 debug( $personne );
//             }
            array_pop( $this->_stack );
        }

        //*********************************************************************

        function main() {
            // FIXME: assert isset $this->Dispatch->args[0] file_exists( $this->Dispatch->args[0] )
            $this_start = microtime( true );
            echo "Démarrage: ".date( 'Y-m-d H:i:s' )."\n";

            //-----------------------------------------------------------------

            $this->_open( $this->Dispatch->args[0] );
            while( $data = fread( $this->_xmlFile, 4096 ) ) {
                if( !xml_parse( $this->_xmlParser, $data, feof($this->_xmlFile ) ) ) {
                    die( sprintf( "XML error: %s at line %d", xml_error_string( xml_get_error_code( $this->_xmlParser ) ), xml_get_current_line_number( $this->_xmlParser ) ) );
                }
            }

            //-----------------------------------------------------------------

            xml_parser_free( $this->_xmlParser );
            echo "Terminé: ".date( 'Y-m-d H:i:s' )."\n";
            echo number_format( microtime( true ) - $this_start, 2 )."\n";
        }
    }
?>