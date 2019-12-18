<?php

trait EquationTrait
{
    public function equation()
    {
        $num1 = rand(1, 10);
        $num2 = rand(1, 10);

        switch (rand(1, 3)) {
            case 1:
                if ($num1 < $num2) {
                    $t = $num1;
                    $num1 = $num2;
                    $num2 = $t;
                }
                return [
                    'equation' => $num1 . ' - ' . $num2,
                    'result'   => $num1 - $num2,
                ];
                break;
            case 2:
                return [
                    'equation' => $num1 . ' + ' . $num2,
                    'result'   => $num1 + $num2,
                ];
                break;
            case 3:
                return [
                    'equation' => $num1 . ' * ' . $num2,
                    'result'   => $num1 * $num2,
                ];
                break;
        }
    }

}