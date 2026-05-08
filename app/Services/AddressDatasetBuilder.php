<?php

namespace App\Services;

use Illuminate\Support\Str;

/**
 * Pure-PHP builder for the sharded address JSON dataset consumed by the
 * checkout cascade. Decoupled from Laravel Console so it can be invoked from
 * the Acorn command, a WP-CLI script, or a plain PHP runner for testing.
 *
 * Input: a Romanian postcode CSV (header required, separator auto-detected,
 * encoding UTF-8 or CP1250). Output: index.json + localities/{cod}.json +
 * streets/{cod}/{slug}[-shard].json under the given directory.
 */
class AddressDatasetBuilder
{
    /** WC ISO state code → judet name (matches WC i18n/states/RO.php). */
    public const JUDETE = [
        'AB' => 'Alba',
        'AR' => 'Arad',
        'AG' => 'Argeș',
        'BC' => 'Bacău',
        'BH' => 'Bihor',
        'BN' => 'Bistrița-Năsăud',
        'BT' => 'Botoșani',
        'BV' => 'Brașov',
        'BR' => 'Brăila',
        'B'  => 'București',
        'BZ' => 'Buzău',
        'CS' => 'Caraș-Severin',
        'CL' => 'Călărași',
        'CJ' => 'Cluj',
        'CT' => 'Constanța',
        'CV' => 'Covasna',
        'DB' => 'Dâmbovița',
        'DJ' => 'Dolj',
        'GL' => 'Galați',
        'GR' => 'Giurgiu',
        'GJ' => 'Gorj',
        'HR' => 'Harghita',
        'HD' => 'Hunedoara',
        'IL' => 'Ialomița',
        'IS' => 'Iași',
        'IF' => 'Ilfov',
        'MM' => 'Maramureș',
        'MH' => 'Mehedinți',
        'MS' => 'Mureș',
        'NT' => 'Neamț',
        'OT' => 'Olt',
        'PH' => 'Prahova',
        'SM' => 'Satu Mare',
        'SJ' => 'Sălaj',
        'SB' => 'Sibiu',
        'SV' => 'Suceava',
        'TR' => 'Teleorman',
        'TM' => 'Timiș',
        'TL' => 'Tulcea',
        'VS' => 'Vaslui',
        'VL' => 'Vâlcea',
        'VN' => 'Vrancea',
    ];

    /** @var callable|null  fn(string $line): void */
    private $logger;

    private int $shardBytes;

    public function __construct(int $shardBytes = 800000, ?callable $logger = null)
    {
        $this->shardBytes = $shardBytes;
        $this->logger = $logger;
    }

    /**
     * Build the dataset from $sourceCsv into $outDir. Returns stats array.
     *
     * @throws \RuntimeException on unrecoverable error (missing file, bad headers).
     */
    public function build(string $sourceCsv, string $outDir): array
    {
        if (! is_file($sourceCsv) || ! is_readable($sourceCsv)) {
            throw new \RuntimeException("Source file not found or not readable: {$sourceCsv}");
        }

        if (! is_dir($outDir) && ! @mkdir($outDir, 0755, true) && ! is_dir($outDir)) {
            throw new \RuntimeException("Cannot create output directory: {$outDir}");
        }

        $judetByName = $this->buildJudetIndex();

        $rows = $this->readRows($sourceCsv);
        $first = $rows->current();
        if (! $first) {
            throw new \RuntimeException('CSV appears empty.');
        }

        $columns = $this->mapColumns(array_keys($first));
        if (! $columns) {
            throw new \RuntimeException(
                'Could not detect required columns. Need at least: cod_postal, judet, localitate, denumire_artera. '
                .'Detected header: '.implode(', ', array_keys($first))
            );
        }

        $this->log('Column mapping: '.json_encode($columns, JSON_UNESCAPED_UNICODE));

        $data = [];
        $stats = ['rows' => 0, 'unmapped_judet' => 0, 'conflicts' => 0, 'skipped' => 0, 'judete' => 0, 'streets' => 0, 'shards' => 0];

        foreach ($rows as $row) {
            $stats['rows']++;

            $rawJudet = $this->normalize($row[$columns['judet']] ?? '');
            $localitate = $this->polishDisplay(trim((string) ($row[$columns['localitate']] ?? '')));
            $cod = trim((string) ($row[$columns['cod_postal']] ?? ''));

            $tip = $columns['tip_artera'] === '__missing__' ? '' : (string) ($row[$columns['tip_artera']] ?? '');
            $denumire = (string) ($row[$columns['denumire_artera']] ?? '');
            $stradaDisplay = $this->polishDisplay($this->buildStreetName($tip, $denumire));

            $code = $judetByName[$this->normalizeKey($rawJudet)] ?? null;
            if (! $code) {
                $stats['unmapped_judet']++;
                continue;
            }

            if ($localitate === '') {
                $stats['skipped']++;
                continue;
            }

            // Always register the locality, even if this row has no street.
            // Lets judete with locality-only data (e.g. Ilfov in this dataset)
            // surface in the manifest so customers see them in the cascade.
            if (! isset($data[$code][$localitate])) {
                $data[$code][$localitate] = [];
            }

            if ($cod === '' || $stradaDisplay === '') {
                $stats['skipped']++;
                continue;
            }

            if (isset($data[$code][$localitate][$stradaDisplay])) {
                if ($data[$code][$localitate][$stradaDisplay] !== $cod) {
                    $stats['conflicts']++;
                }
                continue;
            }

            $data[$code][$localitate][$stradaDisplay] = $cod;
        }

        $writeStats = $this->writeOutput($data, $outDir);

        return array_merge($stats, $writeStats);
    }

    private function log(string $line): void
    {
        if ($this->logger) {
            ($this->logger)($line);
        }
    }

    private function buildJudetIndex(): array
    {
        $idx = [];
        foreach (self::JUDETE as $code => $name) {
            $idx[$this->normalizeKey($name)] = $code;
            $idx[$this->normalizeKey('mun. '.$name)] = $code;
            $idx[$this->normalizeKey('municipiul '.$name)] = $code;
        }
        $idx[$this->normalizeKey('Bucuresti')] = 'B';
        $idx[$this->normalizeKey('Iași')] = 'IS';
        $idx[$this->normalizeKey('Iasi')] = 'IS';

        return $idx;
    }

    private function readRows(string $path): \Generator
    {
        $fh = fopen($path, 'rb');
        if (! $fh) {
            throw new \RuntimeException("Cannot open {$path}");
        }

        $sample = (string) fread($fh, 8192);
        rewind($fh);

        $delim = $this->detectDelimiter($sample);
        $isCp1250 = ! mb_check_encoding($sample, 'UTF-8');

        $header = fgetcsv($fh, 0, $delim, '"', '\\');
        if ($header === false) {
            fclose($fh);

            return;
        }

        $header = array_map(function ($v) use ($isCp1250) {
            $v = (string) $v;
            if ($isCp1250) {
                $v = mb_convert_encoding($v, 'UTF-8', 'CP1250');
            }

            return ltrim(trim($v), "\xEF\xBB\xBF");
        }, $header);

        while (($row = fgetcsv($fh, 0, $delim, '"', '\\')) !== false) {
            if ($isCp1250) {
                $row = array_map(fn ($v) => mb_convert_encoding((string) $v, 'UTF-8', 'CP1250'), $row);
            }
            $row = array_pad(array_slice($row, 0, count($header)), count($header), '');
            yield array_combine($header, $row);
        }

        fclose($fh);
    }

    private function detectDelimiter(string $sample): string
    {
        $candidates = [';', ',', "\t", '|'];
        $scores = [];
        foreach ($candidates as $d) {
            $scores[$d] = substr_count($sample, $d);
        }
        arsort($scores);

        return (string) array_key_first($scores);
    }

    private function mapColumns(array $headers): ?array
    {
        $matchers = [
            'cod_postal' => ['cod_postal', 'codpostal', 'cod postal', 'postcode', 'zip'],
            'judet' => ['judet', 'judetul', 'county', 'region2'],
            'localitate' => ['localitate', 'oras', 'city', 'town'],
            // tip_artera optional. NB: matcher must NOT include short tokens
            // that get substring-matched into other column names ("tip" alone
            // would falsely hit anything containing "tip").
            'tip_artera' => ['tip_artera', 'tip artera', 'tip_strada', 'street_type'],
            'denumire_artera' => ['denumire_artera', 'denumire artera', 'strada', 'denumire', 'street_name', 'street', 'area1'],
            'sector' => ['sector', 'region3'],
        ];

        $normalizedHeaders = [];
        foreach ($headers as $h) {
            $normalizedHeaders[$h] = $this->normalizeKey((string) $h);
        }

        $map = [];
        foreach ($matchers as $logical => $variants) {
            foreach ($normalizedHeaders as $original => $norm) {
                foreach ($variants as $v) {
                    $vNorm = $this->normalizeKey($v);
                    if ($norm === $vNorm || str_contains($norm, $vNorm)) {
                        $map[$logical] = $original;
                        continue 3;
                    }
                }
            }
        }

        foreach (['cod_postal', 'judet', 'localitate', 'denumire_artera'] as $req) {
            if (! isset($map[$req])) {
                return null;
            }
        }

        $map['tip_artera'] = $map['tip_artera'] ?? '__missing__';

        return $map;
    }

    private function buildStreetName(string $tip, string $denumire): string
    {
        $tip = trim($tip);
        $denumire = trim($denumire);
        if ($denumire === '') {
            return '';
        }
        $combined = $tip === '' ? $denumire : ($tip.' '.$denumire);

        return trim(preg_replace('/\s+/u', ' ', $combined));
    }

    private function normalize(string $s): string
    {
        return trim(preg_replace('/\s+/u', ' ', $s));
    }

    /**
     * Older datasets (Poșta Română 2013/2016) use cedilla forms (ş/ţ) instead
     * of the standard comma-below forms (ș/ț). Normalize for display so the UI
     * shows correct modern spelling.
     */
    private function polishDisplay(string $s): string
    {
        return strtr($s, [
            'ş' => 'ș',
            'ţ' => 'ț',
            'Ş' => 'Ș',
            'Ţ' => 'Ț',
        ]);
    }

    private function normalizeKey(string $s): string
    {
        $s = mb_strtolower($this->normalize($s), 'UTF-8');
        $map = [
            'ă' => 'a', 'â' => 'a', 'î' => 'i', 'ș' => 's', 'ş' => 's',
            'ț' => 't', 'ţ' => 't',
        ];

        return strtr($s, $map);
    }

    private function writeOutput(array $data, string $outDir): array
    {
        @mkdir($outDir.'/localities', 0755, true);
        @mkdir($outDir.'/streets', 0755, true);

        $manifest = [];
        $totalShards = 0;
        $totalStreets = 0;

        foreach (self::JUDETE as $code => $label) {
            if (! isset($data[$code])) {
                continue;
            }

            $localities = array_keys($data[$code]);
            sort($localities, SORT_NATURAL | SORT_FLAG_CASE);

            file_put_contents(
                $outDir.'/localities/'.$code.'.json',
                json_encode($localities, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            );

            $manifest[$code] = ['label' => $label, 'localities' => []];
            $judetDir = $outDir.'/streets/'.$code;
            @mkdir($judetDir, 0755, true);

            foreach ($data[$code] as $localitate => $streets) {
                $shards = $this->writeLocalityShards($code, $localitate, $streets, $judetDir);
                $manifest[$code]['localities'][$localitate] = $shards;
                $totalShards += count($shards);
                $totalStreets += count($streets);
            }

            $this->log(sprintf('  %s (%s): %d localities, %d streets', $code, $label, count($localities), array_sum(array_map('count', $data[$code]))));
        }

        file_put_contents(
            $outDir.'/index.json',
            json_encode($manifest, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
        );

        return [
            'judete' => count($manifest),
            'streets' => $totalStreets,
            'shards' => $totalShards,
        ];
    }

    private function writeLocalityShards(string $code, string $localitate, array $streets, string $judetDir): array
    {
        // Locality has no streets — register it in the manifest with an empty
        // shard list so the JS layer skips fetching but still surfaces it in
        // the locality datalist (with the "no streets" hint at street time).
        if (empty($streets)) {
            return [];
        }

        ksort($streets, SORT_NATURAL | SORT_FLAG_CASE);
        $slug = Str::slug($localitate);

        if ($code === 'B' && $this->normalizeKey($localitate) === 'bucuresti') {
            $bySector = [];
            foreach ($streets as $strada => $cod) {
                $sector = ctype_digit($cod[1] ?? '') ? $cod[1] : '0';
                $bySector[$sector][$strada] = $cod;
            }
            $shards = [];
            foreach ($bySector as $sector => $map) {
                ksort($map, SORT_NATURAL | SORT_FLAG_CASE);
                $suffix = 's'.$sector;
                file_put_contents(
                    $judetDir.'/'.$slug.'-'.$suffix.'.json',
                    json_encode($map, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                );
                $shards[] = $suffix;
            }
            sort($shards);

            return $shards;
        }

        return $this->writeBalancedShards($streets, $judetDir, $slug, '');
    }

    private function writeBalancedShards(array $streets, string $judetDir, string $slug, string $rangeLabel): array
    {
        $json = json_encode($streets, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if (strlen($json) <= $this->shardBytes || count($streets) < 2) {
            $suffix = $rangeLabel;
            $name = $suffix === '' ? $slug.'.json' : $slug.'-'.$suffix.'.json';
            file_put_contents($judetDir.'/'.$name, $json);

            return [$suffix];
        }

        $names = array_keys($streets);
        $mid = (int) floor(count($names) / 2);
        $firstHalfKeys = array_slice($names, 0, $mid);
        $secondHalfKeys = array_slice($names, $mid);
        $first = array_intersect_key($streets, array_flip($firstHalfKeys));
        $second = array_intersect_key($streets, array_flip($secondHalfKeys));

        $firstStart = $this->shardLetter($firstHalfKeys[0]);
        $firstEnd = $this->shardLetter(end($firstHalfKeys));
        $secondStart = $this->shardLetter($secondHalfKeys[0]);
        $secondEnd = $this->shardLetter(end($secondHalfKeys));

        $firstLabel = $firstStart === $firstEnd ? $firstStart : $firstStart.'-'.$firstEnd;
        $secondLabel = $secondStart === $secondEnd ? $secondStart : $secondStart.'-'.$secondEnd;

        $shards = [];
        $shards = array_merge($shards, $this->writeBalancedShards($first, $judetDir, $slug, $firstLabel));
        $shards = array_merge($shards, $this->writeBalancedShards($second, $judetDir, $slug, $secondLabel));

        return $shards;
    }

    private function shardLetter(string $name): string
    {
        $key = $this->normalizeKey($name);
        $skip = ['strada ', 'str. ', 'str ', 'bulevardul ', 'bd. ', 'b-dul ', 'aleea ', 'calea ', 'soseaua ', 'piata ', 'splaiul ', 'intrarea '];
        foreach ($skip as $prefix) {
            if (str_starts_with($key, $prefix)) {
                $key = substr($key, strlen($prefix));
                break;
            }
        }
        $first = mb_substr($key, 0, 1, 'UTF-8') ?: 'a';

        return preg_match('/[a-z]/', $first) ? $first : 'a';
    }
}
