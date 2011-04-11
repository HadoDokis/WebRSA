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

        <form method="post" action="suivi_candidats"> 
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

        <fieldset>
            <legend>Présentation de votre structure</legend>
            <label style="vertical-align: top; font-weight:bold;">Identification</label>
            <div class="input text">
                <label>N° SIRET</label>
                <input type="text">
            </div>
            <div class="input text">
                <label>Code APE</label>
                <input type="text">
            </div>
            <div class="input text">
                <label>Numéro RNA ou à défaut N° de récepissé en préfecture</label>
                <input type="text">
            </div>
            <div class="input text">
                <label>Nom/ Raison Sociale (nom complet, pas de sigle)</label>
                <input type="text">
            </div>
            <div class="input text">
                <label>Sigle (le cas échéant)</label>
                <input type="text">
            </div>
            <div class="input text">
                <label>Objet social et activités habituelles de la structure</label>
                <input type="text">
            </div>

            <label style="vertical-align: top; font-weight:bold;">Activités principales réalisées<br />  </label>
            <label style="vertical-align: top;">Adresse du siège social :</label>
            <div class="input text" style="  margin-left:2em;">
                <label style="vertical-align: top;">Code postal:</label>
                    <input type="text" maxlength="5" >
                <label style="vertical-align: top;">Commune:</label>
                    <input type="text" maxlength="5">
            </div>
            <label style="vertical-align: top;">Adresse de correspondance, si différente du siège :</label>
            <div class="input text" style="  margin-left:2em;">
                <label style="vertical-align: top;">Code postal:</label>
                    <input type="text" maxlength="5" >
                <label style="vertical-align: top;">Commune:</label>
                    <input type="text" maxlength="5">
            </div>
            <div class="input text">
                <label>Téléphone</label>
                <input type="text">
            </div>
            <div class="input text">
                <label>Télécopie</label>
                <input type="text">
            </div>
            <div class="input text">
                <label>Courriel</label>
                <input type="text">
            </div>
            <div class="input text">
                <label>Site internet</label>
                <input type="text">
            </div>
            <div class="input text aere">
                <label>Union, fédération ou réseau auquel est affiliée votre association (indiquer le nom complet, ne pas utiliser de sigle)</label>
                <input type="text ">
            </div>

            <label style="vertical-align: top; font-weight:bold;">Identification du représentant légal de la structure (président ou autre personne désignée par les statuts)<br />  </label>
            <div class="input date aere">
                <label>Civilité</label>
                <select id="civilite" name="civilite">
                    <option value=""></option>
                    <option value="MLE">Mademoiselle</option>
                    <option value="MME">Madame</option>
                    <option value="MR">Monsieur</option>
                </select>
            </div>
                <div class="input text">
                <label style="vertical-align: top;">Nom</label>
                    <input type="text" >
                </div>
                <div class="input text">
                <label style="vertical-align: top;">Prénom </label>
                    <input type="text" >
                </div>
                <div class="input text">
                <label style="vertical-align: top;">Fonction au sein de la structure</label>
                    <input type="text" maxlength="10">
                </div>
                <label style="vertical-align: top;">Adresse postale complète</label>
                <div class="input text" style="  margin-left:2em;">
                    <label style="vertical-align: top;">Code postal:</label>
                    <input type="text" maxlength="5" >
                    <label style="vertical-align: top;">Commune:</label>
                    <input type="text" maxlength="5">
                </div>
                <div class="input text">
                    <label style="vertical-align: top;">Téléphone</label>
                    <input type="text" maxlength="10">
                </div>
                <div class="input text">
                    <label style="vertical-align: top;">Télécopie</label>
                    <input type="text" maxlength="10">
                </div>
                <div class="input text">
                    <label style="vertical-align: top;">Courriel</label>
                    <input type="text" maxlength="10">
                </div>
                <div class="input text">
                    <label style="vertical-align: top;">Délégation de signature</label>
                    <select id="civilite" name="civilite">
                        <option value=""></option>
                        <option value="oui">Oui</option>
                        <option value="non">Non</option>
                    </select>
                </div>

                <label style="vertical-align: top; font-weight:bold;" class="aere">Identification de la personne chargée du présent dossier (si différente du représentant légal)<br />  </label>
                <div class="input date aere">
                    <label>Civilité</label>
                    <select id="civilite" name="civilite">
                        <option value=""></option>
                        <option value="MLE">Mademoiselle</option>
                        <option value="MME">Madame</option>
                        <option value="MR">Monsieur</option>
                    </select>
                </div>
                <div class="input text">
                <label style="vertical-align: top;">Nom</label>
                    <input type="text" >
                </div>
                <div class="input text">
                <label style="vertical-align: top;">Prénom </label>
                    <input type="text" >
                </div>
                <div class="input text">
                <label style="vertical-align: top;">Fonction au sein de la structure</label>
                    <input type="text" maxlength="10">
                </div>
                <label style="vertical-align: top;">Adresse postale complète</label>
                <div class="input text" style="  margin-left:2em;">
                    <label style="vertical-align: top;">Code postal:</label>
                    <input type="text" maxlength="5" >
                    <label style="vertical-align: top;">Commune:</label>
                    <input type="text" maxlength="5">
                </div>
                <div class="input text">
                    <label style="vertical-align: top;">Téléphone</label>
                    <input type="text" maxlength="10">
                </div>
                <div class="input text">
                    <label style="vertical-align: top;">Télécopie</label>
                    <input type="text" maxlength="10">
                </div>
                <div class="input text">
                    <label style="vertical-align: top;">Courriel</label>
                    <input type="text" maxlength="10">
                </div>
    </fieldset>


    <fieldset>
            <div class="input date">
                <label style="vertical-align: bottom;">Date de la dernière Assemblée Générale</label>
                <select>
                    <option value=""></option>
                    <option value="01">1</option>
                    <option value="02">2</option>
                    <option value="03">3</option>
                    <option value="04">4</option>
                    <option value="05">5</option>
                    <option value="06">6</option>
                    <option value="07">7</option>
                    <option value="08">8</option>
                    <option value="09">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21" selected="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                    <option value="29">29</option>
                    <option value="30">30</option>
                    <option value="31">31</option>
                </select>-
                <select>
                    <option value=""></option>
                    <option value="01">janvier</option>
                    <option value="02">février</option>
                    <option value="03">mars</option>
                    <option value="04">avril</option>
                    <option value="05">mai</option>
                    <option value="06">juin</option>
                    <option value="07">juillet</option>
                    <option value="08">août</option>
                    <option value="09">septembre</option>
                    <option value="10" selected="octobre">octobre</option>
                    <option value="11">novembre</option>
                    <option value="12">décembre</option>
                </select>-
                <select>
                    <option value=""></option>
                    <option value="2010" selected="2010">2010</option>
                    <option value="2009">2009</option>
                    <option value="2008">2008</option>
                    <option value="2007">2007</option>
                    <option value="2006">2006</option>
                    <option value="2005">2005</option>
                </select>
            </div>

            <div class="input date">
                <label style="vertical-align: bottom;">Date des derniers Conseils d'Administration</label>
                <select>
                    <option value=""></option>
                    <option value="01">1</option>
                    <option value="02">2</option>
                    <option value="03">3</option>
                    <option value="04">4</option>
                    <option value="05">5</option>
                    <option value="06">6</option>
                    <option value="07">7</option>
                    <option value="08">8</option>
                    <option value="09">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21" selected="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                    <option value="29">29</option>
                    <option value="30">30</option>
                    <option value="31">31</option>
                </select>-
                <select>
                    <option value=""></option>
                    <option value="01">janvier</option>
                    <option value="02">février</option>
                    <option value="03">mars</option>
                    <option value="04">avril</option>
                    <option value="05">mai</option>
                    <option value="06">juin</option>
                    <option value="07">juillet</option>
                    <option value="08">août</option>
                    <option value="09">septembre</option>
                    <option value="10" selected="octobre">octobre</option>
                    <option value="11">novembre</option>
                    <option value="12">décembre</option>
                </select>-
                <select>
                    <option value=""></option>
                    <option value="2010" selected="2010">2010</option>
                    <option value="2009">2009</option>
                    <option value="2008">2008</option>
                    <option value="2007">2007</option>
                    <option value="2006">2006</option>
                    <option value="2005">2005</option>
                </select>
            </div>

            <div class="input text">
                <label style="vertical-align: top;">Identités et adresse des structures du secteur marchand avec lesquelles votre structure est liée</label>
                    <textarea cols="70" rows="3" type="textarea" maxlength="250"></textarea>
            </div>

      </fieldset>



              <div class="submit"><input value="Candidater" type="submit"></div>    </form></div>

<div class="clearer"><hr></div>            </div>

        </div>

    </body></html>
