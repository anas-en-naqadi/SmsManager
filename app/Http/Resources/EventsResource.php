<?php

namespace App\Http\Resources;

use App\Models\ServiceCredentialsModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
class EventsResource extends JsonResource
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
            'title' => $this->body,
            'start' => Carbon::parse($this->scheduled_at),
            'end'=>'',
            'status' => $this->status,
            'to' => json_decode($this->to),


                 ];
    }
}
