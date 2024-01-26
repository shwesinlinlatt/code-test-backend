<?php


namespace App\Utils;


class AccountPassword
{
    static public function generate($role)
    {
        $plain_password = '';

        switch ($role) {
            case Roles::SENIOR_TECHNICAL_ADVISER_TB:
                $plain_password .= 'STA@#';
                break;
            case Roles::DEPUTY_PROGRAMME_DIRECTOR:
                $plain_password .= 'DPD@#';
                break;
            case Roles::PROGRAMME_MANAGER_MDR:
                $plain_password .= 'PM@#';
                break;
            case Roles::PROGRAMME_MANAGER_AIS:
                $plain_password .= 'PMAIS@#';
                break;
            case Roles::HIS_AND_DIGITAL_LITERACY_MANAGER:
                $plain_password .= 'MealHISPM@#';
                break;
            case Roles::MEAL_AND_DIGITAL_TOOL_OFFICER:
                $plain_password .= 'MealHISOfficer@#';
                break;
            case Roles::MEAL_ASSOCIATE:
                $plain_password .= 'MealAssociate@#';
                break;
            case Roles::PROJECT_OFFICER:
                $plain_password .= 'PO@#';
                break;
            case Roles::FIELD_OFFICER:
                $plain_password .= 'FO@#';
                break;
            default:
                break;
        }
        $plain_password .= rand(1000, 9999);

        return $plain_password;
    }
}
