<?php

  class Citta {

    /**
    * City Name
    *
    */
    private $nome; // nome della città

    /**
    * provincia
    */
    private $provincia;



    public function __construct ( $nome_citta, $prov) {

      $this->nome = $this->setNome($nome_citta);
      $this->provincia = $this->setProvincia($prov);

    }



    /**
     * Get the value of City Name
     *
     * @return mixed
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set the value of City Name
     *
     * @param mixed nome
     *
     * @return self
     */
    public function setNome( $nome)
    {
        $nome = trim($nome);

        $this->nome = $nome;

        return $this;
    }

    /**
     * Get the value of provincia
     *
     * @return mixed
     */
    public function getProvincia()
    {
        return $this->provincia;
    }

    /**
     * Set the value of provincia
     *
     * @param mixed provincia
     *
     * @return self
     */
    public function setProvincia( $provincia)
    {
        $provincia = trim($provincia);

        $this->provincia = $provincia;

        return $this;
    }

  }
