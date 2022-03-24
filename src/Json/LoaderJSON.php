<?php

namespace App\Json;

class LoaderJSON
{
    private string $file;
    private array $json;

    public function getJson(string $key): array
    {
        if($key) {
            return $this->json[$key];
        } else return $this->json;
    }

    /**
     * @param mixed $file
     * @return LoaderJSON
     */
    public function setFile(string $file): self
    {
        $this->file = $file;
        return $this;
    }

    public function library(): array|string
    {
        if (is_file(__DIR__ . '/Libs/' . $this->file . '.json')) {
            $file = file_get_contents(__DIR__ . '/Libs/' . $this->file . '.json');

            try {
                return $this->json = json_decode(json: $file, associative: true, flags: JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                return $e->getMessage() . ' JSON.';
            }
        }
        return $this->json = [];
    }
}
