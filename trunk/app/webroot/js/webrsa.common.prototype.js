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
            var link = img.wrap( 'a', { 'href': '#', 'class' : 'toggler', 'onclick' : 'return false;' } );
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
						tips.push( new Tooltip( $( td ), 'innerTable' + $( table ).readAttribute( 'id' ) + iPosition ) );
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
    d.setMonth( $F( masterPrefix + 'Month' ) ); //FIXME: suppression du -1 afin d'obtenir le nombre de mois exact
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

function setDateIntervalCer( masterPrefix, slavePrefix, nMonths, firstDay ) {
    // Initialisation
    var d = new Date();
    d.setDate( $F( masterPrefix + 'Day' ) );
    d.setMonth( $F( masterPrefix + 'Month' ) - 1 );
    d.setYear( $F( masterPrefix + 'Year' ) );

    // Ajout de trois mois, et retour au derenier jour du mois précédent
    d.setMonth( d.getMonth() + nMonths );
    d.setDate( d.getDate() - 1 );

    // Assignation
    var day = d.getDate();
    $( slavePrefix + 'Day' ).value = ( day < 10 ) ? '0' + day : day;
    var month = d.getMonth() + 1;
    $( slavePrefix + 'Month' ).value = ( month < 10 ) ? '0' + month : month;
    $( slavePrefix + 'Year' ).value = d.getFullYear();
}


function setNbDayInterval( masterPrefix, slavePrefix, nDays ) {
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

//==============================================================================

function disableFieldsOnRadioValue( form, radioName, fieldsIds, value, condition, toggleVisibility ) {
    if( ( typeof value ) != 'object' ) {
        value = [ value ];
    }
    toggleVisibility = typeof(toggleVisibility) != 'undefined' ? toggleVisibility : false;
	var v = $( form ).getInputs( 'radio', radioName );

	var currentValue = undefined;
	$( v ).each( function( radio ) {
		if( radio.checked ) {
			currentValue = radio.value;
		}
	} );

    var disabled = false;
    value.each( function( elmt ) {
        if( !( ( currentValue == elmt ) == condition ) ) {
            disabled = true;
        }
    } );

	//var disabled = !( ( currentValue == value ) == condition );

	fieldsIds.each( function ( fieldId ) {
		var field = $( fieldId );
		if( !disabled ) {


            field.enable();

			if( input = field.up( 'div.input' ) )
				input.removeClassName( 'disabled' );
			else if( input = field.up( 'div.checkbox' ) )
				input.removeClassName( 'disabled' );

            //Ajout suite aux modifs ds les traitements PDOs
            if( toggleVisibility ) {
                input.show();
            }
		}
		else {

            field.disable();


			if( input = field.up( 'div.input' ) )
				input.addClassName( 'disabled' );
			else if( input = field.up( 'div.checkbox' ) )
				input.addClassName( 'disabled' );

            //Ajout suite aux modifs ds les traitements PDOs
            if( toggleVisibility ) {
                input.hide();
            }
		}
	} );
}

//-----------------------------------------------------------------------------

function observeDisableFieldsOnRadioValue( form, radioName, fieldsIds, value, condition, toggleVisibility ) {
    toggleVisibility = typeof(toggleVisibility) != 'undefined' ? toggleVisibility : false;
    disableFieldsOnRadioValue( form, radioName, fieldsIds, value, condition, toggleVisibility );

	var v = $( form ).getInputs( 'radio', radioName );
	var currentValue = undefined;
	$( v ).each( function( radio ) {
		$( radio ).observe( 'change', function( event ) {
            disableFieldsOnRadioValue( form, radioName, fieldsIds, value, condition, toggleVisibility );
		} );
	} );
}


//*****************************************************************************

function disableFieldsetOnRadioValue( form, radioName, fieldsetId, value, condition, toggleVisibility ) {
    if( ( typeof value ) != 'object' ) {
        value = [ value ];
    }

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

    var disabled = false;
    value.each( function( elmt ) {
        if( !( ( currentValue == elmt ) == condition ) ) {
            disabled = true;
        }
    } );

    //var disabled = !( ( currentValue == value ) == condition );

// if( radioName == 'data[Cui][isbeneficiaire]' && $( fieldsetId ).id == 'IsBeneficiaire' ) {
// 	alert( currentValue == value );
// 	alert( condition );
// 	alert( disabled );
// }

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

//-----------------------------------------------------------------------------

function make_treemenus_droits( absoluteBaseUrl, large ) {
    var dir = absoluteBaseUrl + 'img/icons';

    $$( '#tableEditDroits tr.niveau0 td.label' ).each( function ( elmtTd ) {
		if( elmtTd.up( 'tr' ).next( 'tr' ).hasClassName('niveau1')) {

			var thisTr = $( elmtTd ).up( 'tr' );
			var nextTr = $( thisTr ).next( 'tr' );
			var value = 2;
			var etat = 'fermer';
			while( nextTr != undefined && Element.hasClassName( nextTr, 'niveau1' ) ) {
				var checkboxes = $( nextTr ).getElementsBySelector( 'input[type=checkbox]' );
				if ( value == 2) { value = $F( checkboxes[0] ); }
				else if ( value != $F( checkboxes[0] )) { etat = 'ouvert'; }
				nextTr = $( nextTr ).next( 'tr' );
			}

			if( etat == 'fermer' ) {
				if( large )
					var img = new Element( 'img', { 'src': dir + '/bullet_toggle_plus2.png', 'alt': 'Étendre', 'width': '12px' } );
				else
					var img = new Element( 'img', { 'src': dir + '/bullet_toggle_plus2.png', 'alt': 'Étendre' } );

				nextTr = $( thisTr ).next( 'tr' );
				while( nextTr != undefined && Element.hasClassName( nextTr, 'niveau1' ) ) {
					nextTr.hide();
					nextTr = $( nextTr ).next( 'tr' );
				}
			}
			else {
				if( large )
					var img = new Element( 'img', { 'src': dir + '/bullet_toggle_minus2.png', 'alt': 'Réduire', 'width': '12px' } );
				else
					var img = new Element( 'img', { 'src': dir + '/bullet_toggle_minus2.png', 'alt': 'Réduire' } );
			}

			// INFO: onclick -> return false est indispensable.
			var link = img.wrap( 'a', { 'href': '#', 'class' : 'toggler', 'onclick' : 'return false;' } );

			$( link ).observe( 'click', function( event ) {
				var nextTr = $( this ).up( 'td' ).up( 'tr' ).next( 'tr' );

				while( nextTr != undefined && Element.hasClassName( nextTr, 'niveau1' ) ) {
					nextTr.toggle();

					if( nextTr.visible() ) {
					    $( this ).down( 'img' ).src = dir + '/bullet_toggle_minus2.png';
					    $( this ).down( 'img' ).alt = 'Réduire';
					}
					else {
					    $( this ).down( 'img' ).src = dir + '/bullet_toggle_plus2.png';
					    $( this ).down( 'img' ).alt = 'Étendre';
					}

					nextTr = $( nextTr ).next( 'tr' );
				}
			} );

	        $( elmtTd ).insert( { 'top' : link } );
	    }
	} );

	var tabledroit = $$( '#tableEditDroits' ).each(function (elmt) {
    	if( large )
			var img = new Element( 'img', { 'src': dir + '/bullet_toggle_plus2.png', 'alt': 'Étendre', 'width': '12px' } );
		else
			var img = new Element( 'img', { 'src': dir + '/bullet_toggle_plus2.png', 'alt': 'Étendre' } );

		var biglink = img.wrap( 'a', { 'href': '#', 'class' : 'toggler', 'onclick' : 'return false;' } );

		$( biglink ).observe( 'click', function( event ) {
			$$( '#tableEditDroits tr.niveau0 td.label' ).each( function ( elmtTd ) {
				if( elmtTd.up( 'tr' ).next( 'tr' ).hasClassName('niveau1')) {
					var nextTr = $( elmtTd ).up( 'tr' ).next( 'tr' );

					while( nextTr != undefined && Element.hasClassName( nextTr, 'niveau1' ) ) {
						if( $( elmt ).down( 'img' ).alt == 'Étendre' ) {
							$( elmtTd ).down( 'img' ).src = dir + '/bullet_toggle_minus2.png';
							$( elmtTd ).down( 'img' ).alt = 'Réduire';
							nextTr.show();
						}
						else {
							$( elmtTd ).down( 'img' ).src = dir + '/bullet_toggle_plus2.png';
							$( elmtTd ).down( 'img' ).alt = 'Étendre';
							nextTr.hide();
						}

						nextTr = $( nextTr ).next( 'tr' );
					}
				}
			} );
			if( $( elmt ).down( 'img' ).alt == 'Étendre' ) {
				$( elmt ).down( 'img' ).src = dir + '/bullet_toggle_minus2.png';
				$( elmt ).down( 'img' ).alt = 'Réduire';
			}
			else {
				$( elmt ).down( 'img' ).src = dir + '/bullet_toggle_plus2.png';
				$( elmt ).down( 'img' ).alt = 'Étendre';
			}
		} );

    	$( elmt ).insert( { 'top' : biglink } );
    });

}

function OpenTree(action, absoluteBaseUrl, large) {
	var dir = absoluteBaseUrl + 'img/icons';
	$$( '#tableEditDroits tr.niveau0 td.label' ).each( function ( elmtTd ) {
		if( elmtTd.up( 'tr' ).next( 'tr' ).hasClassName('niveau1')) {
			var thisTr = $( elmtTd ).up( 'tr' );
			if( action == 'open' ) {
				$( elmtTd ).down( 'a' ).down( 'img' ).src = dir + '/bullet_toggle_minus2.png';
				$( elmtTd ).down( 'a' ).down( 'img' ).alt = 'Réduire';
				var nextTr = $( thisTr ).next( 'tr' );
				while( nextTr != undefined && Element.hasClassName( nextTr, 'niveau1' ) ) {
					nextTr.show();
					nextTr = $( nextTr ).next( 'tr' );
				}
			}
			else {
				$( elmtTd ).down( 'a' ).down( 'img' ).src = dir + '/bullet_toggle_plus2.png';
				$( elmtTd ).down( 'a' ).down( 'img' ).alt = 'Étendre';
				var nextTr = $( thisTr ).next( 'tr' );
				while( nextTr != undefined && Element.hasClassName( nextTr, 'niveau1' ) ) {
					nextTr.hide();
					nextTr = $( nextTr ).next( 'tr' );
				}
			}
		}
	} );
}

// Fonction non-prototype commune

function printit(){
	if (window.print) {
		window.print() ;
	} else {
		var WebBrowser = '<object id="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></object>';
		document.body.insertAdjacentHTML('beforeEnd', WebBrowser);
		WebBrowser1.ExecWB(6, 2);//Use a 1 vs. a 2 for a prompting dialog box    WebBrowser1.outerHTML = "";
	}
}



/*
 *   Title :     charcount.js
 *   Author :        Terri Ann Swallow
 *   URL :       http://www.ninedays.org/
 *   Project :       Ninedays Blog
 *   Copyright:      (c) 2008 Sam Stephenson
 *               This script is is freely distributable under the terms of an MIT-style license.
 *   Description :   Functions in relation to limiting and displaying the number of characters allowed in a textarea
 *   Version:        2.1
 *   Changes:        Added overage override.  Read blog for updates: http://blog.ninedays.org/2008/01/17/limit-characters-in-a-textarea-with-prototype/
 *   Created :       1/17/2008 - January 17, 2008
 *   Modified :      5/20/2008 - May 20, 2008
 * 
 *   Functions:      init()                      Function called when the window loads to initiate and apply character counting capabilities to select textareas
 *   charCounter(id, maxlimit, limited)  Function that counts the number of characters, alters the display number and the calss applied to the display number
 *   makeItCount(id, maxsize, limited)   Function called in the init() function, sets the listeners on teh textarea nd instantiates the feedback display number if it does not exist
 */

function textareaCharCounter(id, maxlimit, limited){
    if (!$('counter-'+id)){
        $(id).insert({after: '<div id="counter-'+id+'"></div>'});
        }
    if($F(id).length >= maxlimit){
        if(limited){    $(id).value = $F(id).substring(0, maxlimit); }
        $('counter-'+id).addClassName('charcount-limit');
        $('counter-'+id).removeClassName('charcount-safe');
    } else {
        $('counter-'+id).removeClassName('charcount-limit');
        $('counter-'+id).addClassName('charcount-safe');
    }
    $('counter-'+id).update( $F(id).length + '/' + maxlimit );
}

function textareaMakeItCount(textareaId, maxsize, limited){
    if(limited == null) limited = true;
    if ($(textareaId)){
        Event.observe($(textareaId), 'keyup', function(){textareaCharCounter(textareaId, maxsize, limited);}, false);
        Event.observe($(textareaId), 'keydown', function(){textareaCharCounter(textareaId, maxsize, limited);}, false);
        textareaCharCounter(textareaId,maxsize,limited);
    }
}

// http://jehiah.cz/a/firing-javascript-events-properly
function fireEvent(element,event) {
	if (document.createEventObject) {// dispatch for IE
		var evt = document.createEventObject();
		return element.fireEvent('on'+event,evt)
	}
	else { // dispatch for firefox + others
		var evt = document.createEvent("HTMLEvents");
		evt.initEvent(event, true, true ); // event type,bubbling,cancelable
		return !element.dispatchEvent(evt);
	}
}
