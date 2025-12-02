<?php
// Simple QR reader wrapper using Zxing library
class QrReader {
    private $text;

    public function __construct($filename) {
        $output = shell_exec('java -cp .:core.jar:javase.jar com.google.zxing.client.j2se.CommandLineRunner ' . escapeshellarg($filename));
        preg_match('/text:(.*)/', $output, $matches);
        $this->text = trim($matches[1] ?? '');
    }

    public function text() {
        return $this->text;
    }
}
?>
