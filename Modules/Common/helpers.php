<?php

if (!function_exists('get_enum_values')) {
    /**
     * Get values from enum file.
     *
     * @param array $data
     *
     * @return array
     */
    function get_enum_values(array $data)
    {
        $values = [];

        foreach ($data as $value) {
            $values[] = $value->value;
        }

        return $values;
    }
}
