<?php
	$departement = Configure::read( 'Cg.departement' );
	$controller = $this->params->controller;
	$action = $this->action;
	$formId = ucfirst($controller) . ucfirst($action) . 'Form';
	$availableDomains = MultiDomainsTranslator::urlDomains();
	$domain = isset( $availableDomains[0] ) ? $availableDomains[0] : $controller;
	echo $this->Default3->titleForLayout( array(), array( 'domain' => $domain ) );
	
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ), array( 'inline' => false ) );
	}
	
	$requestManagerGroupOptions = '';
	foreach ( Hash::get($options, 'Requestmanager.requestgroup_id') as $key => $value ) {
		$requestManagerGroupOptions .= "<option value=\"{$key}\">{$value}</option>";
	}
	
	$modelsList = '';
	foreach ( Hash::get($options, 'Requestmanager.modellist') as $key => $value ) {
		$modelsList .= "<option value=\"{$key}\">{$value}</option>";
	}
?>

<form action="<?php echo Router::url( array( 'controller' => $controller, 'action' => 'search' ) );?>" method="post">
	<fieldset>
		<legend><?php echo __m('Requestmanager.savedsearch');?></legend>
		<?php echo $this->Xform->input( "Requestmanager.name", 
			array( 
				'label' => __m('Requestmanager.name'), 
				'type' => 'select', 
				'options' => Hash::get($options, 'Requestmanager.grouped_name'), 
				'empty' => true 
			) 
		);?>
		<div class="center">
			<input type="submit" value="Rechercher">
			<input type="button" value="Charger" id="generateButton">
		</div>
	</fieldset>
</form>

<br><hr>

<h2><?php echo __m('Requestmanager.title_creation');?></h2>
<form action="<?php echo Router::url( array( 'controller' => $controller, 'action' => 'newrequest' ) );?>" method="post" id="FormRequestmaster">
	<fieldset>
		<legend><?php echo __m('Requestmanager.newsearch');?></legend>
		<input type="hidden" name="data[Requestmanager][actif]" id="RequestmanagerActif" value="1">
		<?php echo $this->Xform->input( "Requestmanager.requestgroup_id", 
			array( 
				'label' => __m('Requestmanager.requestgroup_id').REQUIRED_MARK, 
				'type' => 'select', 
				'options' => Hash::get($options, 'Requestmanager.requestgroup_id'), 
				'empty' => true 
			) 
		)
		.$this->Xform->input( "Requestmanager.name", 
			array( 
				'label' => __m('Requestmanager.name').REQUIRED_MARK, 
				'id' => 'RequestmanagerNameNew'
			) 
		);?>
	</fieldset>
	<br>
	<h3><?php echo __m('Requestmanage.main_table');?></h3>
	<fieldset>
		<div class="input select">
			<label for="RequestmanagerFrom"><?php echo __m('Requestmanager.from').REQUIRED_MARK;?></label>
			<select name="data[Requestmanager][from]" id="RequestmanagerFrom">
				<option value=""></option>
				<?php echo $modelsList; ?>
			</select>			
		</div>
		<div class="error" style="display:none" id="error-from-from"><p>Une érreur s'est produite!</p></div>
		<div style="display:none" id="fields-from-from"></div>
		<div style="display:none" id="joins-from-from"></div>
	</fieldset>
	<div id="zoneJointure"></div>
	<fieldset id="endForm">
		<div class="input textarea">
			<label for="AddFields">Champs supplémentaires<label>
					<textarea id="Addfields" name="data[Add][fields]" placeholder='MYFUNCT("Matable1"."monchamp", "Matable2"."monautrechamp") AS "Monchampcustom__avec2underscore"'></textarea>
		</div>
		<div class="input textarea">
			<label for="AddConditions">Conditions supplémentaires<label>
			<textarea id="AddConditions" name="data[Add][conditions]" placeholder='(Matable1.monchamp IN (SELECT matable1_id from matable2) AND Matable1.monautrechamp = 2) OR Matable2.encoreunchamp IS NULL'></textarea>
		</div>
		<div class="input text">
			<label for="Order">Order by</label>
			<input type="text" id="Order" name="data[Add][order]" placeholder='Matable1.date DESC, Matable1.id'>
		</div>
		<div class="center notice" id="div-verification">
			<p id="msg-validation" class="center">Selectionnez une table principale</p>
			<input type="button" value="Vérifier" id="verificationButton">
		</div>
		<div class="center">
			<input type="submit" value="Enregistrer et rechercher" name="saveandsearch" class="disable-if-not-validated" disabled="true">
			<input type="submit" value="Ne pas enregistrer mais rechercher" name="donotsaveandsearch" class="disable-if-not-validated" disabled="true">
		</div>
	</fieldset>
</form>


<script>
	/* global Ajax, extract, $break */
	var labelTable = '<?php echo __m('Requestmanager.labeltable');?>';
	var labelJoin = '<?php echo __m('Requestmanager.labeljoin');?>';
	var modelsList = '<?php echo $modelsList;?>';
	var _collection = {};
	var joins = {};
	var generatedFields = [];
	var generatedConditions = [];
	var joinFinish = new Element('finish', {nbJoins: 0}); // Element spécial uniquement présent pour le support d'evenement
	var nbJoin = {}; // Enregistre le nombre de jointures effectué par Modeles
	
	/**
	 * Création d'un menu déroulant pour les jointures
	 * 
	 * @param {json} json
	 * @param {string} index - Pour les noms dynamiques, garni les div et nomme en fonction de index
	 * @param {string} oldindex - ancien index pour le remplissage des champs Alias
	 * @returns {void}
	 */
	function createJoinList( json, index, oldindex ) {console.log(['createJoinList', json, index, oldindex]);
		var idJoin = 'join-'+oldindex+'-'+index+'-'+$('joins-'+oldindex+'-'+index).select('select').length;
		var div = new Element('div', {'class': 'input select'});
		var label = new Element('label', {for: idJoin});
		var select = new Element('select', {
			id: idJoin,
			name: idJoin
		});
		var options = [];
		
		label.insert(labelJoin);
		select.insert(new Element('option',{value:''}));
		div.insert(label);
		div.insert({bottom: select});
		
		for (var i=0; i<json.joins.length; i++) {
			options[i] = new Element('option', {value: json.joins[i]});
			options[i].insert(json.joins[i]);
			select.insert({bottom: options[i]});
		}
		
		$('joins-'+oldindex+'-'+index).insert( {bottom: div} );console.log(['createJoinList:insert:joins-'+oldindex+'-'+index, div]);
		
		_collection[idJoin] = select;
		
		$(idJoin).observe('change', function(){console.log('select:event:change');
			var alias = this.getValue(),
				idDiv = json.alias+'__'+alias;
			
			if ( alias !== '' && !$(idDiv) ) {
				if ( $(this.prevId) !== undefined ) {console.log(['select:event:change:remove', $(this.prevId)]);
					$(this.prevId).remove();
				}

				// On ajoute un select en cas de selection pour jointure multiple
				createJoinList( json, index, oldindex );

				// Ajout d'un nouveau fieldset (voir plus haut, la partie en html)
				$('zoneJointure').insert({bottom: '<div id="'+idDiv+'"><br><h3>Jointure depuis '+json.alias+' vers '+alias+'</h3><fieldset><div class="error" style="display:none" id="error-'+index+'-'+alias+'"><p>Une érreur s\'est produite!</p></div><div style="display:none" id="fields-'+json.alias+'-'+alias+'"></div><div style="display:none" id="joins-'+json.alias+'-'+alias+'"></div></fieldset><div>'});
				console.log(['select:event:change:zoneJointure:insert', $(idDiv)]);
				this.prevId = idDiv;

				// Appel ajax pour remplir le fieldset
				$('joins-'+json.alias+'-'+alias).insert( {top: "<h4>Jointures sur la table "+alias+"</h4>"} );console.log(['select:event:change:joins-'+json.alias+'-'+alias+':insert', $('joins-'+json.alias+'-'+alias)]);

				// Lorsque getModel est fini, on indique que la jointure est terminée
				this.observe('finish:getModel:'+alias, function() {
					this.fire('finish:join:'+this.id);
				});
				
				getModel( alias, alias, json.alias, this );
			}
			else if (this.prevId !== undefined) {console.log(['select:event:change:remove', $(this.prevId)]);
				$(this.prevId).remove();console.log(['select:event:change:remove', this.up('div')]);
				this.up('div').remove();
			} 
			else {console.log(['select:event:change:this:setValue:', this]);
				this.setValue('');
			}
		});
	}
	
	function inputChange() {console.log('inputChange');
		if ( this.oldValue === undefined ) {
			// On permet un bon alignement avec des elements invisibles identique
			var newDiv = new Element('div', {id: 'div-'+this.id+'_', class: 'subinput'});
			var clone = this.clone(true);
			
			clone.id = clone.id + '_';
			clone.name = clone.name + '_';
			clone.setValue('');
			
			newDiv.insert({bottom: clone});
			this.up('div').up('div').insert({bottom: newDiv});
			this.oldValue = $(this.id).getValue();
			clone.observe('change', inputChange);
		}
		else if ( this.getValue() !== '' ) {
			this.oldValue = this.getValue();
		}
		else {
			this.oldValue = undefined;
			this.setValue($(this.id+'_').getValue());
			$('div-'+this.id+'_').remove();
			this.simulate('change');
		}
		
		this.fire('finish:condition:'+this.id);
	}
	
	/**
	 * onClick des boutons aux '...' - Appel ajax des valeurs possibles d'un champ (max 100)
	 * Ajoute un select
	 * 
	 * @returns {void}	 
	 */
	function findList() {console.log('findList');
		var button = this;
		new Ajax.Request('<?php echo Router::url( array( 'controller' => $controller, 'action' => 'ajax_list' ) ); ?>/', {
			asynchronous:true, 
			evalScripts:true, 
			parameters: {
				'alias': button.readAttribute('alias'),
				'field': button.readAttribute('field')
			}, 
			requestHeaders: {Accept: 'application/json'},
			onComplete:function(request, json) {console.log('findList:onComplete');
				// On transforme l'input en select
				var input = $(button.readAttribute('link'));
				var select = new Element('select', {
					id: input.id,
					name: input.name
				});
				input.simulate('change');
				input.remove();
				var option = [new Element('option', {value: ''})];
				select.insert(option[0]);
				
				for (var i=0; i<json.enum.length; i++) {
					option.push(new Element('option', {value: json.enum[i]}));
					option[option.length -1].insert(json.enum[i]);
					select.insert({bottom: option[option.length -1]});
				}
				// On selectionne la derniere valeur pour simuler un "change" pour recréer un input
				select.setValue(json.enum[i]);
				
				button.up('div').insertBefore(select, button);
				button.remove();
				
				select.observe('change', inputChange);
			}
		});
	}
	
	/**
	 * Appel ajax pour obtenir la liste des champs d'un model et ses relations, ses enums et traductions.
	 * 
	 * @param {string} modelName - Nom du modele à intéroger
	 * @param {string} index - Pour les noms dynamiques, garni les div et nomme en fonction de index
	 * @param {string} oldindex - ancien index pour le remplissage des champs Alias
	 * @param {dom} dom - element sur lequel envoyer l'evenement finish
	 * @returns {void}	 
	 */ 
	function getModel( modelName, index, oldindex, dom ) {console.log(['getModel', modelName, index, oldindex, dom]);
		new Ajax.Request('<?php echo Router::url( array( 'controller' => $controller, 'action' => 'ajax_get' ) ); ?>/', {
			asynchronous:true, 
			evalScripts:true, 
			parameters: {
				'model': modelName
			}, 
			requestHeaders: {Accept: 'application/json'},
			onComplete:function(request, json) {console.log('getModel:onComplete');
				$('error-'+oldindex+'-'+index).hide();
				$('fields-'+oldindex+'-'+index).show();
				$('joins-'+oldindex+'-'+index).show();
				
				// On s'assure que les div portent le bon id (les premières portent from-from dans l'id, il faut renommer en from-Monmodel
				$('error-'+oldindex+'-'+index).oldid = 'error-'+oldindex+'-'+index;
				$('fields-'+oldindex+'-'+index).oldid = 'fields-'+oldindex+'-'+index;
				$('joins-'+oldindex+'-'+index).oldid = 'joins-'+oldindex+'-'+index;
				
				$('error-'+oldindex+'-'+index).id = 'error-'+oldindex+'-'+modelName;
				$('fields-'+oldindex+'-'+index).id = 'fields-'+oldindex+'-'+modelName;
				$('joins-'+oldindex+'-'+index).id = 'joins-'+oldindex+'-'+modelName;

				var h4 = new Element('h4');
				h4.insert('Champs de la table '+modelName);
				$('fields-'+oldindex+'-'+modelName).insert({bottom: h4});
				
				// Verifi que ce model a bien des enums
				var enums = false;
				for (var key in json.enums){
					if ( json.enums.hasOwnProperty(key) ) {
						enums = true;
					}
				}
				
				// Sur chaque champs...
				for (var i=0; i<json.fields.length; i++) {
					// On creer la structure HTML avec un checkbox, un label avec un input et/ou select
					var divSelect = new Element('div', {class: 'input checkbox'});
					var divMain = new Element('div');
					divSelect.insert(divMain);
					var checkbox = new Element('input', {
						type: 'checkbox',
						name: oldindex+'-'+index+'-'+json.names[i],
						id: oldindex+'-'+index+'-'+json.ids[i],
						'original-name': json.names[i]
					});
					divMain.insert({bottom: checkbox});
					var label = new Element('label', {for: oldindex+'-'+index+'-'+json.ids[i]});
					var span = new Element('span');
					span.insert(json.fields[i]+' ('+json.traductions[i]+')');
					label.insert(span);
					divMain.insert({bottom: label});
					
					// Si un enum existe pour ce champ, on créer un select rempli des bonnes options
					if ( enums && json.enums[json.alias][json.fields[i]] !== undefined ) {
						var select = new Element('select', {
							id: 'conditions-select-'+oldindex+'-'+modelName+'-'+json.fields[i],
							name: 'conditions-select-'+oldindex+'-'+modelName+'-'+json.fields[i]
						});
						var option = [new Element('option', {value: ''})];
						select.insert({bottom: option[0]});
						
						// Pour chaque valeur de l'enum, on ajoute une option
						for (var key in json.enums[json.alias][json.fields[i]]){
							if ( json.enums[json.alias][json.fields[i]].hasOwnProperty(key) ) {
								option.push(new Element('option', {value: key}));
								option[option.length -1].insert(key + ' - ' + json.enums[json.alias][json.fields[i]][key]);
								select.insert({bottom: option[option.length -1]});
							}
						}
						
						divMain.insert({bottom: select});
						
						select.observe('change', inputChange);
						
						var input = new Element('input', {
							type: 'text',
							id: 'conditions-text-'+oldindex+'-'+modelName+'-'+json.fields[i],
							name: 'conditions-text-'+oldindex+'-'+modelName+'-'+json.fields[i],
							'original-name': json.names[i]
						});
						var subDiv = new Element('div', {class: 'subinput'});
						subDiv.insert(input);
						
						divMain.insert({bottom: subDiv});
						
						input.observe('change', inputChange);
					}
					
					// Sinon, on se contente d'un champ text et d'un boutton pour trouver des valeurs
					else {
						var input = new Element('input', {
							type: 'text',
							id: 'conditions-'+oldindex+'-'+modelName+'-'+json.fields[i],
							name: 'conditions-'+oldindex+'-'+modelName+'-'+json.fields[i],
							'original-name': json.names[i]
						});
						divMain.insert({bottom: input});
						var button = new Element('input', {
							value: '...',
							type: 'button',
							link: 'conditions-'+oldindex+'-'+modelName+'-'+json.fields[i],
							alias: json.alias,
							field: json.fields[i],
							title: 'Trouver des valeurs (max 100)'
						});
						divMain.insert({bottom: button});
						
						input.observe('change', inputChange);
						button.observe('click', findList);
					}
					
					$('fields-'+oldindex+'-'+modelName).insert( {bottom: divSelect} );
				}

				createJoinList( json, modelName, oldindex );
				dom.fire('finish:getModel:'+modelName);
			},
			onFail:function() {
				$('error-'+oldindex+'-'+index).show();
			},
			onException:function() {
				$('error-'+oldindex+'-'+index).show();
			}
		});
	}
	
	$('RequestmanagerFrom').observe('change', function(event) {console.log('RequestmanagerForm:event:change');console.info(this.getValue());
		$('RequestmanagerFrom').up('fieldset').select('div').each(function(div) {
			if ( div.oldid !== undefined ) {
				div.id = div.oldid;
				div.innerHTML = '';
			}
		});
		$('zoneJointure').innerHTML = '';
		num = 0;
		getModel( $F('RequestmanagerFrom'), 'from', 'from', $('RequestmanagerFrom') );
		$('joins-from-from').insert( {top: "<h4>Jointures sur la table "+$F('RequestmanagerFrom')+"</h4>"} );
	});
	
	$('FormRequestmaster').observe('change', function(){console.log('FormRequestmaster:event:change');
		$$('.disable-if-not-validated').each(function(submit){submit.setAttribute('disabled', true);});
		$('div-verification').removeClassName('success').removeClassName('error_message').addClassName('notice');
		$('msg-validation').innerHTML = 'La requête doit-être validée';
	});
	
	$('verificationButton').observe('click', function() {console.log('verificationButton:event:click');
		var params = Form.serializeElements( $$( '#FormRequestmaster input, #FormRequestmaster select, #FormRequestmaster textarea' ), { hash: true, submit: false } );

		new Ajax.Request('<?php echo Router::url( array( 'controller' => $controller, 'action' => 'ajax_check' ) ); ?>/', {
			asynchronous:true, 
			evalScripts:true, 
			parameters: params, 
			requestHeaders: {Accept: 'application/json'},
			onComplete:function(request, json) {
				if ( json.success ) {
					$$('.disable-if-not-validated').each(function(submit){
						// On ne permet la sauvegarde que si Catégorie et Titre de la requête sont remplis
						if ( submit.name !== 'saveandsearch' 
						|| ($('RequestmanagerRequestgroupId').getValue() !== '' && $('RequestmanagerNameNew').getValue() !== '' )) {
							submit.removeAttribute('disabled');
						}
					});
					$('div-verification').removeClassName('notice').removeClassName('error_message').addClassName('success');
					$('msg-validation').innerHTML = 'Requête validée';
				}
				else {
					$$('.disable-if-not-validated').each(function(submit){submit.setAttribute('disabled', true);});
					$('div-verification').removeClassName('notice').removeClassName('success').addClassName('error_message');
					$('msg-validation').innerHTML = 'Il y a une erreur dans votre requête :<br>'+json.message+
							'<br><br><div id="div-error-sql" style="display:none;">'+json.value+
							'</div><br><a href="#none" onclick="$(\'div-error-sql\').toggle();">Afficher SQL</a>'
					;
				}
			}
		});
	});
	
	
	$('generateButton').observe('click', function(){console.log('generateButton:event:click');
		if ( $('RequestmanagerName').getValue() === '' ) {
			return false;
		}
		
		resetForm();
		
		new Ajax.Request('<?php echo Router::url( array( 'controller' => $controller, 'action' => 'ajax_load' ) ); ?>/'+$('RequestmanagerName').getValue(), {
			asynchronous:true, 
			evalScripts:true,  
			requestHeaders: {Accept: 'application/json'},
			onComplete:function(request, json) {
				var request = JSON.parse(json.json);
				delete request.recursive;
				delete request.contain;
				delete request.json;
				
				/*
				 * INFO : Ici on fait toutes les jointures.
				 * Lorsque toutes les jointures sont faites, un evenement est envoyé.
				 * On surveille cet evenement pour s'occuper de cocher les cases et remplir les champs.
				 * L'ordre des blocs de code est très important pour un bon déroulement de l'opération car
				 * on fait appel à de nombreux évenements pour générer le formulaire.
				 */
				
				// Si il n'y a pas de jointure, on met un array vide pour éviter de faire planter le reste du script
				if ( request.joins === undefined ) {
					request.joins = [];
				}
				
				joinFinish.nbJoins = request.joins.length;
			
				// Lorsque toutes les jointures sont faite, on coche les checkbox
				joinFinish.observe('finish:allJoins', function() {console.log('joinFinish:event:finish:allJoins');
					console.log('-------------------------------------------------------------');
					// On coche toutes les cases à partir de request.field (si regex [\w]+.[\w]+)
					var matches,
						reg;
					
		console.log(request.fields);
					
					for (i=0; i<request.fields.length; i++) {
						matches = request.fields[i].match( /^([\w]+)\.([\w]+)$/ );
						
						if ( matches ) {
							$$('#FormRequestmaster input[original-name="data['+matches[1]+']['+matches[2]+']"]').first().setAttribute('checked', true);
						}
						else {
							generatedFields.push(request.fields[i]);
						}
					}
					console.log('-------------------------------------------------------------');
					
					for (var key in request.conditions) {
						if ( !request.conditions.hasOwnProperty(key) ) {
							continue;
						}
						
						if ( isNaN(key) ) {
							autoCondition(key, request.conditions[key], 0);
						}
						else {
							generatedConditions.push(request.conditions[key]);
						}
					}
					
					
					// Pour chaques fields stockés, on génère un string pour remplir le textarea fields
					$('Addfields').setValue( generatedFields.join(", ") );
					
					// Pour chaques conditions stockés, on génère un string pour remplir le textarea conditions
					$('AddConditions').setValue( generatedConditions.join(" AND ") );
				});
			
				joinFinish.observe('change', function() {console.log('joinFinish:event:change');
					joinFinish.nbJoins--;
					
					if ( joinFinish.nbJoins === 0 ) {
						joinFinish.fire('finish:allJoins');
					}
				});
				
				// On prépare la liste de jointure
				var jointure;
				for (var i=0; i<request.joins.length; i++) {
					jointure = findJoin( request.joins[i] );
					
					if ( joins[jointure.base] === undefined ) {
						joins[jointure.base] = [];
					}
					
					nbJoin[jointure.base] = 0;
					joins[jointure.base].push(jointure.join);
					
					if ( request.joins[i].type === 'INNER' ) {
						generatedConditions.push('"'+request.joins[i].alias+'"."id" IS NOT NULL');
						console.log(generatedConditions);
					}
				}
				
				
				
				// Lorsque l'evenement change est fini, on fait la jointure sur le model suivant (fait toutes les jointures dans la vue)
				$('RequestmanagerFrom').observe('finish:getModel:'+json.model, function(){console.log('RequestmanagerFrom:event:finish:getModel:'+json.model);
					autoJoin( $('RequestmanagerFrom').getValue(), 'from', 0 );
					
					// Nécéssaire s'il n'y a pas de jointures
					if ( joinFinish.nbJoins === 0 ) {
						joinFinish.fire('finish:allJoins');
					}
				});
				
				// On charge le modele principale
				$('RequestmanagerFrom').setValue(json.model);
				$('RequestmanagerFrom').simulate('change');
			}
		});
	});
	
	/**
	 * Permet à partir d'une requete join de type cakephp en json, de trouver le modele sur lequel faire la jointure et le nom du modele join
	 * Fonctionne sur une condition de jointure classique : "Model1"."champ1" = "Model2"."champ2" AND ... (Conditions additionnelles)
	 * 
	 * @param {json} joinRequest
	 * @returns {json}
	 */
	function findJoin( joinRequest ) {console.log(['findJoin', joinRequest]);
		// Si l'alias est trouvé au debut :
		var reg = new RegExp('"'+joinRequest.alias+'"\."[^"]+" = "([^"]+)"');
		var testReg = joinRequest.conditions.match( reg );
		
		if ( !testReg ) {
			reg = new RegExp('"([^"]+)"\."[^"]+" = "'+joinRequest.alias+'"');
			testReg = joinRequest.conditions.match( reg );
		}
		
		return {
			base: testReg[1],
			join: joinRequest.alias
		};
	}
	
	/**
	 * Rempli automatiquement les selects en fonction de la variable globale "joins"
	 * 
	 * @param {string} index
	 * @param {string} oldindex
	 * @returns {boolean}
	 */
	function autoJoin( index, oldindex, i ) {
		'use strict';
		console.log(['autoJoin', index, oldindex, i]);
		var baseId = 'join-'+oldindex+'-'+index,
			select;
	console.info('================= '+baseId + '-' + i);
		if ( joins[index] === undefined || i >= joins[index].length ) {
			return true;
		}
		
		select = _collection[baseId + '-' + i];

		select.observe('finish:join:'+select.id, function() {console.info('select:event:finish:join:'+select.id);
			var matches = this.id.match(/^join\-([\w]+)\-([\w]+)\-([0-9]+)$/); // Donne oldindex

			// On fait les jointures sur la jointure enfant
			autoJoin( this.getValue(), matches[2], 0 );

			// On fait la jointure suivante du modele actuel
			autoJoin( matches[2], matches[1], parseInt(matches[3], 10)+1 );
			
			joinFinish.simulate('change');
		});

		select.setValue(joins[index][i]);
		select.simulate('change');
		
		return true;
	}
	
	/**
	 * Rempli automatiquement les conditions en fonction de key et value
	 * 
	 * @param {type} key
	 * @param {type} value
	 * @param {type} i
	 * @returns {Boolean}	 
	 */
	function autoCondition( key, value, i ) {console.info(['autoCondition', key, value, i]);
		var matches = key.match(/^([\w]+)\.([\w]+)$/);
		console.info(matches);
		if ( value.length <= i ) {console.info('i>length');
			return false;
		}
		
		if ( matches === null ) {
			generatedConditions.push(value[i]);
		}
		else {
			var input = $$('#FormRequestmaster input[type="text"][original-name="data['+matches[1]+']['+matches[2]+']"]').last();
			
			if ( input === undefined ) {
				return false;
			}
			
			console.info('is Defined');
			input.observe('finish:condition:'+input.id, function() {console.info(['autoCondition:event:finish:condition:'+this.id, this]);
				autoCondition( key, value, i+1 );
			});
console.info('setValue:'+input.id+':'+value[i]);
			input.setValue(value[i]);
			input.simulate('change');
		}
		
		return true;
	}
	
	/**
	 * Réinitialise le formulaire
	 * 
	 * @returns {void}	 
	 */
	function resetForm() {
		var value = $('RequestmanagerFrom').getValue(),
			suffix = value === '' ? 'from' : value;
		
		_collection = {};
		joins = {};
		generatedFields = [];
		generatedConditions = [];
		joinFinish = new Element('finish', {nbJoins: 0});
		
		$('fields-from-'+suffix).innerHTML = '';
		$('fields-from-'+suffix).id = 'fields-from-from';
		$('joins-from-'+suffix).innerHTML = '';
		$('joins-from-'+suffix).id = 'joins-from-from';
		$('error-from-'+suffix).id = 'error-from-from';
		$('zoneJointure').innerHTML = '';
	}
</script>