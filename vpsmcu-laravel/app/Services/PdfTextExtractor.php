<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class PdfTextExtractor
{
    /**
     * สกัดข้อความภาษาไทย/อังกฤษจากไฟล์ PDF เพื่อใช้ทำ Full-text / Search Index
     */
    public static function extractText(?string $filePath): string
    {
        if (empty($filePath) || !file_exists($filePath)) {
            return '';
        }

        try {
            $content = file_get_contents($filePath);
            if (!$content) {
                return '';
            }

            $extracted = '';

            // 1. แยก Streams ทั้งหมดใน PDF
            if (preg_match_all('/stream(.*?)endstream/s', $content, $matches)) {
                foreach ($matches[1] as $stream) {
                    $stream = ltrim($stream, "\r\n");
                    $stream = rtrim($stream, "\r\n");

                    // ลองคลายการบีบอัด FlateDecode
                    $data = @gzuncompress($stream);
                    if ($data === false) {
                        $data = @gzinflate($stream);
                    }
                    if ($data === false) {
                        $data = $stream; // เผื่อเป็น stream ที่ไม่ได้บีบอัด
                    }

                    if (!empty($data)) {
                        $text = self::parseTextFromDecodedStream($data);
                        if (!empty($text)) {
                            $extracted .= ' ' . $text;
                        }
                    }
                }
            }

            // ทำความสะอาดข้อความ
            $extracted = preg_replace('/[^\P{C}\n\t]+/u', '', $extracted); // ตัด control characters
            $extracted = preg_replace('/\s+/', ' ', $extracted);
            return trim($extracted);
        } catch (\Throwable $e) {
            Log::warning('PdfTextExtractor failed on file: ' . $filePath . ' Error: ' . $e->getMessage());
            return '';
        }
    }

    private static function parseTextFromDecodedStream(string $stream): string
    {
        $result = '';

        // ค้นหาบล็อกข้อความ BT ... ET
        if (preg_match_all('/BT(.*?)ET/s', $stream, $btMatches)) {
            foreach ($btMatches[1] as $bt) {
                // จับคำสั่ง (text) Tj
                if (preg_match_all('/\((.*?)\)\s*Tj/s', $bt, $tjMatches)) {
                    foreach ($tjMatches[1] as $str) {
                        $result .= ' ' . self::decodePdfString($str);
                    }
                }

                // จับคำสั่ง [(text) -10 (text2)] TJ
                if (preg_match_all('/\[(.*?)\]\s*TJ/s', $bt, $arrayMatches)) {
                    foreach ($arrayMatches[1] as $arr) {
                        if (preg_match_all('/\((.*?)\)/s', $arr, $subMatches)) {
                            foreach ($subMatches[1] as $str) {
                                $result .= self::decodePdfString($str);
                            }
                            $result .= ' ';
                        }
                    }
                }

                // จับคำสั่ง ' หรือ "
                if (preg_match_all('/\((.*?)\)\s*[\'"]/s', $bt, $quoteMatches)) {
                    foreach ($quoteMatches[1] as $str) {
                        $result .= ' ' . self::decodePdfString($str);
                    }
                }
            }
        }

        return $result;
    }

    private static function decodePdfString(string $str): string
    {
        // แก้ไข escape sequences มาตรฐาน PDF
        $str = str_replace(
            ['\\n', '\\r', '\\t', '\\b', '\\f', '\\(', '\\)', '\\\\'],
            ["\n", "\r", "\t", "\x08", "\x0C", '(', ')', '\\'],
            $str
        );

        // แก้ไข Octal escape เช่น \123
        $str = preg_replace_callback('/\\\([0-7]{1,3})/', function ($m) {
            return chr(octdec($m[1]));
        }, $str);

        // ตรวจสอบว่าเป็น UTF-16BE BOM หรือไม่ (\xFE\xFF)
        if (str_starts_with($str, "\xFE\xFF")) {
            $converted = @mb_convert_encoding(substr($str, 2), 'UTF-8', 'UTF-16BE');
            if ($converted !== false) {
                return $converted;
            }
        }

        // ลองแปลงถ้าเป็น TIS-620 หรือ Windows-874 (สำหรับ PDF ภาษาไทยรุ่นเก่า)
        if (!mb_check_encoding($str, 'UTF-8')) {
            $converted = @iconv('TIS-620', 'UTF-8//IGNORE', $str);
            if ($converted !== false) {
                return $converted;
            }
        }

        return $str;
    }
}

