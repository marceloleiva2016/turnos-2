<?php
class IntervencionMenor
{
    var $idIntervencion;
    var $descripcion;
    var $seleccionado;

    public function IntervencionMenor($idIntervencion,$descripcion,$seleccionado)
    {
        $this->idIntervencion = $idIntervencion;
        $this->descripcion = $descripcion;
        $this->seleccionado = $seleccionado;
    }

    public function toStringHTML()
    {
        if($this->seleccionado=='True')
        {
            $chequeado="checked";
        }
        else
        {
            $chequeado="";
        }

        return $this->descripcion."<input type='checkbox' name='intervenciones[]' value='".$this->idIntervencion."'".$chequeado.">";
    }

    public function toStringHTMLEpicrisis()
    {
        if($this->seleccionado=='True')
        {
            $chequeado="checked";
        }
        else
        {
            $chequeado="";
        }

        return $this->descripcion."<input type='checkbox' name='intervenciones[]' value='".$this->idIntervencion."' id='checkbox-1-1' class='regular-checkbox' ".$chequeado." disabled/><label for='checkbox-1-1'></label>";
    }
} 