<h1>Etat de liquidation</h1>

<?php
    $etatliquidatif['Etatliquidatif']['tranche'] = ( ( $etatliquidatif['Etatliquidatif']['typeapre'] == 'forfaitaire' ) ? 'T01' : 'T02' );
    $etatliquidatif['Etatliquidatif']['objet'] = $etatliquidatif['Etatliquidatif']['lib_programme'].' '.date( 'm/Y', strtotime( $etatliquidatif['Etatliquidatif']['datecloture'] ) );
    $etatliquidatif['Etatliquidatif']['montanttotalapre'] = $locale->money( $etatliquidatif['Etatliquidatif']['montanttotalapre'] );
?>

<table class="etatliquidatif header">
	<tr>
		<th>Entité financière:</th><td><?php echo $etatliquidatif['Etatliquidatif']['entitefi']?></td>
		<th>Opération:</th><td><?php echo $etatliquidatif['Etatliquidatif']['operation']?></td>
	</tr>
	<tr>
		<th>Exercice budgétaire:</th><td><?php echo $etatliquidatif['Budgetapre']['exercicebudgetai']?></td>
		<th>Nature analytique:</th><td><?php echo $etatliquidatif['Etatliquidatif']['lib_natureanalytique']?></td>
	</tr>
	<tr>
		<th>Cdr:</th><td><?php echo $etatliquidatif['Etatliquidatif']['libellecdr']?></td>
		<th>Objet:</th><td><?php echo $etatliquidatif['Etatliquidatif']['objet']?></td>
	</tr>
</table>

<?php
	function paiementfoyerComplet( $paiementfoyer ) {
		$keys = array_keys( Set::filter( $paiementfoyer ) );
		$requiredKeys = array( 'titurib', 'nomprenomtiturib', 'etaban', 'guiban', 'numcomptban', 'clerib' );
		return ( count( $keys ) == count( $requiredKeys ) );
	}

	/// Vérification de données manquantes FIXME: déléguer dans le modèle ?
	$nbrAttentdu = count( Set::extract( $elements, '/Apre' ) );
	$nbrLibellesDomicialiation = count( Set::filter( Set::extract( $elements, '/Domiciliationbancaire/libelledomiciliation' ) ) );

	$nbrPaiementsFoyer = 0;
	foreach( Set::extract( $elements, '{n}.Paiementfoyer' ) as $paiementfoyer ) {
		$nbrPaiementsFoyer += ( paiementfoyerComplet( $paiementfoyer ) ? 1 : 0 );
	}

	$problems = array();
	if( $nbrLibellesDomicialiation != $nbrAttentdu ) {
		$nbrProblems = ( $nbrAttentdu - $nbrLibellesDomicialiation );
		$problems[] = sprintf( __n( '%s entrée sans libellé de domiciliation', '%s entrées sans libellé de domiciliation', $nbrProblems, true ), $nbrProblems );
	}
	if( $nbrPaiementsFoyer != $nbrAttentdu ) {
		$nbrProblems = ( $nbrAttentdu - $nbrPaiementsFoyer );
		$problems[] = sprintf( __n( '%s entrée dont les informations de paiement pour le foyer ne sont pas complètes', '%s entrées dont les informations de paiement pour le foyer ne sont pas complètes', $nbrProblems, true ), $nbrProblems );
	}
	if( !empty( $problems ) ) {
		echo '<ul><li>'.implode( '</li><li>', $problems ).'</li></ul>';
	}
?>

<table class="etatliquidatif apres">
	<thead>
		<tr>
			<th>Titre</th>
			<th>Nom Prénom</th>
			<th>Adresse</th>
			<th>C.P.</th>
			<th>Ville</th>
			<th>Banque</th>
			<th>Guichet</th>
			<th>Compte</th>
			<th>RIB</th>
			<th>Allocation</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach( $elements as $element ):?>
            <?php
                // Formattage élément
                $element['Adresse']['adresse'] = implode(
                    ' ',
                    array(
                        Set::classicExtract( $element, 'Adresse.numvoie' ),
                        mb_convert_case( Set::enum( Set::classicExtract( $element, 'Adresse.typevoie' ), $typevoie ), MB_CASE_UPPER, Configure::read( 'App.encoding' ) ),
                        Set::classicExtract( $element, 'Adresse.nomvoie' ),
                        Set::classicExtract( $element, 'Adresse.complideadr' ),
                        Set::classicExtract( $element, 'Adresse.compladr' ),
                    )
                );

                $element['Paiementfoyer']['clerib'] = str_pad( $element['Paiementfoyer']['clerib'], 2, '0', STR_PAD_LEFT );

				/// Vérification de données manquantes FIXME: déléguer dans le modèle ?
				$trClass = null;
				$libelledomiciliation = Set::filter( Set::classicExtract( $element, 'Domiciliationbancaire.libelledomiciliation' ) );
				if( empty( $libelledomiciliation ) || !paiementfoyerComplet( Set::extract( $element, 'Paiementfoyer' ) ) ) {
					$trClass = 'error';
				}
            ?>
			<tr<?php if( !empty( $trClass ) ) echo ' class="'.$trClass.'" style="color: red;"';?>>
				<td><?php echo $element['Paiementfoyer']['titurib'];?></td>
				<td><?php echo $element['Paiementfoyer']['nomprenomtiturib'];?></td>
				<td><?php echo $element['Adresse']['adresse'];?></td>
				<td><?php echo $element['Adresse']['codepos'];?></td>
				<td><?php echo $element['Adresse']['locaadr'];?></td>
				<td><?php echo $element['Paiementfoyer']['etaban'];?></td>
				<td><?php echo $element['Paiementfoyer']['guiban'];?></td>
				<td><?php echo $element['Paiementfoyer']['numcomptban'];?></td>
				<td><?php echo $element['Paiementfoyer']['clerib'];?></td>
				<td class="number"><?php echo str_replace( ' ', '&nbsp;', $locale->money( $element['Apre']['allocation'] ) );?></td>
			</tr>
		<?php endforeach;?>
		<tr>
			<th colspan="8">Total</th>
			<td class="number" colspan="2"><?php echo str_replace( ' ', '&nbsp;', $etatliquidatif['Etatliquidatif']['montanttotalapre'] );?></td>
		</tr>
	</tbody>
</table>
