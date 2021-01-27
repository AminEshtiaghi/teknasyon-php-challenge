<?php

namespace App\Services;

use App\Models\Subscription;
use DB;

class ReportService
{
    const COLUMN_COUNT = 'count';
    const COLUMN_COUNT_RAW = 'count(1) `count`';

    public static function get(): array
    {
        $result = [
            Subscription::STATUS_NEW => 0,
            Subscription::STATUS_EXPIRED => 0,
            Subscription::STATUS_RENEWED => 0,
        ];

        $validStatus = [
            Subscription::STATUS_NEW,
            Subscription::STATUS_EXPIRED,
            Subscription::STATUS_RENEWED,
        ];

        $items = (new Subscription())
            ->whereIn(Subscription::COLUMN_STATUS, $validStatus)
            ->groupBy(Subscription::COLUMN_STATUS)
            ->select(Subscription::COLUMN_STATUS, DB::raw(self::COLUMN_COUNT_RAW))
            ->get();

        foreach ($items as $item) {
            $result[$item->getStatus()] = $item->{self::COLUMN_COUNT};

        }

        return $result;
    }
}