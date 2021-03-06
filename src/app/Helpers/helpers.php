<?php

use Illuminate\Support\Facades\{App, Log};
use Carbon\Carbon;

if (!function_exists('triggerLogExceptionError')) {
    function triggerLogExceptionError(string $file, $error)
    {
        Log::error("{$file} ha sucedido un error --> " . $error->getMessage());
        Log::error($error->getTraceAsString());
    }
}


if (!function_exists('switch_app_language')) {
    function switch_app_language(array $app_languages = [], string $lang = 'es')
    {
        if (!empty($app_languages) && isset($app_languages[$lang])) {
            setlocale(LC_TIME, $app_languages[$lang][1] . '.utf8');
            Carbon::setLocale($lang);
            App::setLocale($lang);
        }
    }
}

if (!function_exists('array_key_default_value')) {
    /**
     * Comprueba si la key existe en el array objetivo, si no es asi
     * devuelve el valor por defecto definido por parámetro
     * @param Array $haystack - Array objetivo que queremos utilizar
     * @param $needle - Key del array que queremos buscar
     * @param $defaultValue - Valor por defecto a retornar si no existe la key en el array
     * 
     * @return $result - Devuelve el valor del array asociativo o el valor por defecto.
     */
    function array_key_default_value(array $haystack, $needle, $defaultValue = "")
    {
        if (array_key_exists($needle, $haystack)) {
            return $haystack[$needle];
        }

        return $defaultValue;
    }
}

if (!function_exists('lower_and_replace_special_characters')) {
    /**
     * Aplica unas conversiones especificas a una cadena de texto
     * Ejemplo: Correos Express --> correosexpress / E-log --> elog
     * @param String $value - Valor que queremos transformar
     * 
     * @return String $value - Resultado con las conversiones aplicadas.
     */
    function lower_and_replace_special_characters(string $value = "")
    {
        $lower =  strtolower(trim($value));

        return preg_replace('/(\s|-)/', "", $lower);
    }
}


if (!function_exists('class_function_name')) {
    function class_function_name($class, $function)
    {
        return  "{$class}::{$function}";
    }
}

if (!function_exists('allowed_rules')) {
    function allowed_rules($rules, $allowed = [])
    {
        if (empty($allowed)) return $rules;

        $filtered_rules =  array_filter($rules, function ($key) use ($allowed) {
            return in_array($key, $allowed);
        }, ARRAY_FILTER_USE_KEY);

        return array_map(function ($rule) {
            return str_replace('required|', '', $rule);
        }, $filtered_rules);
    }
}

if (!function_exists('chunkAddress')) {
    /**
     * Separa direcciones muy largas en piezas dentro de un array, esta caracteristica especial
     * se usa a la hora de enviar datos a los WebServices de algunos operadores logisticos.
     * @param String $address - Direccion que queremos separar en varias partes.
     * @param Array $addresses - Lista de direcciones si lo que queremos es meter las partes nuevas en esta existente
     * @param Integer $maxCharacters - Caracteres maximos para comenzar.
     * 
     * @return Array $addresses - Direcciones separadas segun el num de caracteres maximos
     */
    function chunkAddress(string $address = "", array $addresses = [], int $maxCharacters = 35)
    {
        $words = explode(" ", $address);
        $count = 0;
        $charactersLength = 0;

        foreach ($words as $word) {
            $charactersLength += strlen($word) + 1;

            if ($charactersLength > $maxCharacters) {

                $address = implode(" ", array_splice($words, 0, $count));
                $addresses[] = $address;
                $rest = implode(" ", $words);
                if (strlen($rest) > 35) {
                    $addresses = $addresses + chunkAddress($rest, $addresses);
                } else if (!empty($rest)) {
                    $addresses[] = $rest;
                }
                return $addresses;
                $charactersLength = 0;
            } else {
                $count += 1;
            }
        }
        return $addresses;
    }
}

if (!function_exists('isBase64')) {
    /**
     * Comprueba si el parametro es una codificacion en base64 válida
     * @param String $base64 - Contenido codificado en base64
     * @return Bool $result - Determina si la codificación es correcta
     */
    function isBase64(string $base64 = "")
    {
        $base64Regex = "/^([A-Za-z0-9+\/]{4})*([A-Za-z0-9+\/]{3}=|[A-Za-z0-9+\/]{2}==)?$/im";

        return preg_match($base64Regex, $base64);
    }
}

if (!function_exists('base64_decode_multiple')) {
    /** 
     *Decodifica el contenido en base64 incluso comprobando
     * si esta codificado multiples veces y aplicando una nueva descodificacion.
     * 
     * @param String $data - Contenido codificado en base64
     * @param Integer $count - Limite de veces a comprobar
     * @return $data - Contenido decodificado
     */

    function base64_decode_multiple(string $data, int $count = 2)
    {
        while ($count-- > 0 && ($decoded = base64_decode($data, true)) !== false) {
            $content = $decoded;
        }
        return $content;
    }
}
