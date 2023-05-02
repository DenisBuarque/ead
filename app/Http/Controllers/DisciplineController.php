<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Discipline;
use App\Models\DisciplineModule;
use App\Models\Course;
use App\Models\User;

class DisciplineController extends Controller
{
    private $user;
    private $discipline;
    private $disciplineModule;
    private $course;

    public function __construct(User $user, Discipline $discipline, Course $course, DisciplineModule $disciplineModule) 
    {    
        $this->middleware('auth');
        $this->user = $user;
        $this->discipline = $discipline;
        $this->disciplineModule = $disciplineModule;
        $this->course = $course;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->discipline->query();
        
        $search_title = $request->title;
        $search_course = $request->course;
        $search_institution = $request->institution;
        $search_status = $request->status;

        if (isset($search_title)) {
            $query->where('title', 'LIKE', '%' . $search_title . '%');
        }

        if (isset($search_course)) {
            $query->where('course_id', $search_course);
        }

        if (isset($search_institution)) {
            $query->where('institution', $search_institution);
        }

        if (isset($search_status)) {
            $query->where('status', $search_status);
        }
       
        $total = $this->discipline->all();
        $courses = $this->course->where('status','active')->get();

        $disciplines = $query->orderBY('id','DESC')->paginate(20);
        return view('admin.disciplines.index',[
            'total' => $total,
            'courses' => $courses,
            'disciplines' => $disciplines,
            'search_title' => $search_title,
            'search_course' => $search_course,
            'search_institution' => $search_institution,
            'search_status' => $search_status
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers = $this->user->where('nivel','teacher')->where('access','active')->get();
        $courses = $this->course->where('status','active')->get();
        return view('admin.disciplines.create', ['teachers' => $teachers, 'courses' => $courses]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        Validator::make($data, [
            'institution' => 'required',
            'course_id' => 'required',
            'title' => 'required|string|unique:courses|max:100',
            'description' => 'required|string'
        ])->validate();

        $data['slug'] = Str::slug($data['title'], '-');

        if($this->discipline->create($data)) {
            return redirect('admin/disciplines')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/disciplines/create')->with('error', 'Erro ao inserir o registro!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $discipline = $this->discipline->find($id);
        if(!$discipline) {
            return redirect('admin/disciplines')->with('error', 'Registro não encontrado!');
        }

        $modules = $this->disciplineModule->where('discipline_id', $id)->get();
        return view('admin.disciplines.show', [
            'discipline' => $discipline, 
            'modules' => $modules
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $teachers = $this->user->where('nivel','teacher')->get();
        $courses = $this->course->all();

        $discipline = $this->discipline->find($id);
        if(!$discipline) {
            return redirect('admin/disciplines')->with('error', 'Registro não encontrado!');
        }

        return view('admin.disciplines.edit', [
            'discipline' => $discipline,
            'courses' => $courses,
            'teachers' => $teachers, 
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $discipline = $this->discipline->find($id);

        if (!$discipline) {
            return redirect('admin/disciplines')->with('error', 'Registro não encontrado!');
        }

        Validator::make($data, [
            'institution' => 'required',
            'course_id' => 'required',
            'title' => 'required|string|unique:courses|max:100',
            'description' => 'required|string'
        ])->validate();

        $data['slug'] = Str::slug($data['title'], '-');

        if($discipline->update($data)) {
            return redirect('admin/disciplines')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/disciplines')->with('error', 'Erro ao alterado o registro!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = $this->discipline->find($id);
        if ($data->delete()) {
            return redirect('admin/disciplines')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/disciplines')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
