<?php


namespace App\Utilities\Inspections;


class DetectKeyHeldDown implements InspectionInterface
{
    public function detect($body)
    {
        if (preg_match('/(.)\\1{4,}/', $body, $matches) || preg_match('/([А-Яа-яЁё])\\1{4,}/u', $body, $matches)){
            throw new \Exception('Ваш ответ содержит спам.');
        };
    }
}
