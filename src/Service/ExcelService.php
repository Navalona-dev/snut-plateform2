<?php

namespace App\Service;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class ExcelService
{
    public function extractData($filePath, $sheetName, $startRow, $startColumn, $endRow, $endColumn)
    {
        $result = [];
        $startColumn = $startColumn - 1;
        $endColumn = $endColumn - 1;

        try {
            // Create a reader
            $reader = ReaderEntityFactory::createXLSXReader();

            // Open the file
            $reader->open($filePath);

            // Iterate over sheets
            foreach ($reader->getSheetIterator() as $sheet) {
                // Check if it's the desired sheet
                if ($sheet->getName() === $sheetName) {
                    $currentRow = 1;

                    // Iterate over rows
                    foreach ($sheet->getRowIterator() as $rowIndex => $row) {
                        // Consider the offset of 6 rows
                        $adjustedRow = $rowIndex + 6;

                        // Check if the row is within the specified range
                        if ($adjustedRow >= $startRow && $adjustedRow <= $endRow) {
                            $rowData = [];

                            // Iterate over cells
                            foreach ($row->getCells() as $colIndex => $cell) {
                                // Check if the cell is within the specified range
                                if ($colIndex >= $startColumn && $colIndex <= $endColumn) {
                                    $rowData[] = $cell->getValue();
                                }
                            }
                            $result[] = $rowData;
                        }

                        // Break out of the loop if we've reached the end of the desired range
                        if ($adjustedRow > $endRow) {
                            break;
                        }
                    }
                    break; // Break out of the loop after processing the desired sheet
                }
            }

            // Close the reader
            $reader->close();
        } catch (\Exception $e) {
            // Handle exceptions
            throw new \Exception('An error occurred: ' . $e->getMessage());
        }

        return $result;
    }

    /**
     * Extraire les données avec de resultat par colonne.
     */
    public function extractDataByColumn($filePath, $sheetName, $startRow, $startColumn, $endRow, $endColumn)
    {
        $result = [];
        $startColumn = $startColumn - 1;
        $endColumn = $endColumn - 1;

        try {
            // Create a reader
            $reader = ReaderEntityFactory::createXLSXReader();

            // Open the file
            $reader->open($filePath);

            // Iterate over sheets
            foreach ($reader->getSheetIterator() as $sheet) {
                // Check if it's the desired sheet
                if ($sheet->getName() === $sheetName) {
                    // Iterate over columns
                    for ($colIndex = $startColumn; $colIndex <= $endColumn; $colIndex++) {
                        $columnData = [];
                        $currentRow = 1;

                        // Iterate over rows
                        foreach ($sheet->getRowIterator() as $rowIndex => $row) {
                            // Consider the offset of 6 rows
                            $adjustedRow = $rowIndex + 6;

                            // Check if the row is within the specified range
                            if ($adjustedRow >= $startRow && $adjustedRow <= $endRow) {
                                $cell = $row->getCellAtIndex($colIndex);
                                if ($cell != null) {
                                    $columnData[] = $cell->getValue();
                                }
                            }

                            // Break out of the loop if we've reached the end of the desired range
                            if ($adjustedRow > $endRow) {
                                break;
                            }
                        }

                        $result[] = $columnData;
                    }

                    break; // Break out of the loop after processing the desired sheet
                }
            }

            // Close the reader
            $reader->close();
        } catch (\Exception $e) {
            // Handle exceptions
            // throw new \Exception('An error occurred: ' . $e->getMessage());
            return $result;
        }

        return $result;
    }
}
