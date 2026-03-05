<?php defined('BASEPATH') OR exit('No direct script access allowed');

class CurrencyConverter {
    
    /**
     * Converte valores monetários.
     * @param float $amount Valor original
     * @param string $from Moeda de origem (USD, EUR)
     * @param string $to Moeda de destino (BRL)
     * @param float $rate Taxa de câmbio manual
     * @return float Valor convertido
     */
    public function convert($amount, $from, $to, $rate = 1.00) {
        if ($from == $to) return $amount;
        
        // Garante que a taxa seja um número válido
        $rate = ($rate && $rate > 0) ? $rate : 1.00;
        
        return $amount * $rate;
    }
}