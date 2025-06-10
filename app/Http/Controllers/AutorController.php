<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Autor;
use App\Http\Requests\StoreUpdateAutor;

class AutorController extends Controller
{
    public readonly Autor $autor;

    public function __construct()
    {
        $this->autor = new Autor();
    }

    public function index()
    {
        $autores = Autor::all();
        return response()->json($autores);
    }

    public function create()
    {
        return view('Create/autor_create');
    }

    public function store(StoreUpdateAutor $request)
    {
        $created = $this->autor->create([
            'nome' => $request->input('nome'),
            'data_nascimento' => $request->input('data_nascimento'),
            'nacionalidade' => $request->input('nacionalidade'),
            'email' => $request->input('email'),
            'genero' => $request->input('genero'),
            'biografia' => $request->input('biografia'),
        ]);

        if ($created) {
            return response()->json([
                'success' => true,
                'message' => 'Autor criado com sucesso!',
                'data' => $created
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erro ao criar autor!'
        ], 400);
    }

    public function show(Autor $autor)
    {
        return response()->json($autor);
    }

    public function edit(Autor $autor)
    {
        return view('Edit/autor_edit');
    }

    public function update(StoreUpdateAutor $request, string $id)
    {
        $updated = $this->autor->where('id', $id)->update($request->except(['_token', '_method']));

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Autor atualizado com sucesso!',
                'data' => $this->autor->find($id)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erro ao atualizar autor!'
        ], 400);
    }

    public function destroy(string $id)
    {
        $deleted = $this->autor->where('id', $id)->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Autor excluído com sucesso!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erro ao excluir autor!'
        ], 400);
    }
}
