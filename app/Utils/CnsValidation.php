<?php 

namespace App\Utils;

class CnsValidation
{
    private $cns;

    public function validate($attribute, $value, $parameters, $validator)
    {
        return $this->isValidate($attribute, $value);
    }

    protected function isValidate($attribute, $value)
    {
        // Retira todos os caracteres que nao sejam 0-9
        $this->cns = str_replace(' ', '', preg_replace('/[^0-9]/', '', $value));
        
        // Retorna falso se for diferente que 15 caracteres
        if ((strlen(trim($this->cns))) != 15) { 
            return false;
        }
        
        // Retorna falso se houver letras no cns
        if (!(preg_match('/[0-9]/',$this->cns)))
        {
            return false;
        }
        
        $acao = substr($this->cns,0,1);

        switch ($acao)
        {
            case '1':
            case '2': $ret = $this->validaCNS(); break;
            case '7':
            case '8':
            case '9': $ret = $this->validaCNS_PROVISORIO(); break;
            default: $ret = false;
        }
        
        // Analisa o retorno e gera um Exception se for falso
        if (!$ret)
        {
            return false;
        }

        return true;
    }

    private function validaCNS()
    {
        $pis = substr($this->cns,0,11);
        $soma = 0;
        for ( $i = 0, $j = strlen($pis), $k = 15; $i < $j; $i++, $k-- )
        {
            $soma += $pis[$i] * $k;
        }
        $dv = 11 - fmod($soma, 11);
        $dv = ($dv != 11) ? $dv : '0'; // retorna '0' se for igual a 11
        if ( $dv == 10 )
        {
            $soma += 2;
            $dv = 11 - fmod($soma, 11);
            $resultado = $pis.'001'.$dv;
        }
        else
        {
            $resultado = $pis.'000'.$dv;
        }
        if ( $this->cns != $resultado )
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    
    private function validaCNS_PROVISORIO()
    {
        $soma = 0;
        for ( $i = 0, $j = strlen($this->cns), $k = $j; $i < $j; $i++, $k-- )
        {
            $soma += $this->cns[$i] * $k;
        }
        return $soma % 11 == 0 && $j == 15;
    }
}