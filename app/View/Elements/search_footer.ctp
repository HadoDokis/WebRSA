<?php
	/**
	 * Cet élément retourne différents éléments à placer en-dessous d'un moteur
	 * de recherche (utilisation obligatoire de la pagination):
	 *	- un message d'avetissement si le nombre de résultats dépasse 65000
	 *	- un lien permettant d'imprimer le tableau de résultats (la page)
	 *	- un lien permettant d'exporter les résultats de la recherche au format CSV
	 *
	 * Les paramètres pouvant être passés à cet élément ont les clés suivantes:
	 *	- modelName: le nom du modèle sur lequel se fait la pagination, par défaut
	 *	  le nom du modèle lié au contrôleur courant.
	 *	- url: un array contenant les clés controller (par défaut le nom du
	 *	  contrôleur courant) et action (par défaut exportcsv) de l'action
	 *    permettant de réaliser l'export CSV des résultats de la recherche. Les
	 *    valeurs des filtres du moteur de recherche seront ajoutés à l'URL.
	 */
	$modelName = isset( $modelName ) ? $modelName : Inflector::classify( $this->request->params['controller'] );
	$count = (int)Hash::get( $this->request->params, "paging.{$modelName}.count" );

	$defaultUrl = array( 'controller' => $this->request->params['controller'], 'action' => 'exportcsv' );
	$url = isset( $url ) ? $url : $defaultUrl;
	$url += $defaultUrl;

	$comeFrom = array( 'prevAction' => $this->action );

	if( $count > 65000 ) {
		echo '<p class="noprint" style="border: 1px solid #556; background: #ffe;padding: 0.5em;">'.$this->Xhtml->image( 'icons/error.png' ).'<strong>Attention</strong>, il est possible que votre tableur ne puisse pas vous afficher les résultats au-delà de la 65&nbsp;000ème ligne.</p>';
	}

	echo '<ul class="actionMenu">'
		.'<li>'
			.$this->Xhtml->printLinkJs(
				'Imprimer le tableau',
				array( 'onclick' => 'printit(); return false;', 'class' => 'noprint' )
			)
		.'</li>'
		.'<li>'
		. $this->Xhtml->exportLink(
			'Télécharger le tableau',
			array( 'controller' => $url['controller'], 'action' => $url['action'] ) + Hash::flatten( $this->request->data + $comeFrom, '__' ),
			( $this->Permissions->check( $url['controller'], $url['action'] ) && $count > 0 )
		)
		.'</li>'
	.'</ul>';
?>