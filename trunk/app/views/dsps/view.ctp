<?php
	// CSS
	echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );

	// Titre
	$this->pageTitle = sprintf(
		__( 'Données socio-professionnelles de %s', true ),
		Set::extract( $dsp, 'Personne.qual' ).' '.Set::extract( $dsp, 'Personne.nom' ).' '.Set::extract( $dsp, 'Personne.prenom' )
	);
	echo $html->tag( 'h1', $this->pageTitle );

	// Formulaire
	echo $xform->create( null );

	$tmp = '';
	$id = Set::extract( $this->data, 'Dsp.id' );
	if( !empty( $id ) ) {
		$tmp .= $xform->input( 'Dsp.id', array( 'type' => 'hidden' ) );
	}
	$tmp .= $xform->input( 'Dsp.personne_id', array( 'type' => 'hidden', 'value' => 1 ) ); // FIXME
	echo $html->tag( 'div', $tmp );

	asort( $options['sitpersdemrsa'] );

	echo $html->tag( 'h2', 'Généralités' );
	echo $xform->enum( 'Dsp.sitpersdemrsa', array( 'options' => $options['sitpersdemrsa'] ) );
	echo $xform->enum( 'Dsp.topisogroouenf', array( 'options' => $options['topisogroouenf'] ) );
	echo $xform->enum( 'Dsp.topdrorsarmiant', array( 'options' => $options['topdrorsarmiant'] ) );
	echo $xform->enum( 'Dsp.drorsarmianta2', array( 'options' => $options['drorsarmianta2'] ) );

	echo $html->tag( 'h2', 'Situation sociale' );
	echo $html->tag( 'h3', 'Généralités' );
	echo $xform->enum( 'Dsp.accosocfam', array( 'options' => $options['accosocfam'] ) );
	echo $xform->input( 'Dsp.libcooraccosocfam', array( 'domain' => 'dsp', 'type' => 'textarea' ) );
	echo $xform->enum( 'Dsp.accosocindi', array( 'options' => $options['accosocindi'] ) );
	echo $xform->input( 'Dsp.libcooraccosocindi', array( 'domain' => 'dsp', 'type' => 'textarea' ) );
	echo $xform->enum( 'Dsp.soutdemarsoc', array( 'options' => $options['soutdemarsoc'] ) );

// 	echo $html->tag( 'h3', 'Difficultés' );

// 	echo $html->tag( 'h3', 'Détails accompagnement social familial' );

// 	echo $html->tag( 'h3', 'Détails accompagnement social individuel' );

// 	echo $html->tag( 'h3', 'Difficultés de disponibilité' );

	echo $html->tag( 'h2', 'Niveau d\'étude' );
	echo $xform->enum( 'Dsp.nivetu', array( 'options' => $options['nivetu'] ) );
	echo $xform->enum( 'Dsp.nivdipmaxobt', array( 'options' => $options['nivdipmaxobt'] ) );
	echo $xform->input( 'Dsp.annobtnivdipmax', array( 'domain' => 'dsp', 'type' => 'select', 'options' => array_range( date( 'Y' ), 1900 ), 'empty' => '' ) );
	echo $xform->enum( 'Dsp.topqualipro', array( 'options' => $options['topqualipro'] ) );
	echo $xform->input( 'Dsp.libautrqualipro', array( 'domain' => 'dsp' ) );
	echo $xform->enum( 'Dsp.topcompeextrapro', array( 'options' => $options['topcompeextrapro'] ) );
	echo $xform->input( 'Dsp.libcompeextrapro', array( 'domain' => 'dsp' ) );

	echo $html->tag( 'h2', 'Disponibilités emploi' );
	echo $xform->enum( 'Dsp.topengdemarechemploi', array( 'options' => $options['topengdemarechemploi'] ) );

	echo $html->tag( 'h2', 'Situation professionnelle' );
	echo $xform->enum( 'Dsp.hispro', array( 'options' => $options['hispro'] ) );
	echo $xform->input( 'Dsp.libderact', array( 'domain' => 'dsp' ) );
	echo $xform->input( 'Dsp.libsecactderact', array( 'domain' => 'dsp' ) );
	echo $xform->enum( 'Dsp.cessderact', array( 'options' => $options['cessderact'] ) );
	echo $xform->enum( 'Dsp.topdomideract', array( 'options' => $options['topdomideract'] ) );
	echo $xform->input( 'Dsp.libactdomi', array( 'domain' => 'dsp' ) );
	echo $xform->input( 'Dsp.libsecactdomi', array( 'domain' => 'dsp' ) );
	echo $xform->enum( 'Dsp.duractdomi', array( 'options' => $options['duractdomi'] ) );
	echo $xform->enum( 'Dsp.inscdememploi', array( 'options' => $options['inscdememploi'] ) );
	echo $xform->enum( 'Dsp.topisogrorechemploi', array( 'options' => $options['topisogrorechemploi'] ) );
	echo $xform->enum( 'Dsp.accoemploi', array( 'options' => $options['accoemploi'] ) );
	echo $xform->input( 'Dsp.libcooraccoemploi', array( 'domain' => 'dsp', 'type' => 'textarea' ) );
	echo $xform->enum( 'Dsp.topprojpro', array( 'options' => $options['topprojpro'] ) );
	echo $xform->input( 'Dsp.libemploirech', array( 'domain' => 'dsp' ) );
	echo $xform->input( 'Dsp.libsecactrech', array( 'domain' => 'dsp' ) );
	echo $xform->enum( 'Dsp.topcreareprientre', array( 'options' => $options['topcreareprientre'] ) );
	echo $xform->enum( 'Dsp.concoformqualiemploi', array( 'options' => $options['concoformqualiemploi'] ) );

	echo $html->tag( 'h2', 'Mobilité' );
	echo $xform->enum( 'Dsp.topmoyloco', array( 'options' => $options['topmoyloco'] ) );
	echo $xform->enum( 'Dsp.toppermicondub', array( 'options' => $options['toppermicondub'] ) );
	echo $xform->enum( 'Dsp.topautrpermicondu', array( 'options' => $options['topautrpermicondu'] ) );
	echo $xform->input( 'Dsp.libautrpermicondu', array( 'domain' => 'dsp', 'type' => 'textarea' ) );

	echo $html->tag( 'h2', 'Difficultés logement' );
	echo $xform->enum( 'Dsp.natlog', array( 'options' => $options['natlog'] ) );
	echo $xform->enum( 'Dsp.demarlog', array( 'options' => $options['demarlog'] ) );

	echo $xform->submit( 'Form::Save' );
	echo $xform->end();

	debug( $this->data );
?>