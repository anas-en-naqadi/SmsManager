<?php

namespace App\Http\Resources;

use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'to' => $this->to,
            'from' => $this->from,
            'message' => $this->body,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'status' => $this->status,
                       'scheduled_at' => $this->scheduled_at !== null ? Carbon::parse($this->scheduled_at)->format('Y-m-d H:i:s') : null ,


            'service' => $this->service->service_name
        ];
    }
}
