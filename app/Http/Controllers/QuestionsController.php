<?php

namespace App\Http\Controllers;


use App\Question;
use Illuminate\Http\Request;
use App\Http\Requests\AskQuestionRequest;


class QuestionsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {  // _ _ 2 ครั้ง
        $this->middleware('auth',['except' => ['index','show'] ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // \DB::enableQueryLog();
        $questions = Question::with('user')->latest()->paginate(5);
        return view('questions.index',compact('questions'))->render() ;

        // dd(\DB::getQueryLog());

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $question = new Question();
        return view('questions.create', compact('question'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AskQuestionRequest $request)
    {
        $request->user()->questions()->create($request->only('title','body'));
        return redirect()->route('questions.index')->with('success','Your question has been submitted.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        // dd('question');
        $question->increment('views');  // ทุกครั้งที่เปิดดู จะนับจำนวน views ด้วยคำสั่งนี้
        return view('questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)  // ถ้าเรียกแบบแรก parameter เป็น $id
    {
        $this->authorize('update',$question);
        // $question = Question::findOrFail($id); // แบบแรก เรียกแบบนี้ก็ได้
        return view('questions.edit',compact('question'));  // เรียกโดยใช้ model ที่สร้างขึ้น
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(AskQuestionRequest $request, Question $question)
    {
        $this->authorize('update',$question);
        $question->update( $request->only('title','body') );

        if($request->expectsJson()){
            return response()->json([
                'message' => "Your question has been updated.",
                'body_html' => $question->body_html,
                'title' => $question->title
            ]);
        }

        return redirect('/questions')->with('success',"Your question has been updated. ");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $this->authorize('delete',$question);
        $question->delete();

        if(request()->expectsJson() ){
            return response()->json([
                'message' => "Your question has been deleted."
            ]);
        }

        return redirect('/questions')->with('success',"Your Question has been deleted. ");
    }
}
