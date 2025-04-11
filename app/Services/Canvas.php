<?php

namespace App\Service;

namespace App\Services;

class Canvas
{
    protected $width;
    protected $height;
    protected $matrix;

    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
        $this->matrix = array_fill(0, $height, array_fill(0, $width, ' '));
    }

    public function drawLine($x1, $y1, $x2, $y2)
    {
        if ($x1 == $x2) { // vertical
            for ($y = min($y1, $y2) - 1; $y < max($y1, $y2); $y++) {    //   iterar sobre un rango de valores en la dirección del eje Y
                $this->matrix[$y][$x1 - 1] = 'x';
            }
        } elseif ($y1 == $y2) { // horizontal
            for ($x = min($x1, $x2) - 1; $x < max($x1, $x2); $x++) {    //  recorrer un rango de valores en el eje X
                $this->matrix[$y1 - 1][$x] = 'x';
            }
        }
    }

    public function drawRectangle($x1, $y1, $x2, $y2)
    {
        $this->drawLine($x1, $y1, $x2, $y1); // top
        $this->drawLine($x1, $y2, $x2, $y2); // bottom
        $this->drawLine($x1, $y1, $x1, $y2); // left
        $this->drawLine($x2, $y1, $x2, $y2); // right
    }

    public function bucketFill($x, $y, $color)
    {
        $x--; $y--; // Ajuste de coordenadas (probablemente porque el sistema es 1-based, y la matriz es 0-based)
        $targetColor = $this->matrix[$y][$x];   // Color actual en la posición (x, y)
        if ($targetColor === $color || $targetColor === 'x') return;    // Si ya tiene el color deseado o es un borde ('x'), no se hace nada

        $this->floodFill($x, $y, $targetColor, $color); // Se llama a floodFill para comenzar el relleno
    }

    private function floodFill($x, $y, $targetColor, $newColor)
    {
        if ($x < 0 || $y < 0 || $x >= $this->width || $y >= $this->height) return;
        if ($this->matrix[$y][$x] !== $targetColor) return;

        $this->matrix[$y][$x] = $newColor;

        $this->floodFill($x + 1, $y, $targetColor, $newColor);
        $this->floodFill($x - 1, $y, $targetColor, $newColor);
        $this->floodFill($x, $y + 1, $targetColor, $newColor);
        $this->floodFill($x, $y - 1, $targetColor, $newColor);
    }

    public function render()
    {
        $lines = array_map(fn($row) => implode('', $row), $this->matrix);
        return implode("\n", $lines);
    }
}


?>
