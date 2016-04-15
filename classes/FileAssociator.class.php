<?php

require_once("../interfaces/CityCodeAssociator.interface.php"); // importa l'interfaccia della classe
require_once('./Citta.class.php'); // importa Citta

class FileAssociator Implements CityCodeAssociator {

  private $hendlefile;

  public function __construct ($nomefile) {

    $nomefile = trim($nomefile);

    $this->openFile($nomefile); // apri il file

  }

  public function getByCity(Citta $city) {

    $FOUND = FALSE; // servirà ad uscire dal ciclo strutturatamente

    $res = 'NOTFOUND'; // valore di default di ritorno

    while (!feof($this->handle) || $found === FALSE) {

      $buffer = fgets($this->handle);   // legge una riga intera da file
      $buffer = rtrim($buffer);   // rimuove carattere di return a fine riga

      list($codice, $comune, $provincia) = explode (";", $buffer);  // divide la stringa in tre rispetto al separatore ; usato nel file

      if ($comune == strtoupper($city->getNome()) && $provincia == strtoupper($city->getProvincia())) {

        $res = $codice;
        $found = TRUE;

      }

    }

    rewind($this->handle); // torna all'inizio del file

    return $res;

  }


  private function openFile($fname) {

    // apri il file in lettura
    $this->handle = fopen($fname, "r");

  }

  // distruttore
  public function __destruct () {

    fclose($this->handle);

  }
}
