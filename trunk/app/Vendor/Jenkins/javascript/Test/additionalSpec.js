/* global expect, getElementByString */

document.observe( "dom:loaded", function(){
	describe("getElementByString()", function() {
		it("Selectionne l'element à la façon cakePhp", function() {
			expect(getElementByString('Model1.champ1.day').id).toEqual( 'Model1Champ1Day' );
			expect(getElementByString('Model1.champ2').id).toEqual( 'Model1Champ2' );
			expect(getElementByString('Search.Model1.champ3.TEST1').id).toEqual( 'SearchModel1Champ3TEST1' );
		});
		it("Selectionne l'element par id", function() {
			expect(getElementByString('Model1Champ1Day').id).toEqual( 'Model1Champ1Day' );
			expect(getElementByString('Model1Champ2').id).toEqual( 'Model1Champ2' );
			expect(getElementByString('SearchModel1Champ3TEST1').id).toEqual( 'SearchModel1Champ3TEST1' );
		});
		it("Renvoi l'element si le paramètre est déja un element", function() {
			expect(getElementByString($('Model1Champ1Day')).id).toEqual( 'Model1Champ1Day' );
			expect(getElementByString($('Model1Champ2')).id).toEqual( 'Model1Champ2' );
			expect(getElementByString($('SearchModel1Champ3TEST1')).id).toEqual( 'SearchModel1Champ3TEST1' );
		});
	});
	
	describe("disableElementsOnValues()", function() {
		it("Désactive un element avec une seule condition", function() {
			disableElementsOnValues(
				'Model1.champ1.day',
				{element: 'Model1Champ4', value: 'on'}
			);
			expect($('Model1Champ1Day').readAttribute('disabled') !== null).toEqual( true );
			
			disableElementsOnValues(
				'Model1.champ1.day',
				{element: 'Model1Champ4', value: 'on', operator: '!='}
			);
			expect($('Model1Champ1Day').readAttribute('disabled') !== null).toEqual( false );
		});
		
		it("Désactive un lot d'elements avec une seule condition", function() {
			disableElementsOnValues(
				[
					'Model1.champ1.day',
					'Model1.champ1.month',
					'Model1.champ1.year'
				],
				{element: 'Model1Champ4', value: 'on'}
			);
			expect($('Model1Champ1Month').readAttribute('disabled') !== null).toEqual( true );
			
			disableElementsOnValues(
				[
					'Model1.champ1.day',
					'Model1.champ1.month',
					'Model1.champ1.year'
				],
				{element: 'Model1Champ4', value: 'on', operator: '!='}
			);
			expect($('Model1Champ1Month').readAttribute('disabled') !== null).toEqual( false );
		});
		
		it("Désactive un element avec plusieurs conditions (toutes vrais)", function() {
			disableElementsOnValues(
				'Model1.champ1.day',
				[
					{element: 'SearchModel1Champ3TEST1', value: null},
					{element: 'SearchModel1Champ3TEST2', value: null},
					{element: 'SearchModel1Champ3TEST3', value: null}
				]
			);
			expect($('Model1Champ1Day').readAttribute('disabled') !== null).toEqual( true );
		});
		
		it("Désactive un lot d'elements avec plusieurs conditions (toutes vrais)", function() {
			disableElementsOnValues(
				[
					'Model1.champ1.day',
					'Model1.champ1.month',
					'Model1.champ1.year'
				],
				[
					{element: 'SearchModel1Champ3TEST1', value: null},
					{element: 'SearchModel1Champ3TEST2', value: null},
					{element: 'SearchModel1Champ3TEST3', value: null}
				]
			);
			expect($('Model1Champ1Month').readAttribute('disabled') !== null).toEqual( true );
		});
		
		it("Désactive un element avec plusieurs conditions (une seule vrai)", function() {
			disableElementsOnValues(
				'Model1.champ1.day',
				[
					{element: 'SearchModel1Champ3TEST1', value: null}, // Condition vrai
					{element: 'Model1Champ4', value: 'on', operator: '!='} // Condition fausse
				]
			);
			expect($('Model1Champ1Day').readAttribute('disabled') !== null).toEqual( false );
			
			disableElementsOnValues(
				'Model1.champ1.day',
				[
					{element: 'SearchModel1Champ3TEST1', value: null}, // Condition vrai
					{element: 'Model1Champ4', value: 'on', operator: '!='} // Condition fausse
				],
				false, // hide - Inutile dans ce test
				true // Ne prend en compte qu'une seule condition vrai
			);
			expect($('Model1Champ1Day').readAttribute('disabled') !== null).toEqual( true );
		});
		
		it("Désactive un lot d'elements avec plusieurs conditions (une seule vrai)", function() {
			disableElementsOnValues(
				[
					'Model1.champ1.day',
					'Model1.champ1.month',
					'Model1.champ1.year'
				],
				[
					{element: 'SearchModel1Champ3TEST1', value: null}, // Condition vrai
					{element: 'Model1Champ4', value: 'on', operator: '!='} // Condition fausse
				]
			);
			expect($('Model1Champ1Day').readAttribute('disabled') !== null).toEqual( false );
			
			disableElementsOnValues(
				[
					'Model1.champ1.day',
					'Model1.champ1.month',
					'Model1.champ1.year'
				],
				[
					{element: 'SearchModel1Champ3TEST1', value: null}, // Condition vrai
					{element: 'Model1Champ4', value: 'on', operator: '!='} // Condition fausse
				],
				false, // hide - Inutile dans ce test
				true // Ne prend en compte qu'une seule condition vrai
			);
			expect($('Model1Champ1Day').readAttribute('disabled') !== null).toEqual( true );
		});
		
		it("Désactive un element si une valeur est supérieur à la valeur renseignée", function() {
			disableElementsOnValues(
				'Model1.champ1.day',
				{element: 'Model1Champ2', value: '10', operator: '>'}
			);
			expect($('Model1Champ1Day').readAttribute('disabled') !== null).toEqual( false );
			
			$('Model1Champ2').setValue('100');
			
			disableElementsOnValues(
				'Model1.champ1.day',
				{element: 'Model1Champ2', value: '10', operator: '>'}
			);
			expect($('Model1Champ1Day').readAttribute('disabled') !== null).toEqual( true );
		});
	});
});