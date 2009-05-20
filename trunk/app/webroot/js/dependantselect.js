    //
    // INFO: 
    // * si on veut avoir les valeurs exactes des select, on peut voir
    //   pour les enlever / remettre avec des classes
    // * les textes qu'on met dans la BDD pour les selects ne peuvent
    //   pas comprendre ' - ' ... ou alors faire ue variable
    //
    // - http://codylindley.com/Webdev/315/ie-hiding-option-elements-with-css-and-dealing-with-innerhtml
    // - http://bytes.com/forum/thread92041.html
    // - http://www.javascriptfr.com/codes/GERER-OPTGROUP-LISTE-DEROULANTE_36855.aspx
    // - http://www.highdots.com/forums/alt-html/optgroup-optgroup-display-none-style-264456.html
    //

    //*****************************************************************

    // var selects = new Array(); // TODO dependantselects
    function dependantSelect( select2Id, select1Id ) {
	// selects[select2Id] = new Array();

	// Nettoyage du texte des options
	$$('#' + select2Id + ' option').each( function ( option ) {
	    var data = $(option).innerHTML;
	    $(option).update( data.replace( new RegExp( '^.* - ', 'gi' ), '' ) );
	} );

	// Sauvegarde
	var select2Values = new Array();
	var select2Options = new Array();
	$$('#' + select2Id + ' option').each( function ( option ) {
	    select2Values.push( option.value );
	    select2Options.push( option.innerHTML );
	} );

	// Vidage de la liste
	$$('#' + select2Id + ' option').each( function ( option ) {
	    if( ( $(option).value != '' ) && ( ( $(option).value != '' ) && ( $(option).value.match( new RegExp( '^' + $F( select1Id ) + '_', 'gi' ) ) == null ) ) )
		$(option).remove();
	} );

	// Onchage event - Partie dynamique
	$(select1Id).onchange = function() {
	    $$('#' + select2Id + ' option').each( function ( option ) {
		$(option).remove();
	    } );

	    for( var i = 0 ; i < select2Values.length ; i++ ) {
		if( select2Values[i] == '' || select2Values[i].match( new RegExp( '^' + $(select1Id).value + '_' ), "g" ) ) {
		    $(select2Id).insert( new Element( 'option', { 'value': select2Values[i] } ).update( select2Options[i] ) );
		}
	    }

	    var opt = $$('#' + select2Id + ' option');
	    opt[0].selected = 'selected';
	    try {
		// INFO -> fonctionne quand même, mais génère une erreur
		$( select2Id ).onchange();
	    }
	    catch(id) {
	    }
	};
    }