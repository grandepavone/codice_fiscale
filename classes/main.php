<?php

  require_once('./Citta.class.php');
  require_once('./Date.class.php');
  require_once('./FileAssociator.class.php');
  require_once('./Persona.class.php');
  require_once('./CodiceFiscale.class.php');


  $nome = $argv[1]; // input inline nome
  $cognome = $argv[2];
  $data_n = $argv[3];
  $citta_n = $argv[4];
  $provincia = $argv[5];
  $sex = $argv[6];

  $sex = ($sex === "M") ? FALSE : TRUE; // poni FALSE se maschio

  $nascita = new Date($data_n);

  $citta = new Citta($citta_n, $provincia);

  $gioa = array ('nome' => $nome, 'cognome' => $cognome, 'data_nascita' => $nascita, 'citta_nascita' => $citta, 'sesso' => $sex);

  $f = new FileAssociator('../file/codici_comuni_italiani.txt');

  $gio = new Persona($gioa);
  $cod = new CodiceFiscale($gio, $f);

  echo $cod->getCode() . "\n";


 ?>
