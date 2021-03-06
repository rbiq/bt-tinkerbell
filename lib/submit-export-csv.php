<?php

require_once('./globals.php');

$fullname     = _getVar($_REQUEST, 'fullname');
$order_number = _getVar($_REQUEST, 'order_number');
$event_id     = _getVar($_REQUEST, 'event_id');
$event_name   = _getVar($_REQUEST, 'event_name');
$event_date   = _getVar($_REQUEST, 'event_date');
$event_time   = _getVar($_REQUEST, 'event_time');
$venue        = _getVar($_REQUEST, 'venue');
$section      = _getVar($_REQUEST, 'section');
$row          = _getVar($_REQUEST, 'row');
$seat         = _getVar($_REQUEST, 'seat1');
$seat_notes   = _getVar($_REQUEST, 'seat_notes');
$barcode      = _getVar($_REQUEST, 'barcode1');
$zip_code     = _getVar($_REQUEST, 'zip_code');
$cc_code      = _getVar($_REQUEST, 'cc_code');
$cc_zip       = _getVar($_REQUEST, 'cc_zip');
$ticket_date  = _getVar($_REQUEST, 'ticket_date');
$notes        = _getVar($_REQUEST, 'notes');

$filename = sprintf('%s-%s.csv',
    $event_id,
    substr($event_name, 0, 10)
);
$filename = scrubFilename($filename);

header('Content-Type: text/csv; charset=utf-8');
header("Content-Disposition: attachment;filename={$filename}");

$headers = ["event_id", "event_name", "venue",
  "section", "row", "seat", "seat_notes",
  "event_date", "event_time", "barcode",
  "fullname", "order_number", "ticket_date",
  "cc_code", "zip_code", "cc_zip", "notes"];

$data = [
  $headers,
  [
    $event_id, $event_name, $venue,
    $section, $row, $seat, $seat_notes,
    $event_date, $event_time, $barcode,
    $fullname, $order_number, $ticket_date,
    $cc_code, $zip_code, $cc_zip, $notes
  ],
];

$file = fopen('php://output', 'w');
foreach ($data as $row)
{
    fputs($file, implode(",", array_map("encodeFunc", $row))."\r\n");
}

// Close the file
fclose($file);

/***
 * @param $value array
 * @return string array values enclosed in quotes every time.
 */
function encodeFunc($value) {
    ///remove any ESCAPED double quotes within string.
    $value = str_replace('\\"','"',$value);
    //then force escape these same double quotes And Any UNESCAPED Ones.
    $value = str_replace('"','\"',$value);
    //force wrap value in quotes and return
    return '"'.$value.'"';
}
