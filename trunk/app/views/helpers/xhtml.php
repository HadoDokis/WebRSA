<?php
	App::import( 'Helper', 'Html' );

    class XhtmlHelper extends HtmlHelper
    {
        /** ********************************************************************
        *
        ** ********************************************************************/

        function details( $rows = array(), $htmlAttributes = array(), $allowEmpty = true ) {
            $return = null;
            if( count( $rows ) > 0 ) {
                $class = 'odd';
                foreach( $rows as $row ) {
                    if( $allowEmpty || ( !empty( $row[1] ) || valid_int( $row[1] ) ) ) {
                        $return .= $this->tag(
                            'tr',
                            $this->tag( 'th', $row[0] ).$this->tag( 'td', ( ( !empty( $row[1] ) || valid_int( $row[1] ) ) ? $row[1] : ' ' ) ),
                            array( 'class' => $class )
                        );
                        $class = ( ( $class == 'odd' ) ? 'even' : 'odd' );
                    }
                }

                if( !empty( $return ) ) {
                    $return = $this->tag(
                        'table',
                        $this->tag(
                            'tbody',
                            $return
                        ),
                        Set::merge( $htmlAttributes, array( 'class' => 'details' ) )
                    );
                }
            }

            return $return;
        }
    }
?>