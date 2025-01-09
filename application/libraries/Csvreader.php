<?php
class Csvreader
{
    public function parse_file($filePath)
    {
        $data = [];
        $file = fopen($filePath, 'r');
        $header = fgetcsv($file);

        while ($row = fgetcsv($file)) {
            $data[] = array_combine($header, $row);
        }

        fclose($file);
        return $data;
    }
}
