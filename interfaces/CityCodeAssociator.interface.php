<?php

  require_once('../classes/Citta.class.php'); // importa Citta

  interface CityCodeAssociator {

  public function getByKey(Citta $chiave);

}
