<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreUpdateLivrosCreate;

use Illuminate\Http\Request;
use App\Models\Livro;

use App\Http\Requests\StoreUpdateLivro;

class LivroController extends Controller
{
    public readonly Livro $livro;

    public function __construct()
    {
        $this->livro = new Livro();
    }

    public function index()
    {
        $livros = Livro::all();
        return response()->json($livros);
    }

    public function create()
    {
        return view('Create/livro_create');
    }
    
    public function store(StoreUpdateLivro $request)
    {
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('livros', 'public');
        } else {
            $imagePath = null;
        }

        $created = $this->livro->create([
            'titulo' => $request->input('titulo'),
            'autor' => $request->input('autor'),
            'editora' => $request->input('editora'),
            'data' => $request->input('data'),
            'categoria' => $request->input('categoria'),
            'preco' => $request->input('preco'),
            'image' => $imagePath,
        ]);

        if ($created) {
            return response()->json([
                'success' => true,
                'message' => 'Livro criado com sucesso!',
                'data' => $created
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
        $dataFormatada = \Carbon\Carbon::parse($request->data_publicacao)->format('Y-m-d');
    
        $precoFormatado = str_replace(',', '.', $request->preco);

        $request->merge([
            'data' => $dataFormatada,
            'preco' => $precoFormatado,
        ]);

        $updated = $this->livro->where('id', $id)->update($request->except(['_token', '_method']));

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Livro atualizado com sucesso!',
                'data' => $this->livro->find($id)
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