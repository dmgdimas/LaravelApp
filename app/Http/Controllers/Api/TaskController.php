<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    // 1. Вывод списка
    public function index(Request $request): JsonResponse
    {
        $query = Task::query()->with(['project', 'tags'])->withCount('comments');

        if ($request->boolean('overdue')) {
            $query->overdue();
        } else {
            $query->active();
        }

        // Добавим scopeRecent как пример, если нужно показать последние 10 активных задач
        // $query->recent(10); 

        $tasks = $query->paginate(10);

        return response()->json(new TaskCollection($tasks));
    }

    // 2. Просмотр элемента
    public function show(Task $task): JsonResponse
    {
        // Загружаем связи для конкретного элемента
        $task->load(['project', 'tags', 'comments']);
        return response()->json(new TaskResource($task));
    }

    // 3. Создание элемента
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:new,in_progress,done',
            'due_date' => 'nullable|date|after_or_equal:today',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:tags,id',
        ]);

        $task = Task::create($validated);

        // Синхронизация связи Many-to-Many
        if ($request->has('tag_ids')) {
            $task->tags()->sync($request->tag_ids);
        }

        // Создание полиморфного комментария при создании задачи
        $task->comments()->create(['body' => 'Задача создана автоматически.']);

        return response()->json([
            'message' => 'Задача успешно создана',
            'data' => new TaskResource($task->load(['project', 'tags']))
        ], 201);
    }

    // 4. Редактирование элемента
    public function update(Request $request, Task $task): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'status' => 'sometimes|in:new,in_progress,done',
            'due_date' => 'nullable|date',
        ]);

        $task->update($validated);

        return response()->json([
            'message' => 'Задача обновлена',
            'data' => new TaskResource($task->fresh())
        ]);
    }

    // 5. Удаление элемента (Мягкое)
    public function destroy(Task $task): JsonResponse
    {
        $task->delete(); // запись не удаляется из БД, а помечается датой в deleted_at

        return response()->json([
            'message' => 'Задача перемещена в корзину (мягкое удаление)'
        ]);
    }
    
    // 6. Восстановление мягко удаленной записи
    public function restore($id): JsonResponse
    {
        $task = Task::withTrashed()->findOrFail($id);
        $task->restore();
        
        return response()->json(['message' => 'Задача восстановлена']);
    }
}
