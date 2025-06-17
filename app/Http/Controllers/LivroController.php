<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreUpdateLivrosCreate;

use Illuminate\Http\Request;
use App\Models\Livro;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

use App\Http\Requests\StoreUpdateLivro;

class LivroController extends Controller
{
    public readonly Livro $livro;

    public function __construct()
    {
        $this->livro = new Livro();
    }

    public function index(): JsonResponse
    {
        $livros = Livro::with('autor')
            ->orderBy('id')
            ->get()
            ->map(function ($livro) {
                return [
                    'id' => $livro->id,
                    'titulo' => $livro->titulo,
                    'categoria' => $livro->categoria,
                    'editora' => $livro->editora,
                    'preco' => $livro->preco,
                    'data' => Carbon::parse($livro->data)->format('d/m/Y'),
                    'autor' => [
                        'id' => $livro->autor->id ?? null,
                        'nome' => $livro->autor->nome ?? null,
                    ],
                    'created_at' => $livro->created_at,
                    'updated_at' => $livro->updated_at,
                ];
            });

        return response()->json([
            'message' => $livros->isEmpty() ? 'Nenhum livro encontrado.' : 'Livros encontrados com sucesso.',
            'data' => $livros
        ]);
    }

    public function create()
    {
        return view('Create/livro_create');
    }

    public function store(StoreUpdateLivro $request)
    {


        $created = $this->livro->create([
            'titulo' => $request->input('titulo'),
            'autor_id' => $request->input('autor_id'),
            'editora' => $request->input('editora'),
            'data' => $request->input('data'),
            'categoria' => $request->input('categoria'),
            'preco' => $request->input('preco'),
        ]);

        if ($created) {
            return response()->json([
                'success' => true,
                'message' => 'Livro criado com sucesso!',
                'data' => [
                    ...$created->toArray(),
                    'data' => Carbon::parse($created->data)->format('d/m/Y'),
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erro ao criar!'
        ], 400);
    }

    public function show(Livro $livro)
    {
        return response()->json($livro);
    }

    public function edit(Livro $livro)
    {
        return view('Edit/livro_edit');
    }

    public function update(StoreUpdateLivro $request, string $id)
    {

        $updated = $this->livro->where('id', $id)->update($request->except(['_token', '_method']));

        if ($updated) {
            $livro = $this->livro->with('autor')->find($id);

            return response()->json([
                'success' => true,
                'message' => 'Livro atualizado com sucesso!',
                'data' => [
                    'id' => $livro->id,
                    'titulo' => $livro->titulo,
                    'categoria' => $livro->categoria,
                    'editora' => $livro->editora,
                    'preco' => $livro->preco,
                    'data' => Carbon::parse($livro->data)->format('d/m/Y'),
                    'autor' => [
                        'id' => $livro->autor->id ?? null,
                        'nome' => $livro->autor->nome ?? null,
                    ],
                    'created_at' => $livro->created_at,
                    'updated_at' => $livro->updated_at,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erro ao atualizar!'
        ], 400);
    }
    public function destroy(string $id)
    {
        $deleted = $this->livro->where('id', $id)->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Livro excluído com sucesso!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erro ao excluir!'
        ], 400);
    }
}