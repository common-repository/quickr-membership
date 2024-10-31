<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Log_Exporter
 *
 * @author nur85
 */
class Quickr_Log_Exporter {

    public function __construct() {
        add_action('admin_init', array($this, 'export'));
        add_action('quickr_extensions_tab_extensions_general', array($this, 'show'));
    }

    public function show() {
        require (QUICKR_VIEWS . 'admin/log_menu.php');
    }

    public function export() {
        $is_submitted = !empty(filter_input(INPUT_POST, 'quickr-log-export-submit'));
        if ($is_submitted) {
            $temp_file = tempnam("/tmp", "quickr_");
            $this->build($temp_file);
            $this->write_to_stream($temp_file);
        }
    }

    private function build($temp_file) {
        $file_ref = fopen($temp_file, 'w');
        $cpt_args = array(
            'post_type' => 'quickr_log',
            'posts_per_page' => -1,
            'post_status' => 'publish');
        $logs = get_posts($cpt_args);
        fwrite($file_ref, sprintf('[Quickr Log Viewer (message count : %s)]', count($logs)) . PHP_EOL);
        foreach ($logs as $log) {
            $log_types = wp_get_object_terms($log->ID, 'quickr_log_type', array('fields' => 'names'));
            $log_type = isset($log_types[0]) ? $log_types[0] : '';
            $msg = sprintf("%10s [%5s]  %5s - %10s", $log->post_date, strtoupper($log_type), $log->post_title, $log->post_content) . PHP_EOL;
            fwrite($file_ref, $msg);
            $metas = get_post_meta($log->ID, '', true);
            foreach ($metas as $meta_key => $meta_value) {
                fwrite($file_ref, sprintf("%50s : %s", substr($meta_key, 8), $meta_value[0]) . PHP_EOL);
            }
        }
        fwrite($file_ref, '[End of log]' . PHP_EOL);
        fclose($file_ref);
    }

    /**
     * writes fully generated csv file to output stream.
     */
    private function write_to_stream($temp_file) {
        $is_download = filter_input(INPUT_POST, 'export-as-file');
        if ($is_download) {
            $this->write_header($temp_file);
            echo file_get_contents($temp_file);
        } else {
            echo '<pre>' . file_get_contents($temp_file) . '</pre>';
        }
        @unlink($temp_file);
        exit;
    }

    /**
     * writes various header info to output stream.
     */
    private function write_header($temp_file) {
        header('Content-Encoding: UTF-8');
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Description: File Transfer");
        header('Content-Type: text/plain; charset=utf-8');
        header("Content-disposition: attachment; filename=quickr_log_" . date('Y-m-d H:i:s') . ".log");

        header("Content-Length: " . filesize($temp_file));
        echo "\xEF\xBB\xBF";
    }

}

$GLOBALS['quickr_log_exporter'] = new Quickr_Log_Exporter();
