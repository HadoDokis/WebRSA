
describe("Formulaire", function() {
	it("getModelName( name )", function() {
		expect(getModelName('data[model1][field1]')).toEqual( 'model1' );
		expect(getModelName('data[ma_date][monchamp][day]')).toEqual( 'ma_date' );
	});
	
	it("getFieldName( name )", function() {
		expect(getFieldName('data[model1][field1]')).toEqual( 'field1' );
		expect(getFieldName('data[ma_date][monchamp][day]')).toEqual( 'monchamp' );
	});
	
	it("getThirdParam( name )", function() {
		expect(getThirdParam('data[model1][field1]')).toEqual( null );
		expect(getThirdParam('data[ma_date][monchamp][day]')).toEqual( 'day' );
	});
	
	it("showHeaderError()", function() {
		var headerError = $$('#incrustation_erreur>p.error');
		expect( headerError.length <= 0 ).toEqual( true );
		showHeaderError();
		headerError = $$('#incrustation_erreur>p.error');
		expect( headerError.length <= 0 ).toEqual( false );
	});

	it("getDate( name )", function() {
		expect( getDate( 'data[model3][champ1][day]' ) ).toEqual( '11-11-2015' );
		expect( getDate( 'data[model3][champ2][month]' ) ).toEqual( '31-02-2015' );
	});

	it("getValue( editable )", function() {
		// Champ date
		expect( getValue( $('model3champ1day') ) ).toEqual( '11-11-2015' );
		$('model3champ1month').value = '12';
		expect( getValue( $('model3champ1day') ) ).toEqual( '11-12-2015' );
		expect( getValue( $('model3champ2month') ) ).toEqual( '31-02-2015' );
		
		// Champ text
		expect( getValue( $('model1champ1') ) ).toEqual( '' );
		$('model1champ1').value = 'toto';
		expect( getValue( $('model1champ1') ) ).toEqual( 'toto' );
		
		// Bouton Radio
		expect( getValue( $('model3champ3__1') ) ).toEqual( 'toto' );
		expect( getValue( $('model3champ3__2') ) ).toEqual( 'toto' );
		$('model3champ3__2').checked = true;
		expect( getValue( $('model3champ3__3') ) ).toEqual( 'tata' );
		
		// Select
		expect( getValue( $('model3champ4') ) ).toEqual( '' );
		$('model3champ4').value = 'toto';
		expect( getValue( $('model3champ4') ) ).toEqual( 'toto' );
	});
	
	it("validate( editable, onchange=undefined )", function() {
		expect( validate( $('model3champ1day') ) ).toEqual( true );
		expect( validate( $('model3champ2day') ) ).toEqual( false );
		expect( validate( $('model3champ3__3') ) ).toEqual( true );
		expect( validate( $('model1champ1') ) ).toEqual( false );
		$('model1champ1').value = '1';
		expect( validate( $('model1champ1') ) ).toEqual( true );
		expect( validate( null ) ).toEqual( true );
	});
	
	it("suffix( value, separator='_' )", function() {
		expect( suffix( '11_4' ) ).toEqual( '4' );
		expect( suffix( '11+4', '+' ) ).toEqual( '4' );
		expect( suffix( '12+-+5', '+-+' ) ).toEqual( '5' );
	});
});
