<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class TeacherController extends Controller
{
    private $user;

    public function __construct(User $user) 
    {
        $this->middleware('auth');
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->user->query(); // consulta a tabela de users
        
        $search_name = $request->name;
        $search_institution = $request->institution;


        if (isset($search_name)) {
            $columns = ['name'];
            foreach ($columns as $key => $value) {
                $query->where('nivel','teacher')->orWhere($value, 'LIKE', '%' . $search_name . '%');
            }
        }

        if (isset($search_institution)) {
            $query->where('institution', $search_institution)->where('nivel','teacher');
        }

        $records = $this->user->where('nivel','teacher')->get();

        $teachers = $query->where('nivel','teacher')->orderBY('id','DESC')->paginate(10);

        return view('admin.teachers.index',[
            'teachers' => $teachers,
            'search_name' => $search_name,
            'search_institution' => $search_institution,
            'records' => $records
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.teachers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        Validator::make($data, [
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:16',
            'email' => 'required|string|max:100|unique:users',
            'password' => 'sometimes|required|string|min:6',
            'image' => 'sometimes|required|mimes:jpg,jpeg,gif,png',
        ])->validate();

        $data['nivel'] = 'teacher';

        if($request->password){
            $data['password'] =  bcrypt($request->password);
        }

        // envia a imagem
        if($request->hasFile('image') && $request->file('image')->isValid()){
            $file = $request->image->store('teachers','public');
            $data['image'] = $file;
        }

        if($this->user->create($data)) {
            return redirect('admin/teachers')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/teacher/create')->with('error', 'Erro ao inserir o registro!');
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
        $teacher = $this->user->find($id); // consulta a tabela de users
        if ($teacher) {
            return view('admin.teachers.edit', ['teacher' => $teacher]);
        } else {
            return redirect('admin/teachers')->with('alert', 'Registro não encontrado!'); 
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all(); // recebe os dados do form.
        $teacher = $this->user->find($id); // consulta a tabela de users

        if (!$teacher) {
            return redirect('admin/teachers')->with('alert', 'Registro não encontrado!');
        }

        if(!$data['password']):
            unset($data['password']);
        endif;

        Validator::make($data, [
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:16',
            'email' => ['required','string','email','max:100',Rule::unique('users')->ignore($id)],
            'password' => 'sometimes|required|string|min:6',
            'image' => 'sometimes|required|mimes:jpg,jpeg,gif,png',
        ])->validate();

        if($request->password){
            $data['password'] =  bcrypt($request->password);
        }

        if($request->hasFile('image') && $request->file('image')->isValid()){
            if($teacher['image'] != null){
                if(Storage::exists($teacher['image'])) {
                    Storage::delete($teacher['image']);
                }
            }
            
            $new_file = $request->image->store('teachers','public');
            $data['image'] = $new_file;
        }

        if($teacher->update($data)) {
            return redirect('admin/teachers')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/teachers')->with('error', 'Erro ao alterado o registro!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = $this->user->find($id); // consulta a tabela users
        if($data->delete()) 
        {
            if(($data['image'] != null) && (Storage::exists($data['image']))){
                Storage::delete($data['image']);
            }
            return redirect('admin/teachers')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/teachers')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
