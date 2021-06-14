<?php

namespace App\Service;

class Slugify
{
    private const ACCCHARS = [
        "Š",
        "Ž",
        "š",
        "ž",
        "Ÿ",
        "À",
        "Á",
        "Â",
        "Ã",
        "Ä",
        "Å",
        "Ç",
        "È",
        "É",
        "Ê",
        "Ë",
        "Ì",
        "Í",
        "Î",
        "Ï",
        "Ð",
        "Ñ",
        "Ò",
        "Ó",
        "Ô",
        "Õ",
        "Ö",
        "Ù",
        "Ú",
        "Û",
        "Ü",
        "Ý",
        "à",
        "á",
        "â",
        "ã",
        "ä",
        "å",
        "ç",
        "è",
        "é",
        "ê",
        "ë",
        "ì",
        "í",
        "î",
        "ï",
        "ð",
        "ñ",
        "ò",
        "ó",
        "ô",
        "õ",
        "ö",
        "ù",
        "ú",
        "û",
        "ü",
        "ý",
        "ÿ",
    ];
    
    private const REGCHARS = [
        "S",
        "Z",
        "s",
        "z",
        "Y",
        "A",
        "A",
        "A",
        "A",
        "A",
        "A",
        "C",
        "E",
        "E",
        "E",
        "E",
        "I",
        "I",
        "I",
        "I",
        "D",
        "N",
        "O",
        "O",
        "O",
        "O",
        "O",
        "U",
        "U",
        "U",
        "U",
        "Y",
        "a",
        "a",
        "a",
        "a",
        "a",
        "a",
        "c",
        "e",
        "e",
        "e",
        "e",
        "i",
        "i",
        "i",
        "i",
        "d",
        "n",
        "o",
        "o",
        "o",
        "o",
        "o",
        "u",
        "u",
        "u",
        "u",
        "y",
        "y"
    ];
    
    public function generate(string $slug): string
    {
        //Trim spaces at the start and end:
        $slug = trim($slug);
        //Replace accents:
        $slug = $this->accentedChars($slug);
        //Replace special chars
        $slug = preg_replace('~[^-\s*\w]+~', '', $slug);
        //Remove duplicate divider
        $slug = preg_replace('~-+~', '-', $slug);
        //Replace spaces with - (first delete the original ones with the previous line so no doubles):
        $slug = str_replace(' ', '-', $slug);
        //Everything to lower case:
        return strtolower($slug);
    }

    public function accentedChars(string $slug): string
    {
        return str_replace(self::ACCCHARS, self::REGCHARS, $slug);
    }
}