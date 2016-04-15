<?php

  require_once('./Persona.class.php');
  require_once('../interfaces/CityCodeAssociator.interface.php');
  require_once('./FileAssociator.class.php');

  class CodiceFiscale {

    private $VOCALI; // array  di vocali
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
       $this->SPECIALI = str_split(  '\'\\",.-_:;+ùàòèé[] ' );
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

      $res = $this->convertNS( array(0, 1, 2), strtolower($this->persona->getCognome()));
      $res .= $this->convertNS(array(0, 2, 3), strtolower($this->persona->getNome()) );

      list($codAnno, $codMese, $codGiorno) = $this->convertBirthDate();
      $res .= $codAnno;
      $res .= $codMese;
      $res .= $codGiorno;

      $res .= $this->convertCity();

      $res .= $this->generateLastChar($res); // genera il carattere di controllo


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

      return $this->cityconverter->getByCity($this->persona->getCittaNascita());


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
     * @param string Codice fiscale senza ultimo carattere
     * @return string Carattere di controllo
     */
    private function generateLastChar($partialcode) {

      $partialcode = strtoupper($partialcode); // per sicurezza tutto in maiuscolo

      $costante_divisione = 26; // servirà per la divisione finale

      $pari = array(
          '0' =>  0, '1' =>  1, '2' =>  2, '3' =>  3, '4' =>  4,
          '5' =>  5, '6' =>  6, '7' =>  7, '8' =>  8, '9' =>  9,
          'A' =>  0, 'B' =>  1, 'C' =>  2, 'D' =>  3, 'E' =>  4,
          'F' =>  5, 'G' =>  6, 'H' =>  7, 'I' =>  8, 'J' =>  9,
          'K' => 10, 'L' => 11, 'M' => 12, 'N' => 13, 'O' => 14,
          'P' => 15, 'Q' => 16, 'R' => 17, 'S' => 18, 'T' => 19,
          'U' => 20, 'V' => 21, 'W' => 22, 'X' => 23, 'Y' => 24,
          'Z' => 25
      );

      $dispari = array(
          '0' =>  1, '1' =>  0, '2' =>  5, '3' =>  7, '4' =>  9,
          '5' => 13, '6' => 15, '7' => 17, '8' => 19, '9' => 21,
          'A' =>  1, 'B' =>  0, 'C' =>  5, 'D' =>  7, 'E' =>  9,
          'F' => 13, 'G' => 15, 'H' => 17, 'I' => 19, 'J' => 21,
          'K' =>  2, 'L' =>  4, 'M' => 18, 'N' => 20, 'O' => 11,
          'P' =>  3, 'Q' =>  6, 'R' =>  8, 'S' => 12, 'T' => 14,
          'U' => 16, 'V' => 10, 'W' => 22, 'X' => 25, 'Y' => 24,
          'Z' => 23
      );

      $controllo = array(
          '0'  => 'A', '1'  => 'B', '2'  => 'C', '3'  => 'D',
          '4'  => 'E', '5'  => 'F', '6'  => 'G', '7'  => 'H',
          '8'  => 'I', '9'  => 'J', '10' => 'K', '11' => 'L',
          '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P',
          '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T',
          '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X',
          '24' => 'Y', '25' => 'Z'
      );


      $splitted = str_split($partialcode); // array di tutti i caratteri del codice

      $sum = 0; // valore totale di controllo

      foreach ($splitted as $position => $char) {

        $position++; // incrementa il valore di 1 per i calcoli successivi

        if ( ($position % 2) === 0) { // se la posizione è PARI

          $sum += $pari[$char];

        }
        else {

          $sum += $dispari[$char];

        }

        $resto = $sum % $costante_divisione; // trova il resto della divisione

        $res = $controllo[ $sum ]; // individua il valore di controllo

        return $res;


      }


    }
}
