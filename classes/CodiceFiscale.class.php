<?php

  require_once('./Persona.class.php');
  require_once('../interfaces/CityCodeAssociator.interface.php');
  require_once('./FileAssociator.class.php');

  class CodiceFiscale {

    private  $VOCALI; // array  di vocali
    private  $CONSONANTI; // array di CONSONANTI
    private  $SPECIALI;

    const GIORNI_AGGIUNTIVI_DONNE = 40; // numero da aggiungere al giorno di nascita delle donne

    /**
     * Possessore del codice fiscale
     *
     * @var Persona
     */
    private $persona;

    /**
     * Utility per conversione città-codice
     *
     * @var CityCodeAssociator
     */

    private $cityconverter;

    /**
     * Costruttore
     *
     * @param Persona Proprietario del codice fiscale
     * @param CityCodeAssociator Utility di conversione citta-codice
     *
     */
    public function __construct (Persona $p, CityCodeAssociator $conv) {

      $this->setCityConverter($conv);
      $this->setPersona($p);

      $this->VOCALI = str_split( 'aeiou'); // array  di vocali
      $this->CONSONANTI = str_split( 'qwrtypsdfghjklzxcvbnm'); // array di CONSONANTI
       $this->SPECIALI = str_split(  '\'\\",.-_:;+ùàòèé[]' );
    }


    /**
     * Get the value of Possessore del codice fiscale
     *
     * @return Persona
     */
    public function getPersona() {
        return $this->persona;
    }

    /**
     * Set the value of Possessore del codice fiscale
     *
     * @param Persona persona
     *
     * @return self
     */
    public function setPersona(Persona $persona) {
        $this->persona = $persona;

        return $this;
    }

    /**
     * Get the value of Utility per conversione città-codice
     *
     * @return CityCodeAssociator
     */
    public function getCityconverter(){
        return $this->cityconverter;
    }

    /**
     * Set the value of Utility per conversione città-codice
     *
     * @param CityCodeAssociator cityconverter
     *
     * @return self
     */
    public function setCityconverter(CityCodeAssociator $cityconverter) {
        $this->cityconverter = $cityconverter;

        return $this;
    }

    /**
     * Fornisce il codice fiscale della persona
     *
     * @return string Codice fiscale
     */
    public function getCode() {

      $res = $this->convertNS( array(0, 1, 2), $this->persona->getSurname());
      $res .= $this->convertNS(array(0, 2, 3), $this->persona->getNome() );

      list($codAnno, $codMese, $codGiorno) = $this->convertBirthDate();
      $res .= $codAnno;
      $res .= $codMese;
      $res .= $codGiorno;

      $res .= $this->convertCity();


      return strtoupper($res);

    }


    /**
     * Elimina i caratteri specificati
     *
     * @return string
     */
    private function strRemove($str, array $CONST) {

      return  (string) str_replace($CONST, "", $str);

    }

    /**
     * Genera codice del nome/cognome
     *
    * @param array Specifica posizione dei caratteri da selezionare
    * @param string Stringa sorgente
     * @return string
     */
    private function convertNS (array $pos, $str) {

      // controlla la correttezza di pos

      $tmp = $this->strRemove($str, $this->SPECIALI); // togli caratteri speciali e spazi

      $cons = $this->strRemove($tmp,  $this->VOCALI); // togli le vocali

      if (strlen($cons) == 3) { // se ci sono solo 3 vocali prendile tutte in ordine

        $res = $cons;

      }

      elseif (strlen($cons) > 3 ) {

        $res = $cons[$pos[0]] . $cons[$pos[1]] . $cons[$pos[2]]; // seleziona le consonanti secondo i parametri

      }

      else {

        $res = $cons; // aggiungi le consonanti in testa (stringa vuota se non presenti)

        $voc = $this->strRemove($tmp,  $this->CONSONANTI); // togli le CONSONANTI

        $notfullfil = (strlen($voc) === 0) ?  FALSE : TRUE; // se non ci sono vocali riempirà con X

        for ($i=0; strlen($res) < 3; $i++) {

          if ($notfullfil === TRUE) {

            $res .= $voc[$i]; // accoda la vocale

            if (!isset($voc[$i])) { // se ho superato l'ultima vocale

              $notfullfil = FALSE;

            }

          }
          else {

            $res .= "x"; // aggiungi una x alla fine

          }

        }
      }

      return $res;
    }

    /**
     * Genera codice della citta di nascita

     * @return string Codice corrispondente
     */
    private function convertCity() {

      return $this->cityconverter->getByKey($this->persona->getCittaNascita());


    }


    /**
     * Genera codice della data di nascita

     * @return array Codice corrispondente anno - mese - giorno
     */
    private function convertBirthDate() {

      $date = $this->getPersona()->getDataNascita(); // data di nascita

      // codifica anno
      $anno_nascita = $date->getYear(); // stringa anno di nascita
      $cdAnno = $anno_nascita[2] . $anno_nascita[3]; // codifica l'anno

      //codifica giorno
      $cdGiorno = $date->getDay(); // codice giorno a 2 cifre

      if ($this->persona->getSesso() === TRUE ) { // se è una donna
        $cdGiorno += self::GIORNI_AGGIUNTIVI_DONNE;
        $cdGiorno = (string) $cdGiorno; // torna a stringa dopo il casting automatico
      }

      // codifica mese
      $mese_nascita = $date->getMonth(); // mese di nascita

      $alf_codificato = 'abcdehlmprst'; // codificato per la traduzione del mese
      $cdMese = $alf_codificato[ ((integer) $mese_nascita) - 1]; // codifica il mese



      return array($cdAnno, $cdMese, $cdGiorno);


    }

    /**
     * Genera carattere di controllo

     * @return string Carattere
     */
    private function generateLastChar() {



    }
}

$nascita = new Date("1994-3-15");

$citta = new Citta('Ortona', 'CH');

$gioa = array ('nome' => 'gioacchino', 'cognome' => 'castorio', 'data_nascita' => $nascita, 'citta_nascita' => $citta, 'sesso' => FALSE);

$f = new FileAssociator('../file/codici_comuni_italiani.txt');

$gio = new Persona($gioa);
$cod = new CodiceFiscale($gio, $f);

echo $cod->getCode();

// echo "\n" . $citta->getNome() . " " . $citta->getProvincia() ."\n";
