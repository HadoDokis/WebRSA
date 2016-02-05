/*global document, $$, toString*/

/**
 * Polyfill
 * 
 * @source https://developer.mozilla.org/fr/docs/Web/JavaScript/Reference/Objets_globaux/Object/create#Polyfill
 */
if (typeof Object.create !== 'function') {
	Object.create = (function () {
		'use strict';
		var Temp = function () {
			return;
		};
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
function uncheckable(radio) {
	'use strict';
	radio.onclick = function () {
		$$('input[name="'+radio.name+'"]').each(function (radio) {
			if (radio.checked && radio.state) {
				radio.state = false;
				radio.checked = false;
				
				if (typeof radio.simulate === 'function') {
					radio.simulate('change');
				}
			}
			else if (radio.checked) {
				radio.state = true;
			}
			else{
				radio.state = false;
			}
		});
	};
}


/*************************************************************************
 * Cache les optgroup vide dans un select								 *
 *************************************************************************/

/**
 * Cache les optgroup vide dans un select
 * 
 * @param {HTML} select
 * @returns {boolean}
 */
function removeEmptyOptgroup(select) {
	'use strict';
	var i, j, optgroups = select.select('optgroup'), options, empty;
	
	if (optgroups === null || optgroups.length === 0) {
		return false;
	}
	
	for (i=0; i<optgroups.length; i++) {
		options = optgroups[i].select('option');
		
		// Si il n'y a pas d'option on cache
		if (options === null || options.length === 0) {
			optgroups[i].hide();
			continue;
		}
		
		// Si il y a des options mais qu'elles sont toutes cachés, on cache
		empty = true;
		for (j=0; j<options.length; j++) {
			if (options[j].visible()) {
				empty = false;
				break;
			}
		}
		
		if (empty) {
			optgroups[i].hide();
		} else {
			optgroups[i].show();
		}
	}
	
	return true;
}

/*************************************************************************
 * Ajoute un id au parent de l'élément ciblé							 *
 *************************************************************************/

/**
 * Ajoute un id au parent de l'élément ciblé
 * 
 * @param {DOM} dom
 * @param {integer|string} id
 * @returns {Boolean}
 */
function addParentId(dom, id) {
	'use strict';
	if (dom === undefined || dom === null || dom.up().id !== '') {
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
function divideIntoCollumn(dom, nbCollumn) {
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
	dom.siblings().each(function (sibling) {
		if (sibling.tagName.toUpperCase() === 'LABEL') {
			sibling.style.width = '100%';
		}
	});

	// Stock les labels et copie les elements
	parent.select('div').each(function (div) {
		var name = div.select('label').first().innerHTML.replace(/[^A-Za-z]+/g, '');
		childs[name.toUpperCase()] = Element.clone(div, true);
		childsNames.push(name.toUpperCase());
	});

	// Les labels sont trié
	childsNames.sort();

	// On insert les colonnes
	for (; i < nbCollumn; i++) {
		divList[i] = new Element('div', {style: 'width:' + Math.floor(parentWidth / nbCollumn - 1) + 'px;display:inline-block;vertical-align:top;'});
		parent.insert(divList[i]);
	}

	// On rempli les colonnes dans le bon ordre
	for (i = 0; i < childsNames.length; i++) {
		divList[Math.floor(i / Math.ceil(childsNames.length / nbCollumn))].insert(childs[childsNames[i]]);
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
function giveDefaultValue(valeur, defaultValue) {
	'use strict';
	return valeur === undefined ? defaultValue : valeur;
}

/**
 * Ajoute les 0 manquant si besoin (ex: 1-2-2015 => 01-02-2015)
 * @param {String} dateString
 * @returns {String}
 */ 
function zeroFillDate(dateString) {
	'use strict';
	return dateString.replace( /^(\d)\-/, '0$1-' ).replace( /\-(\d)\-/, '-0$1-' ).replace( /\-(\d)$/, '-0$1' );
}

/**
 * Vérifi si un array contien une valeur
 * @param {String|Number} value
 * @param {Array} array
 * @returns {Boolean}
 */
function inArray(needle, haystack) {
	'use strict';
	var key;
	if (needle === null || typeof toString(needle) !== 'string' || !Array.isArray( haystack )) {
		return false;
	}
	for (key in haystack) {
		if (haystack.hasOwnProperty(key) && haystack[key] === needle) {
			return true;
		}
	}
	return false;
}

/**
 * Cast d'un array
 * @param {Mixed} values
 * @returns {Array}
 */
function castArray(values) {
	'use strict';
	return typeof values !== 'object' ? [values] : values;
}

/**
 * Permet d'obtenir un identifiant façon cake à partir d'un Model.nomdechamp
 * @param {String} modelField
 * @returns {String}
 */
function fieldId(modelField) {
	'use strict';
	var i, result = '', x, exploded = modelField.split(/[\._]/);
	for (i = 0; i < exploded.length; i++) {
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
		i = 1
	;

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
				default:
					break;
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
function setDateCloture(id, target) {
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

	if (isNaN(duree*2) || exploded.length < 2) {
		return false;
	}
	
	for (; i<exploded.length; i++) {
		baseTargetName += '['+exploded[i]+']';
	}
	
	targetDay = $$('select[name="'+baseTargetName+'[day]"]').first();
	targetMonth = $$('select[name="'+baseTargetName+'[month]"]').first();
	targetYear = $$('select[name="'+baseTargetName+'[year]"]').first();
	
	if (targetDay === undefined || targetMonth === undefined || targetYear === undefined) {
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
 * @param {string|object} string 'MonElement' ou 'Mon.element' ou $('MonElement)
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
 * Désactive un element avec ou sans fonction element.disable()
 * 
 * @param {DOM} element à désactiver
 */
function disable(element) {
	if (typeof element.disable === 'function') {
		element.disable();
	} else {
		element.style.pointerEvents = 'none';
	}
}

/**
 * Active un element avec ou sans fonction element.enable()
 * 
 * @param {DOM} element à désactiver
 */
function enable(element) {
	if (typeof element.enable === 'function') {
		element.enable();
	} else {
		element.style.pointerEvents = 'auto';
	}
}

/**
 * Permet sans utiliser eval de comparer la valeur de deux champs en fonction d'un operateur
 * 
 * @param {DOM} value1
 * @param {DOM} value2
 * @param {string} operator accepte : true, =, ==, ===, false, !, !=, !==, <, >, <=, >=
 * @returns {Boolean}
 */
function evalCompare(value1, operator, value2) {
	var result, value1, value2;
	
	switch (operator === undefined ? '=' : operator) {
		case true:
		case '=':
		case '==':
		case '===':
			result = value1 === value2;
			break;
		case false:
		case '!':
		case '!=':
		case '!==':
			result = value1 !== value2;
			break;
		case '<':
			result = parseFloat(value1, 10) < parseFloat(value2, 10);
			break;
		case '>':
			result = parseFloat(value1, 10) > parseFloat(value2, 10);
			break;
		case '<=':
			result = parseFloat(value1, 10) <= parseFloat(value2, 10);
			break;
		case '>=':
			result = parseFloat(value1, 10) >= parseFloat(value2, 10);
			break;
		default:
			throw "operator must be in (true, =, ==, ===, false, !, !=, !==, <, >, <=, >=)";
	}
	
	return result;
}

/**
 * Cache un ou plusieurs élements en fonction d'une ou plusieurs valeurs d'autres elements
 * 
 * Dans values les clefs obligatoire sont : 
 *		- element: 'MonElement' ou 'Mon.element' ou $('MonElement)
 *		- value: Valeur de l'element pour activer/desactiver le disabled
 *		- operateur || operator: Par defaut defini à "=", accepte : true, =, ==, ===, false, !, !=, !==, <, >, <=, >=
 *		
 *	Note :
 *		- Un input checkbox à une valeur soit de null, soit de '1'
 *		- Un input radio à une valeur soit de null, soit du value de l'element
 *		
 * @param {array|string} elements Liste des elements (DOM ou string) sur lesquels appliquer le disable ex: [ $(monElementId), $(monElementId2) ]
 * @param {array|object} values Liste des valeurs à avoir pour appliquer le disable ex: [ {element: $(monElement), value: '1', operator: '!='}, ... ]
 * @param {boolean} hide Si mis à TRUE, cache l'element plutôt que de le griser
 * @param {boolean} oneValueIsValid Si mis à TRUE, une valeur juste parmis la liste suffit à désactiver les elements
 * @param {boolean} debug Fait un console.log des valeurs des elements contenu dans values
 */
function observeDisableElementsOnValues(elements, values, hide, oneValueIsValid, debug) {
	'use strict';
	var i;
	
	elements = elements.constructor !== Array ? [elements] : elements;
	values = values.constructor !== Array ? [values] : values;
	hide = hide === undefined ? false : hide;
	oneValueIsValid = oneValueIsValid === undefined ? true : oneValueIsValid;
	
	disableElementsOnValues(elements, values, hide, oneValueIsValid, debug);
	
	for (i=0; i<values.length; i++) {
		// On s'assure que les clefs sont présente
		if (values[i].element === undefined || values[i].value === undefined) {
			throw "Values must have element and value keys";
		}
		
		getElementByString(values[i].element).observe('change', function() {
			disableElementsOnValues(elements, values, hide, oneValueIsValid, debug);
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
 * Note :
 *		- Un input checkbox à une valeur soit de null, soit de '1'
 *		- Un input radio à une valeur soit de null, soit du value de l'element
 * 
 * @param {array|string} elements Liste des elements (DOM ou string) sur lesquels appliquer le disable ex: [ $(monElementId), $(monElementId2) ]
 * @param {array|object} values Liste des valeurs à avoir pour appliquer le disable ex: [ {element: $(monElement), value: '1', operator: '!='}, ... ]
 * @param {boolean} hide Si mis à TRUE, cache l'element plutôt que de le griser
 * @param {boolean} oneValueIsValid Si mis à TRUE, une valeur juste parmis la liste suffit à désactiver les elements
 * @param {boolean} debug Fait un console.log des valeurs des elements contenu dans values
 */
function disableElementsOnValues(elements, values, hide, oneValueIsValid, debug) {
	'use strict';
	var i,
		j,
		element,
		condition = true,
		newCondition,
		valueElement,
		valueName,
		validParents = ['div.input', 'div.checkbox', 'td.action'],
		haveAValidParent
	;
	
	// On commence par formater les variable de façon pour qu'on puisse les traiter pour une seul type (array et boolean)
	elements = elements.constructor !== Array ? [elements] : elements;
	values = values.constructor !== Array ? [values] : values;
	hide = hide === undefined ? false : hide;
	
	oneValueIsValid = oneValueIsValid === undefined ? true : oneValueIsValid;
	
	// On vérifi les valeurs
	for (i=0; i<values.length; i++) {
		// On s'assure que les clefs sont présente
		if (values[i].element === undefined || values[i].value === undefined) {
			throw "Values must have element and value keys";
		}
		
		valueElement = getElementByString(values[i].element);
		valueName = valueElement.id !== null 
			? valueElement.id 
			: (typeof values[i].element === 'string' ? values[i].element : '<object id=null>')
		;
		
		// On s'assure que l'element existe
		if (valueElement === null) {
			throw "Element "+valueName+" is not found!";
		}
		
		// Alias pour operator
		if (values[i].operateur !== undefined) {
			values[i].operator = values[i].operateur;
		}
		
		values[i].operator = values[i].operator === undefined ? '=' : values[i].operator;
		newCondition = evalCompare(valueElement.getValue(), values[i].operator, values[i].value);
		condition = oneValueIsValid && i > 0 ? condition || newCondition : condition && newCondition;
		
		// Pratique pour comprendre pourquoi un element s'active ou se désactive
		if (debug) {
			console.log("----------DEBUG: disableElementsOnValues()----------");
			console.log("Element: '"+valueName+"' tagetValue: '"+values[i].operator+" "+values[i].value+"' value: '"+valueElement.getValue()+"' condition: "+(condition ? 'true' : 'false'));
		}
	}
	
	// On applique le disable sur les elements
	for (i=0; i<elements.length; i++) {
		element = getElementByString(elements[i]);
		
		// On s'assure que l'element existe
		if (element === null) {
			throw "Element "+elements[i]+" is not found!";
		}
		
		// Si condition === true alors on desactive/cache l'element
		for (j=0; j<validParents.length; j++) {
			// Les conditions sont rempli, donc on desactive/cache l'element
			if (condition && element.up(validParents[j])) {
				haveAValidParent = true;
				disable(element);
				element.up( validParents[j] ).addClassName( 'disabled' );
				if (hide) {
					element.hide();
				}
				break;
				
			// Les conditions ne sont pas rempli, on active/montre l'element
			} else if (element.up(validParents[j])) {
				haveAValidParent = true;
				enable(element);
				element.up( validParents[j] ).removeClassName( 'disabled' );
				if (hide) {
					element.show();
				}
				break;
			}
		}
		
		// On s'assure qu'un parent a bien été trouvé
		if (!haveAValidParent) {
			throw "L"+(condition ? "'" : 'a dés')+"activation de l'element n'a pas pu être effectué car l'element choisi ne fait pas parti de la liste des cas pris en charge.";
		}
	}
}


/*************************************************************************
 * Execution systématique												 *
 *************************************************************************/

document.observe("dom:loaded", function () {
	'use strict';

	// Rend les boutons radio décochable si ils portent la class uncheckable
	$$('input[type="radio"].uncheckable').each(function (radio) {
		// Ajoute un hidden vide si le bouton n'en possède pas
		var parent = radio.up('fieldset');
		var hidden = parent !== undefined ? parent.select('input[type="hidden"][name="' + radio.name + '"]').first() : undefined;
		if (parent === undefined) {
			parent = radio.up();
		}
		if (hidden === undefined) {
			parent.insert({top: '<input type="hidden" name="' + radio.name + '" value="" />'});
		}

		radio.state = radio.checked;
		uncheckable( radio );
	});

	// Ajoute un visuel sur les input portant la class percent ou euros
	$$('input.percent').each(function (input) {
		input.insert({after: '<div class="input-group-addon">%</div>'});
	});
	$$('input.euros').each(function (input) {
		input.insert({after: '<div class="input-group-addon">€</div>'});
	});

	// Ajoute un id au parent de l'élément ciblé
	$$('.add-parent-id').each(function (dom) {
		addParentId(dom);
	});

	// Divise les elements portant la class divideInto2Collumn en deux colonnes
	$$('.divideInto2Collumn').each(function (dom) {
		divideIntoCollumn(dom, 2);
	});

	// Divise les elements portant la class divideInto3Collumn en trois colonnes
	$$('.divideInto3Collumn').each(function (dom) {
		divideIntoCollumn(dom, 3);
	});
});