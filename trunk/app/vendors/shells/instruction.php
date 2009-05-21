<?php
class ArrayToXML
{
    /**
     * The main function for converting to an XML document.
     * Pass in a multi dimensional array and this recrusively loops through and builds up an XML document.
     *
     * @param array $data
     * @param string $rootNodeName - what you want the root node to be - defaultsto data.
     * @param SimpleXMLElement $xml - should only be used recursively
     * @return string XML
     */
    public static function toXml($data, $rootNodeName = 'data', $xml=null)
    {
        // turn off compatibility mode as simple xml throws a wobbly if you don't.
        if (ini_get('zend.ze1_compatibility_mode') == 1)
        {
            ini_set ('zend.ze1_compatibility_mode', 0);
        }

        if ($xml == null)
        {
            $xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");
        }

        // loop through the data passed in.
        foreach($data as $key => $value)
        {
            // no numeric keys in our xml please!
            if (is_numeric($key))
            {
                // make string key...
                $key = "unknownNode_". (string) $key;
            }

            // replace anything not alpha numeric
            $key = preg_replace('/[^a-z]/i', '', $key);

            // if there is another array found recrusively call this function
            if (is_array($value))
            {
                $node = $xml->addChild($key);
                // recrusive call.
                ArrayToXML::toXml($value, $rootNodeName, $node);
            }
            else
            {
                // add single node.
                                $value = htmlentities($value);
                $xml->addChild($key,$value);
            }

        }
        // pass back as string. or simple xml object if you want!
        return $xml->asXML();
    }
}

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

            switch( $currentTagName ) {
                case 'infodemandersa':
                /**
                    Fin d'un dossier RSA
                */
                    $infodemandersa = simplexml_load_string( ArrayToXML::toXml( $this->_cdataElements['racine']['infodemandersa'], 'infodemandersa' ) );
                    debug( $infodemandersa );
                    $this->_cdataElements['racine']['infodemandersa'] = array();
                    $this->_dossier = null;
                    $this->_foyer = null;
                    break;
                /**

                */
//                 case 'demandersa':
//                     $demandersa = $this->_cdataElements['racine']['infodemandersa']['identificationrsa']['demandersa'];
//
//                     $this->_dossier = $this->Dossier->find(
//                         'first',
//                         array(
//                             'conditions' => array( 'numdemrsa' => $demandersa['numdemrsa'] ),
//                             'recursive' => -1
//                         )
//                     );
//
//                     $this->_dossier['Dossier'] = Set::merge( $this->_dossier['Dossier'], $demandersa );
//
//                     $this->Dossier->create();
//                     $this->Dossier->save( $this->_dossier['Dossier'] );
//
//                     $this->_dossier = $this->Dossier->find(
//                         'first',
//                         array(
//                             'conditions' => array( 'numdemrsa' => $demandersa['numdemrsa'] ),
//                             'recursive' => -1
//                         )
//                     );
//
//                     assert( !empty( $this->_dossier ) );
//                     break;
                /**
                */
//                 case 'logement':
//                     $foyer = $this->_cdataElements['racine']['infodemandersa']['donneesadministratives']['logement'];
//                     $foyer['dossier_rsa_id'] = $this->_dossier['Dossier']['id'];
//
//                     $this->_foyer = $this->Foyer->find(
//                         'first',
//                         array(
//                             'conditions' => array( 'dossier_rsa_id' => $this->_dossier['Dossier']['id'] ),
//                             'recursive' => -1
//                         )
//                     );
//
//                     $this->_foyer['Foyer'] = Set::merge( $this->_foyer['Foyer'], $foyer );
//                     $this->Foyer->create();
//                     $this->Foyer->save( $this->_foyer['Foyer'] );
//
//                     $this->_foyer = $this->Foyer->find(
//                         'first',
//                         array(
//                             'conditions' => array( 'dossier_rsa_id' => $this->_dossier['Dossier']['id'] ),
//                             'recursive' => -1
//                         )
//                     );
//                     assert( !empty( $this->_foyer ) );
//                     break;
                /**
                */
//                 case 'personne':
//                     debug( $this->_foyer );
//                     break;
//                     $personne = $this->_cdataElements['racine']['infodemandersa']['personne']['identification'];
//                     $personne['foyer_id'] = $this->_foyer['Foyer']['id'];
// //                     debug( $personne );
//                     break;
            }

            //-----------------------------------------------------------------

//             else if( $currentTagName == 'personne' ) { // Personne/Identification
//                 // FIXME: trouver si la personne  existe  pour faire les modifs
//                 $personne = $this->_cdataElements['racine']['infodemandersa']['personne']['identification'];
//                 $personne['foyer_id'] = $this->_foyer['Foyer']['id'];
//                 debug( $personne );
// //                 $this->Personne->create();
// //                 $this->Personne->save( $personne );
//
//                 $this->_cdataElements['racine']['infodemandersa']['personne'] = array();
//             }
            array_pop( $this->_stack );
        }

        //*********************************************************************

        function main() {
            $this_start = microtime( true );
            echo "Démarrage: ".date( 'Y-m-d H:i:s' )."\n";

            //-----------------------------------------------------------------

            assert( !empty( $this->Dispatch->args[0] ) );
            assert( file_exists( $this->Dispatch->args[0] ) );

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