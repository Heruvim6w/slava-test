<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExcelRequest;
use App\Jobs\ParseFileJob;
use App\Models\Row;
use Illuminate\Support\Facades\View;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ExcelController extends Controller
{
    public function upload(ExcelRequest $request)
    {
        $file = $request->validated();
        $path = $file['file']->store('excel');
        $storagePath = storage_path('app/' . $path);

        $reader = IOFactory::createReaderForFile($storagePath);

        $spreadsheet = $reader->load($storagePath);
        $worksheet = $spreadsheet->getActiveSheet();

        $totalRows = $worksheet->getHighestRow();

        $batchSize = 1000;
        for ($i = 1; $i <= $totalRows; $i += $batchSize) {
            $rows = [];
            for ($j = $i; $j <= min($i + $batchSize - 1, $totalRows); $j++) {
                $row = $worksheet->getRowIterator($j)->current();
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE);

                if ($row->getRowIndex() === 1) {
                    continue;
                }

                $rowData = [];
                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getCalculatedValue();
                }

                if (!empty($rowData[0]) && !empty($rowData[1]) && !empty($rowData[2])) {
                    $rows[] = [
                        'id' => (int) $rowData[0],
                        'name' => $rowData[1],
                        'date' => Date::excelToDateTimeObject($rowData[2])->format('Y-m-d')
                    ];
                }
            }

            if (!empty($rows)) {
                ParseFileJob::dispatch($path, $rows);
            }
        }

//        return response()
//            ->json(['message' => 'File uploaded to queue']);
        return redirect('/rows');
    }

    public function showRows()
    {
        $rows = Row::all();

        return view('rows', compact('rows'));
    }
}
