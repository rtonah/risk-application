<?php

use App\Models\AppSetting;

if (!function_exists('get_setting')) {
    /**
     * Récupère une valeur de paramètre à partir de la table app_settings
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function get_setting(string $key, $default = null)
    {
        $setting = AppSetting::where('key', $key)->value('value');

        if (is_null($setting)) {
            return $default;
        }

        // Tentative de décodage JSON (ex: types_paiement)
        $jsonDecoded = json_decode($setting, true);

        return (json_last_error() === JSON_ERROR_NONE) ? $jsonDecoded : $setting;
    }
}
