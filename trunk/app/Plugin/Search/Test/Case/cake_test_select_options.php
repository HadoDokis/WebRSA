<?php
	/**
	 * Fichier source de la classe CakeTestSelectOptions.
	 *
	 * PHP 5.3
	 * @package Search
	 * @subpackage Test.Case
	 */

	/**
	 * La classe CakeTestSelectOptions permet d'obtenir des options select de
	 * dates par clarifier l'écriture de tests unitaires.
	 *
	 * @todo debug( CakeTestSelectOptions::years( '-1 week', '-120 years 1 week', '-1 week' ) );
	 *
	 * @package Search
	 * @subpackage Test.Case
	 */
	abstract class CakeTestSelectOptions
	{
		public static function years( $fromYear, $toYear, $selectedYear ) {
			$years = range( $fromYear, $toYear, ( $fromYear > $toYear ) ? -1 : 1 );

			foreach( $years as $i => $year ) {
				$selected = ( ( $year == $selectedYear ) ? ' selected="selected"' : '' );
				$years[$i] = "<option value=\"{$year}\"{$selected}>{$year}</option>";
			}

			return implode( "\n", $years );
		}

		public static function months( $selectedMonth ) {
			$months = range( 1, 12 );

			foreach( $months as $i => $month ) {
				$selected = ( ( $month == $selectedMonth ) ? ' selected="selected"' : '' );
				$monthNumber = sprintf( '%02d', $month );
				$monthLabel = strftime( '%B', strtotime( "2014-{$month}-01" ) );
				$months[$i] = "<option value=\"{$monthNumber}\"{$selected}>{$monthLabel}</option>";
			}

			return implode( "\n", $months );
		}

		public static function days( $selectedDay ) {
			$days = range( 1, 31 );

			foreach( $days as $i => $day ) {
				$selected = ( ( $day == $selectedDay ) ? ' selected="selected"' : '' );
				$dayNumber = sprintf( '%02d', $day );
				$dayLabel = $day;
				$days[$i] = "<option value=\"{$dayNumber}\"{$selected}>{$dayLabel}</option>";
			}

			return implode( "\n", $days );
		}
	}
?>
