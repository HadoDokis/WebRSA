<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php $this->pageTitle = 'Liste des structures candidates';?>

<div class="">
    <h1>Liste des structures candidates</h1>
         <table class="tooltips_oupas">
            <thead>
                <tr>
                    <th>Numéro de projet</th>
                    <th>Type de structure</th>
                    <th>Type d'activité</th>
                    <th>Date début de candidature</th>
                    <th>Traité</th>
                    <th colspan="2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr class="odd dynamic" id="innerTableTrigger0">
                    <td>0001</td>
                    <td>SIAE</td>
                    <td>Régie quartier</td>
                    <td>01/06/2009</td>
                    <td>Oui</td>
                    <td><a href="../webrsa/saisie_candidature_indiv" title=""><img src="suivi_stagiaires_fichiers/disk.png"> Analyse candidature</a></td>
                </tr>
                <tr class="even dynamic" id="innerTableTrigger0">
                    <td>0002</td>
                    <td>Organisme de formations</td>
                    <td>Restauration</td>
                    <td>01/06/2009</td>
                    <td>Non</td>
                    <td><a href="../webrsa/saisie_candidature_indiv" title=""><img src="suivi_stagiaires_fichiers/disk.png"> Analyse candidature</a></td>
                </tr>
                <tr class="odd dynamic" id="innerTableTrigger0">
                    <td>0003</td>
                    <td>Organisme de formations</td>
                    <td>Bâtiment</td>
                    <td>01/06/2009</td>
                    <td>Non</td>
                    <td><a href="../webrsa/saisie_candidature_indiv" title=""><img src="suivi_stagiaires_fichiers/disk.png"> Analyse candidature</a></td>
                </tr>
                <tr class="even dynamic" id="innerTableTrigger0">
                    <td>0004</td>
                    <td>Association : insertion sociale</td>
                    <td>Alpha</td>
                    <td>01/06/2009</td>
                    <td>Non</td>
                    <td><a href="../webrsa/saisie_candidature_indiv" title=""><img src="suivi_stagiaires_fichiers/disk.png"> Analyse candidature</a></td>
                </tr>

            </tbody>
        </table>
    </div><br>
<input value="Imprimer cette page" onclick="printit();" type="button">    </div>
  <div class="clearer"><hr></div>            </div>

   </body></html>