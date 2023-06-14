<?php

namespace Tests\Unit;

use App\Http\Controllers\ExcelController;
use App\Http\Requests\ExcelRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Tests\TestCase;

class ExcelControllerTest extends TestCase
{
    private ExcelController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new ExcelController();
    }

    public function testUpload()
    {
        // Загрузка тестового файла
        $filePath = base_path('tests/Unit/test.xlsx');
        $file = new UploadedFile($filePath, 'test.xlsx', 'xlsx', null, true);
        $requestData = ['file' => $file];
        $request = new ExcelRequest($requestData);

        //Отправка данных на роут
        $response = $this->call(
            'POST',
            route('excel.upload'),
            [],
            [],
            ['file' => $file],
            [],
            $request
        );

        // Проверка ответа
        $response->assertJson(['message' => 'File uploaded to queue']);

        // Проверка сохраненного файла
        $storagePath = 'excel/' . $file->hashName();
        Storage::assertExists($storagePath);

        // Проверка содержимого файла
        $reader = IOFactory::createReaderForFile(Storage::path($storagePath));
        $spreadsheet = $reader->load(Storage::path($storagePath));
        $worksheet = $spreadsheet->getActiveSheet();
        $totalRows = $worksheet->getHighestRow();

        for ($i = 2; $i <= $totalRows; $i++) {
            $row = $worksheet->getRowIterator($i)->current();
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            $rowData = [];

            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getCalculatedValue();
            }

            if (!empty($rowData[0]) && !empty($rowData[1]) && !empty($rowData[2])) {
                $this->assertDatabaseHas('rows', [
                    'id' => (int) $rowData[0],
                    'name' => $rowData[1],
                    'date' => Date::excelToDateTimeObject($rowData[2])->format('Y-m-d')
                ]);
            }
        }
    }
}
