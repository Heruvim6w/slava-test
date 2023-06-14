<?php

namespace App\Jobs;

use App\Events\RowCreated;
use App\Models\Row;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class ParseFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $path;
    public $rows;

    public function __construct($path, $rows)
    {
        $this->path = Storage::path($path);
        $this->rows = $rows;
    }

    public function handle()
    {
        $redis = Redis::connection();

        $key = 'parse_excel_' . uniqid();

        // Сохранение строк в базу данных. Так будет быстрее
//        Row::query()->insert($this->rows);

        // Сохранение строк в базу данных. Так, если нам нужно отслеживать событие записи для каждой строки
        foreach ($this->rows as $row) {
            $createdRow = Row::query()->create($row);
            Event::dispatch(new RowCreated($createdRow));
        }

        $redis->incrby($key, count($this->rows));

        Storage::delete($this->path);
    }
}
