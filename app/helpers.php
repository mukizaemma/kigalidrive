<?php

if (!function_exists('getCurrencySymbol')) {
    /**
     * Get currency symbol based on currency code
     *
     * @param string $currency Currency code (USD, EUR, GBP, etc.)
     * @return string Currency symbol
     */
    function getCurrencySymbol($currency = 'USD')
    {
        $symbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'RWF' => 'Fr',
            'KES' => 'KSh',
            'UGX' => 'USh',
            'TZS' => 'TSh',
        ];
        
        return $symbols[$currency] ?? '$';
    }
}
