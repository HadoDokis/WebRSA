<?php
	App::import( 'Helper', 'Html' );

    class XhtmlHelper extends HtmlHelper
    {
        /** ********************************************************************
        *
        ** ********************************************************************/

        function details( $rows = array(), $htmlAttributes = array(), $allowEmpty = true ) {
			$type = 'list';// FIXME + $allowEmpty + $htmlAttributes -> array options
            $return = null;
            if( count( $rows ) > 0 ) {
                $class = 'odd';
                foreach( $rows as $row ) {
                    if( $allowEmpty || ( !empty( $row[1] ) || valid_int( $row[1] ) ) ) {
						$question = $row[0];
						$answer = ( ( !empty( $row[1] ) || valid_int( $row[1] ) ) ? $row[1] : ' ' );

						if( $type == 'table' ) {
							$return .= $this->tag(
								'tr',
								$this->tag( 'th', $question ).$this->tag( 'td', $answer ),
								array( 'class' => $class )
							);
						}
						else if( $type == 'list' ) {
							$return .= $this->tag( 'dt', $question, array( 'class' => $class ) );
							$return .= $this->tag( 'dd', $answer, array( 'class' => $class ) );
						}

                        $class = ( ( $class == 'odd' ) ? 'even' : 'odd' );
                    }
                }

                if( !empty( $return ) ) {
					if( $type == 'table' ) {
						$return = $this->tag(
							'table',
							$this->tag(
								'tbody',
								$return
							),
							Set::merge( $htmlAttributes, array( 'class' => 'details' ) )
						);
					}
					else if( $type == 'list' ) {
						$return = $this->tag(
							'dl',
							$return,
							Set::merge( $htmlAttributes, array( 'class' => 'details' ) )
						);
					}
                }
            }

            return $return;
        }
    }
?>