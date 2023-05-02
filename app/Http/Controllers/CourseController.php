<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Course;
use App\Models\Polo;

class CourseController extends Controller
{
    private $course;
    private $polo;

    public function __construct(Course $course, Polo $polo) 
    {
        $this->middleware('auth');
        $this->course = $course;
        $this->polo = $polo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $this->course->query(); // consulta a tabela courses
        
        $search_title = $request->title;
        $search_polo = $request->polo;
        $search_institution = $request->institution;
        $search_status = $request->status;

        // se o campo busca estiver preenchido
        if (isset($search_title)) {
            $columns = ['title'];
            foreach ($columns as $key => $value) {
                $query->orWhere($value, 'LIKE', '%' . $search_title . '%');
            }
        }

        if (isset($search_polo)) {
            $query->where('polo_id', $search_polo);
        }

        if (isset($search_institution)) {
            $query->where('institution', $search_institution);
        }
        
        if (isset($search_status)) {
            $query->where('status', $search_status);
        }

        $polos = $this->polo->all();

        $courses = $query->orderBy('id','DESC')->paginate(10);

        return view('admin.courses.index',[
            'courses' => $courses,
            'polos' => $polos,
            'search_title' => $search_title,
            'search_polo' => $search_polo,
            'search_institution' => $search_institution,
            'search_status' => $search_status
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $polos = $this->polo->all();
        return view('admin.courses.create',['polos' => $polos]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        Validator::make($data, [
            'title' => 'required|string|unique:courses|max:100',
            'duration' => 'required|string|max:30',
            'description' => 'required'
            //'file' => 'required|mimes:pdf,doc,docx,xlsx,xlsm,xlsb,xltx',
        ])->validate();

        $data['slug'] = Str::slug($data['title'], '-');

        // envia a imagem
        if($request->hasFile('image') && $request->file('image')->isValid()){
            $file = $request->image->store('courses','public');
            $data['image'] = $file;
        }

        if($this->course->create($data)) {
            return redirect('admin/courses')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/course/create')->with('error', 'Erro ao inserir o registro!');
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
        $polos = $this->polo->all();
        $cource = $this->course->find($id);
        if (!$cource) {
            return redirect('admin/courses')->with('alert', 'Registro não encontrado.');
        }

        return view('admin.courses.edit',['course' => $cource, 'polos' => $polos]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $course = $this->course->find($id);

        Validator::make($data, [
            'title' => ['required','string','max:100',Rule::unique('courses')->ignore($id)],
            'duration' => 'required|string|max:30',
            'description' => 'required'
            //'file' => 'required|mimes:pdf,doc,docx,xlsx,xlsm,xlsb,xltx',
        ])->validate();

        $data['slug'] = Str::slug($data['title'], '-');

        //imagem
        if($request->hasFile('image') && $request->file('image')->isValid()){
            
            if(($course['image'] != null) && (Storage::disk('public')->exists($course['image']))){
                Storage::disk('public')->delete($course['image']);
            } 

            $new_file = $request->image->store('courses','public');
            $data['image'] = $new_file;
        }

        if($course->update($data)) {
            return redirect('admin/courses')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/courses')->with('error', 'Erro ao inserir o registro!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = $this->course->find($id);
        if($data->delete()) 
        {
            if(($data['image'] != null) && (Storage::disk('public')->exists($data['image']))){
                Storage::disk('public')->delete($data['image']);
            }

            return redirect('admin/courses')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/courses')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
