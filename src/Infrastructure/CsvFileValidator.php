<?php

namespace Infrastructure;

use RuntimeException;

/**
 * Class CsvFileValidator
 *
 * @author Martin Krastev <martin.krastev@devision.bg>
 */
class CsvFileValidator
{
    /**
     * @param array $fileData
     *
     * @return array
     */
    public static function validate(array $fileData): array
    {
        // could be spread to handle more errors but... not today
        if ($fileData['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Failed to upload data');
        }

        $extension = pathinfo($fileData['name'], PATHINFO_EXTENSION);

        if ($extension !== 'csv') {
            throw new RuntimeException('Only csv extension allowed');
        }

        $csvStream = fopen($fileData['tmp_name'], 'r+');
        $data = [];

        while (($line = fgetcsv($csvStream))) {
            $data[] = $line;
        }

        fclose($csvStream);

        if (count($data) < 2) {
            throw new RuntimeException('Missing data');
        }

        // not validated headings... too much work for not so important stuff here. Agree? :)

        array_shift($data); // remove headings

        return $data; // only body
    }
}
