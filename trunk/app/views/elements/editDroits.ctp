<?php
	/*
		Affiche les menus-controlleurs pour la saisie des droits
		Paramètres :
	*/
	
	?>
		<script type="text/javascript">
			function GereChkbox(conteneur, a_faire) {
				$( conteneur ).getElementsBySelector( 'input[type="checkbox"]' ).each( function( input ) {
					if (a_faire=='cocher') blnEtat=true;
					else if (a_faire=='decocher') blnEtat=false;
					else {
						if ($(input).checked==true) blnEtat=false;
						else blnEtat=true;
					}
					
					$(input).checked=blnEtat;
				} );
			}
            document.observe( "dom:loaded", function() {
                var baseUrl = '<?php echo Router::url( '/', true );?>';
				make_treemenus_droits( baseUrl, <?php echo ( Configure::read( 'UI.menu.large' ) ? 'true' : 'false' );?> );
			} );
		</script>
		<input type="button" value="Tout cocher" onClick="GereChkbox('tableEditDroits','cocher');">&nbsp;&nbsp;&nbsp;
		<input type="button" value="Tout décocher" onClick="GereChkbox('tableEditDroits','decocher');">&nbsp;&nbsp;&nbsp;
		<input type="button" value="Inverser la sélection" onClick="GereChkbox('tableEditDroits','inverser');">
	<?php
				
	echo $javascript->link('droits', true);
	echo $html->tag('table', null, array('cellspacing'=>'0', 'cellpadding'=>'0', 'id'=>'tableEditDroits', 'style'=>'margin-top:20px;'));
	foreach($listeCtrlAction as $rownum => $ctrlAction) {
		$classTd = 'niveau'.$ctrlAction['niveau'];
		if ($ctrlAction['niveau']==0) $ctrlAction['title']='<b>'.$ctrlAction['title'].'</b>';
		$indentation = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $ctrlAction['niveau']);
		$optionsCheckBox = array(
			'label' => '',
			'type' => 'checkbox',
			'id' => 'chkBoxDroits'.$rownum,
			'div' => false
		);
		if ($ctrlAction['nbSousElements']>0)
			$optionsCheckBox['onclick'] = 'toggleCheckBoxDroits('.$rownum.', '.$ctrlAction['nbSousElements'].');';

		echo $html->tag('tr', null,  array('class'=>"$classTd") );
			echo $html->tag('td', ' '.$indentation.$ctrlAction['title'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', array('class'=>"$classTd label"));
			if ($ctrlAction['modifiable']) {
				echo $html->tag('td', $form->input('Droits.'.$ctrlAction['acosAlias'], $optionsCheckBox), array('class'=>$classTd));
			}
			else
				echo $html->tag('td', $form->hidden('Droits.'.$ctrlAction['acosAlias']), array('class'=>$classTd));
		echo $html->tag('/tr');
	}
	echo $html->tag('/table');
?>
