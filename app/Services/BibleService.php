<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class BibleService
{
    private $storagePath = 'bible/offline';
    private $currentVersion = 'almeida_ra';
    private $bibleData = [];
    private $index = null;
    
    // Versões disponíveis
    private $versions = [
        'almeida_ra' => [
            'name' => 'Almeida Revista e Atualizada',
            'abbrev' => 'ARA',
            'file' => 'almeida_ra.json'
        ],
        'almeida_rc' => [
            'name' => 'Almeida Revista e Corrigida',
            'abbrev' => 'ARC',
            'file' => 'almeida_rc.json'
        ],
        'blivre' => [
            'name' => 'Bíblia Livre',
            'abbrev' => 'BL',
            'file' => 'blivre.json'
        ]
    ];

    // Mapeamento de livros
    private $books = [
        'gn' => ['name' => 'Gênesis', 'chapters' => 50],
        'ex' => ['name' => 'Êxodo', 'chapters' => 40],
        'lv' => ['name' => 'Levítico', 'chapters' => 27],
        'nm' => ['name' => 'Números', 'chapters' => 36],
        'dt' => ['name' => 'Deuteronômio', 'chapters' => 34],
        'js' => ['name' => 'Josué', 'chapters' => 24],
        'jz' => ['name' => 'Juízes', 'chapters' => 21],
        'rt' => ['name' => 'Rute', 'chapters' => 4],
        '1sm' => ['name' => '1 Samuel', 'chapters' => 31],
        '2sm' => ['name' => '2 Samuel', 'chapters' => 24],
        '1rs' => ['name' => '1 Reis', 'chapters' => 22],
        '2rs' => ['name' => '2 Reis', 'chapters' => 25],
        '1cr' => ['name' => '1 Crônicas', 'chapters' => 29],
        '2cr' => ['name' => '2 Crônicas', 'chapters' => 36],
        'ed' => ['name' => 'Esdras', 'chapters' => 10],
        'ne' => ['name' => 'Neemias', 'chapters' => 13],
        'et' => ['name' => 'Ester', 'chapters' => 10],
        'jó' => ['name' => 'Jó', 'chapters' => 42],
        'sl' => ['name' => 'Salmos', 'chapters' => 150],
        'pv' => ['name' => 'Provérbios', 'chapters' => 31],
        'ec' => ['name' => 'Eclesiastes', 'chapters' => 12],
        'ct' => ['name' => 'Cânticos', 'chapters' => 8],
        'is' => ['name' => 'Isaías', 'chapters' => 66],
        'jr' => ['name' => 'Jeremias', 'chapters' => 52],
        'lm' => ['name' => 'Lamentações', 'chapters' => 5],
        'ez' => ['name' => 'Ezequiel', 'chapters' => 48],
        'dn' => ['name' => 'Daniel', 'chapters' => 12],
        'os' => ['name' => 'Oséias', 'chapters' => 14],
        'jl' => ['name' => 'Joel', 'chapters' => 3],
        'am' => ['name' => 'Amós', 'chapters' => 9],
        'ob' => ['name' => 'Obadias', 'chapters' => 1],
        'jn' => ['name' => 'Jonas', 'chapters' => 4],
        'mq' => ['name' => 'Miquéias', 'chapters' => 7],
        'na' => ['name' => 'Naum', 'chapters' => 3],
        'hc' => ['name' => 'Habacuque', 'chapters' => 3],
        'sf' => ['name' => 'Sofonias', 'chapters' => 3],
        'ag' => ['name' => 'Ageu', 'chapters' => 2],
        'zc' => ['name' => 'Zacarias', 'chapters' => 14],
        'ml' => ['name' => 'Malaquias', 'chapters' => 4],
        'mt' => ['name' => 'Mateus', 'chapters' => 28],
        'mc' => ['name' => 'Marcos', 'chapters' => 16],
        'lc' => ['name' => 'Lucas', 'chapters' => 24],
        'jo' => ['name' => 'João', 'chapters' => 21],
        'at' => ['name' => 'Atos', 'chapters' => 28],
        'rm' => ['name' => 'Romanos', 'chapters' => 16],
        '1co' => ['name' => '1 Coríntios', 'chapters' => 16],
        '2co' => ['name' => '2 Coríntios', 'chapters' => 13],
        'gl' => ['name' => 'Gálatas', 'chapters' => 6],
        'ef' => ['name' => 'Efésios', 'chapters' => 6],
        'fp' => ['name' => 'Filipenses', 'chapters' => 4],
        'cl' => ['name' => 'Colossenses', 'chapters' => 4],
        '1ts' => ['name' => '1 Tessalonicenses', 'chapters' => 5],
        '2ts' => ['name' => '2 Tessalonicenses', 'chapters' => 3],
        '1tm' => ['name' => '1 Timóteo', 'chapters' => 6],
        '2tm' => ['name' => '2 Timóteo', 'chapters' => 4],
        'tt' => ['name' => 'Tito', 'chapters' => 3],
        'fm' => ['name' => 'Filemom', 'chapters' => 1],
        'hb' => ['name' => 'Hebreus', 'chapters' => 13],
        'tg' => ['name' => 'Tiago', 'chapters' => 5],
        '1pe' => ['name' => '1 Pedro', 'chapters' => 5],
        '2pe' => ['name' => '2 Pedro', 'chapters' => 3],
        '1jo' => ['name' => '1 João', 'chapters' => 5],
        '2jo' => ['name' => '2 João', 'chapters' => 1],
        '3jo' => ['name' => '3 João', 'chapters' => 1],
        'jd' => ['name' => 'Judas', 'chapters' => 1],
        'ap' => ['name' => 'Apocalipse', 'chapters' => 22]
    ];

    public function __construct($version = 'almeida_ra')
    {
        $this->setVersion($version);
        $this->loadBibleData();
    }

    /**
     * Verificar se a Bíblia offline está disponível
     */
    public function isAvailable()
    {
        $version = $this->versions[$this->currentVersion];
        return Storage::exists($this->storagePath . '/' . $version['file']);
    }

    /**
     * Obter versões disponíveis
     */
    public function getVersions()
    {
        $available = [];
        foreach ($this->versions as $key => $version) {
            if (Storage::exists($this->storagePath . '/' . $version['file'])) {
                $available[$key] = $version;
            }
        }
        return $available;
    }

    /**
     * Definir versão atual
     */
    public function setVersion($version)
    {
        if (array_key_exists($version, $this->versions)) {
            $this->currentVersion = $version;
            $this->loadBibleData();
            return true;
        }
        return false;
    }

    /**
     * Obter versão atual
     */
    public function getCurrentVersion()
    {
        return $this->currentVersion;
    }

    /**
     * Obter informações da versão atual
     */
    public function getCurrentVersionInfo()
    {
        if (!isset($this->versions[$this->currentVersion])) {
            return null;
        }
        return $this->versions[$this->currentVersion];
    }

    /**
     * Carregar dados da Bíblia
     */
    private function loadBibleData()
    {
        if (!$this->isAvailable()) {
            return;
        }

        $version = $this->versions[$this->currentVersion];
        $cacheKey = "bible_data_{$this->currentVersion}";

        // Usar cache em arquivo para evitar problemas com dados grandes
        $this->bibleData = Cache::store('file')->remember($cacheKey, 3600, function () use ($version) {
            $content = Storage::get($this->storagePath . '/' . $version['file']);
            return json_decode($content, true);
        });
    }

    /**
     * Buscar versículo por referência
     */
    public function getVerse($reference, $version = null)
    {
        if ($version) {
            $this->setVersion($version);
        }

        if (!$this->isAvailable() || !$this->bibleData) {
            return null;
        }

        $parsed = $this->parseReference($reference);
        if (!$parsed) {
            return null;
        }

        // Buscar versículo na estrutura correta
        foreach ($this->bibleData['verses'] as $verse) {
            if ($verse['book_name'] === $this->books[$parsed['book']]['name'] &&
                $verse['chapter'] == $parsed['chapter'] &&
                $verse['verse'] == $parsed['verse']) {
                
                // Construir a referência a partir dos dados disponíveis
                $reference = $verse['book_name'] . ' ' . $verse['chapter'] . ':' . $verse['verse'];
                
                $versionInfo = $this->getCurrentVersionInfo();
                return [
                    'text' => $verse['text'],
                    'reference' => $reference,
                    'book' => $parsed['book'],
                    'chapter' => $parsed['chapter'],
                    'verse' => $parsed['verse'],
                    'version' => $versionInfo ? $versionInfo['name'] : 'N/A',
                    'version_abbrev' => $versionInfo ? $versionInfo['abbrev'] : 'N/A',
                    'source' => 'offline'
                ];
            }
        }

        return null;
    }

    /**
     * Buscar capítulo completo
     */
    public function getChapter($book, $chapter, $version = null)
    {
        if ($version) {
            $this->setVersion($version);
        }

        if (!$this->isAvailable() || !$this->bibleData) {
            return null;
        }

        $bookAbbrev = $this->getBookAbbrev($book);
        if (!$bookAbbrev) {
            return null;
        }

        $verses = [];
        $maxVerses = 200; // Limite de segurança

        // Buscar versículos do capítulo específico
        foreach ($this->bibleData['verses'] as $verseData) {
            // Verificar se o versículo pertence ao livro e capítulo desejados
            // Usar o nome do livro em vez da abreviação
            $bookName = $this->books[$bookAbbrev]['name'];
            if ($verseData['book_name'] === $bookName && $verseData['chapter'] == $chapter) {
                $reference = $verseData['book_name'] . ' ' . $verseData['chapter'] . ':' . $verseData['verse'];
                
                $verses[] = [
                    'text' => $verseData['text'],
                    'verse' => $verseData['verse'],
                    'reference' => $reference
                ];
                
                // Limitar o número de versículos
                if (count($verses) >= $maxVerses) {
                    break;
                }
            }
        }

        if (empty($verses)) {
            return null;
        }

        $versionInfo = $this->getCurrentVersionInfo();
        return [
            'book' => $this->capitalizeBookName($this->books[$bookAbbrev]['name']),
            'book_abbrev' => $bookAbbrev,
            'chapter' => $chapter,
            'verses' => $verses,
            'total_verses' => count($verses),
            'version' => $versionInfo ? $versionInfo['name'] : 'N/A',
            'version_abbrev' => $versionInfo ? $versionInfo['abbrev'] : 'N/A'
        ];
    }

    /**
     * Buscar versículos por palavra-chave (Pesquisa Avançada)
     */
    public function searchByKeyword($keyword, $limit = 50, $version = null, $options = [])
    {
        if ($version) {
            $this->setVersion($version);
        }

        if (!$this->isAvailable() || !$this->bibleData) {
            return [];
        }

        $keyword = mb_strtolower(trim($keyword));
        if (mb_strlen($keyword) < 2) {
            return [];
        }

        $exactMatch = $options['exact'] ?? false;
        $caseSensitive = $options['case_sensitive'] ?? false;
        $bookFilter = $options['book'] ?? null;
        $chapterFilter = $options['chapter'] ?? null;

        $results = [];
        $count = 0;

        foreach ($this->bibleData['verses'] as $key => $verse) {
            // Filtrar por livro se especificado
            if ($bookFilter && mb_strtolower($verse['book_name']) !== mb_strtolower($bookFilter)) {
                continue;
            }

            // Filtrar por capítulo se especificado
            if ($chapterFilter && $verse['chapter'] != $chapterFilter) {
                continue;
            }

            $verseText = $verse['text'];
            $verseLower = mb_strtolower($verseText);
            $found = false;

            if ($exactMatch) {
                // Busca exata
                $words = explode(' ', $keyword);
                $verseWords = explode(' ', $verseLower);
                $found = count(array_intersect($words, $verseWords)) == count($words);
            } else {
                // Busca parcial
                if ($caseSensitive) {
                    $found = mb_strpos($verseText, $keyword) !== false;
                } else {
                    $found = mb_strpos($verseLower, $keyword) !== false;
                }
            }

            if ($found) {
                $reference = $verse['book_name'] . ' ' . $verse['chapter'] . ':' . $verse['verse'];
                
                $versionInfo = $this->getCurrentVersionInfo();
                $results[] = [
                    'text' => $verse['text'],
                    'reference' => $reference,
                    'book' => $this->capitalizeBookName($verse['book_name']),
                    'chapter' => $verse['chapter'],
                    'verse' => $verse['verse'],
                    'version' => $versionInfo ? $versionInfo['name'] : 'N/A',
                    'version_abbrev' => $versionInfo ? $versionInfo['abbrev'] : 'N/A',
                    'search_score' => $this->calculateSearchScore($verseText, $keyword)
                ];
                $count++;
                
                if ($count >= $limit) {
                    break;
                }
            }
        }

        // Ordenar por relevância
        usort($results, function($a, $b) {
            return $b['search_score'] <=> $a['search_score'];
        });

        return array_slice($results, 0, $limit);
    }

    /**
     * Calcular pontuação de relevância da busca
     */
    private function calculateSearchScore($verse, $keyword)
    {
        $verseLower = mb_strtolower($verse);
        $keywordLower = mb_strtolower($keyword);
        
        $score = 0;
        
        // Pontuação baseada na posição da palavra
        $pos = mb_strpos($verseLower, $keywordLower);
        if ($pos !== false) {
            $score += 100 - $pos; // Quanto mais no início, maior a pontuação
        }
        
        // Pontuação baseada na frequência
        $count = mb_substr_count($verseLower, $keywordLower);
        $score += $count * 10;
        
        // Pontuação baseada no comprimento da palavra
        $score += mb_strlen($keywordLower) * 2;
        
        return $score;
    }

    /**
     * Obter versículo aleatório
     */
    public function getRandomVerse($version = null)
    {
        if ($version) {
            $this->setVersion($version);
        }

        if (!$this->isAvailable() || !$this->bibleData) {
            return null;
        }

        $verses = array_values($this->bibleData['verses']);
        $randomVerse = $verses[array_rand($verses)];

        // Construir a referência a partir dos dados disponíveis
        $reference = $this->capitalizeBookName($randomVerse['book_name']) . ' ' . $randomVerse['chapter'] . ':' . $randomVerse['verse'];

        $versionInfo = $this->getCurrentVersionInfo();
        return [
            'text' => $randomVerse['text'],
            'reference' => $reference,
            'version' => $versionInfo ? $versionInfo['name'] : 'N/A',
            'version_abbrev' => $versionInfo ? $versionInfo['abbrev'] : 'N/A',
            'source' => 'offline'
        ];
    }

    /**
     * Obter versículo do dia
     */
    public function getVerseOfTheDay($version = null)
    {
        if ($version) {
            $this->setVersion($version);
        }

        if (!$this->isAvailable() || !$this->bibleData) {
            return null;
        }

        $verses = array_values($this->bibleData['verses']);
        $dayOfYear = Carbon::now()->dayOfYear;
        $index = $dayOfYear % count($verses);
        
        $verse = $verses[$index];

        // Construir a referência a partir dos dados disponíveis
        $reference = $this->capitalizeBookName($verse['book_name']) . ' ' . $verse['chapter'] . ':' . $verse['verse'];

        $versionInfo = $this->getCurrentVersionInfo();
        return [
            'text' => $verse['text'],
            'reference' => $reference,
            'version' => $versionInfo ? $versionInfo['name'] : 'N/A',
            'version_abbrev' => $versionInfo ? $versionInfo['abbrev'] : 'N/A',
            'source' => 'offline'
        ];
    }

    /**
     * Obter lista de livros
     */
    public function getBooks()
    {
        return $this->books;
    }

    /**
     * Obter estatísticas da Bíblia
     */
    public function getStatistics()
    {
        if (!$this->isAvailable() || !$this->bibleData) {
            return null;
        }

        $currentVersionInfo = $this->getCurrentVersionInfo();
        
        return [
            'total_books' => count($this->books),
            'total_verses' => count($this->bibleData['verses']),
            'total_chapters' => 1189, // Valor padrão da Bíblia
            'available_versions' => count($this->getVersions()),
            'current_version' => $currentVersionInfo ? $currentVersionInfo['name'] : 'N/A',
            'current_version_abbrev' => $currentVersionInfo ? $currentVersionInfo['abbrev'] : 'N/A'
        ];
    }

    /**
     * Parsear referência bíblica
     */
    private function parseReference($reference)
    {
        // Limpar a referência
        $reference = trim($reference);
        
        // Padrões: "João 3:16", "Jo 3:16", "João 3", "Rute 1:16", etc.
        $patterns = [
            '/^([A-Za-zÀ-ÿ\s]+)\s+(\d+):(\d+)$/',
            '/^([A-Za-zÀ-ÿ\s]+)\s+(\d+)$/'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $reference, $matches)) {
                $book = trim($matches[1]);
                $chapter = (int)$matches[2];
                $verse = isset($matches[3]) ? (int)$matches[3] : 1;

                $bookAbbrev = $this->getBookAbbrev($book);
                
                if ($bookAbbrev) {
                    return [
                        'book' => $bookAbbrev,
                        'chapter' => $chapter,
                        'verse' => $verse
                    ];
                }
            }
        }

        return null;
    }

    /**
     * Obter abreviação do livro
     */
    private function getBookAbbrev($bookName)
    {
        $bookName = trim($bookName);
        
        // Primeiro verificar se é uma abreviação direta
        if (isset($this->books[$bookName])) {
            return $bookName;
        }
        
        // Normalizar o nome do livro (remover acentos e caracteres especiais)
        $normalizedBookName = $this->normalizeBookName($bookName);
        
        // Primeiro tentar match exato
        foreach ($this->books as $abbrev => $book) {
            if (strcasecmp($bookName, $book['name']) === 0) {
                return $abbrev;
            }
        }

        // Depois tentar match com nome normalizado
        foreach ($this->books as $abbrev => $book) {
            $normalizedBook = $this->normalizeBookName($book['name']);
            if (strcasecmp($normalizedBookName, $normalizedBook) === 0) {
                return $abbrev;
            }
        }

        // Depois tentar match parcial
        foreach ($this->books as $abbrev => $book) {
            if (stripos($bookName, $book['name']) !== false || stripos($book['name'], $bookName) !== false) {
                return $abbrev;
            }
        }

        // Mapeamento de abreviações comuns
        $commonAbbrevs = [
            'gen' => 'gn', 'exo' => 'ex', 'lev' => 'lv', 'num' => 'nm', 'deu' => 'dt',
            'jos' => 'js', 'jud' => 'jz', 'rut' => 'rt', '1sa' => '1sm', '2sa' => '2sm',
            '1ki' => '1rs', '2ki' => '2rs', '1ch' => '1cr', '2ch' => '2cr',
            'ezr' => 'ed', 'neh' => 'ne', 'est' => 'et', 'job' => 'jó', 'psa' => 'sl',
            'pro' => 'pv', 'ecc' => 'ec', 'son' => 'ct', 'isa' => 'is', 'jer' => 'jr',
            'lam' => 'lm', 'eze' => 'ez', 'dan' => 'dn', 'hos' => 'os', 'joe' => 'jl',
            'amo' => 'am', 'oba' => 'ob', 'jon' => 'jn', 'mic' => 'mq', 'nah' => 'na',
            'hab' => 'hc', 'zep' => 'sf', 'hag' => 'ag', 'zec' => 'zc', 'mal' => 'ml',
            'mat' => 'mt', 'mar' => 'mc', 'luk' => 'lc', 'joh' => 'jo', 'act' => 'at',
            'rom' => 'rm', '1co' => '1co', '2co' => '2co', 'gal' => 'gl', 'eph' => 'ef',
            'phi' => 'fp', 'col' => 'cl', '1th' => '1ts', '2th' => '2ts', '1ti' => '1tm',
            '2ti' => '2tm', 'tit' => 'tt', 'phm' => 'fm', 'heb' => 'hb', 'jam' => 'tg',
            '1pe' => '1pe', '2pe' => '2pe', '1jo' => '1jo', '2jo' => '2jo', '3jo' => '3jo',
            'jud' => 'jd', 'rev' => 'ap'
        ];

        foreach ($commonAbbrevs as $abbrev => $standardAbbrev) {
            if (strcasecmp($bookName, $abbrev) === 0) {
                return $standardAbbrev;
            }
        }

        return null;
    }

    /**
     * Normalizar nome do livro (remover acentos e caracteres especiais)
     */
    private function normalizeBookName($bookName)
    {
        // Remover acentos e caracteres especiais
        $normalized = str_replace(
            ['á', 'à', 'ã', 'â', 'ä', 'é', 'è', 'ê', 'ë', 'í', 'ì', 'î', 'ï', 'ó', 'ò', 'õ', 'ô', 'ö', 'ú', 'ù', 'û', 'ü', 'ç', 'ñ'],
            ['a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'c', 'n'],
            mb_strtolower($bookName)
        );
        
        return $normalized;
    }

    /**
     * Capitalizar nome do livro
     */
    private function capitalizeBookName($bookName)
    {
        // Capitalizar primeira letra e manter o resto
        return mb_convert_case(mb_strtolower($bookName), MB_CASE_TITLE, 'UTF-8');
    }

    /**
     * Busca avançada com múltiplos critérios
     */
    public function advancedSearch($criteria, $limit = 50, $version = null)
    {
        if ($version) {
            $this->setVersion($version);
        }

        if (!$this->isAvailable() || !$this->bibleData) {
            return [];
        }

        $results = [];
        $count = 0;

        foreach ($this->bibleData['verses'] as $key => $verse) {
            $match = true;

            // Filtrar por livro
            if (isset($criteria['book']) && mb_strtolower($verse['book_name']) !== mb_strtolower($criteria['book'])) {
                continue;
            }

            // Filtrar por capítulo
            if (isset($criteria['chapter']) && $verse['chapter'] != $criteria['chapter']) {
                continue;
            }

            // Filtrar por versículo
            if (isset($criteria['verse']) && $verse['verse'] != $criteria['verse']) {
                continue;
            }

            // Buscar por texto
            if (isset($criteria['text'])) {
                $verseText = mb_strtolower($verse['text']);
                $searchText = mb_strtolower($criteria['text']);
                
                if (isset($criteria['exact']) && $criteria['exact']) {
                    if ($verseText !== $searchText) {
                        continue;
                    }
                } else {
                    if (mb_strpos($verseText, $searchText) === false) {
                        continue;
                    }
                }
            }

            // Filtrar por comprimento do versículo
            if (isset($criteria['min_length'])) {
                if (mb_strlen($verse['text']) < $criteria['min_length']) {
                    continue;
                }
            }

            if (isset($criteria['max_length'])) {
                if (mb_strlen($verse['text']) > $criteria['max_length']) {
                    continue;
                }
            }

            if ($match) {
                $reference = $verse['book_name'] . ' ' . $verse['chapter'] . ':' . $verse['verse'];
                
                $results[] = [
                    'text' => $verse['text'],
                    'reference' => $reference,
                    'book' => $this->capitalizeBookName($verse['book_name']),
                    'chapter' => $verse['chapter'],
                    'verse' => $verse['verse'],
                    'version' => $this->getCurrentVersionInfo()['name'],
                    'version_abbrev' => $this->getCurrentVersionInfo()['abbrev'],
                    'length' => mb_strlen($verse['text'])
                ];
                $count++;
                
                if ($count >= $limit) {
                    break;
                }
            }
        }

        return $results;
    }

    /**
     * Buscar versículos por tema
     */
    public function searchByTheme($theme, $limit = 30, $version = null)
    {
        $themes = [
            'amor' => ['amor', 'caridade', 'bondade', 'compaixão', 'misericórdia'],
            'fé' => ['fé', 'crença', 'confiança', 'esperança'],
            'perdão' => ['perdão', 'arrependimento', 'reconciliação'],
            'oração' => ['oração', 'orar', 'suplicar', 'interceder'],
            'sabedoria' => ['sabedoria', 'entendimento', 'conhecimento'],
            'paz' => ['paz', 'tranquilidade', 'descanso'],
            'justiça' => ['justiça', 'retidão', 'equidade'],
            'gratidão' => ['gratidão', 'agradecer', 'louvor', 'adorar']
        ];

        if (!isset($themes[$theme])) {
            return [];
        }

        $keywords = $themes[$theme];
        $results = [];

        foreach ($keywords as $keyword) {
            $searchResults = $this->searchByKeyword($keyword, 10, $version);
            $results = array_merge($results, $searchResults);
        }

        // Remover duplicatas
        $uniqueResults = [];
        $seen = [];
        
        foreach ($results as $result) {
            $key = $result['reference'];
            if (!isset($seen[$key])) {
                $seen[$key] = true;
                $uniqueResults[] = $result;
            }
        }

        return array_slice($uniqueResults, 0, $limit);
    }

    /**
     * Obter estatísticas detalhadas
     */
    public function getDetailedStatistics()
    {
        if (!$this->isAvailable() || !$this->bibleData) {
            return null;
        }

        $stats = [
            'total_verses' => count($this->bibleData['verses']),
            'total_books' => count($this->books),
            'available_versions' => count($this->getVersions()),
            'current_version' => $this->getCurrentVersionInfo()['name'],
            'current_version_abbrev' => $this->getCurrentVersionInfo()['abbrev'],
            'books_stats' => [],
            'longest_verse' => null,
            'shortest_verse' => null,
            'average_verse_length' => 0
        ];

        $totalLength = 0;
        $longestVerse = null;
        $shortestVerse = null;
        $bookStats = [];

        foreach ($this->bibleData['verses'] as $verse) {
            $length = mb_strlen($verse['text']);
            $totalLength += $length;

            // Estatísticas por livro
            if (!isset($bookStats[$verse['book_name']])) {
                $bookStats[$verse['book_name']] = [
                    'verses' => 0,
                    'total_length' => 0,
                    'average_length' => 0
                ];
            }
            $bookStats[$verse['book_name']]['verses']++;
            $bookStats[$verse['book_name']]['total_length'] += $length;

            // Versículo mais longo
            if (!$longestVerse || $length > mb_strlen($longestVerse['text'])) {
                $longestVerse = $verse;
            }

            // Versículo mais curto
            if (!$shortestVerse || $length < mb_strlen($shortestVerse['text'])) {
                $shortestVerse = $verse;
            }
        }

        // Calcular médias por livro
        foreach ($bookStats as $book => $stats) {
            $bookStats[$book]['average_length'] = $stats['total_length'] / $stats['verses'];
        }

        $stats['books_stats'] = $bookStats;
        $stats['longest_verse'] = $longestVerse;
        $stats['shortest_verse'] = $shortestVerse;
        $stats['average_verse_length'] = $totalLength / count($this->bibleData['verses']);

        return $stats;
    }

    /**
     * Verificar integridade dos dados
     */
    public function checkDataIntegrity()
    {
        if (!$this->isAvailable() || !$this->bibleData) {
            return false;
        }

        $issues = [];
        $verseCount = 0;

        foreach ($this->books as $abbrev => $book) {
            $expectedChapters = $book['chapters'];
            $actualVerses = 0;

            foreach ($this->bibleData['verses'] as $verse) {
                if ($verse['book_name'] === $book['name']) {
                    $actualVerses++;
                }
            }

            if ($actualVerses === 0) {
                $issues[] = "Livro '{$book['name']}' não encontrado nos dados";
            }
        }

        return [
            'valid' => empty($issues),
            'issues' => $issues,
            'total_verses' => count($this->bibleData['verses'])
        ];
    }

    /**
     * Debug: Verificar se um versículo existe
     */
    public function debugVerse($reference, $version = null)
    {
        if ($version) {
            $this->setVersion($version);
        }

        if (!$this->isAvailable() || !$this->bibleData) {
            return ['error' => 'Bíblia não disponível'];
        }

        $parsed = $this->parseReference($reference);
        if (!$parsed) {
            return ['error' => 'Referência não pode ser parseada', 'reference' => $reference];
        }

        $bookName = $this->books[$parsed['book']]['name'];
        $found = false;
        $sampleVerses = [];

        // Buscar versículo na estrutura correta
        foreach ($this->bibleData['verses'] as $verse) {
            if ($verse['book_name'] === $bookName &&
                $verse['chapter'] == $parsed['chapter'] &&
                $verse['verse'] == $parsed['verse']) {
                $found = true;
                break;
            }
            
            // Coletar alguns exemplos para debug
            if (count($sampleVerses) < 5 && $verse['book_name'] === $bookName) {
                $sampleVerses[] = $verse;
            }
        }
        
        return [
            'parsed' => $parsed,
            'bookName' => $bookName,
            'exists' => $found,
            'sample_verses' => $sampleVerses,
            'version' => $this->getCurrentVersion()
        ];
    }
} 