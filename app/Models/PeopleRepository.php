<?php

namespace App\Models;

use Generator;
use Illuminate\Support\Facades\Http;

class PeopleRepository
{
    private ?array $people = null;

    public function all(): Generator
    {
        $this->load();

        foreach ($this->people['results'] as $attributes) {
            yield People::create($attributes);
        }
    }

    private function load()
    {
        if (is_array($this->people)) {
            return;
        }

        $data = Http::get('https://swapi.dev/api/people/');
        $this->people = json_decode($data, true);
    }
}
