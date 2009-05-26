<?php $this->pageTitle = 'Dossier RSA '.$dossier['Dossier']['numdemrsa'];?>

<?php echo $this->element( 'dossier_menu', array( 'id' => $dossier['Dossier']['id'] ) );?>

<div class="with_treemenu">
    <h1>Dossier RSA <?php echo h( $dossier['Dossier']['numdemrsa'] );?></h1>

<div id="ficheDossier">
    <table>
        <tbody>
            <tr class="odd">
                <th>Numéro de dossier</th>
                <td><?php echo h( $dossier['Dossier']['numdemrsa'] );?></td>
            </tr>
            <tr class="even">
                <th>Date de demande</th>
                <td><?php echo h( date_short( $dossier['Dossier']['dtdemrsa'] ) );?></td>
            </tr>
            <tr class="odd">
                <th>État du dossier</th>
                <td><?php echo h( $dossier['Situationdossierrsa']['etatdosrsa'] );?></td>
            </tr>
        </tbody>
    </table>
    <table>
        <tbody>
            <tr class="odd">
                <th><?php __( 'nom' );?></th>
                <td><?php echo $dossier['Personne']['nom'];?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'prenom' );?></th>
                <td><?php echo $dossier['Personne']['prenom'];?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'adresse' );?></th>
                <td><?php echo $dossier['Adresse']['numvoie'].' '.$dossier['Adresse']['typevoie'].' '.$dossier['Adresse']['nomvoie'];?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'numero_poste' );?></th>
                <td><?php echo isset( $dossier['ModeContact']['numtel'] ) ? $dossier['ModeContact']['numtel'] : null;?></td>
            </tr>
        </tbody>
    </table>
    <h2>Orientation</h2>
    <table>
        <tbody>
            <tr class="odd">
                <th>Type d'orientation</th>
                <td><?php echo h( isset( $dossier['Typeorient']['lib_type_orient'] ) ? $dossier['Typeorient']['lib_type_orient'] : null );?></td>
            </tr>
            <tr class="even">
                <th>Type de structure</th>
                <td><?php echo h( isset( $dossier['Structurereferente']['lib_struc'] ) ? $dossier['Structurereferente']['lib_struc'] : null);?></td>
            </tr>
            <tr class="odd">
                <th>Date de l'orientation</th>
                <td><?php echo h(  date_short( isset( $dossier['Personne']['Orientstruct']['date_valid'] ) ) ? date_short( $dossier['Personne']['Orientstruct']['date_valid'] ) : null );?></td>
            </tr>
        </tbody>
    </table>
    <h2>Contrat d'insertion</h2>
    <table>
        <tbody>
            <tr class="odd">
                <th>Type de contrat</th>
                <td><?php echo h( isset( $type_ci[$dossier['Personne']['Contratinsertion']['type_ci']] ) ? $type_ci[$dossier['Personne']['Contratinsertion']['type_ci']] : null );?></td>
            </tr>
            <tr class="even">
                <th>Date de début</th>
                <td><?php echo h( date_short( isset( $dossier['Personne']['Contratinsertion']['dd_ci'] ) ) ? date_short( $dossier['Personne']['Contratinsertion']['dd_ci'] ) : null );?></td>
            </tr>
            <tr class="odd">
                <th>Date de fin</th>
                <td><?php echo h( date_short( isset( $dossier['Personne']['Contratinsertion']['df_ci'] ) ) ? date_short( $dossier['Personne']['Contratinsertion']['df_ci'] ) : null );?></td>
            </tr>
            <tr class="even">
                <th>Date de décision</th>
                <td><?php echo h( date_short( isset( $dossier['Personne']['Contratinsertion']['datevalidation_ci'] ) ) ? date_short( $dossier['Personne']['Contratinsertion']['datevalidation_ci'] ) : null );?></td>
            </tr>
            <tr class="odd">
                <th>Décision</th>
                <td><?php echo h( $decision_ci[$dossier['Personne']['Contratinsertion']['decision_ci']].' '.date_short( $dossier['Personne']['Contratinsertion']['datevalidation_ci'] ) ) ;?></td>
            </tr>
        </tbody>
    </table>
   <h2>Informations CAF</h2>
    <table>
        <tbody>

            <tr class="even">
                <th>Date de demande RSA</th>
                <td><?php echo h( date_short( isset( $dossier['Dossier']['dtdemrsa'] ) ? $dossier['Dossier']['dtdemrsa'] : null ) );?></td>
            </tr>
            <tr class="odd">
                <th>Numéro de demande RSA</th>
                <td><?php echo h( isset( $dossier['Dossier']['numdemrsa'] ) ? $dossier['Dossier']['numdemrsa'] : null );?></td>
            </tr>
            <tr class="even">
                <th>DSP</th>
                <td><?php echo h( isset( $dossier['Dossier'] ) ? 'Oui' : 'Non');?></td>
            </tr>
            <tr class="odd">
                <th>Montant RSA</th>
                <td><?php echo h( isset( $dossier['Detaildroitrsa']['Detailcalculdroitrsa']['mtrsavers'] ) ? $dossier['Detaildroitrsa']['Detailcalculdroitrsa']['mtrsavers'] : null );?></td>
            </tr>
            <tr class="even">
                <th>Date dernier montant</th>
                <td><?php echo h( date_short( isset( $dossier['Detaildroitrsa']['Detailcalculdroitrsa']['dtderrsavers'] ) ) ? date_short( $dossier['Detaildroitrsa']['Detailcalculdroitrsa']['dtderrsavers'] ) : null);?></td>
            </tr>
            <tr class="odd">
                <th>Motif</th>
                <td><?php echo h( isset( $natpf[$dossier['Detaildroitrsa']['Detailcalculdroitrsa']['natpf']] ) ? $natpf[$dossier['Detaildroitrsa']['Detailcalculdroitrsa']['natpf']] : null );?></td>
            </tr>
            <tr class="even">
                <th>Soumis à droit et devoir</th>
                <td><?php echo h( $dossier['Personne']['toppersdrodevorsa'] ? 'Oui' : 'Non' );?></td>
            </tr>
            <tr class="odd">
                <th>Montant INDUS</th>
                <td><?php echo h( isset( $dossier['Infofinanciere']['mtmoucompta'] ) ? $dossier['Infofinanciere']['mtmoucompta'] : null  );?></td>
            </tr>
            <tr class="even">
                <th>Motif</th>
                <td><?php echo h( isset( $dossier['Creance']['motiindu'] ) ? $dossier['Creance']['motiindu'] : null );?></td>
            </tr>
        </tbody>
    </table>
</div>
</div>
<div class="clearer"><hr /></div>
