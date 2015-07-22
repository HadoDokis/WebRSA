	/**
	* Contient le contenu des parenthèses retiré du rapport
	* @type Array
	*/
   var brackets = [];

   /**
	* Transforme un SELECT [0] FROM ... en SELECT (contenu de bracket[0]) FROM ...
	* 
	* @param {HTML} span Le <span> contenant un texte de type [0]
	* @returns {void}
	*/
   function restoreBrackets( span ) {
	   var innerText = span.target.innerHTML;
	   if ( brackets[innerText.substr(1, innerText.length -2)] !== undefined ) {
		   span.target.innerHTML = '('+ brackets[innerText.substr(1, innerText.length -2)] +')';
	   }
   }
   
   /**
	* Envoi sql à url et affiche le resultat dans pre
	* 
	* @param {string} sql code SQL à traiter
	* @param {HTML} pre container pour l'affichage du resultat
	* @param {string} url pour la requete Ajax
	* @param {string} image image de chargement
	* @param {string} failureMsg message en cas d'evenement onFailure
	* @param {string} exceptionMsg message en cas d'evenement onException
	* @returns {void}
	*/
   function analyse( sql, pre, url, image, failureMsg, exceptionMsg ){
		/**
		 * On affiche le bloc <pre> et on y colle une image de charchement
		 */
		pre.style.display = 'block';
		pre.innerHTML = '<div class="center">'+image+'</div>';

		/**
		 * On demande à AnalysesqlsController de produire un rapport sur le contenu de sql
		 */
		new Ajax.Request(url+'/', {
			asynchronous:true, 
			evalScripts:true, 
			parameters: {
				'sql': sql.replace("\t", "")
			}, 
			requestHeaders: {Accept: 'application/json'},
			/**
			 * En cas de succès, en rempli la variable brackets et on insert le rapport dans la balise <pre>
			 * On ajoute également un evenement au clic sur les span du rapport qui lance restoreBrackets()
			 * 
			 * @param {object} request
			 * @param {json} json
			 * @returns {void}
			 */
			onComplete:function(request, json) {
				brackets = json.innerBrackets;
				pre.innerHTML = json.text;
				pre.select('span').each(function(span){
					span.observe('click', restoreBrackets, span);
				});
			},

			/**
			 * Affiche d'un message d'érreur en cas de problème
			 * 
			 * @returns {void}
			 */
			onFailure:function() {
				pre.innerHTML = failureMsg;
			},
			onException:function() {
				pre.innerHTML = exceptionMsg;
			}
		});
	}