<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Task;
use Illuminate\Support\Facades\Session;

class TaskController extends Controller
{
    public function taskIndex()
    {
        $tasks = Task::where('user_id', Auth::user()->id)
            ->where('status', 0)
            ->latest()
            ->get();

        return view('user.task.index', compact('tasks'));
    }
    
    public function taskCreate()
    {
        $director_users = User::whereRoleIs(['manager', 'employee'])
                ->where('department_id', '!=', null)
                ->orWhere('department_id', '=', Auth::user()->id)
                ->get();
        $manager_users = User::whereRoleIs(['employee'])
                ->where('department_id', '!=', null)
                ->orWhere('department_id', '=', Auth::user()->id)
                ->get();

        return view('user.task.create', compact('director_users', 'manager_users'));
    }

    public function taskStore(Request $request)
    {
        $request->validate([
            'title'         => 'required|max:255',
            'priority'      => 'required',
            'start_date'    => 'required',
            'end_date'      => 'required',
            'description'   => 'required|min:3'
        ]);

        $task = new Task;

        $task->user_id          = Auth::user()->id;
        $task->department_id    = Auth::user()->department_id;
        $task->title            = $request->title;
        $task->priority         = $request->priority;
        $task->start_date       = $request->start_date;
        $task->end_date         = $request->end_date;
        $task->description      = $request->description;
        $task->save();

        if (!empty($request->director_users)) {
            $task->users()->sync($request->director_users);
        }

        if((!empty($request->manager_users))) {
            $task->users()->sync($request->manager_users);
        }

        Session::flash('success', 'Task Created Successfully');
        return redirect()->route('taskIndex');
    }

    public function taskShow($id)
    {
        $task = Task::findOrFail($id);
        $created_by = User::where('id', $task->user_id)->first();
        return view('user.task.show', compact('task', 'created_by'));
    }

    public function taskEdit($id)
    {
        $task = Task::findOrFail($id);
        $director_users = User::whereRoleIs(['manager', 'employee'])
                ->where('department_id', '!=', null)
                ->orWhere('department_id', '=', Auth::user()->id)
                ->get();
        $manager_users = User::whereRoleIs(['employee'])
                ->where('department_id', '!=', null)
                ->orWhere('department_id', '=', Auth::user()->id)
                ->get();
        return view('user.task.edit', compact('task', 'director_users', 'manager_users'));
    }

    public function taskUpdate(Request $request, $id)
    {
        $request->validate([
            'title'         => 'required|max:255',
            'priority'      => 'required',
            'start_date'    => 'required',
            'end_date'      => 'required',
            'description'   => 'required|min:3'
        ]);

        $task = Task::findOrFail($id);

        $task->title            = $request->title;
        $task->priority         = $request->priority;
        $task->start_date       = $request->start_date;
        $task->end_date         = $request->end_date;
        $task->description      = $request->description;
        $task->save();

        if (!empty($request->director_users)) {
            $task->users()->sync($request->director_users);
        }

        if((!empty($request->manager_users))) {
            $task->users()->sync($request->manager_users);
        }

        Session::flash('success', 'Task Updated Successfully');
        return redirect()->route('taskIndex');
    }

    public function taskCompleted()
    {
        $tasks = Task::where('user_id', Auth::user()->id)
            ->where('status', 1)
            ->orderBy('updated_at', 'asc')
            ->get();

        return view('user.task.completed', compact('tasks'));
    }

    public function taskInbox()
    {
        $tasks = Auth::user()
            ->task()
            ->where('status', 0)
            ->latest()
            ->get();
        $director_users = User::whereRoleIs(['manager', 'employee'])
                ->where('department_id', '!=', null)
                ->orWhere('department_id', '=', Auth::user()->id)
                ->get();
        $manager_users = User::whereRoleIs(['employee'])
                ->where('department_id', '!=', null)
                ->orWhere('department_id', '=', Auth::user()->id)
                ->get();

        return view('user.task.inbox', compact('tasks', 'director_users', 'manager_users'));
    }

    public function taskPerformance(Request $request, $id)
    {
        $request->validate([
            'progress'      => 'required',
            'result'        => 'required|min:3'
        ]);

        $task = Task::findOrFail($id);

        $task->performed_by     = Auth::user()->id;
        $task->progress         = $request->progress;
        $task->result           = $request->result;

        if ($request->progress == '100') {
            $task->status = 1;
        }

        if ($request->hasFile('File')) {
            if ($task->file != null)  {
                unlink(public_path('tasks/performance/' . $task->file));
            }

            $file       = $request->file('file');
            $file_ext   = $file->getClientOriginalExtension();
            $file_name  = rand(123456, 999999) . '.' . $file_ext;
            $file_path  = public_path('tasks/performance/');
            $file->move($file_path, $file_name);
            $task->file = $file_name;
        }

        $task->save();

        Session::flash('sucess', 'Task Performance Submitted Successfully');
        return redirect()->route('taskInbox');
    }
}
