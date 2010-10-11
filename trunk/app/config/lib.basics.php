<?php
	/**
	* CREATE INDEX referents_nom_complet_idx ON referents ( ( nom || ' ' || prenom ) );
	* CREATE INDEX personnes_nom_complet_idx ON personnes ( ( nom || ' ' || prenom ) );
	*/

	function __translate( $value ) {
		switch( gettype( $value ) ) {
			case 'NULL':
				$value = 'NULL';
				break;
			case 'boolean':
				$value = ( $value ? 'true' : 'false' );
				break;
			case 'integer':
			case 'double':
				$value = $value;
				break;
			case 'string':
				$value = "'$value'";
				break;
			default:
				trigger_error( gettype( $value ), E_USER_WARNING );
		}
		return $value;
	}

	/**
	*
	*/

	function dump( $datas = false, $showHtml = false, $showFrom = true ) {
		if (Configure::read() > 0) {
			if ($showFrom) {
				$calledFrom = debug_backtrace();
				echo '<strong>' . substr(str_replace(ROOT, '', $calledFrom[0]['file']), 1) . '</strong>';
				echo ' (line <strong>' . $calledFrom[0]['line'] . '</strong>)';
			}
			echo "\n<pre class=\"cake-debug\">\n";

			if( is_array( $datas ) ) {
				$datas = Set::flatten( $datas, '_____' );

				foreach( $datas as $key => $value ) {
					$datas[$key] = __translate( $value );
				}

				$datas = Xset::bump( $datas, '_____' );
			}
			else {
				$datas = __translate( $datas );
			}

			$datas = print_r($datas, true);

			if ($showHtml) {
				$datas = str_replace('<', '&lt;', str_replace('>', '&gt;', $datas));
			}
			echo $datas . "\n</pre>\n";
		}
	}

    /**
        * ...
        *
        * @param array $array
        * @param array $filterKeys
        * @return array $newArray
    */

    function array_filter_keys( array $array, array $filterKeys, $remove = false ) { // FIXME ?
        $newArray = array();
        foreach( $array as $key => $value) {
            if( $remove && !in_array( $key, $filterKeys ) ) {
                $newArray[$key] = $value;
            }
            else if( !$remove && in_array( $key, $filterKeys ) ) {
                $newArray[$key] = $value;
            }
        }
        return $newArray;
    }

	/**
	* FIXME: remplacer la wootFonction dans AppModel
	* @param array $array
	* @param array $replacements
	* @return array
	*/

	function recursive_key_value_preg_replace( array $array, array $replacements ) {
		$newArray = array();
		foreach( $array as $key => $value ) {
			foreach( $replacements as $pattern => $replacement ) {
				$key = preg_replace( $pattern, $replacement, $key );
			}

			if( is_array( $value ) ) {
				$value = recursive_key_value_preg_replace( $value, $replacements );
			}
			else {
				foreach( $replacements as $pattern => $replacement ) {
					$value = preg_replace( $pattern, $replacement, $value );
				}
			}
			$newArray[$key] = $value;
		}
		return $newArray;
	}

	/**
	* INFO: http://fr.php.net/manual/fr/function.strpos.php#49739
	*/

	function strallpos( $pajar, $aguja, $offset = 0, &$count = null ) {
		if( $offset > strlen( $pajar ) ) {
			trigger_error("strallpos(): Offset not contained in string.", E_USER_WARNING);
		}
		$match = array();
		for( $count = 0; ( ( $pos = strpos( $pajar, $aguja, $offset ) ) !== false ); $count++ ) {
			$match[] = $pos;
			$offset = $pos + strlen( $aguja );
		}
		return $match;
	}

	/**
	* Extracts model name and field name from a path.
	* @param string $path ie. User.username, User.0.id
	* @return array( string $model, string $field ) ie. array( 'User', 'username' ), array( 'User', 'id' )
	*/

	function model_field( $path ) {
		if( preg_match( "/(?<!\w)(\w+)(\.|\.[0-9]+\.)(\w+)$/", $path, $matches ) ) {
			return array( $matches[1], $matches[3] );
		}

		trigger_error( "Could not extract model and field names from the following path: \"{$path}\"", E_USER_WARNING );
		return null;
	}

	/**
	* Returns a human-readable amount from a number of bytes
	* SOURCE: http://www.phpfront.com/php/Convert-Bytes-to-corresponding-size/
	*/

	function byteSize( $bytes ) {
		$size = $bytes / 1024;
		if($size < 1024) {
			$size = number_format($size, 2);
			$size .= ' KB';
		}
		else {
			if($size / 1024 < 1024) {
				$size = number_format($size / 1024, 2);
				$size .= ' MB';
			}
			else if ($size / 1024 / 1024 < 1024) {
				$size = number_format($size / 1024 / 1024, 2);
				$size .= ' GB';
			}
		}
		return $size;
	}
?>