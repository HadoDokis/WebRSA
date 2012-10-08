<?php
/*
 * Fichier de description des menus
 *
 *	$menuVar = array(
 *		'menuClass'' => str|array(str),		classe du menu
 *		'itemTag' => str|array(str),		defaut = 'li', nom de la balise des �l�ments du menu
 *		'currentItem' => str|array(str),	nom de la classe utilis�e pour l'�l�ment courant du menu
 *		'items' => array(					liste des �l�ments du menu
 *			str => array(					nom affich� de l'�l�ment du menu
 *				'link' => str,				lien cake du style /nomContoleur/index
 *				'title' => str,				infobulle
 *				'subMenu' => array()		sous-menu qui a la m�me structure que le menu
 *			)
 *		)
 *	)
 *
 */

$menu= array(
	'menuClass' => array('menuNiveau0', 'menuNiveau1'),
	'currentItem' => 'menuCourant',
	'items' => array('Accueil' => array('link' => '/dossiers/index')));
?>
