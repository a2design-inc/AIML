<?php

namespace A2Design\AIML\Contracts\Interfaces;

interface Searchable {

    public function searchCategory($question);
    public function searchPattern($pattern);
    public function searchAnswer($question);
}
