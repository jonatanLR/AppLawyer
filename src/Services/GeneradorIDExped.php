<?php

namespace App\Services;
use App\Repository\ExpedienteRepository;

class GeneradorIDExped
{

    public function __construct(private ExpedienteRepository $expedRepo)
    {
        
    }

    // function to get the current bigges expediente ID
    public function getBigId()
    {
        // The follows methods make the same thing
        //                                      criteria,  ORDER BY,    LIMIT
        // $expedientes = $this->expedRepo->findBy([], ['id' => "DESC"], 1);
        $lastInsert = $this->expedRepo->greaterId();
        return $lastInsert;
        
    }

    // function to return to format ID expediente data and increment in 1 this
    public function formatData(): ?String
    {
        $currentID = $this->getBigId();
        $partStringNumber = substr($currentID[0]->getId(), 9, 5);
        // convertir el string en entero
        $intNumber = intval($partStringNumber);
        // sumar o incrementar en 1 el id obtenido
        $incrementNumber = $intNumber + 1;
        // agregar cerros a la izquierda del numero aumentado
        $newStringNumber = str_pad($incrementNumber, 5, 0, STR_PAD_LEFT);

        return $newStringNumber;
 
    }

    // function to generate the next ID to show in the formtype
    public function nextId()
    {
        $contador = $this->expedRepo->count([]);
        $pieceDate = date('Ym');
        $secuencyNumber = "00000";

        if ($contador > 0) {
           $secuencyNumber = $this->formatData();
           $newID = "EXP" . $pieceDate . $secuencyNumber;

           return $newID;
        }else{
            $newID = "EXP" . $pieceDate . $secuencyNumber; 

            return $newID;
        }

    }

}