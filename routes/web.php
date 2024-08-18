<?php
use Smalot\PdfParser\Parser;
use App\Models\Paciente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Evolucao;
use Gemini\Laravel\Facades\Gemini;
use GuzzleHttp\Client;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::match(['get'], '/', function (Request $request) {
    //ver a home
    return view('home');
})->name("/")->middleware('autenticador');


Route::match(['get'], '/home', function (Request $request) {
    //ver a home
    return view('home');
})->name("home")->middleware('autenticador');



Route::match(['get', 'post'], '/nutri_maca', function (Request $request) {

    if ($request->isMethod('get')) {
        // If it's a GET request, return the 'nutri_maca' view.
        return view('nutri_maca');
    } else {
        // If it's a POST request, process the form data.
        $pergunta = $request->input('pergunta');
        $anamnesia = $request->input('anamnesia');

        if ($request->hasfile('anamnesia')) {
            try {
                // Validate the uploaded file (anamnesia) if it exists.
                $request->validate([
                    'anamnesia' => 'nullable|mimes:pdf|max:300',
                ]);
                $anamnesia = $request->file('anamnesia');
                $parser = new Parser();
                $pdf = $parser->parseFile($anamnesia->getPathname());
                $text = $pdf->getText();
            } catch (\Throwable $e) {
                // Handle any validation or parsing errors.
                return redirect()->route('pacientes')->with([
                    'error' => 'pdf inválido ou tamanho de 300kb ultrapassado',
                ]);
            }
        }
        try {
            if ($request->hasfile('anamnesia')) {
                // If anamnesia file exists, generate content using Gemini API with pergunta and text.
                $result = Gemini::geminiPro()
                    ->generateContent([
                        "Voce se chama Nutri maça e foi feita para analisar anamneses de pacientes, como fixas de anamneses completas, e responder perguntas sobre nutrição, dando dicas e explicando o que voce. Caso a pergunta não seja sobre nutrição, responda que não pode resolver a pergunta e diga para que voce foi feita. Caso voçê receba uma anamnesia, comente o que voçe consegue analisar sobre e sujira algumas coisas útris. Nunca responda nada em negrito ou em tópicos. sempre responda me portugues.NÃO RESPONDA NADA EM NEGRITO OU EM TÓPICOS.a pergunta que voce tem que responder é: ",
                        $pergunta,
                        "minha anamnese é: ",
                        $text,
                    ]);
            } else {
                // If anamnesia file doesn't exist, generate content using Gemini API with pergunta only.
                $result = Gemini::geminiPro()->generateContent(
                    "Voce se chama Nutri maça e foi feita para analisar anamneses de pacientes, como fixas de anamneses completas, e responder perguntas sobre nutrição, dando dicas e explicando o que voce. Caso a pergunta não seja sobre nutrição, responda que não pode resolver a pergunta e diga para que voce foi feita. Caso voçê receba uma anamnesia, comente o que voçe consegue analisar sobre e sujira algumas coisas útris. Nunca responda nada em negrito ou em tópicos. sempre responda me portugues.NÃO RESPONDA NADA EM NEGRITO OU EM TÓPICOS.a pergunta que voce tem que responder é: ",
                    $pergunta,
                );
            }
            $resposta = $result->text();
            $resposta = str_replace('*', '', $resposta);

        } catch (\Throwable $e) {
            // Handle any errors that occur during content generation.
            $resposta = "Estou com problemas, me desculpe :(";
            Log::error('Erro ao chamar a API do Gemini: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
        }
        // Return the 'nutri_maca' view with the generated response and pergunta.
        return view('nutri_maca', compact('resposta', 'pergunta'));
    }
})->name("nutri_maca")->middleware('autenticador');



Route::match(['get', 'post'], '/ferramentas_nutri', function (Request $request) {
    if ($request->isMethod('post')) {
        try {
            // Validate the form data
            $request->validate([
                'sexo' => 'required',
                'idade' => 'required',
                'altura' => 'required',
                'peso' => 'required',
                'atividade_fisica' => 'required',
            ]);
        } catch (ValidationException $e) {
            // If validation fails, redirect back to the form with an error message
            return redirect()->route('ferramentas_nutri')->with([
                'error' => 'Preencha todos os campos',
            ]);
        }
        // Get the form data
        $sexo = $request->input('sexo');
        $idade = $request->input('idade');
        $altura = $request->input('altura');

        $tmbmetodo = 'Harris-Benedict';
        $getmetodo = '';
        if ($idade < 18) {
            $metodo_input = $request->input('metodoa');
            $getmetodo = 'DRI/IOM';
            if ($metodo_input == 'D') {
                if ($idade >= 3)
                    $tmbmetodo = 'DRI/IOM';
                else
                    $tmbmetodo = 'scholfield';
            } else if ($metodo_input == 'F') {
                $tmbmetodo = 'FAO/OMS';
            } else if ($metodo_input == 'S') {
                $tmbmetodo = 'scholfield';
            } else {
                // If an invalid method is chosen, redirect back to the form with an error message
                return redirect()->route('ferramentas_nutri')->with([
                    'error' => 'Escolha um método válido',
                ]);
            }
        }
        $peso = $request->input('peso');
        $atividade_fisica = $request->input('atividade_fisica');
        $imc = round(imc($peso, $altura), 2);
        $condicao = condicao($imc);
        $fa = round(fa($idade, $atividade_fisica, $sexo, $condicao), 2);
        $tmb = round(tmb($peso, $altura, $idade, $sexo, $tmbmetodo, $fa, $condicao, $imc), 2);
        $get = round(get($idade, $peso, $altura, $sexo, $fa, $condicao, $getmetodo, $tmb), 2);
        list($id, $indice) = indice_imc($imc, $sexo, $idade);

        // Return the 'avulso' view with the calculated values
        return view('avulso', compact('imc', 'tmb', 'get', 'fa', 'tmb', 'id', 'indice'));

    } else if ($request->isMethod('get')) {
        // Check if the user is a nutritionist or a patient and return the appropriate view
        if (session('is_nutri') == false) {
            $user_id = Auth::user()->id;
            $paciente = Paciente::where('user_id', $user_id)->first();

            $atividade_fisica = $paciente->fa;
            $imc = round(imc($paciente->peso, $paciente->altura), 2);
            $condicao = condicao($imc);

            $fa = round(fa($paciente->idade, $atividade_fisica, $paciente->sexo, $condicao), 2);
            $tmb = round(tmb($paciente->peso, $paciente->altura, $paciente->idade, $paciente->sexo, 'Harris-Benedict', $fa, $condicao, $imc), 2);
            $get = round(get($paciente->idade, $paciente->peso, $paciente->altura, $paciente->sexo, $fa, $condicao, 'normal', $tmb), 2);
            list($id, $indice) = indice_imc($imc, $paciente->sexo, $paciente->idade);

            return view('ferramentas_n', compact('paciente', 'imc', 'tmb', 'get', 'tmb', 'id', 'indice'));
        } else {
            $pacientes = Paciente::where('nutri_id', Auth::user()->id)->get();
            return view('ferramentas_n', compact('pacientes'));
        }
    }
})->name('ferramentas_nutri')->middleware('autenticador');


// This route handles the '/analise' endpoint for POST requests.
Route::match(['post'], '/analise', function (Request $request) {
    if ($request->isMethod('post')) {
        try {
            $request->validate([
                'paciente' => 'required',
            ]);
        } catch (ValidationException $e) {
            return redirect()->route('ferramentas_nutri')->with([
                'error' => 'Escolha um paciente',
            ]);
        }
        $paciente_id = $request->input('paciente');
        $paciente = Paciente::where('id', $paciente_id)->WHERE('nutri_id', Auth::user()->id)->first();
        // It validates the 'paciente' input and retrieves the patient's data.
        if ($paciente) {
            $nome = $paciente->nome;
            $sexo = $paciente->sexo;
            $idade = $paciente->idade;
            $altura = $paciente->altura;
            $peso = $paciente->peso;
            $atividade_fisica = $paciente->fa;
            $imc = round(imc($peso, $altura), 2);
            $condicao = condicao($imc);
            $fa = round(fa($idade, $atividade_fisica, $sexo, $condicao), 2);

            $tmbmetodo = 'Harris-Benedict';
            $getmetodo = '';
            if ($idade < 18) {
                $metodo_input = $request->input('metodo');
                $getmetodo = 'DRI/IOM';
                if ($metodo_input == 'D') {
                    if ($idade >= 3)
                        $tmbmetodo = 'DRI/IOM';
                    else
                        $tmbmetodo = 'scholfield';
                } else if ($metodo_input == 'F') {
                    $tmbmetodo = 'FAO/OMS';
                } else if ($metodo_input == 'S') {
                    $tmbmetodo = 'scholfield';
                } else {
                    return redirect()->route('ferramentas_nutri')->with([
                        'error' => 'Escolha um método válido',
                    ]);

                }
            }
            $tmb = round(tmb($peso, $altura, $idade, $sexo, $tmbmetodo, $fa, $condicao, $imc), 2);
            $get = round(get($idade, $peso, $altura, $sexo, $fa, $condicao, $getmetodo, $tmb), 2);
            list($id, $indice) = indice_imc($imc, $sexo, $idade);
        } else {
            return redirect()->route('ferramentas_paciente')->with([
                'error' => 'Paciente não encontrado',
            ]);
        }
        if ($sexo == 1) {
            $sexo = 'Masculino';
        } else {
            $sexo = 'Feminino';
        }
        return view('analise', compact('nome', 'altura', 'peso', 'fa', 'idade', 'sexo', 'imc', 'tmb', 'get', 'fa', 'tmb', 'id', 'indice'));
    }

})->name('analise')->middleware('autenticador');


Route::match(['get', 'post'], '/pacientes', function (Request $request) {

    if (session('is_nutri') == false) {
        return redirect()->route('home')->with([
            'error' => 'Acesso negado',
        ]);
    }

    if ($request->isMethod('post')) {
        try {
            // Validate the form data
            $request->validate([
                'nome' => 'required',
                'sexo' => 'required',
                'idade' => 'required',
                'altura' => 'required',
                'peso' => 'required',
                'atividade_fisica' => 'required',
            ]);
        } catch (ValidationException $e) {
            return redirect()->route('pacientes')->with([
                'error' => 'Preencha os campos',
            ]);
        }

        // Retrieve the form data
        $nome = $request->input('nome');
        $sexo = $request->input('sexo');
        $idade = $request->input('idade');
        $altura = $request->input('altura');
        $peso = $request->input('peso');
        $atividade_fisica = $request->input('atividade_fisica');

        // Validate the input values
        if ($idade == null || $altura == null || $peso == null) {
            return redirect()->route('pacientes')->with([
                'error' => 'Idade, altura e peso devem ser preenchidos',
            ]);
        } else if ($idade < 0 || $idade > 120) {
            return redirect()->route('pacientes')->with([
                'error' => 'Idade inválida',
            ]);
        } else if ($altura < 100 || $altura > 250) {
            return redirect()->route('pacientes')->with([
                'error' => 'Altura inválida',
            ]);
        } else if ($peso < 10 || $peso > 250) {
            return redirect()->route('pacientes')->with([
                'error' => 'Peso inválido',
            ]);
        }

        // Validate the 'atividade_fisica' input
        if ($atividade_fisica != '1' && $atividade_fisica != '2' && $atividade_fisica != '3' && $atividade_fisica != '4') {
            return redirect()->route('pacientes')->with([
                'error' => 'Atividade física inválida',
            ]);
        } else {
            $atividade_fisica = intval($atividade_fisica);
        }

        // Validate the 'nome' input
        if (strlen($nome) < 3 || strlen($nome) > 20) {
            return redirect()->route('pacientes')->with([
                'error' => 'Nomes devem ter no mínimo 3 e no máximo 20 letras',
            ]);
        }

        // Validate the 'sexo' input
        if ($sexo != 'M' && $sexo != 'F') {
            return redirect()->route('pacientes')->with([
                'error' => 'Sexo inválido',
            ]);
        } else if ($sexo == 'M') {
            $sexo = true;
        } else if ($sexo == 'F') {
            $sexo = false;
        }

        try {
            // Create a new patient record
            $paciente = Paciente::create([
                'nutri_id' => Auth::user()->id,
                'nome' => $nome,
                'idade' => $idade,
                'altura' => $altura,
                'peso' => $peso,
                'sexo' => $sexo,
                'fa' => $atividade_fisica,
                'anaminesia' => null,
                'user_id' => null,
            ]);

            // Create a new evolution record
            $evolucao = Evolucao::create([
                'paciente' => $paciente->id,
                'peso' => $peso,
                'data' => date('Y-m-d'),
            ]);
        } catch (\Exception $e) {
            return redirect()->route('pacientes')->with([
                'error' => 'Erro'
            ]);
        }

        // Handle the 'anaminesia' file upload
        if ($request->hasFile('anaminesia')) {
            $pdf = $request->file('anaminesia');
            $pdfContent = file_get_contents($pdf->getPathname());

            // Save the PDF content to the 'anaminesia' field
            $paciente->anaminesia = $pdfContent;
            try {
                $paciente->save();
            } catch (\Exception $e) {
                return redirect()->route('pacientes')->with([
                    'error' => 'Erro ao salvar o PDF: '
                ]);
            }
        } else {
            try {
                // Load a default PDF file
                $anaminesia_limpa = storage_path('app/public/anaminesia_limpa.pdf');
                $anaminesia = file_get_contents($anaminesia_limpa);
                $paciente->anaminesia = $anaminesia;
                $paciente->save();
            } catch (\Exception $e) {
                return redirect()->route('pacientes')->with([
                    'error' => 'erro'
                ]);
            }
        }

        return redirect()->route('pacientes')->with([
            'error' => 'Paciente cadastrado com sucesso',
        ]);
    }

    if ($request->isMethod('get')) {
        // Retrieve the list of patients
        $pacientes = Paciente::where('nutri_id', Auth::user()->id)->get();
        return view('pacientes', compact('pacientes'));
    }

})->name('pacientes')->middleware('autenticador');


// Define uma rota POST para 'analisar_paciente'. Esta rota é usada para realizar várias ações relacionadas a um paciente específico.
Route::post('analisar_paciente', function (Request $request) {

    $id = $request->input('paciente');
    $action = $request->input('action');

    $paciente = Paciente::where('id', $id)->WHERE('nutri_id', Auth::user()->id)->first();

    if ($paciente == null) {
        return redirect('pacientes')->with(['error' => 'Paciente não encontrado.']);
    }

    if ($action == 'baixar_anamnese') {
        if ($paciente->anaminesia != null) {
            return Response::make($paciente->anaminesia, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="anaminesia_' . $paciente->nome . '.pdf"',
            ]);
        } else {
            return redirect('pacientes')->with(['error' => 'Anamnese não encontrada.']);
        }
    }

    if ($action == 'evolucao') {
        return redirect('evolucao')->with('paciente', $paciente);
    }

    if ($request->hasfile('action')) {
        try {
            $request->validate([
                'pdf' => 'nullable|mimes:pdf|max:300',
            ]);
        } catch (ValidationException $e) {
            return redirect()->route('pacientes')->with([
                'error' => 'pdf inválido',
            ]);
        }
        $pdf = $request->file('action');
        $pdfContent = file_get_contents($pdf->getPathname());
        $paciente->anaminesia = $pdfContent;
        try {
            $paciente->save();
        } catch (\Exception $e) {
            return redirect('pacientes')->with(['error' => 'Erro ao salvar o PDF: ']);
        }
        return redirect('pacientes')->with(['error' => 'Anamnese enviada com sucesso.']);
    }

    if ($action == 'deletar') {
        $evolucoes_paciente = Evolucao::Where('paciente', $paciente->id)->get();
        foreach ($evolucoes_paciente as $evolucao) {
            $evolucao->delete();
        }

        $paciente->delete();
        return redirect('pacientes')->with(['error' => 'Paciente deletado com sucesso.']);
    }

    if ($action == 'atualizar_dados') {
        return redirect('atualizar_dados')->with('paciente', $paciente);
    }

})->name('analisar_paciente')->middleware('autenticador');


Route::match(['get', 'post'], '/atualizar_dados', function (Request $request) {
    if ($request->isMethod('post')) {
        if (session('is_nutri') == true) {
            $paciente = session()->get('paciente');
            $request->session()->flash('paciente', $paciente);
        } else {
            $paciente = Paciente::where('user_id', Auth::user()->id)->first();
        }
        try {
            $request->validate([
                'nome' => 'required',
                'idade' => 'required',
                'altura' => 'required',
                'peso' => 'required',
                'sexo' => 'required',
                'fa' => 'required',
                'anaminesia' => 'nullable|mimes:pdf|max:300',
            ]);
        } catch (ValidationException $e) {
            return redirect()->route('atualizar_dados')->with([
                'error' => 'Preencha todos os campos',
            ]);
        }
        $nome = $request->input('nome');
        $idade = $request->input('idade');
        $altura = $request->input('altura');
        $peso = $request->input('peso');
        $sexo = $request->input('sexo');
        $fa = $request->input('fa');

        if ($idade == null || $altura == null || $peso == null) {
            return redirect()->route('atualizar_dados')->with([
                'error' => 'Idade, altura e peso devem ser preenchidos',
            ]);
        } else if ($idade < 0 || $idade > 120) {
            return redirect()->route('atualizar_dados')->with([
                'error' => 'Idade inválida',
            ]);
        } else if ($altura < 100 || $altura > 250) {
            return redirect()->route('atualizar_dados')->with([
                'error' => 'Altura inválida',
            ]);
        } else if ($peso < 10 || $peso > 250) {
            return redirect()->route('atualizar_dados')->with([
                'error' => 'Peso inválido',
            ]);
        }
        if ($fa != '1' && $fa != '2' && $fa != '3' && $fa != '4') {
            return redirect()->route('atualizar_dados')->with([
                'error' => 'Atividade física inválida',
            ]);
        } else {
            $fa = intval($fa);
        }

        if (strlen($nome) < 3 || strlen($nome) > 20) {
            return redirect()->route('atualizar_dados')->with([
                'error' => 'Nomes devem ter no mínimo 3 e no máximo 20 letras',
            ]);
        }

        if ($sexo != 'M' && $sexo != 'F') {
            return redirect()->route('atualizar_dados')->with([
                'error' => 'Sexo inválido',
            ]);
        } else if ($sexo == 'M') {
            $sexo = true;
        } else if ($sexo == 'F')
            $sexo = false;

        $paciente->nome = $nome;
        $paciente->idade = $idade;
        $paciente->altura = $altura;
        $paciente->peso = $peso;
        $paciente->sexo = $sexo;
        $paciente->fa = $fa;
        try {
            $evolucao = Evolucao::create([
                'paciente' => $paciente->id,
                'peso' => $peso,
                'data' => date('Y-m-d'),
            ]);
            $paciente->save();
        } catch (\Exception $e) {
            if (session('is_nutri') == true) {
                return redirect()->route('atualizar_dados')->with([
                    'error' => 'Erro ao salvar os dados: ',
                ]);
            } else {
                return redirect()->route('evolucao')->with([
                    'error' => 'Erro ao atualizar dados'
                ]);
            }
        }
        if (session('is_nutri') == true) {
            return redirect()->route('pacientes')->with([
                'error' => 'Dados atualizados com sucesso',
            ]);
        } else if (session('is_nutri') == false) {
            return redirect()->route('evolucao')->with([
                'error' => 'Dados atualizados com sucesso',
            ]);
        }

    } elseif ($request->isMethod('get')) {
        $paciente = $request->session()->get('paciente');
        if ($paciente) {
            $request->session()->flash('paciente', $paciente);
        } else {
            return redirect()->route('pacientes')->with([
                'error' => 'Paciente não encontrado',
            ]);
        }
        $nome = $paciente->nome;
        $idade = $paciente->idade;
        $altura = $paciente->altura;
        $peso = $paciente->peso;
        $sexo = $paciente->sexo;
        $fa = $paciente->fa;
        return view('atualizar_dados', compact('paciente', 'nome', 'idade', 'altura', 'peso', 'sexo', 'fa'));
    }

})->name('atualizar_dados')->middleware('autenticador');


Route::name('regis.')->group(function () {

    // Route for both GET and POST requests to '/email'
    Route::match(['get', 'post'], '/email', function (Request $request) {
        if ($request->isMethod('post')) {
            try {
                $request->validate([
                    'email' => 'required|email|unique:users',
                ]);
            } catch (ValidationException $e) {
                // Redirect with error if validation fails
                return redirect()->route('regis.email')->with(['error' => 'Email já existe']);
            }
            $email = $request->input('email');
            $apiKey = env('ZEROBOUCE_API_KEY');

            try {
                $client = new Client();
                $response = $client->request('GET', 'https://api.zerobounce.net/v2/validate', [
                    'query' => [
                        'api_key' => $apiKey,
                        'email' => $email,
                    ]
                ]);

                $result = json_decode($response->getBody(), true);

                if ($result['status'] === 'valid') {
                    $request->session()->put('email', $email);
                    return redirect()->route('regis.senha');
                } else {
                    return redirect()->route('regis.email')->with(['error' => 'Email inexistente']);
                }

            } catch (Exception $e) {
                return redirect('email')->with(['error' => 'Erro ao validar email']);
            }

        } elseif ($request->isMethod('get')) {
            // Return the registration view for GET requests
            return view('registre');
        }

    })->name('email'); // Name this route 'email'

    // Route for both GET and POST requests to '/senha'
    Route::match(['get', 'post'], '/senha', function (Request $request) {
        // Handle POST request
        if ($request->isMethod('post')) {
            // Retrieve data from session and request
            $email = $request->session()->get('email');
            $senha = $request->input('senha');
            $confirm = $request->input('confirma');
            $objetivo = $request->input('objetivo');
            // Store email and senha in session

            // Validate senha and confirm match, and senha length
            if ($senha != $confirm) {
                return redirect()->route('regis.senha')->with(['error' => 'Senhas não conferem']);
            } else if (strlen($senha) < 8) {
                return redirect()->route('regis.senha')->with(['error' => 'O minimo são 8 caracteres']);
            }
            // Validate objetivo value
            if ($objetivo != 'n' && $objetivo != 'p') {
                return redirect()->route('regis.senha')->with(['error' => 'Objetivo inválido',]);
            }
            // Store objetivo in session
            // Redirect based on objetivo value
            $request->session()->put('senha', $senha);
            $request->session()->put('objetivo', $objetivo);

            if ($objetivo == 'p') {
                return redirect()->route('regis.dados');
            }
            if ($objetivo == 'n') {
                return redirect()->route('regis.nutri');
            }

        } else if ($request->isMethod('get')) {
            // Handle GET request, return view with email
            $email = $request->session()->get('email');
            return view('registre2', compact('email'));
        }

    })->name('senha')->middleware('check.email.session'); // Name this route 'senha' and apply middleware

    // Route for both GET and POST requests to '/nutri'
    Route::match(['get', 'post'], '/nutrix', function (Request $request) {
        // Handle POST request
        if ($request->isMethod('post')) {
            // Validate required fields
            try {
                $request->validate([
                    'primeiro' => 'required',
                    'ultimo' => 'required',
                    'sexo' => 'required',
                ]);
            } catch (ValidationException $e) {
                // Redirect with error if validation fails
                return redirect()->route('regis.nutri')->with(['error' => 'Preencha todos os campos']);
            }
            // Retrieve data from session and request
            $email = $request->session()->get('email');
            $senha = $request->session()->get('senha');
            $request->session()->flash('email', $email);
            $primeiro_nome = $request->input('primeiro');
            $ultimo_nome = $request->input('ultimo');
            $sexo = $request->input('sexo');
            // Validate nome and sexo fields
            if (strlen($primeiro_nome) < 3 || strlen($ultimo_nome) < 3 || strlen($primeiro_nome) > 20 || strlen($ultimo_nome) > 20) {
                return redirect()->route('regis.nutri')->with(['error' => 'Nome e sobrenome devem ter no mínimo 3 e no máximo 20 letras']);
            }
            if ($sexo != 'M' && $sexo != 'F') {
                return redirect()->route('regis.nutri')->with(['error' => 'Sexo inválido']);
            }
            // Convert sexo to boolean
            if ($sexo == 'M') {
                $sexo = true;
            }
            if ($sexo == 'F') {
                $sexo = false;
            }
            $nome_completo = $primeiro_nome . ' ' . $ultimo_nome;

            // Attempt to create user and handle exceptions
            try {
                $user = User::create([
                    'name' => $nome_completo,
                    'email' => $email,
                    'password' => $senha,
                    'sexo' => $sexo,
                    'is_nutri' => true,
                    'codigo' => gerarcodigo(),
                ]);

            } catch (\Exception $e) {
                return redirect()->route('regis.nutri')->with(['error' => 'Email já cadastrado']);
            }
            // Redirect to login route on success
            return redirect()->route('login')->with(['error' => 'Registrado com sucesso!']);
            $request->session()->forget(['email', 'senha', 'objetivo']);
        }
        // Handle GET request
        if ($request->isMethod('get')) {
            // Redirect if objetivo is not 'n'
            if ($request->session()->get('objetivo') != 'n') {
                $request->session()->forget(['objetivo', 'senha']);
                return redirect()->route('regis.senha')->with(['error' => 'Acesso negado']);
            }

            // Return view for nutri registration
            return view('registre_n');

        }
    })->name('nutri')->middleware('check.email.session')->middleware('check.senha.session'); // Name this route 'nutri' and apply middleware

    // Define uma rota que aceita os métodos GET e POST para o caminho '/dados'
    Route::match(['get', 'post'], '/dados', function (Request $request) {
        // Verifica se o método da requisição é POST
        if ($request->isMethod('post')) {
            try {
                // Valida os campos obrigatórios do formulário
                $request->validate([
                    'primeiro' => 'required',
                    'ultimo' => 'required',
                    'sexo' => 'required',
                    'idade' => 'required',
                    'altura' => 'required',
                    'peso' => 'required',
                ]);
            } catch (ValidationException $e) {
                // Redireciona para a rota 'regis.dados' com uma mensagem de erro se a validação falhar
                return redirect()->route('regis.dados')->with([
                    'error' => 'Preencha todos os campos',
                ]);
            }

            // Recupera dados da sessão
            $email = $request->session()->get('email');
            $senha = $request->session()->get('senha');

            // Recupera dados do formulário
            $codigo = $request->input('codigo');
            $primeiro_nome = $request->input('primeiro');
            $ultimo_nome = $request->input('ultimo');
            $sexo = $request->input('sexo');
            $fa = $request->input('atividade_fisica');
            $idadeInput = $request->input('idade');
            $idade = is_numeric($idadeInput) ? round(intval($idadeInput), 0) : null;
            $alturaInput = $request->input('altura');
            $altura = is_numeric($alturaInput) ? round(floatval($alturaInput), 0) : null;
            $pesoInput = $request->input('peso');
            $peso = is_numeric($pesoInput) ? round(floatval($pesoInput), 2) : null;

            if ($altura == null || $peso == null) {
                return redirect()->route('regis.dados')->with([
                    'error' => 'Idade, altura e peso devem ser preenchidos',
                ]);
            } else if ($idade < 0 || $idade > 120) {
                return redirect()->route('regis.dados')->with([
                    'error' => 'Idade inválida',
                ]);
            } else if ($altura < 100 || $altura > 250) {
                return redirect()->route('regis.dados')->with([
                    'error' => 'Altura inválida',
                ]);
            } else if ($peso < 10 || $peso > 250) {
                return redirect()->route('regis.dados')->with([
                    'error' => 'Peso inválido',
                ]);
            }
            // Validação para o campo de atividade física.
            if ($fa != '1' && $fa != '2' && $fa != '3' && $fa != '4') {
                return redirect()->route('regis.dados')->with([
                    'error' => 'Atividade física inválida',
                ]);
            } else {
                $fa = intval($fa);
            }

            // Validação para o campo nome.
            if (strlen($primeiro_nome) < 3 || strlen($primeiro_nome) > 20 || strlen($ultimo_nome) < 3 || strlen($ultimo_nome) > 20) {
                return redirect()->route('regis.dados')->with([
                    'error' => 'Nomes devem ter no mínimo 3 e no máximo 20 letras',
                ]);
            } else {
                $nome_completo = $primeiro_nome . ' ' . $ultimo_nome;
            }

            // Validação para o campo sexo.
            if ($sexo != 'M' && $sexo != 'F') {
                return redirect()->route('regis.dados')->with([
                    'error' => 'Sexo inválido',
                ]);
            } else if ($sexo == 'M') {
                $sexo = true;
            } else if ($sexo == 'F')
                $sexo = false;

            try {
                // Cria um novo usuário
                DB::beginTransaction();
                $user = User::create([
                    'name' => $nome_completo,
                    'email' => $email,
                    'password' => $senha, // Será automaticamente hashada
                    'sexo' => $sexo,
                    'codigo' => $codigo,
                ]);

                // Cria um novo paciente
                $paciente = Paciente::create([
                    'nutri_id' => null,
                    'user_id' => $user->id,
                    'nome' => $nome_completo,
                    'idade' => $idade,
                    'altura' => $altura,
                    'peso' => $peso,
                    'sexo' => $sexo,
                    'anamnese' => null,
                    'fa' => $fa,
                ]);

                // Cria um novo registro de evolução para o paciente
                $paciente_id = Paciente::where('user_id', $user->id)->first()->id;
                $evolucao = Evolucao::create([
                    'paciente' => $paciente_id,
                    'peso' => $peso,
                    'data' => date('Y-m-d'),
                ]);
                if ($codigo != null) {
                    if (strlen($codigo) != 10) {
                        return redirect()->route('regis.dados')->with([
                            'error' => 'Código inválido'
                        ]);
                    }
                    $nutri = User::where('codigo', $codigo)->where('is_nutri', true)->first();
                    if ($nutri == null) {
                        return redirect()->route('regis.dados')->with([
                            'error' => 'Código da Nutricionista não encontrado'
                        ]);
                    } else {
                        $nutri_id = $nutri->id;
                    }
                    $paciente->nutri_id = $nutri_id;
                    $paciente->save();
                }
                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                // Captura exceções, como email já cadastrado
                return redirect()->route('regis.dados')->with([
                    'error' => 'Email já cadastrado'
                ]);
            }

            session()->forget(['email', 'senha', 'objetivo']);
            return redirect()->route('login')->with([
                'error' => 'Registrado com sucesso!',
            ]);

        } else if ($request->isMethod('get')) {
            if ($request->session()->get('objetivo') != 'p') {
                session()->forget(['objetivo', 'senha']);
                return redirect()->route('regis.senha')->with([
                    'error' => 'Acesso negado',
                ]);
            }

            $email = $request->session()->get('email');
            return view('registre3', compact('email'));
        }
    })->name('dados')->middleware('check.email.session')->middleware('check.senha.session');

});


// Define a route that responds to GET requests on '/evolucao'
Route::match(['get'], '/evolucao', function (Request $request) {
    // Check if the request method is GET
    if ($request->isMethod('get')) {
        // Check if the session indicates the user is a nutritionist
        if (session('is_nutri') == true) {
            // Retrieve the 'paciente' from session
            $paciente = $request->session()->get('paciente');
            // If a 'paciente' exists in the session
            if ($paciente) {
                // Flash the 'paciente' data for the next request
                $request->session()->flash('paciente', $paciente);
                // Retrieve the patient's name
                $nome = $paciente->nome;
                // Fetch evolution data for the patient from the database
                $evolucao = Evolucao::where('paciente', $paciente->id)->get();
                // Extract the 'peso' (weight) and 'data' (date) fields from the evolution records
                $pesos = $evolucao->pluck('peso');
                $datas = $evolucao->pluck('data');
                // Return the 'evolucao' view, passing the necessary data
                return view('evolucao', compact('paciente', 'pesos', 'datas', 'nome'));

            } else {
                // If no patient is found in the session, redirect to the 'pacientes' route with an error message
                return redirect()->route('pacientes')->with([
                    'error' => 'Paciente não encontrado',
                ]);
            }
        } else {
            // If the session does not indicate a nutritionist, assume it's a patient accessing their own data
            // Authenticate the user
            $user = Auth::user();
            // Fetch the patient record associated with the authenticated user
            $paciente = Paciente::where('user_id', $user->id)->first();
            // Retrieve the patient's name
            $nome = $paciente->nome;
            // Fetch evolution data for the patient from the database
            $evolucao = Evolucao::where('paciente', $paciente->id)->get();
            // Extract the 'peso' (weight) and 'data' (date) fields from the evolution records
            $pesos = $evolucao->pluck('peso');
            $datas = $evolucao->pluck('data');
            // Return the 'evolucao' view, passing the necessary data
            return view('evolucao', compact('pesos', 'datas', 'nome', 'paciente'));

        }
    }
    // Name this route 'evolucao' and apply the 'autenticador' middleware for authentication
})->name('evolucao')->middleware('autenticador');

// Define a route that responds to GET requests on '/conta'. It displays user account information.
Route::match(['get'], '/conta', function (Request $request) {
    // Check if the request method is GET
    if ($request->isMethod('get')) {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Check if the user is a nutritionist
        if ($user->is_nutri == true) {
            // Retrieve the 'codigo' (code) for the nutritionist user
            $codigo = User::where('id', $user->id)->first()->codigo;
            // Return the 'conta' view for a nutritionist, passing the user and their code
            return view('conta', compact('user', 'codigo'));
        } else {
            // Return the 'conta' view for a regular user, passing only the user
            return view('conta', compact('user'));
        }
    }
    // Name this route 'conta' and apply the 'autenticador' middleware for authentication
})->name('conta')->middleware('autenticador');

// Define a route that responds to POST requests on '/deletar_conta'. It handles account deletion.
Route::match(['post'], '/deletar_conta', function (Request $request) {
    // Check if the request method is POST
    if ($request->isMethod('post')) {
        // Find the user by ID
        $user = User::Where("id", Auth::user()->id)->first();
        // Check if the user exists
        if ($user) {
            // Delete the user
            $user->delete();
            // Redirect to the 'logout' route with a success message
            return redirect()->route('logout')->with(['error' => 'Conta excluida']);
        } else {
            // Redirect back to the 'conta' route with an error message if user not found
            return redirect()->route('conta')->with(['error' => 'Erro ao excluir conta']);
        }
    }
    // Name this route 'deletar_conta' and apply the 'autenticador' middleware for authentication
})->name('deletar_conta')->middleware('autenticador');

// Define a route that responds to both GET and POST requests on '/login'. It handles user login.
Route::match(['get', 'post'], '/login', function (Request $request) {
    // Retrieve 'email' and 'senha' (password) from the request
    $email = $request->input('email');
    $senha = $request->input('senha');
    // Check if the request method is POST
    if ($request->isMethod('post')) {
        // Attempt to authenticate the user with the provided email and password
        if (Auth::attempt(['email' => $email, 'password' => $senha])) {
            // On successful login, create a unique session ID and store user ID and nutritionist status in the session
            Session::put('session_id', uniqid());
            Session::put('user_id', $request->user()->id);
            $user = Auth::user();
            $is_nutri = $user->is_nutri;
            Session::put('is_nutri', $is_nutri);
            // Redirect to the 'home' route with a success message
            return redirect()->route('home')->with([
                'error' => 'Você foi logado',
            ]);
        } else {
            // Redirect back to the 'login' route with an error message if authentication fails
            return redirect()->route('login')->with([
                'error' => 'Email e/ou senha inválidos',
            ]);
        }
    } elseif ($request->isMethod('get')) {
        // If the request method is GET, clear the 'session_id' from the session
        $request->session()->forget('session_id');
        // Return the 'login' view
        return view('login');
    }
    // Name this route 'login'
})->name('login');

// Define a route that responds to GET requests on '/logout'. It handles user logout.
Route::match(['get'], '/logout', function (Request $request) {
    // Clear all session data
    $request->session()->flush();
    // Redirect to the 'login' route with a success message
    return redirect()->route('login')->with([
        'error' => 'Deslogado com sucesso',
    ]);
    // Name this route 'logout'
})->name('logout');



