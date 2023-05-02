<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Course;
use App\Models\Discipline;
use App\Models\DirectChat;
use App\Models\DirectChatMessage;

class DirectChatController extends Controller
{
    private $user;
    private $course;
    private $discipline;
    private $directchat;
    private $directchatmessage;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        User $user,
        Course $course, 
        Discipline $discipline, 
        DirectChat $directchat, 
        DirectChatMessage $directchatmessage)
    {
        $this->middleware('auth');
        $this->user = $user;
        $this->course = $course;
        $this->discipline = $discipline;
        $this->directchat = $directchat;
        $this->directchatmessage = $directchatmessage;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->directchat->query();

        $search = $request->search;

        if (isset($search)) {
            $query->where('user_id', $search);
        }

        $users = $this->user->where('nivel','student')->get();
        $directchatmessages = $this->directchatmessage->all();

        $directchats = $query->orderBy('id','DESC')->paginate(10);

        return view('admin.directchats.index',[
            'directchats' => $directchats,
            'directchatmessages' => $directchatmessages,
            'users' => $users,
            'search' => $search
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = $this->directchat->find($id);
        if($data->delete()) {
            return redirect('admin/directchats')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/directchats')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
