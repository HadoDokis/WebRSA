//-----------------------------------------------------------------------------

function make_folded_forms() {
    $$( 'form.folded' ).each( function( elmt ) {
//         var a = new Element( 'a', { 'class': 'toggler', 'href': '#', 'onclick' : '$( \'' + $( elmt ).id + '\' ).toggle(); return false;' } ).update( 'Visibilité formulaire' );
//         var p = a.wrap( 'p' );
//         $( elmt ).insert( { 'before' : p } );
        $( elmt ).hide();
    } );
}

//-----------------------------------------------------------------------------

function make_treemenus( absoluteBaseUrl, large ) {
    var dir = absoluteBaseUrl + 'img/icons';
    $$( '.treemenu li' ).each( function ( elmtLi ) {
        if( elmtLi.down( 'ul' ) ) {
            if( large ) {
                var img = new Element( 'img', { 'src': dir + '/bullet_toggle_plus2.png', 'alt': 'Étendre', 'width': '12px'
                } );
            }
            else  {
                var img = new Element( 'img', { 'src': dir + '/bullet_toggle_plus2.png', 'alt': 'Étendre' } );
            }
            var link = img.wrap( 'a', { 'href': '#', 'class' : 'toggler' } );
            var sign = '+';

            $( link ).observe( 'click', function( event ) {
                var innerUl = $( this ).up( 'li' ).down( 'ul' );
                innerUl.toggle();
                if( innerUl.visible() ) {
                    $( this ).down( 'img' ).src = dir + '/bullet_toggle_minus2.png';
                    $( this ).down( 'img' ).alt = 'Réduire';
                }
                else {
                    $( this ).down( 'img' ).src = dir + '/bullet_toggle_plus2.png';
                    $( this ).down( 'img' ).alt = 'Étendre';
                }
                return false;
            } );

            $( elmtLi ).down( 1 ).insert( { 'before' : link } );
            $( elmtLi ).down( 'ul' ).hide();
        }
    } );

    var currentUrl = location.href.replace( new RegExp( '(#.*)$' ), '' ).replace( absoluteBaseUrl, '/' );
    var relBaseUrl = absoluteBaseUrl.replace( new RegExp( '^(http://[^/]+/)' ), '/' );

    $$( '.treemenu a' ).each( function ( elmtA ) {
        // FIXME: plus propre
        if( elmtA.href.replace( absoluteBaseUrl, '/' ) == currentUrl || elmtA.href.replace( absoluteBaseUrl, '/' ) == currentUrl.replace( '/edit/', '/view/' ) || elmtA.href.replace( absoluteBaseUrl, '/' ) == currentUrl.replace( '/add/', '/view/' ) || elmtA.href.replace( absoluteBaseUrl, '/' ) == currentUrl.replace( '/add/', '/index/' ) ) {
            // Montrer tous les ancètres
            elmtA.ancestors().each( function ( aAncestor ) {
                aAncestor.show();
                if( aAncestor.tagName == 'LI' ) {
                    var toggler = aAncestor.down( 'a.toggler img' );
                    if( toggler != undefined ) {
                        toggler.src = dir + '/bullet_toggle_minus2.png';
                        toggler.alt = 'Réduire';
                    }
                }
            } );
            $( elmtA ).addClassName( 'selected' );
            // Montrer son descendant direct
            try {
                var upLi = elmtA.up( 'li' );
                if( upLi != undefined ) {
                    var ul = upLi.down( 'ul' );
                    if( ul != undefined ) {
                        ul.show();
                    }
                }
            }
            catch( e ) {
            }
        }
    } );
}

/// Fonction permettant "d'enrouler" le menu du dossier allocataire
function expandableTreeMenuContent( elmt, sign, dir ) {
    $( elmt ).up( 'ul' ).getElementsBySelector( 'li > a.toggler' ).each( function( elmtA ) {
        if( sign == 'plus' ) {
            elmtA.up( 'li' ).down( 'ul' ).show();
        }
        else {
            elmtA.up( 'li' ).down( 'ul' ).hide();
        }

        if( elmtA.down( 'img' ) != undefined ) {
            if( sign == 'plus' ) {
                elmtA.down( 'img' ).src = dir + '/bullet_toggle_minus2.png';
                elmtA.down( 'img' ).alt = 'Réduire';
            }
            else {
                elmtA.down( 'img' ).src = dir + '/bullet_toggle_plus2.png';
                elmtA.down( 'img' ).alt = 'Étendre';
            }
        }
    } );
}

/// Fonction permettant "de dérouler" le menu du dossier allocataire
function treeMenuExpandsAll( absoluteBaseUrl ) {

    var toggleLink = $( 'treemenuToggleLink' );
    var dir = absoluteBaseUrl + 'img/icons';

    var sign = $( toggleLink ).down( 'img' ).src.replace( new RegExp( '^.*(minus|plus).*' ), '$1' );

    $$( '.treemenu > ul > li > a.toggler' ).each( function ( elmtA ) {
        // Montrer tous les ancètres
        if( sign == 'plus' ) {
            elmtA.up( 'li' ).down( 'ul' ).show();
        }
        else {
            elmtA.up( 'li' ).down( 'ul' ).hide();
        }

        if( elmtA.down( 'img' ) != undefined ) {
            if( sign == 'plus' ) {
                elmtA.down( 'img' ).src = dir + '/bullet_toggle_minus2.png';
                elmtA.down( 'img' ).alt = 'Réduire';
            }
            else {
                elmtA.down( 'img' ).src = dir + '/bullet_toggle_plus2.png';
                elmtA.down( 'img' ).alt = 'Étendre';
            }
        }

        expandableTreeMenuContent( elmtA, sign, dir );
    } );

    if( sign == 'plus' ) {
        $( toggleLink ).down( 'img' ).src = dir + '/bullet_toggle_minus2.png';
    }
    else {

        $( toggleLink ).down( 'img' ).src = dir + '/bullet_toggle_plus2.png';
    }
}

//-----------------------------------------------------------------------------





// TODO: mettre avant les actions
// function make_table_tooltips() {
//     $$( 'table.tooltips' ).each( function ( elmtTable ) {
//         // FIXME: colspans dans le thead -> alert( $( this ).attr( 'colspan' ) );
//         var tooltipPositions = new Array();
//         var tooltipHeaders = new Array();
//         var actionPositions = new Array();
//
//         var iPosition = 0;
//         elmtTable.getElementsBySelector( 'thead tr th' ).each( function ( elmtTh ) {
//             var colspan = ( $( elmtTh ).readAttribute( 'colspan' ) != undefined ) ? $( elmtTh ).readAttribute( 'colspan' ) : 1;
//             if( elmtTh.hasClassName( 'tooltip' ) ) {
//                 elmtTh.remove();
//                 for( k = 0 ; k < colspan ; k++ )
//                     tooltipPositions.push( iPosition + k );
//                 tooltipHeaders[iPosition] = elmtTh.innerHTML;
//             }
//             if( elmtTh.hasClassName( 'action' ) ) {
//                 for( k = 0 ; k < colspan ; k++ )
//                     actionPositions.push( iPosition + k );
//             }
//             iPosition++;
//         } );
//
//         // FIXME
//         var th = new Element( 'th', { 'class': 'tooltip_table' } ).update( 'Informations complémentaires' );
//         $( elmtTable ).down( 'thead' ).down( 'tr' ).insert( { 'bottom' : th } );
//
//         elmtTable.getElementsBySelector( 'tbody tr' ).each( function ( elmtTbodyTr ) {
//             var tooltip_table = new Element( 'table', { 'class': 'tooltip' } );
//
//             var iPosition = 0;
//             elmtTbodyTr.getElementsBySelector( 'td' ).each( function ( elmtTbodyTd ) {
//                 if( tooltipPositions.include( iPosition ) ) {
//                     var tooltip_tr = new Element( 'tr', {} );
//                     var tooltip_th = new Element( 'th', {} ).update( tooltipHeaders[iPosition] );
//                     var tooltip_td = new Element( 'td', {} ).update( elmtTbodyTd.innerHTML );
//                     tooltip_tr.insert( { 'bottom' : tooltip_th } );
//                     tooltip_tr.insert( { 'bottom' : tooltip_td } );
//                     $( tooltip_table ).insert( { 'bottom' : tooltip_tr } );
//                     elmtTbodyTd.remove();
//                 }
//                 else if( actionPositions.include( iPosition ) ) {
//                     $( elmtTbodyTd ).addClassName( 'action' );
//                 }
//                 iPosition++;
//             } );
//
//             var tooltip_td = new Element( 'td', { 'class': 'tooltip_table' } );
//             $( tooltip_td ).insert( { 'bottom' : tooltip_table } );
//             $( elmtTbodyTr ).insert( { 'bottom' : tooltip_td } );
//         } );
//
//         elmtTable.getElementsBySelector( 'tbody tr td' ).each( function ( elmtTd ) {
//             // Mouse over
//             $( elmtTd ).observe( 'mouseover', function( event ) {
//                 if( !$( this ).hasClassName( 'action' ) ) {
//                     $( this ).up( 'tr' ).addClassName( 'hover' ); // INFO: IE6
//                     $( this ).up( 'tr' ).getElementsBySelector( 'td.tooltip_table' ).each( function ( tooltip_table ) {
//                         $( tooltip_table ).setStyle( {
//                             'left' : ( event.pointerX() + 5 ) + 'px',
//                             'top' : ( event.pointerY() + 5 ) + 'px',
//                             'display' : 'block'
//                         } );
//                     } );
//                 }
//             } );
//
//             // Mouse move
//             $( elmtTd ).observe( 'mousemove', function( event ) {
//                 if( !$( this ).hasClassName( 'action' ) ) {
//                     $( this ).up( 'tr' ).getElementsBySelector( 'td.tooltip_table' ).each( function ( tooltip_table ) {
//                         $( tooltip_table ).setStyle( {
//                             'left' : ( event.pointerX() + 5 ) + 'px',
//                             'top' : ( event.pointerY() + 5 ) + 'px'
//                         } );
//                     } );
//                 }
//             } );
//
//             // Mouse out
//             $( elmtTd ).observe( 'mouseout', function( event ) {
//                 if( !$( this ).hasClassName( 'action' ) ) {
//                     $( this ).up( 'tr' ).removeClassName( 'hover' ); // INFO: IE6
//                     $( this ).up( 'tr' ).getElementsBySelector( 'td.tooltip_table' ).each( function ( tooltip_table ) {
//                         $( tooltip_table ).setStyle( {
//                             'display' : 'none'
//                         } );
//                     } );
//                 }
//             } );
//         } );
//     } );
// }

//*****************************************************************************

function mkTooltipTables() {
    var tips = new Array();
    $$( 'table.tooltips' ).each( function( table ) {
        var actionPositions = new Array();
//         var iPosition = 0;
		var trs = $( table ).getElementsBySelector( 'thead tr' );

		var headRow = undefined;
		if( trs.length > 1 ) {
			headRow = trs[0];
		}
		else { // FIXME
			headRow = trs[0];
		}

		var realPosition = 0;
        $( headRow ).getElementsBySelector( 'th' ).each( function ( th ) {
            var colspan = ( $( th ).readAttribute( 'colspan' ) != undefined ) ? $( th ).readAttribute( 'colspan' ) : 1;
            if( $( th ).hasClassName( 'action' ) ) {
                for( var k = 0 ; k < colspan ; k++ ) {
                    actionPositions.push( realPosition + k );
                }
            }
            if( $( th ).hasClassName( 'innerTableHeader' ) ) {
                $( th ).addClassName( 'dynamic' );
            }
//             iPosition++;
			realPosition = ( parseInt( realPosition ) + parseInt( colspan ) );
        } );

        var iPosition = 0;
        $( table ).getElementsBySelector( 'tbody tr' ).each( function( tr ) {
            if( $( tr ).up( '.innerTableCell' ) == undefined ) {
                $( tr ).addClassName( 'dynamic' );
                var jPosition = 0;
                $( tr ).getElementsBySelector( 'td' ).each( function( td ) {
                    if( !actionPositions.include( jPosition ) ) {
                        tips.push( new Tooltip( $( td ), 'innerTable' + iPosition ) );
                    }
                    jPosition++;
                } );
                iPosition++;
            }
        } );
    } );
}

//*****************************************************************************

function disableFieldsOnCheckbox( cbId, fieldsIds, condition ) {
    var cb = $( cbId );
    var checked = ( ( $F( cb ) == null ) ? false : true );
    fieldsIds.each( function ( fieldId ) {
        var field = $( fieldId );
        if( checked != condition ) {
            field.enable();
            if( input = field.up( 'div.input' ) )
                input.removeClassName( 'disabled' );
            else if( input = field.up( 'div.checkbox' ) )
                input.removeClassName( 'disabled' );
        }
        else {
            field.disable();
            if( input = field.up( 'div.input' ) )
                input.addClassName( 'disabled' );
            else if( input = field.up( 'div.checkbox' ) )
                input.addClassName( 'disabled' );
        }
    } );
}

//-----------------------------------------------------------------------------

function observeDisableFieldsOnCheckbox( cbId, fieldsIds, condition ) {
    disableFieldsOnCheckbox( cbId, fieldsIds, condition );

    var cb = $( cbId );
    $( cb ).observe( 'click', function( event ) { // FIXME change ?
        disableFieldsOnCheckbox( cbId, fieldsIds, condition );
    } );
}

//*****************************************************************************

function disableFieldsOnValue( selectId, fieldsIds, value, condition ) {
	if( ( typeof value ) != 'object' ) {
		value = [ value ];
	}

    var select = $( selectId );

    var result = false;
	value.each( function( elmt ) {
		if( $F( select ) == elmt ) {
			result = true;
		}
	} );

    fieldsIds.each( function ( fieldId ) {
        var field = $( fieldId );
        if( field != null ) {
            if( result == condition ) {
                field.disable();
                if( input = field.up( 'div.input' ) )
                    input.addClassName( 'disabled' );
                else if( input = field.up( 'div.checkbox' ) )
                    input.addClassName( 'disabled' );
            }
            else {
                field.enable();
                if( input = field.up( 'div.input' ) )
                    input.removeClassName( 'disabled' );
                else if( input = field.up( 'div.checkbox' ) )
                    input.removeClassName( 'disabled' );
            }
        }
    } );
}
//----------------------------------------------------------------------------

function observeDisableFieldsOnValue( selectId, fieldsIds, value, condition ) {
    disableFieldsOnValue( selectId, fieldsIds, value, condition );

    var select = $( selectId );
    $( select ).observe( 'change', function( event ) {
        disableFieldsOnValue( selectId, fieldsIds, value, condition );
    } );
}

//*****************************************************************************

function disableFieldsetOnCheckbox( cbId, fieldsetId, condition, toggleVisibility ) {
	toggleVisibility = typeof(toggleVisibility) != 'undefined' ? toggleVisibility : false;

    var cb = $( cbId );
    var checked = ( ( $F( cb ) == null ) ? false : true );
    var fieldset = $( fieldsetId );

    if( checked != condition ) {
        fieldset.removeClassName( 'disabled' );
		if( toggleVisibility ) {
			fieldset.show();
		}

        $( fieldset ).getElementsBySelector( 'div.input', 'div.checkbox' ).each( function( elmt ) {
            elmt.removeClassName( 'disabled' );
        } );

        $( fieldset ).getElementsBySelector( 'input', 'select', 'button', 'textarea' ).each( function( elmt ) {
            elmt.enable();
        } );
    }
    else {
        fieldset.addClassName( 'disabled' );
		if( toggleVisibility ) {
			fieldset.hide();
		}

        $( fieldset ).getElementsBySelector( 'div.input', 'div.checkbox' ).each( function( elmt ) {
            elmt.addClassName( 'disabled' );
        } );

        $( fieldset ).getElementsBySelector( 'input', 'select', 'button', 'textarea' ).each( function( elmt ) {
            elmt.disable();
        } );
    }
}

//-----------------------------------------------------------------------------

function observeDisableFieldsetOnCheckbox( cbId, fieldsetId, condition, toggleVisibility ) {
	toggleVisibility = typeof(toggleVisibility) != 'undefined' ? toggleVisibility : false;

    disableFieldsetOnCheckbox( cbId, fieldsetId, condition, toggleVisibility );

    var cb = $( cbId );
    $( cb ).observe( 'click', function( event ) { // FIXME change ?
        disableFieldsetOnCheckbox( cbId, fieldsetId, condition, toggleVisibility );
    } );
}

//*****************************************************************************

function disableFieldsOnBoolean( field, fieldsIds, value, condition ) {
    var disabled = !( ( $F( field ) == value ) == condition );
    fieldsIds.each( function ( fieldId ) {
        var field = $( fieldId );
        if( !disabled ) {
            field.enable();
            if( input = field.up( 'div.input' ) )
                input.removeClassName( 'disabled' );
            else if( input = field.up( 'div.checkbox' ) )
                input.removeClassName( 'disabled' );
        }
        else {
            field.disable();
            if( input = field.up( 'div.input' ) )
                input.addClassName( 'disabled' );
            else if( input = field.up( 'div.checkbox' ) )
                input.addClassName( 'disabled' );
        }
    } );
}

//-----------------------------------------------------------------------------

function observeDisableFieldsOnBoolean( prefix, fieldsIds, value, condition ) {
    if( value == '1' ) {
        var otherValue = '0';
        disableFieldsOnBoolean( prefix + otherValue, fieldsIds, otherValue, !condition );
    }
    else {
        var otherValue = '1';
        disableFieldsOnBoolean( prefix + value, fieldsIds, value, condition );
    }

    $( prefix + value ).observe( 'click', function( event ) {
        disableFieldsOnBoolean( prefix + value, fieldsIds, value, condition );
    } );

    $( prefix + otherValue ).observe( 'click', function( event ) {
        disableFieldsOnBoolean( prefix + otherValue, fieldsIds, otherValue, !condition );
    } );
}

//-----------------------------------------------------------------------------

function setDateInterval( masterPrefix, slavePrefix, nMonths, firstDay ) {
    // Initialisation
    var d = new Date();
    d.setDate( 1 );
    d.setMonth( $F( masterPrefix + 'Month' ) - 1 );
    d.setYear( $F( masterPrefix + 'Year' ) );

    // Ajout de trois mois, et retour au derenier jour du mois précédent
    d.setDate( d.getDate() + ( nMonths * 31 ) );
    d.setDate( 1 );
    if( !firstDay ) {
        d.setDate( d.getDate() - 1 );
    }

    // Assignation
    var day = d.getDate();
    $( slavePrefix + 'Day' ).value = ( day < 10 ) ? '0' + day : day;
    var month = d.getMonth() + 1;
    $( slavePrefix + 'Month' ).value = ( month < 10 ) ? '0' + month : month;
    $( slavePrefix + 'Year' ).value = d.getFullYear();
}

//-----------------------------------------------------------------------------

function setDateInterval2( masterPrefix, slavePrefix, nMonths, firstDay ) {
    // Initialisation
    var d = new Date();
    d.setDate( 1 );
    d.setMonth( $F( masterPrefix + 'Month' ) - 1 );
    d.setYear( $F( masterPrefix + 'Year' ) );

    // Ajout de trois mois, et retour au dernier jour du mois précédent
    d.setDate( d.getDate() + ( nMonths * 31 ) );
    d.setDate( 1 );
    if( !firstDay ) {
        d.setDate( d.getDate() - 1 );
    }


    // Assignation
    var day = d.getDate();
    $( slavePrefix + 'Day' ).value =  $( masterPrefix + 'Day' ).value;//( day < 10 ) ? '0' + day : day;
    var month = d.getMonth() + 1;
    $( slavePrefix + 'Month' ).value = ( month < 10 ) ? '0' + month : month;
    $( slavePrefix + 'Year' ).value = d.getFullYear();

	// Calcul du dernier jour du mois
	var slaveDate = new Date();
    slaveDate.setDate( 1 );
    slaveDate.setMonth( $( slavePrefix + 'Month' ).value ); // FIXME ?
    slaveDate.setYear( $( slavePrefix + 'Year' ).value );
	slaveDate.setDate( slaveDate.getDate() - 1 );
	if( slaveDate.getDate() < $( slavePrefix + 'Day' ).value ) {
		$( slavePrefix + 'Day' ).value = slaveDate.getDate();
	}
}

//==============================================================================

function disableFieldsOnRadioValue( form, radioName, fieldsIds, value, condition ) {
	var v = $( form ).getInputs( 'radio', radioName );
	var currentValue = undefined;
	$( v ).each( function( radio ) {
		if( radio.checked ) {
			currentValue = radio.value;
		}
	} );


	var disabled = !( ( currentValue == value ) == condition );

	fieldsIds.each( function ( fieldId ) {
		var field = $( fieldId );
		if( !disabled ) {
			field.enable();
			if( input = field.up( 'div.input' ) )
				input.removeClassName( 'disabled' );
			else if( input = field.up( 'div.checkbox' ) )
				input.removeClassName( 'disabled' );
		}
		else {
			field.disable();
			if( input = field.up( 'div.input' ) )
				input.addClassName( 'disabled' );
			else if( input = field.up( 'div.checkbox' ) )
				input.addClassName( 'disabled' );
		}
	} );
}

//-----------------------------------------------------------------------------

function observeDisableFieldsOnRadioValue( form, radioName, fieldsIds, value, condition ) {
    disableFieldsOnRadioValue( form, radioName, fieldsIds, value, condition );

	var v = $( form ).getInputs( 'radio', radioName );
	var currentValue = undefined;
	$( v ).each( function( radio ) {
		$( radio ).observe( 'change', function( event ) {
			disableFieldsOnRadioValue( form, radioName, fieldsIds, value, condition );
		} );
	} );
}


//*****************************************************************************

function disableFieldsetOnRadioValue( form, radioName, fieldsetId, value, condition, toggleVisibility ) {
    toggleVisibility = typeof(toggleVisibility) != 'undefined' ? toggleVisibility : false;

//     var checked = ( ( $F( fieldset ) == null ) ? false : true );


    var v = $( form ).getInputs( 'radio', radioName );
    var fieldset = $( fieldsetId );
    var currentValue = undefined;

    $( v ).each( function( radio ) {
        if( radio.checked ) {
            currentValue = radio.value;
        }
    } );

    var disabled = !( ( currentValue == value ) == condition );

    if( disabled != condition ) {
        fieldset.removeClassName( 'disabled' );
        if( toggleVisibility ) {
            fieldset.show();
        }

        $( fieldset ).getElementsBySelector( 'div.input', 'radio' ).each( function( elmt ) {
            elmt.removeClassName( 'disabled' );
        } );

        $( fieldset ).getElementsBySelector( 'input', 'select', 'button', 'textarea' ).each( function( elmt ) {
            elmt.enable();
        } );
    }
    else {
        fieldset.addClassName( 'disabled' );
        if( toggleVisibility ) {
            fieldset.hide();
        }

        $( fieldset ).getElementsBySelector( 'div.input', 'radio' ).each( function( elmt ) {
            elmt.addClassName( 'disabled' );
        } );

        $( fieldset ).getElementsBySelector( 'input', 'select', 'button', 'textarea' ).each( function( elmt ) {
            elmt.disable();
        } );
    }
}

//-----------------------------------------------------------------------------

function observeDisableFieldsetOnRadioValue( form, radioName, fieldsetId, value, condition, toggleVisibility ) {
    toggleVisibility = typeof(toggleVisibility) != 'undefined' ? toggleVisibility : false;

    disableFieldsetOnRadioValue( form, radioName, fieldsetId, value, condition, toggleVisibility );

    var v = $( form ).getInputs( 'radio', radioName );

    var currentValue = undefined;

    $( v ).each( function( radio ) {
        $( radio ).observe( 'change', function( event ) {
            disableFieldsetOnRadioValue( form, radioName, fieldsetId, value, condition, toggleVisibility );
        } );
    } );
}

//-----------------------------------------------------------------------------

function makeTabbed( wrapperId, titleLevel ) {
	var ul = new Element( 'ul', { 'class' : 'ui-tabs-nav' } );
	$$( '#' + wrapperId + ' h' + titleLevel + '.title' ).each( function( title ) {
		var parent = title.up();
		var li = new Element( 'li', { 'class' : 'tab' } ).update(
			new Element( 'a', { href: '#' + parent.id } ).update( title.innerHTML )
		);
		ul.appendChild( li );
		parent.addClassName( 'tab' );
		title.addClassName( 'tab hidden' );
	} );

	$( wrapperId ).insert( { 'before' : ul } );

	new Control.Tabs( ul );
}