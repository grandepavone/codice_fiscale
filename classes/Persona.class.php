<?php

  require_once('./Citta.class.php');
  require_once('./Date.class.php');

  class Persona {


    private $nome;

    private $cognome;

    private $citta_nascita;

    private $data_nascita;

    private $sesso;

    /** Costruttore della classe Persona
    *
    * @param array params Array associativo
    *
    */

    public function __construct (array $params) {

      $this->setNome($params['nome']);
      $this->setCognome($params['cognome']);
      $this->setDataNascita($params['data_nascita']);
      $this->setCittaNascita($params['citta_nascita']);
      $this->setSesso($params['sesso']);

    }


    /**
     * Get the value of Nome
     *
     * @return string
     */
    public function getNome()
    {
        return (string) $this->nome;
    }

    /**
     * Set the value of Nome
     *
     * @param string nome
     *
     * @return self
     */
    public function setNome($nome)
    {
        $nome = trim($nome);

        $this->nome = $nome;

        return $this;
    }

    /**
     * Get the value of Cognome
     *
     * @return string
     */
    public function getCognome()
    {
        return (string) $this->surname;
    }

    /**
     * Set the value of Cognome
     *
     * @param string surname
     *
     * @return self
     */
    public function setCognome($surname)
    {
        $surname = trim($surname);
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get the value of Citta Nascita
     *
     * @return mixed
     */
    public function getCittaNascita()
    {
        return $this->citta_nascita;
    }

    /**
     * Set the value of Citta Nascita
     *
     * @param Citta citta_nascita
     *
     * @return self
     */
    public function setCittaNascita(Citta $citta_nascita)
    {
        $this->citta_nascita = $citta_nascita;

        return $this;
    }

    /**
     * Get the value of Data Nascita
     *
     * @return Date
     */
    public function getDataNascita()
    {
        return $this->data_nascita;
    }

    /**
     * Set the value of Data Nascita
     *
     * @param Date data_nascita
     *
     * @return self
     */
    public function setDataNascita(Date $data_nascita)
    {
        $this->data_nascita = $data_nascita;

        return $this;
    }

    /**
     * Get the value of Sesso
     *
     * @return bool
     */
    public function getSesso()
    {
        return (bool) $this->sesso;
    }

    /**
     * Set the value of Sesso
     *
     * @param bool sesso
     *
     * @return self
     */
    public function setSesso($sesso)
    {
        $this->sesso = $sesso;

        return $this;
    }

}
