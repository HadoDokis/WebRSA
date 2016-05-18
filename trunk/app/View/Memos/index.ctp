<?php  $this->pageTitle = 'Mémos concernant la personne';
	App::uses('WebrsaAccess', 'Utility');
	WebrsaAccess::init($dossierMenu);
	
	$domain = current(MultiDomainsTranslator::urlDomains());
	$defaultParams = compact('options', 'domain');
?>

<h1>Mémos</h1>
<?php echo $this->element( 'ancien_dossier' );?>
<ul class="actionMenu">
	<li><?php
			echo $this->Xhtml->addLink(
				'Ajouter',
				array(
					'action' => 'add',
					$personne_id
				),
				WebrsaAccess::addIsEnabled('/Memos/add', $ajoutPossible)
			);
		?>
	</li>
</ul>
<?php
	// Formatage des textes de mémos pour la liste
	if( !empty( $memos ) ) {
		$names = Hash::extract( $memos, '{n}.Memo.name' );

		foreach( $names as $key => $value ) {
			$value = String::truncate( $value, 250 );
			$value = nl2br( $value );

			$memos[$key]['Memo']['name'] = $value;
		}
	}
	
	echo $this->Default3->index(
		$memos,
		array(
			'Memo.name',
			'Memo.created',
			'Memo.modified',
		) + WebrsaAccess::links(
			array(
				'/Memos/view/#Memo.id#',
				'/Memos/edit/#Memo.id#',
				'/Memos/delete/#Memo.id#' => array('confirm' => true),
				'/Memos/filelink/#Memo.id#' => array('msgid' => __m('/Memos/filelink').' (#Memo.nb_fichiers_lies#)'),
			)
		),
		$defaultParams + array(
			'paginate' => false,
		)
	);