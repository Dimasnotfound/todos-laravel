<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::all();
        return view('todos.index', compact('todos'));
    }

    public function store(Request $request)
    {
        $todo = new Todo();
        $todo->title = $request->title;
        $todo->save();

        return response()->json($todo);
    }

    public function update(Request $request, $id)
    {
        $todo = Todo::find($id);

        if ($todo) {
            $todo->completed = $request->has('completed') ? !$todo->completed : $todo->completed;
            $todo->title = $request->has('title') ? $request->title : $todo->title;
            $todo->save();

            return response()->json($todo);
        }

        return response()->json(['error' => 'Todo not found'], 404);
    }

    public function filter(Request $request)
    {
        $status = $request->query('status');

        if ($status === 'completed') {
            $todos = Todo::where('completed', true)->get();
        } elseif ($status === 'active') {
            $todos = Todo::where('completed', false)->get();
        } else {
            $todos = Todo::all();
        }

        return response()->json($todos);
    }

    public function clearCompleted()
    {
        Todo::where('completed', true)->delete();
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $todo = Todo::find($id);

        if ($todo) {
            $todo->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }


}
