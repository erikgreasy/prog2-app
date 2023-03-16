<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
{
    /**
     * Return all properties but process some for better usage
     */
    public function toArray($request): array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
    {
        return collect(parent::toArray($request))->merge([
            'points' => [
                'raw' => $this->points,
                'readable' => number_format($this->points, 2, ',', ' ') . 'b'
            ],
            'deadline' => [
                'raw' => $this->deadline->toString(),
                'readable' => $this->deadline->format('d.m.Y H:i')
            ],
            'published_at' => [
                'raw' => $this->published_at?->toString(),
                'readable' => $this->published_at?->format('d.m.Y H:i')
            ],
        ])->toArray();
    }
}
