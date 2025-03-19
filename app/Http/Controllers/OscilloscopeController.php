<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class OscilloscopeController extends Controller
{
    public function show()
    {
        $data = json_decode(Storage::get('data.txt') ?? '[]');
        $lastPing = now()->getTimestamp() - (new DateTime(Storage::get('last-ping.txt')))->getTimestamp();
        $lastLoad = now()->getTimestamp() - (new DateTime(Storage::get('last-load.txt')))->getTimestamp();
        return Inertia::render('Show', [
            'data' => $data,
            'lastPing' => $lastPing,
            'lastLoad' => $lastLoad,
        ]);
    }

    public function ping()
    {
        Storage::put('last-ping.txt', now()->format('Y-m-d H:i:s'));
        $res = Storage::get('load-params.txt') ?? '';
        Storage::put('load-params.txt', '');
        return $res;
    }

    public function load(Request $request)
    {
        $request->validate([
            'step' => ['required', 'integer', 'min:1'],
            'stepCount' => ['required', 'integer', 'min:1'],
        ]);
        $step = $request->input('step');
        $stepCount = $request->input('stepCount');
        Storage::put('load-params.txt', "$step;$stepCount");
    }

    public function data(Request $request)
    {
        Storage::put('last-load.txt', now()->format('Y-m-d H:i:s'));
        $inputData = $request->input('d');
        $data = [];
//        $recordLen = 40;
        $recordLen = 12;
        $recordsCount = intdiv(strlen($inputData), $recordLen);
        for ($i = 0; $i < $recordsCount; $i++) {
            $recordStr = substr($inputData, $i * $recordLen, $recordLen);
            $data[$i] = [];
            $data[$i]['time'] = hexdec(substr($recordStr, 0, 8));
            $data[$i]['p1'] = hexdec(substr($recordStr, 8, 4));
//            $data[$i]['p2'] = hexdec(substr($recordStr, 12, 4));
//            $data[$i]['p3'] = hexdec(substr($recordStr, 16, 4));
//            $data[$i]['p4'] = hexdec(substr($recordStr, 20, 4));
//            $data[$i]['p5'] = hexdec(substr($recordStr, 24, 4));
//            $data[$i]['p6'] = hexdec(substr($recordStr, 28, 4));
//            $data[$i]['p7'] = hexdec(substr($recordStr, 32, 4));
//            $data[$i]['p8'] = hexdec(substr($recordStr, 36, 4));
        }
        Storage::put('data.txt', json_encode($data));
        return 'ok';
    }
}
