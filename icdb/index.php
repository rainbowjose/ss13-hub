<style>
* {
    padding: 0;
    margin: 0;
}

body {
    background: #000;
    font-family: monospace;
}

.dmi_icon {
    margin: 4px;
    background: black;
    color: #ddd;
    display: inline-flex;
    background: #222;
    border: 1px solid #333;
}
</style>

<?php

//test script for dmi icon browsing

$p = new PNGMetadataExtractor;

$i = $p->getMetadata('tgstation/test.dmi');

$shit = explode("\n", $i);

$o = 0;
$cr = 0;

foreach ($shit as $key) {
    if (preg_match       ('/state = "(.{1,32})"/', $key)) {
        $cs[$o]['name'] = preg_replace('/state = "(.{1,32})"/', '$1', $key);
    }
    if (preg_match       ('/dirs = (\d)/', $key)) {
        $cs[$o]['dirs'] = preg_replace('/dirs = (\d)/', '$1', $key);
    }
    if (preg_match       ('/frames = (\d)/', $key)) {
        $cs[$o]['frames'] = preg_replace('/frames = (\d)/', '$1', $key);
        $o++;
    }
}

$source = @imagecreatefrompng('tgstation/test.dmi');

$source_width = imagesx( $source );
$source_height = imagesy( $source );

for ($row = 0; $row < $source_height / 32; $row++)  {
    for ($col = 0; $col < $source_width / 32; $col++) {
        $im = @imagecreate( 32, 32 );
        imagecopyresized( $im, $source, 0, 0, $col * 32, $row * 32, 32, 32, 32, 32);
        ob_start();
            imagepng($im);
            $crop = base64_encode (ob_get_contents());
        ob_end_clean();
        $arricon[] = $crop;
        //echo "<img src='data:image/png;base64,$crop'>";
        imagedestroy( $im );
    }
}

foreach ($cs as $fu) {
    echo '<div class="dmi_icon">';
    echo $fu['name'].'<br>';
    for ($row = 0; $row < $fu['frames'] * $fu['dirs']; $row++) {
        echo "<img src='data:image/png;base64,".$arricon[$row + $cr]."'>";
    }
    $cr += $fu['frames'] * $fu['dirs'];
    echo '</div>';
}

class PNGMetadataExtractor {
    private static $pngSig;

    private static $crcSize;

    const VERSION = 1;
    const MAX_CHUNK_SIZE = 3145728; // 3 megabytes

    static function getMetadata( $filename ) {
        self::$pngSig = pack( "C8", 137, 80, 78, 71, 13, 10, 26, 10 );
        self::$crcSize = 4;

        if ( !$filename ) {
            throw new Exception( __METHOD__ . ": No file name specified" );
        } elseif ( !file_exists( $filename ) || is_dir( $filename ) ) {
            throw new Exception( __METHOD__ . ": File $filename does not exist" );
        }

        $fh = fopen( $filename, 'rb' );

        if ( !$fh ) {
            throw new Exception( __METHOD__ . ": Unable to open file $filename" );
        }

        // Check for the PNG header
        $buf = fread( $fh, 8 );
        if ( $buf != self::$pngSig ) {
            throw new Exception( __METHOD__ . ": Not a valid PNG file; header: $buf" );
        }

        // Read chunks
        while ( !feof( $fh ) ) {
            $buf = fread( $fh, 4 );
            $chunk_size = unpack( "N", $buf )[1];
            $chunk_type = fread( $fh, 4 );
           if ( $chunk_type == 'zTXt' ) {

                if ( function_exists( 'gzuncompress' ) ) {
                    $buf = self::read( $fh, $chunk_size );

                    // In case there is no \x00 which will make explode fail.
                    if ( strpos( $buf, "\x00" ) === false ) {
                        throw new Exception( __METHOD__ . ": Read error on zTXt chunk" );
                    }

                    list( $keyword, $postKeyword ) = explode( "\x00", $buf, 2 );
                    if ( $keyword === '' || $postKeyword === '' ) {
                        throw new Exception( __METHOD__ . ": Read error on zTXt chunk" );
                    }

                    $keyword = strtolower( $keyword );

                    $compression = substr( $postKeyword, 0, 1 );
                    $content = substr( $postKeyword, 1 );

                    $content = gzuncompress( $content );
                } else {
                    wfDebug( __METHOD__ . " Cannot decompress zTXt chunk due to lack of zlib. Skipping.\n" );
                    fseek( $fh, $chunk_size, SEEK_CUR );
                }
            } elseif ( $chunk_type == "IEND" ) {
                break;
            } else {
                fseek( $fh, $chunk_size, SEEK_CUR );
            }
            fseek( $fh, self::$crcSize, SEEK_CUR );
        }
        fclose( $fh );

        return $content;
    }

    private static function read( $fh, $size ) {
        if ( $size > self::MAX_CHUNK_SIZE ) {
            throw new Exception( __METHOD__ . ': Chunk size of ' . $size .
                ' too big. Max size is: ' . self::MAX_CHUNK_SIZE );
        }

        return fread( $fh, $size );
    }
}
?>
