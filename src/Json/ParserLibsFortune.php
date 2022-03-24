<?php

namespace App\Json;

class ParserLibsFortune
{
    private string $file;
    private array $json;

    public function __construct()
    {
        $this->file = 'Fortune';
    }

    public function getLevel(int $xp, bool $information = false): int|array
    {
        $libs = $this->getFile();
        $sum = 0;

        foreach ($libs['libs']['fortune_level']['item'] as $level => $exp) {
            $sum += (int) $exp['xp'];

            if ($sum >= $xp) {
                if($information){
                    return $exp;
                }
                return (int) $exp['id'];
            }
        }
        return -1;
    }

    public function getDrops(array $cells): array
    {
        $libs = $this->getFile();

        $fortune = $libs['libs']['fortune']['item'][0];

        if (isset($libs['libs']['fortune'])) {
            foreach ($libs['libs']['fortune']['item'] as $key => $lib) {
                if ($lib['cell1'] == $cells[1] and $lib['cell2'] == $cells[2] and $lib['cell3'] == $cells[3]) {
                    $fortune = $lib;
                } elseif ($lib['cell1'] == $cells[1] and $lib['cell2'] == $cells[2] and $lib['cell3'] == 0) {
                    $fortune = $lib;
                } elseif ($lib['cell1'] == $cells[2] and $lib['cell2'] == $cells[3] and $lib['cell3'] == 0) {
                    $fortune = $lib;
                } elseif ($lib['cell1'] == $cells[1] and $lib['cell2'] == $cells[3] and $lib['cell3'] == 0) {
                    $fortune = $lib;
                }
            }
        }
        return $fortune;
    }

    private function getFile()
    {
        if (is_file(__DIR__ . '/Libs/' . $this->file . '.json')) {
            $file = file_get_contents(__DIR__ . '/Libs/' . $this->file . '.json');

            try {
                return json_decode(json: $file, associative: true, flags: JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                return $e->getMessage() . ' JSON.';
            }
        }
        return [];
    }
}
