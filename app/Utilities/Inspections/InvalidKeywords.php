<?php


namespace App\Utilities\Inspections;


use Exception;

class InvalidKeywords implements InspectionInterface
{

    protected $keywords;

    public function __construct()
    {
        $this->keywords = $this->getKeyWordsFromFile();
    }

    public function detect($body)
    {
        foreach ($this->keywords as $keyword){
            if(stripos($body, $keyword) !== false){
                throw new Exception('Ваш ответ содержит спам.');
            }
        }
    }

    protected function getKeyWordsFromFile()
    {
        $data = rtrim(file_get_contents(storage_path().'\app\spam\spamKeyWords.txt'));

        return explode(',', $data);
    }
}
