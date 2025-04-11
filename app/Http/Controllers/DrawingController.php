<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Canvas;
use Illuminate\Support\Facades\Storage;

class DrawingController extends Controller
{
    public function index() {
        return view('index');
    }

    public function upload(Request $request)
    {
        $file = $request->file('inputFile');
        $commands = file($file->getPathname(), FILE_IGNORE_NEW_LINES);

        $canvas = null;
        foreach ($commands as $line) {  //  Recorrer archivo por líneas
            $parts = explode(' ', trim($line));
            $command = array_shift($parts);

            switch ($command) { //  Leer la 1era letra de cada línea
                case 'C':
                    $canvas = new Canvas($parts[0], $parts[1]);
                    break;
                case 'L':
                    $canvas?->drawLine(...$parts);
                    break;
                case 'R':
                    $canvas?->drawRectangle(...$parts);
                    break;
                case 'B':
                    $canvas?->bucketFill(...$parts);
                    break;
            }
        }

        $output = $canvas?->render() ?? '';
        //  Ruta del archivo generado: \storage\app\private
        Storage::disk('local')->put('output.txt', $output);
        return response($output);
    }
}
