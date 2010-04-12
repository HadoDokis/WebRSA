<?php
	App::import( 'Helper', 'Html' );

    class XhtmlHelper extends HtmlHelper
    {
        /** ********************************************************************
        *
        ** ********************************************************************/

		function details( $rows = array(), $options = array(), $oddOptions = array( 'class' => 'odd'), $evenOptions = array( 'class' => 'even') ) {
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
    }
?>