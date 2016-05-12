<?php
class Antecedente
{
    var $idAntecedente;
    var $antecedente;
    var $observacion;

    public function Antecedente($id , $nombre, $texto)
    {   
        $this->idAntecedente = $id;
        $this->antecedente = $nombre;
        $this->observacion = $texto;
    }

    public function toStringHTML()
    {
        return $this->antecedente."<input type='checkbox' value=".$this->idAntecedente." id='checkbox-1-1' class='regular-checkbox' checked disabled/><label for='checkbox-1-1'></label><div class='textarea-style'>".$this->observacion."</div>";
    }
}