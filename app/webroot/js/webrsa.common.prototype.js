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

function make_treemenus( absoluteBaseUrl ) {
    var dir = absoluteBaseUrl + 'img/icons';
    $$( '.treemenu li' ).each( function ( elmtLi ) {
        if( elmtLi.down( 'ul' ) ) {
            var img = new Element( 'img', { 'src': dir + '/bullet_toggle_plus.png', 'alt': 'Étendre' } );
            var link = img.wrap( 'a', { 'href': '#', 'class' : 'toggler' } );
            var sign = '+';

            $( link ).observe( 'click', function( event ) {
                var innerUl = $( this ).up( 'li' ).down( 'ul' );
                innerUl.toggle();
                if( innerUl.visible() ) {
                    $( this ).down( 'img' ).src = dir + '/bullet_toggle_minus.png';
                    $( this ).down( 'img' ).alt = 'Réduire';
                }
                else {
                    $( this ).down( 'img' ).src = dir + '/bullet_toggle_plus.png';
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
        if( elmtA.href.replace( absoluteBaseUrl, '/' ) == currentUrl || elmtA.href.replace( absoluteBaseUrl, '/' ) == currentUrl.replace( '/edit/', '/view/' ) ) {
            // Montrer tous les ancètres
            elmtA.ancestors().each( function ( aAncestor ) {
                aAncestor.show();
                if( aAncestor.tagName == 'LI' ) {
                    var toggler = aAncestor.down( 'a.toggler img' );
                    if( toggler != undefined ) {
                        toggler.src = dir + '/bullet_toggle_minus.png';
                        toggler.alt = 'Réduire';
                    }
                }
            } );
            // Montrer son descendant direct
            var ul = elmtA.up( 'li' ).down( 'ul' );
            if( ul != undefined ) {
                ul.show();
            }
        }
    } );
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
    $$( 'table.tooltips_oupas' ).each( function( table ) {
        var actionPositions = new Array();
        var iPosition = 0;
        $( table ).getElementsBySelector( 'thead tr th' ).each( function ( th ) {
            var colspan = ( $( th ).readAttribute( 'colspan' ) != undefined ) ? $( th ).readAttribute( 'colspan' ) : 1;
            if( $( th ).hasClassName( 'action' ) ) {
                for( k = 0 ; k < colspan ; k++ ) {
                    actionPositions.push( iPosition + k );
                }
            }
            if( $( th ).hasClassName( 'innerTableHeader' ) ) {
                $( th ).addClassName( 'dynamic' );
            }
            iPosition++;
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
    var select = $( selectId );
    var result = ( ( $F( select ) == value ) ? true : false );

    fieldsIds.each( function ( fieldId ) {
        var field = $( fieldId );
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

function disableFieldsetOnCheckbox( cbId, fieldsetId, condition ) {
    var cb = $( cbId );
    var checked = ( ( $F( cb ) == null ) ? false : true );
    var fieldset = $( fieldsetId );

    if( checked != condition ) {
        fieldset.removeClassName( 'disabled' );
        $( fieldset ).getElementsBySelector( 'div.input', 'div.checkbox' ).each( function( elmt ) {
            elmt.removeClassName( 'disabled' );
        } );

        $( fieldset ).getElementsBySelector( 'input', 'select', 'button', 'textarea' ).each( function( elmt ) {
            elmt.enable();
        } );
    }
    else {
        fieldset.addClassName( 'disabled' );
        $( fieldset ).getElementsBySelector( 'div.input', 'div.checkbox' ).each( function( elmt ) {
            elmt.addClassName( 'disabled' );
        } );

        $( fieldset ).getElementsBySelector( 'input', 'select', 'button', 'textarea' ).each( function( elmt ) {
            elmt.disable();
        } );
    }
}

//-----------------------------------------------------------------------------

function observeDisableFieldsetOnCheckbox( cbId, fieldsetId, condition ) {
    disableFieldsetOnCheckbox( cbId, fieldsetId, condition );

    var cb = $( cbId );
    $( cb ).observe( 'click', function( event ) { // FIXME change ?
        disableFieldsetOnCheckbox( cbId, fieldsetId, condition );
    } );
}

//*****************************************************************************

