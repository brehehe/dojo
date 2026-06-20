<?php

$userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';

$fonts = [
    'Cinzel_DMSans' => 'https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,300&display=swap',
    'OpenSans' => 'https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap',
    'Cormorant_Inter' => 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&family=Inter:wght@300;400;500;600;700&display=swap',
    'DMSans_Bebas' => 'https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800&family=Bebas+Neue&display=swap',
    'Inter_Bebas' => 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Bebas+Neue&display=swap',
    'Outfit' => 'https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap',
    'SpaceGrotesk_Bebas' => 'https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Bebas+Neue&display=swap',
    'Inter_Global' => 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap',
    'Inter_Opsz' => 'https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap',
];

$fontsDir = __DIR__.'/../public/fonts';
$cssDir = __DIR__.'/../public/css';

if (! is_dir($fontsDir)) {
    mkdir($fontsDir, 0755, true);
}
if (! is_dir($cssDir)) {
    mkdir($cssDir, 0755, true);
}

$combinedCss = "/* Locally Hosted Fonts */\n\n";

foreach ($fonts as $name => $url) {
    echo "Processing $name...\n";

    // Fetch the CSS from Google
    $opts = [
        'http' => [
            'method' => 'GET',
            'header' => "User-Agent: $userAgent\r\n",
        ],
    ];
    $context = stream_context_create($opts);
    $cssContent = file_get_contents($url, false, $context);

    if ($cssContent === false) {
        echo "Failed to fetch CSS for $name\n";

        continue;
    }

    // Find all font URLs in CSS
    preg_match_all('/url\((https:\/\/[^)]+)\)/', $cssContent, $matches);

    if (empty($matches[1])) {
        echo "No font files found for $name\n";

        continue;
    }

    $urls = array_unique($matches[1]);
    $replacements = [];

    foreach ($urls as $fontUrl) {
        // Generate a clean local filename from the Google Fonts URL hash
        $pathInfo = pathinfo(parse_url($fontUrl, PHP_URL_PATH));
        $ext = isset($pathInfo['extension']) ? $pathInfo['extension'] : 'woff2';

        // Use a hash of the URL to keep filenames unique but stable
        $filename = $name.'_'.md5($fontUrl).'.'.$ext;
        $localPath = $fontsDir.'/'.$filename;

        if (! file_exists($localPath)) {
            echo "Downloading $fontUrl -> $filename\n";
            $fontData = file_get_contents($fontUrl);
            if ($fontData !== false) {
                file_put_contents($localPath, $fontData);
            } else {
                echo "Failed to download $fontUrl\n";
            }
        }

        $replacements[$fontUrl] = '/fonts/'.$filename;
    }

    // Replace remote URLs in the CSS with local paths
    $localCssContent = str_replace(array_keys($replacements), array_values($replacements), $cssContent);
    $combinedCss .= "/* --- Fonts from $name --- */\n".$localCssContent."\n\n";
}

file_put_contents($cssDir.'/fonts.css', $combinedCss);
echo "Completed! Consolidated stylesheet written to public/css/fonts.css\n";
