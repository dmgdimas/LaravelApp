<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'status' => $this->status,
            'due_date' => $this->due_date?->format('Y-m-d'),
            
            // Безопасное получение связанных данных. 
            // Поле появится в JSON только если связь была загружена через ->with()
            'project_name' => $this->whenLoaded('project', fn () => $this->project->name),
            'tags' => $this->whenLoaded('tags', fn () => $this->tags->pluck('name')),
            
            // Безопасный подсчет. Появится, если в контроллере использовалось ->withCount('comments')
            'comments_count' => $this->whenCounted('comments'),
            
            'created_at' => $this->created_at->format('d.m.Y H:i'),
        ];
    }
}