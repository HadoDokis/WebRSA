<?php
	/**
	 * Initialise MultiDomainsTranslator avec le Router (utilise l'url)
	 * Defini les functions __m et __mn colle d'alias pour MultiDomainsTranslator::translate() et MultiDomainsTranslator::translatePlural()
	 * 
	 * @package MultiDomainsTranslator
	 * @subpackage Utility
	 */
	App::uses( 'MultiDomainsTranslator', 'MultiDomainsTranslator.Utility' );
	App::uses( 'Router', 'Routing' );

	/**
	 * Permet d'obtenir la traduction d'une phrase de façon automatique.
	 * 
	 * @param string $singular
	 * @return string
	 */
	function __m( $singular, $args = null ) {
		return MultiDomainsTranslator::translate( $singular, $args );
	}
	
	/**
	 * Permet d'obtenir la traduction d'une phrase au singulier ou au pluriel de façon automatique.
	 * 
	 * @param string $singular
	 * @param string $plural
	 * @param integer $count
	 * @return string
	 */
	function __mn( $singular, $plural, $count, $args = null ) {
		return MultiDomainsTranslator::translatePlural( $singular, $plural, $count, $args );
	}
?>