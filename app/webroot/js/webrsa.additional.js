/*global console, validationJS, document, validationRules, validationOnsubmit, traductions, Validation, validationOnchange, setTimeout, $, $$*/

/**
 * Polyfill
 * 
 * @source https://developer.mozilla.org/fr/docs/Web/JavaScript/Reference/Objets_globaux/Object/create#Polyfill
 */
if (typeof Object.create !== 'function') {
  Object.create = (function() {
	'use strict';
    var Temp = function() { return; };
    return function (prototype) {
      if (arguments.length > 1) {
        throw new Error('Cette prothèse ne supporte pas le second argument');
      }
      if (typeof prototype !== 'object') {
        throw new TypeError('L\'argument doit être un objet');
      }
      Temp.prototype = prototype;
      var result = new Temp();
      Temp.prototype = null;
      return result;
    };
  }());
}


/*************************************************************************
 * Rend les boutons radio décochable si ils portent la class uncheckable *
 *************************************************************************/

/**
 * Décoche un bouton radio renseigné dans radio
 * 
 * @param {HTML} radio
 * @returns {void}
 */
function uncheckable( radio ){
	'use strict';
	radio.onclick = function(){
		$$('input[name="'+radio.name+'"]').each(function( radio ){
			if ( radio.checked && radio.state ){
				radio.state = false;
				radio.checked = false;
				radio.simulate('change');
			}
			else if ( radio.checked ){
				radio.state = true;
			}
			else{
				radio.state = false;
			}
		});
	};
}

/*************************************************************************
 * Ajoute un id au parent de l'élément ciblé							 *
 *************************************************************************/

function addParentId( dom, id ){
	'use strict';
	if ( dom === undefined || dom === null || dom.up().id !== '' ){
		return false;
	}
	
	dom.up().id = id === undefined ? dom.id + 'Parent' : id;
}

/*************************************************************************
 * Autres fonctions utiles												 *
 *************************************************************************/

/**
 * Si une valeur vaut undefined, lui attribu la defaultValue
 * @param {type} valeur
 * @param {type} defaultValue
 * @returns {unresolved}
 */
function giveDefaultValue( valeur, defaultValue ){
	'use strict';
	return valeur === undefined ? defaultValue : valeur;
}

/**
 * Ajoute les 0 manquant si besoin (ex: 1-2-2015 => 01-02-2015)
 * @param {String} dateString
 * @returns {String}
 */ 
function zeroFillDate( dateString ){
	'use strict';
	return dateString.replace( /^(\d)\-/, '0$1-' ).replace( /\-(\d)\-/, '-0$1-' ).replace( /\-(\d)$/, '-0$1' );
}

/*************************************************************************
 * Execution systématique												 *
 *************************************************************************/

document.observe( "dom:loaded", function(){
	'use strict';
	
	// Rend les boutons radio décochable si ils portent la class uncheckable
	$$('input[type="radio"].uncheckable').each(function( radio ){
		radio.state = radio.checked;
		uncheckable( radio );
	});
	
	// Ajoute un visuel sur les input portant la class percent ou euros
	$$('input.percent').each(function( input ){
		input.insert({after: '<div class="input-group-addon">%</div>'});
	});
	$$('input.euros').each(function( input ){
		input.insert({after: '<div class="input-group-addon">€</div>'});
	});
	
	// Ajoute un id au parent de l'élément ciblé
	$$('.add-parent-id').each(function( dom ){ addParentId( dom ); });
});