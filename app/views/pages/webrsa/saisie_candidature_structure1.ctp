<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Candidature';?>

<?php
  $projets = array(
    '',
    'Associations accompagnement jeunes 18-25 ans en insertion',
    'Associations insertion professionnelle',
    'Associations insertion sociale',
    'Missions Locales / Espaces Dynamiques d\'Insertion',
    'Organismes de formation',
    'SIAE',
    'Collectivités'
  );
?>


<div class="">
    <h1>Saisie d'une candidature - Structure</h1><br>

        <form method="post" action="saisie_candidature_structure2"> 
                <p>
<!--                     <strong>Département de Seine Saint Denis</strong><br /> -->
                    <strong>Programme Département d'Insertion – Insertion Sociale</strong><br />
                    <strong>Dossier de demande de subvention / d'appel à projet pour l'année 2010</strong><br />
                </p>
            <fieldset class="">

                <div>
                    <label class="aere" style="font-weight: bold;">Sur quelle demande de subvention et/ou quel appel à projets souhaitez-vous vous positionner ? (cases à cocher – plusieurs réponses possibles)<br /></label>
                    <div class="multiselect text" id="type_action_1">
                        <label><input type="checkbox" value="1" checked/>Associations accompagnement jeunes 18-25 ans en insertion</label>
                        <label><input type="checkbox" value="2" checked/>Associations insertion professionnelle</label>
                        <label><input type="checkbox" value="3" checked/>Associations insertion sociale</label>
                        <label><input type="checkbox" value="4" checked/>Missions Locales / Espaces Dynamiques d\'Insertion</label>
                        <label><input type="checkbox" value="5" />Organismes de formation</label>
                        <label><input type="checkbox" value="6" />SIAE</label>
                        <label><input type="checkbox" value="7" />Collectivités</label>
                    <div>
                </div>

            </fieldset>

            <fieldset>
                <div class="input date">
                    <label style="font-weight: bold;">Vous trouverez dans ce dossier</label>
                    <li> Des informations pratiques</li>
                    <li> Un dossier de candidature</li>
                    <li> Une attestation sur l'honneur</li>
                    <li> La liste des pièces à joindre au dossier</li>
                </div>
                <div class="input text">
                    <label style="font-weight: bold;">Cocher la case correspondant à votre situation : </label>
                    <input type="radio" value="1" name="radio_contact" id="radio1"/>Première demande / Première candidature
                    <input type="radio" value="2" name="radio_contact" id="radio1"/>Renouvellement
                </div>
                <div class="input text">
                    <label style="vertical-align: top; font-weight:bold;">Informations pratiques</label>
                        <p>Nous vous invitons à déposer votre dossier de candidature du <select><option>10</option></select>-<select><option>novembre</option></select>-<select><option>2010</option></select> au <select><option>20</option></select>-<select><option>novembre</option></select>-<select><option>2010</option></select>  au plus tard, soit en ligne, soit au bureau N°<input type="text" value="236" style="width:30px">de <select><option value="9">9h</option></select> à <select><option value="11">11h30</option></select>  et de <select><option value="9">13h</option></select>  à <select><option value="9">16h</option></select>  (+ adresse complète du CG)<br />
                        <li>Les pièces à joindre au dossier pourront être déposées au même bureau, du <select><option>19</option></select>-<select><option>novembre</option></select>-<select><option>2010</option></select> au <select><option>20</option></select>-<select><option>novembre</option></select>-<select><option>2010</option></select> au plus tard, mêmes horaires.</li></p>
                </div>

                <div class="input text">
                    <label style="vertical-align: top; font-weight:bold;">Prérequis</label>
                    <p>
                        <li>Vous devez disposer d'un numéro SIRET et d'un agrément DDTEFP (pour OdF + SIAE) <i>et d'un numéro de récépissé en préfecture </i> qui constituera un identifiant dans vos relations avec les services administratifs. Si vous n'en avez pas, il vous faut dès maintenant en faire la demande à la direction régionale de l'INSEE. Cette démarche est gratuite.</li>
                    </p>
                </div>
            </fieldset>




              <div class="submit"><input value="Suivant" type="submit"></div>    </form></div>

<div class="clearer"><hr></div>            </div>

        </div>

    </body></html>
