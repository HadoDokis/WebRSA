<?php
    class ApreversementHelper extends AppHelper
    {
        var $helpers = array( 'Xform', 'Html', 'Locale' );
        var $validate = array(
            'montantattribue' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
                array(
                    'rule' => 'numeric',
                    'message' => 'Valeur numérique seulement'
                ),
            )
        );
        /**
        *
        */

        function cells( $i, $apre, $nbpaiementsouhait ) {
            $apre_id = Set::classicExtract( $apre, 'Apre.id' );
            $personne_id = Set::classicExtract( $apre, 'Apre.personne_id' );
            $apreetatliquidatif_id = Set::classicExtract( $apre, 'ApreEtatliquidatif.id' );
            $etatliquidatif_id = $this->params['pass'][0];



            $montanttotal = Set::classicExtract( $apre, 'Apre.montantaverser' );

            $montantattribue = Set::classicExtract( $apre, 'ApreEtatliquidatif.montantattribue' );
            $montantdejaverse = Set::classicExtract( $apre, 'Apre.montantdejaverse' );
            $nbpaiementeff = Set::classicExtract( $apre, 'Apre.nbpaiementeff' );
            $nbcourantpaiement = Set::classicExtract( $apre, 'Apre.nbpaiementsouhait' );


            if( $nbpaiementeff > 0 ) {
                $montanttotal =  $montanttotal - $montantattribue;
                $nbpaiementsouhait = array( $nbcourantpaiement - $nbpaiementeff );
                $montantattribue = $montanttotal - $montantdejaverse;
                $montanttotal = Set::classicExtract( $apre, 'Apre.montantaverser' );
            }

            $cells = array(
                $this->Html->tag( 'td', Set::classicExtract( $apre, 'Dossier.numdemrsa' ) ),
                $this->Html->tag( 'td', Set::classicExtract( $apre, 'Apre.numeroapre' ) ),
                $this->Html->tag( 'td', $this->Locale->date( 'Date::short', Set::classicExtract( $apre, 'Apre.datedemandeapre' ) ) ),
                $this->Html->tag( 'td', Set::classicExtract( $apre, 'Personne.nom' ) ),
                $this->Html->tag( 'td', Set::classicExtract( $apre, 'Personne.prenom' ) ),
                $this->Html->tag( 'td', Set::classicExtract( $apre, 'Adresse.locaadr' ) ),
                $this->Html->tag( 'td', $this->Locale->money( $montanttotal ), array( 'class' => 'number' ) ),
               $this->Html->tag( 'td', $this->Xform->input( "Apre.{$i}.id", array( 'type' => 'hidden', 'value' => $apre_id ) ).$this->Xform->input( "Apre.{$i}.personne_id", array( 'type' => 'hidden', 'value' => $personne_id ) ).$this->Xform->input( "Apre.{$i}.nbpaiementsouhait", array( 'label' => false, 'type' => 'select', 'options' => $nbpaiementsouhait, 'empty' => true, 'disabled' => ( $nbpaiementeff > 0 ) ) ) ),

                $this->Html->tag( 'td', $this->Locale->number( ( !is_null( $nbpaiementeff ) ? $nbpaiementeff : 0 ) ), array( 'class' => 'number' ) ),

                $this->Html->tag( 'td', $this->Xform->input( "ApreEtatliquidatif.{$i}.id", array( 'type' => 'hidden', 'value' => $apreetatliquidatif_id ) ).
                $this->Xform->input( "ApreEtatliquidatif.{$i}.etatliquidatif_id", array( 'type' => 'hidden', 'value' => $etatliquidatif_id ) ).
                $this->Xform->input( "ApreEtatliquidatif.{$i}.apre_id", array( 'type' => 'hidden', 'value' => $apre_id ) ).
                $this->Xform->input( "ApreEtatliquidatif.{$i}.montantaverser", array( 'type' => 'hidden', 'value' => $montanttotal ) ).
                $this->Xform->input( "ApreEtatliquidatif.{$i}.montantattribue", array( /*'div' => false, */'type' => 'text', 'label' => false, 'value' => str_replace( '.', ',', $montantattribue ) ) ) ),

                 $this->Html->tag( 'td', $this->Locale->money( $montantdejaverse ), array( 'class' => 'number' ) )
//               $this->Html->tag( 'td', $this->Xform->input( "Apre.{$i}.montantdejaverse", array( 'type' => 'hidden', 'value' => str_replace( '.', ',', $montantdejaverse ), 'class' => 'number', 'label' => false ) ).$this->Locale->money( $montantdejaverse ) )
            );

            /*$cells = array(
                $this->Html->tag( 'td', Set::classicExtract( $apre, 'Dossier.numdemrsa' ) ),
                $this->Html->tag( 'td', Set::classicExtract( $apre, 'Apre.numeroapre' ) ),
                $this->Html->tag( 'td', $this->Locale->date( 'Date::short', Set::classicExtract( $apre, 'Apre.datedemandeapre' ) ) ),
                $this->Html->tag( 'td', Set::classicExtract( $apre, 'Personne.nom' ) ),
                $this->Html->tag( 'td', Set::classicExtract( $apre, 'Personne.prenom' ) ),
                $this->Html->tag( 'td', Set::classicExtract( $apre, 'Adresse.locaadr' ) ),
                $this->Html->tag( 'td', $this->Locale->money( $montanttotal ), array( 'class' => 'number' ) ),
                $this->Html->tag( 'td', $this->Xform->input( "Apre.{$i}.id", array( 'type' => 'hidden', 'value' => $apre_id ) ).$this->Xform->input( "Apre.{$i}.personne_id", array( 'type' => 'hidden', 'value' => $personne_id ) ).$this->Xform->input( "Apre.{$i}.nbpaiementsouhait", array( 'label' => false, 'type' => 'select', 'options' => $nbpaiementsouhait, 'empty' => true, 'disabled' => ( $nbpaiementeff > 0 ) ) ) ),

                $this->Html->tag( 'td', $this->Locale->number( ( !is_null( $nbpaiementeff ) ? $nbpaiementeff : 0 ) ), array( 'class' => 'number' ) ),

                $this->Html->tag( 'td', $this->Xform->input( "ApreEtatliquidatif.{$i}.id", array( 'type' => 'hidden', 'value' => $apreetatliquidatif_id ) ).
                $this->Xform->input( "ApreEtatliquidatif.{$i}.etatliquidatif_id", array( 'type' => 'hidden', 'value' => $etatliquidatif_id ) ).
                $this->Xform->input( "ApreEtatliquidatif.{$i}.apre_id", array( 'type' => 'hidden', 'value' => $apre_id ) ).
                $this->Xform->input( "ApreEtatliquidatif.{$i}.montanttotal", array( 'type' => 'hidden', 'value' => $montanttotal ) ).
                $this->Xform->input( "ApreEtatliquidatif.{$i}.montantattribue", array( 'type' => 'text', 'label' => false, 'value' => str_replace( '.', ',', $montantattribue ) ) ) ),

                $this->Html->tag( 'td', $this->Xform->input( "Apre.{$i}.montantdejaverse", array( 'type' => 'hidden', 'value' => str_replace( '.', ',', $montantdejaverse ), 'class' => 'number', 'label' => false ) ).$this->Locale->money( $montantdejaverse ) )
            );*/

            return implode( '', $cells );
        }
    }
?>