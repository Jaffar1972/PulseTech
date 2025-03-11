<?php
namespace Vehicle;
class Peugeot
{

    public int $annee;
    public int $prix;
    public string $color;

    public function __construct($annee, $prix, $color)
    {

        $this->annee = $annee;
        $this->prix = $prix;
        $this->color = $color;
    }

    public function getAnnee(): int
    {
        return $this->annee;
    }

    public function getPrix(): float
    {
        return $this->prix;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setAnnee($annee): void
    {

        $this->annee = $annee;
    }

    public function setPrix($prix): void
    {

        $this->prix = $prix;
    }

    public function Verified(): bool
    {

        if ($this->annee = 2010) {
            return false;
        }
        if ($this->prix > 20000) {
            return false;
        }
    }
}
