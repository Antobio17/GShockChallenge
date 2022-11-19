<?php

namespace App;

use Symfony\Component\HttpFoundation\Request;

class ToolsHelper
{

    /**
     * Gets the param value from the request passed or null if it not exists.
     *
     * @param Request $request Request of the route.
     * @param string $paramKey Key to search the param value.
     *
     * @return mixed|null mixed|null
     */
    public static function getParamFromRequest(Request $request, string $paramKey)
    {
        $content = json_decode($request->getContent(), true);
        $content = isset($content[0]) && is_array($content[0]) ? $content[0] : $content;

        return $content[$paramKey] ?? $request->request->get($paramKey) ?? $request->query->get($paramKey) ?? NULL;
    }

    /**
     * Validates the fields of the request passed by parameters as an array of key => value.
     *
     *      return array(
     *          sprintf('The %s field cannot be empty', $key),
     *          ...
     *      )
     *
     * @param array $requestFields The array of fields to validate.
     *
     * @return array array
     */
    public static function validateRequiredRequestFields(array $requestFields): array
    {
        $validationErrors = array();
        foreach ($requestFields as $fieldName => $value):
            if (empty($value) && $value !== 0):
                $validationErrors[] = sprintf('The %s field cannot be empty', $fieldName);
            endif;
        endforeach;

        return $validationErrors;
    }
    
    /**
     * Validates the fields of the request passed by parameters as an array of key => value.
     *
     *      return array(
     *          sprintf('The %s field must be integer', $key),
     *          ...
     *      )
     *
     * @param array $requestFields The array of fields to validate.
     *
     * @return array array
     */
    public static function validateRequestNumericFields(array $requestFields): array
    {
        $validationErrors = array();
        foreach ($requestFields as $fieldName => $value):
            if ($value !== NULL && !is_numeric($value)):
                $validationErrors[] = sprintf('The %s field must be numeric', $fieldName);
            endif;
        endforeach;

        return $validationErrors;
    }

}
