<?php
use App\Models\User;

if (!function_exists('imc')) {
    function imc($peso, $altura)
    {
        $altura = $altura / 100;
        $imc = $peso / ($altura * $altura);
        return round($imc, 2);
    }
}

if (!function_exists('tbm')) {
    function tmb($peso, $altura, $idade, $sexo, $modo, $fa, $condicao, $imc)
    {
        if ($modo == 'FAO/OMS') {
            if ($sexo == true) {
                if ($idade < 3) {
                    return 60.9 * $peso - 54;
                }
                if ($idade <= 10) {
                    return 22.7 * $peso + 495;
                }
                if ($idade < 18) {
                    return (17.5 * $peso) + 651;
                }
                return false;
            } else if ($sexo == false) {
                if ($idade < 3) {
                    return 61 * $peso - 51;
                }
                if ($idade <= 10) {
                    return 22.5 * $peso + 499;
                }
                if ($idade < 18) {
                    return 12.2 * $peso + 746;
                }
                return false;
            }
        }
        if ($modo == 'DRI/IOM') {
            $altura = $altura / 100;
            if ($sexo == true) {
                if ($idade >= 3 && $idade <= 10) {
                    if ($condicao == 'Eutrofico') {
                        return 68 - (43.3 * $idade) + (712 * $altura) + (19.2 * $peso);
                    }
                    if ($condicao == 'Sobrepeso' || $condicao == 'Obesidade') {
                        return 420 - (33.5 * $idade) + (418.9 * $altura) + (16.7 * $peso);
                    }
                }
                if ($idade < 18) {
                    if ($imc / $idade > 85) {
                        return 420 - (33.5 * $idade) + (418.9 * $altura) + (16.7 * $peso);
                    } else {
                        return 68 - (43.3 * $idade) + (712 * $altura) + (19.2 * $peso);
                    }
                }
            }
            if ($sexo == false) {
                if ($idade >= 3 && $idade <= 10) {
                    if ($condicao == 'Eutrófico') {
                        return 189 - (17.6 * $idade) + (625 * $altura) + (7.9 * $peso);
                    }
                    if ($condicao == 'Sobrepeso' || $condicao == 'Obesidade') {
                        return 516 - (26.8 * $idade) + (347 * $altura) + (12.4 * $peso);
                    }
                }
                if ($idade < 18) {
                    if ($imc / $idade > 85) {
                        return 516 - (26.8 * $idade) + (347 * $altura) + (12.4 * $peso);
                    } else {
                        return 189 - (17.6 * $idade) + (625 * $altura) + (7.9 * $peso);
                    }
                }
            }
        }
        if ($modo == 'scholfield') {
            if ($sexo == true) {
                if ($idade <= 3) {
                    return (0.167 * $peso) + (15.174 * $altura) - 617.6;
                }
                if ($idade <= 10) {
                    return (19.59 * $peso) + (1.303 * $altura) + 414.9;
                }
                if ($idade < 18) {
                    return (16.25 * $peso) + (1.372 * $altura) + 515.5;
                }
            }
            if ($sexo == false) {
                if ($idade <= 3) {
                    return (16.252 * $peso) + (10.232 * $altura) - 413.5;
                }
                if ($idade <= 10) {
                    return (16.969 * $peso) + (1.618 * $altura) + 371.2;
                }
                if ($idade < 18) {
                    return (8.365 * $peso) + (4.65 * $altura) + 200;
                }
            }
        }
        if ($modo == 'Harris-Benedict' && $idade >= 18) {
            if ($sexo == true) {
                return 88.362 + (13.397 * $peso) + (4.799 * $altura) - (5.677 * $idade);
            }
            if ($sexo == false) {
                return 447.593 + (9.247 * $peso) + (3.098 * $altura) - (4.330 * $idade);
            }
        }
        return false;
    }
}

if (!function_exists('fa')) {
    function fa($idade, $atividade_fisica, $sexo, $condicao)
    {
        if ($idade <= 3) {
            return 0;
        }
        if ($sexo == true) {
            if ($condicao == 'Eutrófico') {
                if ($idade > 3 && $idade < 18) {
                    if ($atividade_fisica == 1) {
                        return 1.0;
                    }
                    if ($atividade_fisica == 2) {
                        return 1.13;
                    }
                    if ($atividade_fisica == 3) {
                        return 1.26;
                    }
                    if ($atividade_fisica == 4) {
                        return 1.42;
                    }
                }
            } else if ($condicao == 'Sobrepeso' || $condicao == 'Obesidade') {
                if ($idade > 3 && $idade < 18) {
                    if ($atividade_fisica == 1) {
                        return 1.0;
                    }
                    if ($atividade_fisica == 2) {
                        return 1.12;
                    }
                    if ($atividade_fisica == 3) {
                        return 1.24;
                    }
                    if ($atividade_fisica == 4) {
                        return 1.45;
                    }
                }
            }
        }
        if ($sexo == false) {
            if ($condicao == 'Eutrófico') {
                if ($idade > 3 && $idade < 18) {
                    if ($atividade_fisica == 1) {
                        return 1.0;
                    }
                    if ($atividade_fisica == 2) {
                        return 1.16;
                    }
                    if ($atividade_fisica == 3) {
                        return 1.31;
                    }
                    if ($atividade_fisica == 4) {
                        return 1.56;
                    }
                }
            } else if ($condicao == 'Sobrepeso' || $condicao == 'Obesidade') {
                if ($idade > 3 && $idade < 18) {
                    if ($atividade_fisica == 1) {
                        return 1.0;
                    }
                    if ($atividade_fisica == 2) {
                        return 1.18;
                    }
                    if ($atividade_fisica == 3) {
                        return 1.35;
                    }
                    if ($atividade_fisica == 4) {
                        return 1.60;
                    }
                }
            }
        }
        if ($idade >= 18) {
            if ($atividade_fisica == 1) {
                return 1.2;
            }
            if ($atividade_fisica == 2) {
                return 1.375;
            }
            if ($atividade_fisica == 3) {
                return 1.55;
            }
            if ($atividade_fisica == 4) {
                return 1.725;
            }
        }
        return false;
    }
}

if (!function_exists('get')) { //kcal
    function get($idade, $peso, $altura, $sexo, $fa, $condicao, $modo, $tmb)
    {
        if ($modo == 'DRI/IOM') {
            $altura = $altura / 100;
            if ($idade >= 0 && $idade < 0.4) {
                return (89 * $peso) - 100 + 175;
            }
            if ($idade <= 0.4 && $idade < 0.7) {
                return (89 * $peso) - 100 + 56;
            }
            if ($idade <= 0.7 && $idade < 1) {
                return (89 * $peso) - 100 + 22;
            }
            if ($idade <= 1 && $idade <= 3) {
                return (89 * $peso) - 100 + 20;
            }
            if ($sexo == true) {
                if ($condicao == 'Eutrófico') {
                    if ($idade > 3 && $idade < 9) {
                        return 88.5 - (61.9 * $idade) + $fa * ((26.7 * $peso) + (903 * $altura)) + 20;
                    }
                    if ($idade >= 9 && $idade <= 18) {
                        return 88.5 - (61.9 * $idade) + $fa * ((26.7 * $peso) + (903 * $altura)) + 25;
                    }
                } else if ($condicao == 'Sobrepeso' || $condicao == 'Obesidade') {
                    if ($idade > 3 && $idade < 10) {
                        return 114 - (50.9 * $idade) + $fa * ((19.5 * $peso) + (1161.4 * $altura));
                    }
                }
            }
            if ($sexo == false) {
                if ($condicao == 'Eutrófico') {
                    if ($idade > 3 && $idade < 9) {
                        return 135.3 - (30.8 * $idade) + $fa * ((10 * $peso) + (934 * $altura)) + 20;
                    }
                    if ($idade >= 9 && $idade <= 18) {
                        return 135.3 - (30.8 * $idade) + $fa * ((10 * $peso) + (934 * $altura)) + 25;
                    }
                } else if ($condicao == 'Sobrepeso' || $condicao == 'Obesidade') {
                    if ($idade > 3 && $idade <= 18) {
                        return 389 - (41.2 * $idade) + $fa * ((15 * $peso) + (701.6 * $altura));
                    }
                }
            }
        }
        return $tmb * $fa;
    }
}


if (!function_exists('condicao')) {
    function condicao($imc)
    {
        if ($imc < 24.9) {
            return 'Eutrófico';
        }
        if ($imc >= 25 && $imc < 29.9) {
            return 'Sobrepeso';
        }
        if ($imc >= 30) {
            return 'Obesidade';
        }
    }
}

if (!function_exists('gerarcodigo')) {
    function gerarcodigo()
    {
        do {
            $codigo = Str::random(10); // Gera uma string aleatória de 10 caracteres
        } while (User::where('codigo', $codigo)->where('is_nutri', true)->exists()); // Verifica se já existe
        return $codigo;
    }
}

if (!function_exists('indice_imc')) {
    function indice_imc($imc, $sexo, $idade)
    {
        if ($idade >= 60) {
            if ($imc < 22) {
                return ['0', 'Baixo peso'];
            }
            if ($imc <= 27) {
                return ['1', 'Eutrofia (adequado)'];
            }
            if ($imc < 30) {
                return ['2', 'Sobrepeso'];
            }
            if ($imc >= 30) {
                return ['3', 'Obesidade'];
            }
        } else if ($idade >= 18 && $idade < 60) {
            if ($imc < 16) {
                return ['m', 'Magreza grau III'];
            }
            if ($imc < 17) {
                return ['f', 'Magreza grau II'];
            }
            if ($imc < 18.5) {
                return ['f', 'Magreza grau I'];
            }
            if ($imc < 25) {
                return ['n', 'Eutrofia (adequado)'];
            }
            if ($imc < 30) {
                return ['p', 'Pré-obesidade'];
            }
            if ($imc < 35) {
                return ['o', 'Obesidade moderada(grau I)'];
            }
            if ($imc < 40) {
                return ['t', 'Obesidade severa(grau II)'];
            }
            if ($imc >= 40) {
                return ['t', 'Obesidade muito severa(grau III)'];
            }
        } else {
            if ($imc < 18.5) {
                return ['f', 'Baixo peso'];
            }
            if ($imc >= 18.5 && $imc < 24.9) {
                return ['n', 'Eutrofia (peso adequado)'];
            }
            if ($imc >= 25 && $imc < 29.9) {
                return ['p', 'Sobrepeso'];
            }
            if ($imc >= 30 && $imc < 34.9) {
                return ['o', 'Obesidade moderada(grau I)'];
            }
            if ($imc >= 35 && $imc < 39.9) {
                return ['t', 'Obesidade severa(grau II)'];
            }
            if ($imc >= 40) {
                return ['t', 'Obesidade muito severa(grau III)'];
            }
        }
    }
}










