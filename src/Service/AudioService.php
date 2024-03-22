<?php

namespace App\Service;

class AudioService
{
    private string $baseUrl = "https://firebasestorage.googleapis.com/v0/b/";
    private string $projectId = "test-web-app-259c6.appspot.com";
    private string $url = "";

    public function __construct()
    {
        $this->url = $this->baseUrl.$this->projectId.'/o/';
    }

    public function getStorageFileUrl($name, $arborescence = []) {
        if(sizeof($arborescence) > 0) {
            $this->url .= implode('%2F', $arborescence).'%2F';
        }

        return $this->url.$name.'?alt=media';
    }
}