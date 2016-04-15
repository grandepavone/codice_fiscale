<?php

  class Date {

    /**
     * Tempo in secondi dalla UNIX ep. della data di nascita
     *
     * @var int
     */
    private $seconds;


    public function __construct ($date) {

      date_default_timezone_set('Europe/Rome');

      $this->setDate($date);

    }


    /**
     * Get the value of Giorno di nascita
     *
     * @return string Formato 2 cifre
     */
    public function getDay() {

        return (string) date("d", $this->seconds);

    }

    /**
     * Get the value of Mese di nascita
     *
     * @return string Formato 2 cifre
     */
    public function getMonth() {

        return (string) date("m", $this->seconds);

    }

    /**
     * Get the value of Anno di nascita
     *
     * @return string Formato 4 cifre
     */
    public function getYear() {

        return (string) date("Y", $this->seconds);

    }

    /**
     * Get the value of Data di nascita
     *
     * @return string Formato yy-mm-dd
     */
    public function getDate() {

        return (string) date("Y-m-d", $this->seconds);

    }


    /**
     * Set the value of Data di nascita
     *
     * @param string Formato yyyy-mm-d
     *
     * @return self
     */
    public function setDate($time)
    {

      list($year, $month, $day) = explode("-", $time);

      $this->seconds = mktime(0,0,0, $month, $day, $year); // scrivi timestamp

      return $this;

    }

}
