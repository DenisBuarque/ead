<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Course;
use App\Models\Discipline;
use App\Models\Polo;
use App\Models\Registration;
use App\Models\Inscription;
use App\Models\CustomerService;
use App\Models\DirectChat;
use App\Models\DirectChatMessage;
use App\Models\Forum;
use App\Models\ForumComment;
use App\Models\ForumOpnion;
use App\Models\Job;
use App\Models\OpenQuestion;
use App\Models\OpenResponse;
use App\Models\MultipleQuestion;
use App\Models\MultipleResponse;
use App\Models\Note;

class StudentController extends Controller
{
    private $user;
    private $course;
    private $discipline;
    private $polo;
    private $registration;
    private $inscription;
    private $customerservice;
    private $directchat;
    private $directchatmessage;
    private $forum;
    private $forumcomment;
    private $forumopnion;
    private $job;
    private $openquestion;
    private $openresponse;
    private $multiplequestion;
    private $multipleresponse;
    private $note;

    public function __construct(
        User $user, 
        Course $course, 
        Discipline $discipline,
        Polo $polo, 
        Registration $registration, 
        Inscription $inscription, 
        CustomerService $customerservice,
        DirectChat $directchat, 
        DirectChatMessage $directchatmessage,
        Forum $forum, 
        ForumComment $forumcomment, 
        ForumOpnion $forumopnion,
        Job $job, 
        OpenQuestion $openquestion,
        OpenResponse $openresponse, 
        MultipleQuestion $multiplequestion,
        MultipleResponse $multipleresponse, 
        Note $note) 
    {
        $this->middleware('auth');
        $this->user = $user;
        $this->course = $course;
        $this->discipline = $discipline;
        $this->polo = $polo;
        $this->registration = $registration;
        $this->inscription = $inscription;
        $this->customerservice = $customerservice;
        $this->directchat = $directchat;
        $this->directchatmessage = $directchatmessage;
        $this->forum = $forum;
        $this->forumcomment = $forumcomment;
        $this->forumopnion = $forumopnion;
        $this->job = $job;
        $this->openquestion = $openquestion;
        $this->openresponse = $openresponse;
        $this->multiplequestion = $multiplequestion;
        $this->multipleresponse = $multipleresponse;
        $this->note = $note;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->user->query(); // consulta a tabela de users
        
        $search = $request->search;
        $search_institution = $request->institution;

        if (isset($search)) {
            $columns = ['name','city'];
            foreach ($columns as $key => $value) {
                $query->orWhere($value, 'LIKE', '%' . $request->search . '%');
            }
        }

        if (isset($search_institution)) {
            $query->where('institution', $search_institution)->where('nivel','student');
        }

        $courses = $this->course->all();
        $records = $this->user->where('nivel','student')->get();

        $students = $query->where('nivel','student')->orderBY('id','DESC')->paginate(20);

        return view('admin.students.index',[
            'students' => $students,
            'courses' => $courses,
            'search' => $search,
            'search_institution' => $search_institution,
            'records' => $records
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $polos = $this->polo->all();
        return view('admin.students.create', ['polos' => $polos]);
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
            'rg' => 'required|string|max:30|unique:users',
            'cpf' => 'required|string|max:15|unique:users',
            'zip_code' => 'required|string|max:9',
            'address' => 'required|string|max:250',
            'number' => 'required|string|max:5',
            'district' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'state' => 'required|string|max:2',
        ])->validate();

        $data['nivel'] = 'student';

        if($request->password){
            $data['password'] =  bcrypt($request->password);
        }

        // envia a imagem
        if($request->hasFile('image') && $request->file('image')->isValid()){
            $file = $request->image->store('students','public');
            $data['image'] = $file;
        }

        if($this->user->create($data)) {
            return redirect('admin/students')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/students/create')->with('error', 'Erro ao inserir o registro!');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function note(Request $request)
    {
        $data = $request->all();

        $course = $this->course->where('id', $data['course_id'])->first();

        if($this->note->create($data)) {
            return redirect('admin/student/historic/' . $course->slug . '/' . $data['user_id'])->with('success', 'NOta adicionada com sucesso.');
        } else {
            return redirect('admin/student/historic/' . $course->slug . '/' . $data['user_id'])->with('error', 'Erro ao inserir a nota!');
        }
    }

    public static function getNote (int $course, int $discipline, int $user) 
    {
        $data = DB::table('notes')
                    ->where('course_id', $course)
                    ->where('discipline_id', $discipline)
                    ->where('user_id',$user)
                    ->first();

        if (!$data) {
            return 0;
        } else {
            return $data->nota;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $registrations = $this->registration->where('user_id', $id)->get();
        $inscriptions = $this->inscription->where('user_id', $id)->get();
        $customerservices = $this->customerservice->where('user_id', $id)->get();
        
        $student = $this->user->find($id);

        if ($student) {
            return view('admin.students.show', [
                'student' => $student, 
                'registrations' => $registrations,
                'inscriptions' => $inscriptions,
                'customerservices' => $customerservices
            ]);
        } else {
            return redirect('admin/students')->with('alert', 'Registro não encontrado!'); 
        }
    }

    /**
     * Display the specified resource.
     */
    public function historic (string $slug, int $id)
    {
        // consult if course exist
        $course = $this->course->where('slug', $slug)->first();
        if (!$course) {
            return redirect('admin/student/show/' . $id)->with('error', 'Curso não encontrado.');
        }
        // consult if student this registration in course
        $verify_registrion = $this->registration->where('course_id', $course->id)->where('payment','yes')->where('user_id', $id)->count();
        if ($verify_registrion == 0) {
            return redirect('admin/students')->with('error', 'O aluno não está matrículado.');
        }

        $inscriptions = $this->inscription->where('course_id', $course->id)->where('user_id', $id)->get();
        // consult table disciplines by course
        $disciplines = $this->discipline->where('course_id', $course->id)->get();
        // consult table users equal registration user
        $student = $this->user->where('id', $id)->first();
        // consult table direct chat
        $directchat = $this->directchat->where('course_id', $course->id)->where('user_id', $id)->first();
        // update messages user direct chat
        if ($directchat) {
            $affected = DB::table('direct_chat_messages')
                ->where('direct_chat_id', $directchat->id)->where('user_id', $id)
                ->update(['check_student' => 0]);
        }

        return view('admin.students.historic', [
            'course' => $course,
            'disciplines' => $disciplines,
            'student' => $student,
            'directchat' => $directchat,
            'inscriptions' => $inscriptions
        ]);
    }

    /**
     * Generate PDF
     */
    //public function pdf (string $slug, int $id) {
    public function pdf (int $id, int $std) 
    {
        $student = $this->user->where('id', $std)->first();
        $course = $this->course->find($id);
        $disciplines = $this->discipline->where('course_id', $course->id)->get();
        //$inscriptions = $this->inscription->where('course_id', $course->id)->where('user_id', $student->id)->get();

        //return \PDF::loadView('admin.students.pdf', ['courses' => $courses])->setPaper('a4', 'landscape')->download('arquivo-pdf-gerado.pdf');  
        return \PDF::loadView('admin.students.pdf', [
            'course' => $course,
            'disciplines' => $disciplines,
            'student' => $student,
            //'inscriptions' => $inscriptions
        ])->setPaper('a4', 'portrait')->stream('arquivo-pdf-gerado.pdf');  
    }

    /**
     * calculator date 
     */
    public static function expiration_date ($student, $course, $discipline) 
    {
        $expiraction = DB::table('inscriptions')
                        ->where('user_id', $student)
                        ->where('course_id', $course)
                        ->where('discipline_id', $discipline)
                        ->first();
        return $expiraction;
    }

    /*
    * consult inscription student
    */
    public static function verify_inscription ($student, $course, $discipline) 
    {
        $inscrition = DB::table('inscriptions')
                        ->where('user_id', $student)
                        ->where('course_id', $course)
                        ->where('discipline_id', $discipline)
                        ->where('status', 'pago')
                        ->count();
        return $inscrition;
    }

    /**
     * show page historic discipline ead
     */
    public function ead (string $course, string $discipline, int $id)
    {
        // consult if course exist
        $course = $this->course->where('slug', $course)->first();
        if (!$course) {
            return redirect('admin/student/show/' . $id)->with('error', 'Dados não encontrado.');
        }

        // consult if discipline exist
        $discipline = $this->discipline->where('slug', $discipline)->first();
        if (!$discipline) {
            return redirect('admin/student/show/' . $id)->with('error', 'Dados não encontrado.');
        }

        // consult if student this inscription at discipline
        $verify_inscription = $this->inscription->where('course_id', $course->id)->where('status','pago')->where('user_id', $id)->count();
        if ($verify_inscription == 0) {
            return redirect('admin/students')->with('error', 'O aluno não está inscrito.');
        }
        // consult table users
        $student = $this->user->find($id);
        // consult table direct chat
        $directchat = $this->directchat->where('user_id', $id)
                                       ->where('course_id', $course->id)
                                       ->where('discipline_id', $discipline->id)
                                       ->where('active', 1)
                                       ->first();

        $multiple_response = $this->multipleresponse->where('discipline_id', $discipline->id)->where('user_id', $student->id)->get();
        $open_response = $this->openresponse->where('discipline_id', $discipline->id)->where('user_id', $student->id)->get();
        $job = $this->job->where('discipline_id', $discipline->id)->where('user_id', $student->id)->first();

        // verifica se o forum foi criado
        $forum = $this->forum->where('discipline_id', $discipline->id)->first();
        $forumopnions = $this->forumopnion->orderBy('id','DESC')->get();

        if ($directchat) {
            $affected = DB::table('direct_chat_messages')
                    ->where('direct_chat_id', $directchat->id)->where('user_id', $id)
                    ->update(['check_student' => 0]);
        }

        return view('admin.students.ead', [
            'student' => $student,
            'directchat' => $directchat,
            'course' => $course,
            'discipline' => $discipline,
            'multiple_response' => $multiple_response,
            'open_response' => $open_response,
            'job' => $job,
            'forum' => $forum,
            'forumopnions' => $forumopnions
        ]);
    }

    /**
     * init conversation at chat
     */
     public function start_chat (Request $request) 
     {
         $data = $request->all();
 
         $validator = Validator::make($data, [
             'subject' => 'required',
         ]);
 
         if($this->directchat->create($data)) {
             return redirect('/admin/student/historic/ead/' . $data['course_slug'] . '/' . $data['discipline_slug'] . '/' . $data['user_id'])->with('success', 'Você iniciou o chat, sejá bem-vindo.');
         } else {
             return redirect('/admin/student/historic/ead/' . $data['course_slug'] . '/' . $data['discipline_slug'] . '/' . $data['user_id'])->with('error', 'O chat não pode ser iniciado!');
         }
     }
 
     /*
     * Storage data new direct chat mesage
     */
     public function direct_chat_message (Request $request) 
     {
         $data = $request->all();
 
         $validator = Validator::make($data, [
             'message' => 'required',
         ]);
 
         $data['check_admin'] = true;
         $data['check_student'] = false;
         $data['user_id'] = auth()->user()->id;
 
         if ($validator->passes()) {
 
             DirectChatMessage::create($data);
 
             return response()->json(['success'=>'Mensagem enviada.']);
         }
         return response()->json(['error'=>$validator->errors()]);
     }
 
     /**
      * listing data direct chat message
      */
 
     public function chat_messages (int $id) 
     {
         $directchatmessages = $this->directchatmessage->where('direct_chat_id', $id)->orderBy('id','DESC')->get();
         return view('chat_table',['directchatmessages' => $directchatmessages]);
     }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $polos = $this->polo->all();
        $student = $this->user->find($id); // consulta a tabela de users
        if ($student) {
            return view('admin.students.edit', ['student' => $student, 'polos' => $polos]);
        } else {
            return redirect('admin/students')->with('alert', 'Registro não encontrado!'); 
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all(); // recebe os dados do form.
        $student = $this->user->find($id); // consulta a tabela de users

        if (!$student) {
            return redirect('admin/students')->with('alert', 'Registro não encontrado!');
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
            'rg' => ['required','string','max:30',Rule::unique('users')->ignore($id)],
            'cpf' => ['required','string','max:15',Rule::unique('users')->ignore($id)],
            'zip_code' => 'required|string|max:9',
            'address' => 'required|string|max:250',
            'number' => 'required|string|max:5',
            'district' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'state' => 'required|string|max:2',
        ])->validate();

        if($request->password){
            $data['password'] =  bcrypt($request->password);
        }

        if($request->hasFile('image') && $request->file('image')->isValid()){
            if($student['image'] != null){
                if(Storage::exists($student['image'])) {
                    Storage::delete($student['image']);
                }
            }
            
            $new_file = $request->image->store('students','public');
            $data['image'] = $new_file;
        }

        if($student->update($data)) {
            return redirect('admin/students')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/students')->with('error', 'Erro ao alterado o registro!');
        }
    }

    /**
     * update open note student
     */
    public function close_chat (Request $request, int $id)
    {
        $data = $request->all();

        $chat = $this->directchat->find($id);
        

        if($chat->update($data)) {
            return redirect('admin/student/historic/ead/'.$data['course'].'/'.$data['discipline'].'/'.$data['user'])->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/student/historic/ead/'.$data['course'].'/'.$data['discipline'].'/'.$data['user'])->with('error', 'Erro ao alterado o registro!');
        }
    }

    /**
     * update open note student
     */
    public function update_opennote(Request $request, int $id)
    {
        $data = $request->all();

        $note = $this->openresponse->find($id);

        if($note->update($data)) {
            return redirect('admin/student/historic/ead/'.$data['course'].'/'.$data['discipline'].'/'.$data['user_id'])->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/student/historic/ead/'.$data['course'].'/'.$data['discipline'].'/'.$data['user_id'])->with('error', 'Erro ao alterado o registro!');
        }
    }

    /**
     * update note open file work academic 
     */
    public function update_workacademic(Request $request, int $id)
    {
        $data = $request->all();

        $note = $this->job->find($id);

        if($note->update($data)) {
            return redirect('admin/student/historic/ead/'.$data['course'].'/'.$data['discipline'].'/'.$data['user_id'])->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/student/historic/ead/'.$data['course'].'/'.$data['discipline'].'/'.$data['user_id'])->with('error', 'Erro ao alterado o registro!');
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
            return redirect('admin/students')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/students')->with('error', 'Erro ao excluir o registro!');
        }
    }

    /**
     * Remove the specified resource from storage multiple questions
     */
    public function destroy_multipleresponse(Request $request)
    {
        $data = $request->all();
        $result = $this->multipleresponse->where('course_id', $data['course_id'])->where('discipline_id', $data['discipline_id'])->where('user_id', $data['user_id']);
        if($result->delete()) {
            return redirect('admin/student/historic/ead/'.$data['course'].'/'.$data['discipline'].'/'.$data['user_id'])->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/student/historic/ead/'.$data['course'].'/'.$data['discipline'].'/'.$data['user_id'])->with('error', 'Erro ao alterado o registro!');
        }
    }

    /**
     * Remove the specified resource from storage open questions
     */
    public function destroy_openresponse(Request $request)
    {
        $data = $request->all();
        $result = $this->openresponse->where('course_id', $data['course_id'])->where('discipline_id', $data['discipline_id'])->where('user_id', $data['user_id']);
        if($result->delete()) {
            return redirect('admin/student/historic/ead/'.$data['course'].'/'.$data['discipline'].'/'.$data['user_id'])->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/student/historic/ead/'.$data['course'].'/'.$data['discipline'].'/'.$data['user_id'])->with('error', 'Erro ao alterado o registro!');
        }
    }

    /**
     * Remove the specified resource from storage
     */
    public function destroy_job(string $id)
    {
        $data = $this->job->find($id);
        if($data->delete()) 
        {
            if(($data['file'] != null) && (Storage::exists($data['file']))){
                Storage::delete($data['file']);
            }
            return redirect('admin/student/historic/ead/'.$data['course'].'/'.$data['discipline'].'/'.$data['user_id'])->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/student/historic/ead/'.$data['course'].'/'.$data['discipline'].'/'.$data['user_id'])->with('error', 'Erro ao alterado o registro!');
        }
    }
}
