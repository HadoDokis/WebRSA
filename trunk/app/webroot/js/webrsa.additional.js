/*global document, $$, toString*/

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
				
				if (typeof radio.simulate === 'function') {
					radio.simulate('change');
				}
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
	return true;
}

/*************************************************************************
 * Organise en deux colonnes											 *
 *************************************************************************/

/**
 * Organise, dans le cas d'un multiple checkbox, en X parties rangés par alpha 
 * de haut en bas et de gauche à droite.
 * Fonctionne également sur tout autre élément avec la même structure :
 * <parent>
 *		<label></label>
 *		<div class="divideInto2Collumn">
 *			<label></label>
 *		</div>
 * </parent>
 * @param {HTML} dom
 * @param {integer} nbCollumn
 * @returns {Boolean}
 */
function divideIntoCollumn( dom, nbCollumn ){
	'use strict';
	var parent = dom.up(),
		parentWidth = Element.getWidth(parent), // Pour le calcul de la taille des colonnes
		childs = {}, // Stock les copies de DOM
		childsNames = [], // Utilisé pour trier par alpha
		i = 0,
		divList = [];
	
	// Si deja traité, on retire l'element
	if (parent.divided !== undefined) {
		dom.remove();
		return true;
	}
	
	parent.divided = true;
	
	// Si un label seul est présent, il doit avoir une taille de 100% pour eviter le décalage des colonnes
	dom.siblings().each(function( sibling ) {
		if ( sibling.tagName.toUpperCase() === 'LABEL' ) {
			sibling.style.width = '100%';
		}
	});
	
	// Stock les labels et copie les elements
	parent.select('div').each(function( div ){
		var name = div.select('label').first().innerHTML.replace(/[^A-Za-z]+/g, '');
		childs[name.toUpperCase()] = Element.clone(div, true);
		childsNames.push(name.toUpperCase());
	});
	
	// Les labels sont trié
	childsNames.sort();
	
	// On insert les colonnes
	for (;i<nbCollumn;i++) {
		divList[i] = new Element('div', {style: 'width:'+Math.floor(parentWidth/nbCollumn-1)+'px;display:inline-block;vertical-align:top;'});
		parent.insert(divList[i]);
	}
	
	// On rempli les colonnes dans le bon ordre
	for (i=0;i<childsNames.length;i++) {
		divList[Math.floor(i / Math.ceil(childsNames.length/nbCollumn))].insert(childs[childsNames[i]]);
	}
	
	// On retire l'ancien element
	dom.remove();
	
	return true;
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

/**
 * Vérifi si un array contien une valeur
 * @param {String|Number} value
 * @param {Array} array
 * @returns {Boolean}
 */
function inArray( needle, haystack ){
	'use strict';
	var key;
	if ( needle === null || typeof toString(needle) !== 'string' || !Array.isArray( haystack ) ){
		return false;
	}
	for (key in haystack){
		if ( haystack.hasOwnProperty(key) && haystack[key] === needle ) { return true; }
	}
	return false;
}

/**
 * Cast d'un array
 * @param {Mixed} values
 * @returns {Array}
 */
function castArray( values ){
	'use strict';
	return typeof values !== 'object' ? [values] : values;
}

/**
 * Permet d'obtenir un identifiant façon cake à partir d'un Model.nomdechamp
 * @param {String} modelField
 * @returns {String}
 */
function fieldId( modelField ){
	'use strict';
	var i, result = '', x, exploded = modelField.split(/[\._]/);
	for(i=0; i<exploded.length; i++){
		x = exploded[i];
		result += x.charAt(0).toUpperCase() + x.substring(1);
	}
	return result;
}

/**
 * Equivalent javascript de la fonction php sprintf
 * Fonctionne uniquement pour %s et %d
 * 
 * @param {String} Phrase contenant des %s ou %d
 * @param {String} replace - ajoutez autant d'arguments que nécéssaire
 * @returns {String}
 */
function sprintf() {
    var args = arguments,
    string = args[0],
    i = 1;
    return string.replace(/%((%)|s|d)/g, function (m) {
        // m is the matched format, e.g. %s, %d
        var val = null;
        if (m[2]) {
            val = m[2];
        } else {
            val = args[i];
            // A switch statement so that the formatter can be extended. Default is %s
            switch (m) {
                case '%d':
                    val = parseFloat(val);
                    if (isNaN(val)) {
                        val = 0;
                    }
                    break;
				default: break;
            }
            i++;
        }
        return val;
    });
}

/**
 * Rempli un element de type date Cakephp en fonction de la valeur en mois d'un autre élément.
 * 
 * @param {string} id id de l'element qui défini la durée
 * @param {string} target nom de la cible à la façon Cakephp
 * @throws {error} La cible n'a pas été trouvée
 * @returns {Boolean}
 */
function setDateCloture( id, target ){
	'use strict';
	var duree = parseFloat( $F(id), 10 ),
		now = new Date(),
		jour = now.getUTCDate(),
		mois = now.getUTCMonth() +1,
		annee = now.getUTCFullYear(),
		dateButoir,
		exploded = target.split('.'),
		i = 0,
		baseTargetName = 'data',
		targetDay,
		targetMonth,
		targetYear
	;

	if ( isNaN(duree*2) || exploded.length < 2 ){
		return false;
	}
	
	for (; i<exploded.length; i++) {
		baseTargetName += '['+exploded[i]+']';
	}
	
	targetDay = $$('select[name="'+baseTargetName+'[day]"]').first();
	targetMonth = $$('select[name="'+baseTargetName+'[month]"]').first();
	targetYear = $$('select[name="'+baseTargetName+'[year]"]').first();
	
	if ( targetDay === undefined || targetMonth === undefined || targetYear === undefined ) {
		throw 'select[name="'+baseTargetName+'"] + ([day] | [month] | [year]) Not Found!';
	}
	
	// Si duree est à virgule, on ajoute 0.x fois 30 jours
	dateButoir = new Date(annee, mois + Math.floor(duree) - 1, ((duree % 1)*30 + jour - 1).toFixed(1));

	targetDay.setValue( dateButoir.getDate() );
	targetMonth.setValue( dateButoir.getMonth() +1 );
	targetYear.setValue( dateButoir.getFullYear() );
	
	targetYear.simulate('change');
}

/**
 * Permet de récupérer un élément sans tenir compte du standard utilisé
 * 
 * @param {string|object} 'MonElement' ou 'Mon.element' ou $('MonElement)
 * @return {DOM}
 */
function getElementByString(string) {
	'use strict';
	if (string === null) {
		throw "La valeur de l'element est NULL, vous avez probablement tenté de selectionner un element qui n'existe pas";
	}
	
	if (typeof(string) === 'object') {
		// Est déja un élement Prototype
		if (string.tagName !== undefined) {
			return string;
		}
		else {
			throw "getElementByString() do not accept object";
		}
	}
	
	// Format cakephp
	if (string.match(/[\w]+\.[\w]+(\.[\w]+)*/)) {
		return $(fieldId(string));
	}
	
	// Sinon ce doit être déja un id
	return $(string);
}

/**
 * Cache un ou plusieurs élements en fonction d'une ou plusieurs valeurs d'autres elements
 * 
 * Dans values les clefs obligatoire sont : 
 *		- element: 'MonElement' ou 'Mon.element' ou $('MonElement)
 *		- value: Valeur de l'element pour activer/desactiver le disabled
 *		- operateur || operator: Par defaut defini à "=", accepte : true, =, ==, ===, false, !, !=, !==, <, >, <=, >=
 *		
 * @param {array|string} elements Liste des elements (DOM ou string) sur lesquels appliquer le disable ex: [ $(monElementId), $(monElementId2) ]
 * @param {array|object} values Liste des valeurs à avoir pour appliquer le disable ex: [ {element: $(monElement), value: '1', operator: '!='}, ... ]
 * @param {boolean} hide Si mis à TRUE, cache l'element plutôt que de le griser
 * @param {boolean} oneValueIsValid Si mis à TRUE, une valeur juste parmis la liste suffit à désactiver les elements
 */
function observeDisableElementsOnValues(elements, values, hide, oneValueIsValid) {
	'use strict';
	var i;
	
	elements = elements.constructor !== Array ? [elements] : elements;
	values = values.constructor !== Array ? [values] : values;
	hide = hide === undefined ? false : hide;
	oneValueIsValid = oneValueIsValid === undefined ? false : oneValueIsValid;
	
	disableElementsOnValues(elements, values, hide, oneValueIsValid);
	
	for (i=0; i<values.length; i++) {
		// On s'assure que les clefs sont présente
		if (values[i].element === undefined || values[i].value === undefined) {
			throw "Values must have element and value keys";
		}
		
		getElementByString(values[i].element).observe('change', function() {
			disableElementsOnValues(elements, values, hide, oneValueIsValid);
		});
	}
}

/**
 * Cache un ou plusieurs élements en fonction d'une ou plusieurs valeurs d'autres elements
 * 
 * Dans values les clefs obligatoire sont : 
 *		- element: 'MonElement' ou 'Mon.element' ou $('MonElement)
 *		- value: Valeur de l'element pour activer/desactiver le disabled
 *		- operateur || operator: Par defaut defini à "=", accepte : true, =, ==, ===, false, !, !=, !==, <, >, <=, >=
 * 
 * @param {array|string} elements Liste des elements (DOM ou string) sur lesquels appliquer le disable ex: [ $(monElementId), $(monElementId2) ]
 * @param {array|object} values Liste des valeurs à avoir pour appliquer le disable ex: [ {element: $(monElement), value: '1', operator: '!='}, ... ]
 * @param {boolean} hide Si mis à TRUE, cache l'element plutôt que de le griser
 * @param {boolean} oneValueIsValid Si mis à TRUE, une valeur juste parmis la liste suffit à désactiver les elements
 */
function disableElementsOnValues(elements, values, hide, oneValueIsValid) {
	'use strict';
	var i,
		element,
		condition = true,
		newCondition
	;
	
	// On commence par formater les variable de façon pour qu'on puisse les traiter pour une seul type
	elements = elements.constructor !== Array ? [elements] : elements;
	values = values.constructor !== Array ? [values] : values;
	hide = hide === undefined ? false : hide;
	oneValueIsValid = oneValueIsValid === undefined ? false : oneValueIsValid;
	
	// On vérifi les valeurs
	for (i=0; i<values.length; i++) {
		// On s'assure que les clefs sont présente
		if (values[i].element === undefined || values[i].value === undefined) {
			throw "Values must have element and value keys";
		}
		// On s'assure que l'element existe
		if (getElementByString(values[i].element) === null) {
			throw "Element "+values[i].element+" is not found!";
		}
		
		// Alias pour operator
		if (values[i].operateur !== undefined) {
			values[i].operator = values[i].operateur;
		}
		
		switch (values[i].operator === undefined ? '=' : values[i].operator) {
			case true:
			case '=':
			case '==':
			case '===':
				newCondition = getElementByString(values[i].element).getValue() === values[i].value;
				break;
			case false:
			case '!':
			case '!=':
			case '!==':
				newCondition = getElementByString(values[i].element).getValue() !== values[i].value;
				break;
			case '<':
				newCondition = getElementByString(values[i].element).getValue() < values[i].value;
				break;
			case '>':
				newCondition = getElementByString(values[i].element).getValue() > values[i].value;
				break;
			case '<=':
				newCondition = getElementByString(values[i].element).getValue() <= values[i].value;
				break;
			case '>=':
				newCondition = getElementByString(values[i].element).getValue() >= values[i].value;
				break;
			default:
				throw "values[i].operator must be in (true, =, ==, ===, false, !, !=, !==, <, >, <=, >=)";
		}
		
		condition = oneValueIsValid && i > 0 ? condition || newCondition : condition && newCondition;
	}
	
	// On applique le disable sur les elements
	for (i=0; i<elements.length; i++) {
		element = getElementByString(elements[i]);
		
		// On s'assure que l'element existe
		if (element === null) {
			throw "Element "+elements[i]+" is not found!";
		}
		
		if (condition) {
			element.disable();
			if( element.up( 'div.input' ) ) {
				element.up( 'div.input' ).addClassName( 'disabled' );
			}
			else if( element.up( 'div.checkbox' ) ) {
				element.up( 'div.checkbox' ).addClassName( 'disabled' );
			}
			
			if (hide) {
				element.hide();
			}
		}
		else {
			element.enable();
			if( element.up( 'div.input' ) ) {
				element.up( 'div.input' ).removeClassName( 'disabled' );
			}
			else if( element.up( 'div.checkbox' ) ) {
				element.up( 'div.checkbox' ).removeClassName( 'disabled' );
			}
			
			if (hide) {
				element.show();
			}
		}
	}
}

/*************************************************************************
 * Execution systématique												 *
 *************************************************************************/

document.observe( "dom:loaded", function(){
	'use strict';

	// Rend les boutons radio décochable si ils portent la class uncheckable
	$$('input[type="radio"].uncheckable').each(function( radio ){
		// Ajoute un hidden vide si le bouton n'en possède pas
		var parent = radio.up('fieldset');
		var hidden = parent !== undefined ? parent.select('input[type="hidden"][name="' + radio.name + '"]').first() : undefined;
		if ( parent === undefined ){
			parent = radio.up();
		}
		if ( hidden === undefined ){
			parent.insert({top: '<input type="hidden" name="' + radio.name + '" value="" />'});
		}
		
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
	
	// Divise les elements portant la class divideInto2Collumn en deux colonnes
	$$('.divideInto2Collumn').each(function( dom ){ divideIntoCollumn( dom, 2 ); });
	
	// Divise les elements portant la class divideInto3Collumn en trois colonnes
	$$('.divideInto3Collumn').each(function( dom ){ divideIntoCollumn( dom, 3 ); });
});