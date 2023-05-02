<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\DisciplineModule;
use App\Models\Discipline;


class DisciplineModuleController extends Controller
{
    private $disciplinemodule;
    private $discipline;

    public function __construct(DisciplineModule $disciplinemodule, Discipline $discipline ) {
        $this->disciplinemodule = $disciplinemodule;
        $this->discipline = $discipline;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(string $id)
    {
        $disciplina = $this->subject->find($id);
        if ($disciplina) {

            $subjects = $this->subject->where('status','sim')->where('type','ead')->get();
            $files = $this->module->where('category','file')->orderBy('id','DESC')->get();
            $videos = $this->module->where('category','movie')->orderBy('id','DESC')->get();
            
            $modules = $this->module->where('subject_id',$id)->where('category','module')->orderBy('id','DESC')->get();
            return view('admin.modules.index', [
                'modules' => $modules, 
                'subjects' => $subjects, 
                'disciplina' => $disciplina,
                'files' => $files,
                'videos' => $videos
            ]);
        }
        return redirect('admin/subjects')->with('alert', 'Disciplina e modulos não encontrado.');
    }

    /**
     * search disciplines by select
     */
    public function search (Request $request) {

        $id = $request->discipline_id;

        $discipline = $this->discipline->find($id);
        if ($discipline) {

            $disciplines = $this->discipline->all();
            
            $modules = $this->disciplinemodule->where('discipline_id',$id)->orderBy('id','DESC')->get();
            return view('admin.modules.search', [
                'modules' => $modules, 
                'disciplines' => $disciplines, 
                'discipline' => $discipline
            ]);
        }
        
        return redirect('admin/disciplines')->with('alert', 'Disciplina e modulos não encontrado.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $id)
    {
        $discipline = $this->discipline->find($id);
        if (!$discipline) {
            return redirect('admin/disciplines')->with('alert', 'Disciplina e modulos não encontrados.');
        }
        return view('admin.modules.create', ['discipline' => $discipline]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_movie (string $id)
    {
        $discipline = $this->discipline->find($id);
        if ($discipline) {
            return view('admin.modules.movie', ['discipline' => $discipline]);
        }
        
        return redirect('admin/disciplines')->with('alert', 'Disciplina e modulos não encontrado.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_file (string $id)
    {
        $discipline = $this->discipline->find($id);
        if ($discipline) {
            return view('admin.modules.file', ['discipline' => $discipline]);
        }
        
        return redirect('admin/disciplines')->with('alert', 'Disciplina e modulos não encontrado.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        Validator::make($data, [
            'title' => 'required|string|unique:discipline_modules|max:200',
            'description' => 'required|string',
            //'file' => 'sometimes|required|mimes:pdf,doc,docx',
        ])->validate();

        $data['slug'] = Str::slug($data['title'], '-');

        // envia o arquivo
        /*if($request->hasFile('file') && $request->file('file')->isValid()){
            $file = $request->file->store('files','public');
            $data['file'] = $file;
        }*/

        if($this->disciplinemodule->create($data)) {
            return redirect('admin/discipline/show/'.$data['discipline_id'])->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/discipline/show/'.$data['discipline_id'])->with('error', 'Erro ao inserir o registro!');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_movie (Request $request)
    {
        $data = $request->all();

        Validator::make($data, [
            'title' => 'required|string|unique:discipline_modules|max:200',
            'movie' => 'required|string',
        ])->validate();

        $data['slug'] = Str::slug($data['title'], '-');
        $data['category'] = 'movie';

        if($this->disciplinemodule->create($data)) {
            return redirect('admin/discipline/show/'.$data['discipline_id'])->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/discipline/show/'.$data['discipline_id'])->with('error', 'Erro ao inserir o registro!');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_file(Request $request)
    {
        $data = $request->all();

        Validator::make($data, [
            'title' => 'required|string|unique:discipline_modules|max:200',
            'description' => 'required|string',
            'file' => 'sometimes|required|mimes:pdf,doc,docx',
        ])->validate();

        $data['slug'] = Str::slug($data['title'], '-');
        $data['category'] = 'file';

        // envia o arquivo
        if($request->hasFile('file') && $request->file('file')->isValid()){
            $file = $request->file->store('files','public');
            $data['file'] = $file;
        }

        if($this->disciplinemodule->create($data)) {
            return redirect('admin/discipline/show/'.$data['discipline_id'])->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/discipline/show/'.$data['discipline_id'])->with('error', 'Erro ao inserir o registro!');
        }
    }

 

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $module = $this->disciplinemodule->find($id);
        if (!$module) {
            return redirect('admin/disciplines')->with('error','Registro não encontrado!');
        }

        if ($module['category'] == 'module') {
            return view('admin.modules.edit', ['module' => $module]);
        } else if ($module['category'] == 'movie') {
            return view('admin.modules.edit_movie', ['module' => $module]);
        } else {
            return view('admin.modules.edit_file', ['module' => $module]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $module = $this->disciplinemodule->find($id);

        Validator::make($data, [
            'title' => ['required','string','max:200',Rule::unique('discipline_modules')->ignore($id)],
            'file' => 'sometimes|mimes:pdf,doc,docx',
            'description' => 'required|string',
        ])->validate();

        $data['slug'] = Str::slug($data['title'], '-');

        if($request->hasFile('file') && $request->file('file')->isValid()){
            if(($module['file'] != null) && (Storage::disk('public')->exists($module['file']))) {
                Storage::disk('public')->delete($module['file']);
            }
            
            $new_file = $request->file->store('files','public');
            $data['file'] = $new_file;
        }

        if($module->update($data)) {
            return redirect('admin/discipline/show/'.$data['discipline_id'])->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/discipline/show/'.$data['discipline_id'])->with('error', 'Erro ao alterar o registro!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = $this->disciplinemodule->find($id);

        if(($data['file'] != null) && (Storage::disk('public')->exists($data['file']))) {
            Storage::disk('public')->delete($data['file']);
        }

        if($data->delete()) 
        {
            return redirect('admin/discipline/show/'.$data['discipline_id'])->with('success', 'Registro excluir com sucesso!');
        } else {
            return redirect('admin/discipline/show/'.$data['discipline_id'])->with('error', 'Erro ao excluir o registro!');
        }
    }
}
