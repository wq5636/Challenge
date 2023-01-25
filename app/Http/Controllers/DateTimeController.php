<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DateTimeApi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DateTimeController extends Controller
{
    public function days(Request $request) {
        // Default UCT if not specified
        // Supported timezone: https://www.php.net/manual/en/timezones.php
        $date = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'start_timezone' => 'sometimes|timezone',
            'end_timezone' => 'sometimes|timezone',
        ]);

        $startDate = isset($date['start_timezone']) ? new Carbon($date['start_date'], $date['start_timezone']) : new Carbon($date['start_date']);
        $endDate = isset($date['end_timezone']) ? new Carbon($date['end_date'], $date['end_timezone']) : new Carbon($date['end_date']);

        $days = $startDate->diffInDays($endDate);

        DateTimeApi::create([
            'user_id' => auth()->user()->id,
            'action' => 'weeks',
            'gap' => $days,
            'start_date' => $date['start_date'],
            'start_timezone' => $date['start_timezone'] ?? null,
            'end_date' => $date['end_date'],
            'end_timezone' => $date['end_timezone'] ?? null,
        ]);
        return response()->json(['days' => $days]);
    }


    public function weeks(Request $request) {
        // Default UCT if not specified
        // Supported timezone: https://www.php.net/manual/en/timezones.php
        $date = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'start_timezone' => 'sometimes|timezone',
            'end_timezone' => 'sometimes|timezone',
        ]);

        $startDate = isset($date['start_timezone']) ? new Carbon($date['start_date'], $date['start_timezone']) : new Carbon($date['start_date']);
        $endDate = isset($date['end_timezone']) ? new Carbon($date['end_date'], $date['end_timezone']) : new Carbon($date['end_date']);

        $weeks = $startDate->diffInWeeks($endDate);

        DateTimeApi::create([
            'user_id' => auth()->user()->id,
            'action' => 'weeks',
            'gap' => $weeks,
            'start_date' => $date['start_date'],
            'start_timezone' => $date['start_timezone'] ?? null,
            'end_date' => $date['end_date'],
            'end_timezone' => $date['end_timezone'] ?? null,
        ]);

        return response()->json(['weeks' => $weeks]);
    }
}
