<?php
class MedicacionHabitual
{
    var $observacion;

    public function MedicacionHabitual($texto)
    {
        $this->observacion = $texto;
    }

    public function toString()
    {
        return $this->observacion;
    }
}