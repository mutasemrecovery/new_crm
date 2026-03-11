<?php

function uploadImage($folder, $image)
{
    $extension = strtolower($image->getClientOriginalExtension());

    // generate unique name with timestamp + random string
    $filename = uniqid() . '_' . time() . '.' . $extension;

    $image->move(base_path($folder), $filename);

    return $filename;
}



function uploadFile($file, $folder)
{
    $path = $file->store($folder);
    return $path;
}


if (!function_exists('formatPrice')) {
    /**
     * Format price with selected currency
     */
    function formatPrice($price)
    {
        $currency = session('currency');

        if (!$currency) {
            // Get default currency
            $currency = \App\Models\Currency::where('is_default', 1)->first();
            if ($currency) {
                session()->put('currency', $currency);
            }
        }

        if ($currency) {
            $convertedPrice = $price * $currency->rate;
            return number_format($convertedPrice, 2) . ' ' . $currency->symbol;
        }

        return number_format($price, 2) . ' JD';
    }
}

if (!function_exists('convertPrice')) {
    /**
     * Convert price to selected currency (returns number only)
     */
    function convertPrice($price)
    {
        $currency = session('currency');

        if (!$currency) {
            $currency = \App\Models\Currency::where('is_default', 1)->first();
            if ($currency) {
                session()->put('currency', $currency);
            }
        }

        if ($currency) {
            return $price * $currency->rate;
        }

        return $price;
    }
}

if (!function_exists('getCurrencySymbol')) {
    /**
     * Get current currency symbol
     */
    function getCurrencySymbol()
    {
        $currency = session('currency');

        if (!$currency) {
            $currency = \App\Models\Currency::where('is_default', 1)->first();
            if ($currency) {
                session()->put('currency', $currency);
            }
        }

        return $currency ? $currency->symbol : 'JD';
    }
}

if (!function_exists('getCurrencyCode')) {
    /**
     * Get current currency code
     */
    function getCurrencyCode()
    {
        $currency = session('currency');

        if (!$currency) {
            $currency = \App\Models\Currency::where('is_default', 1)->first();
            if ($currency) {
                session()->put('currency', $currency);
            }
        }

        return $currency ? $currency->code : 'JOD';
    }
}



