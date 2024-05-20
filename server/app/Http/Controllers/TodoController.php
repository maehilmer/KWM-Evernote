<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Label;
use App\Models\Note;
use App\Models\Todo;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TodoController extends Controller
{

    // GET ALL TODOS
    public function index(): JsonResponse {
        $todos = Todo::with(['images', 'labels', 'users'])
        ->get();
        return response()->json($todos, 200);
    }

    // SPEICHERN
    public function save(Request $request) {
        $request = $this->parseRequest($request);
        DB::beginTransaction();
        try {
            $todo = Todo::create($request->all());

            // images
            if (isset($request['images']) && is_array($request['images'])) {
                foreach ($request['images'] as $img) {
                    $image = Image::firstOrNew ([
                        'url' => $img['url'],
                        'title' => $img['title']
                    ]);
                    $todo->images()->save($image);
                }
            }

            // labels
            if (isset($request['labels']) && is_array($request['labels'])) {
                foreach ($request['labels'] as $label) {
                    $label = Label::firstOrNew (['name' => $label['name']]);
                    $todo->labels()->save($label);
                }
            }

            // users
            if (isset($request['users']) && is_array($request['users'])) {
                foreach ($request['users'] as $user) {
                    $user = User::firstOrNew (['name' => $user['name']]);
                    $todo->users()->save($user);
                }
            }

            DB::commit();
            return response()->json($todo, 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json('saving todo failed: '.$e->getMessage(), 500);
        }
    }

    // UPDATEN
    public function update(Request $request, int $todo_id) {
        DB::beginTransaction();
        try {
            $todo = Todo::with(['images', 'labels', 'users'])->where('id', $todo_id)->first();

            if ($todo != null){
                $request = $this->parseRequest($request);
                $todo->update($request->all());
                $todo->images()->delete();

                // images
                if (isset($request['images']) && is_array($request['images'])) {
                    foreach ($request['images'] as $img) {
                        $image = Image::firstOrNew ([
                            'url' => $img['url'],
                            'title' => $img['title']
                        ]);
                        $todo->images()->save($image);
                    }
                }

                // labels
                $ids = [];
                if (isset($request['labels']) && is_array($request['labels'])) {
                    foreach ($request['labels'] as $label) {
                        array_push($ids, $label['id']);
                    }
                }

                $todo->labels()->sync($ids);


                // User
                $idsU = [];
                if (isset($request['users']) && is_array($request['users'])) {
                    foreach ($request['users'] as $user) {
                        array_push($idsU, $user['id']);
                    }
                }

                $todo->users()->sync($idsU);

                $todo->save();
            }

            DB::commit();
            $todo1 = Todo::with(['images', 'labels', 'users'])->where('id', $todo_id)->first();
            return response()->json($todo1, 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json('saving todo failed: '.$e->getMessage(), 500);
        }
    }

    // LÖSCHEN
    public function delete (int $todo_id) {
        $todo = Todo::where('id', $todo_id)->first();
        if ($todo != null) {
            $todo->delete();
            return response()->json("todo (' . $todo_id . ') successfully deleted", 200);
        } else {
            return response()->json("todo (' . $todo_id . ') couldn't be deleted", 422);
        }
    }


    // To do finden (optional?)
    public function findBySearchTerm(string $searchTerm): JsonResponse
    {
        $todos = Todo::with(['images', 'labels', 'users'])
            ->where('title', 'LIKE','%'. $searchTerm . '%')
            ->orWhere('description', 'LIKE','%'. $searchTerm.  '%')
            ->orWhere('due', 'LIKE','%'. $searchTerm.  '%')
            ->orWhere('isPublic', 'LIKE','%'. $searchTerm.  '%')

            ->orWhereHas('user',function($query) use ($searchTerm){
                $query->where('name','LIKE','%'.$searchTerm.'%');
            })

            ->orWhereHas('images',function($query) use ($searchTerm){
                $query->where('title','LIKE','%'.$searchTerm.'%');
            })

            ->orWhereHas('labels',function($query) use ($searchTerm){
                $query->where('name','LIKE','%'.$searchTerm.'%');
            })->get();

        return response()->json($todos,200);
    }

    private function parseRequest(Request $request): Request
    {
        $date = new \DateTime($request->due);
        $request['due'] = $date->format('Y-m-d H:i:s');
        return $request;
    }

    // Notiz durch ID finden
    public function findTodoByID(int $id): JsonResponse
    {
        $todo = Todo::where('id', $id)->with(['images', 'labels', 'users'])->first();
        return $todo != null ? response()->json($todo, 200) : response()->json(null, 200);
    }
}
