<?php

namespace App\Services;

class TrisWinnerChecker
{

    //scorre le varie colonne in base al primo numero di colonna $col assegnandoli sulla board da 0 a 8
    protected function col($col, $bs){
        if ($bs[$col] == 1 && $bs[$col+3] == 1 && $bs[$col+6] == 1){
            return 1;
        }
        elseif ($bs[$col] == 2 && $bs[$col+3] == 2 && $bs[$col+6] == 2){
            return 2;
        }
    }


    //scorre le varie colonne in base al primo numero di riga $row assegnandoli sulla board da 0 a 8
    protected function row($row, $bs){
        if ($bs[$row] == 1 && $bs[$row+1] == 1 && $bs[$row+2] == 1){
            return 1;
        }
        elseif ($bs[$row] == 2 && $bs[$row+1] == 2 && $bs[$row+2] == 2){
            return 2;
        }
    }


    protected function diag($diag, $bs){
        if ($bs[$diag] == 1 && $bs[4] == 1 && $bs[8-$diag] == 1){
            return 1;
        }
        elseif ($bs[$diag] == 2 && $bs[4] == 2 && $bs[8-$diag] == 2){
            return 2;
        }
    }


    public function checkWin($move)
    {
        $bs = json_decode($move);
        //$bs è un array sequenziale con 0 per caselle vuote, 1 per caselle user, 2 per caselle opponent


        if($this->row(0, $bs)==1 ||
            $this->row(3, $bs)==1 ||
            $this->row(6, $bs)==1 ||
            $this->col(0, $bs)==1 ||
            $this->col(1, $bs)==1 ||
            $this->col(2, $bs)==1 ||
            $this->diag(0, $bs)==1 ||
            $this->diag(2, $bs)==1){

            return 1;
        }
        else if($this->row(0, $bs)==2 ||
            $this->row(3, $bs)==2 ||
            $this->row(6, $bs)==2 ||
            $this->col(0, $bs)==2 ||
            $this->col(1, $bs)==2 ||
            $this->col(2, $bs)==2 ||
            $this->diag(0, $bs)==2||
            $this->diag(2, $bs)==2){

            return 2;
        }
    }
}
