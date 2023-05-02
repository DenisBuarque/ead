<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Polo;

class PoloController extends Controller
{
    private $polo;

    public function __construct(Polo $polo) 
    {
        $this->middleware('auth');
        $this->polo = $polo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $polos = $this->polo->paginate(10);
        return view('admin.polos.index',['polos' => $polos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.polos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        Validator::make($data, [
            'title' => 'required|string|unique:polos|max:100',
            'phone' => 'required|string',
            'address' => 'required|string|max:250',
            'email' => 'required|string|max:100',
            'city' => 'required|string|max:50',
            'state' => 'required|string|max:2',
        ])->validate();

        if($this->polo->create($data)) {
            return redirect('admin/polos')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/polos/create')->with('error', 'Erro ao inserir o registro!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $polo = $this->polo->find($id);
        if (!$polo) {
            return redirect('admin/polos')->with('alert', 'Registro não encontrado.');
        }
        return view('admin.polos.edit',['polo' => $polo]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $polo = $this->polo->find($id);

        Validator::make($data, [
            'title' => ['required','string','max:100',Rule::unique('polos')->ignore($id)],
            'phone' => 'required|string',
            'address' => 'required|string|max:250',
            'email' => ['required','string','max:100'],
            'city' => 'required|string|max:50',
            'state' => 'required|string|max:2',
        ])->validate();

        if($polo->update($data)) {
            return redirect('admin/polos')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/polos')->with('error', 'Erro ao inserir o registro!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = $this->polo->find($id);
        if($data->delete()) 
        {
            return redirect('admin/polos')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/polos')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
