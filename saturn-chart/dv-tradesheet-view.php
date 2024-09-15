<?php
/**
 *---------------------------------------------------------------------------------
 *    @package  Tradesheet Table View
 *    @copyright  Copyright (c) 2017
 *---------------------------------------------------------------------------------
 *
 *    Plugin Name: Tradesheet Table View
 *    Plugin URI: http://wp.devuri.com/wordpress-plugins/
 *    Description: Display tradesheet with shortcode [tradesheetdata] uses sticky table header.
 *    Version: 2.5.0
 *    Author: devuri
 *    Author URI:
 *    Contributors: devuri, Vincent Roper
 *    License: GPLv2 or later
 *    License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *    Text Domain: tradesheet-view
 *    Domain Path: /languages
 *    Usage: .
 *    Tags:
 *
 *
 *    Requires PHP: 5.2+
 *    Tested up to PHP: 7.0
 *
 *    Copyright 2017 devuri    (email : uriel@zipteq.com)
 *    License: GNU General Public License
 */


// * DIRECT ACCESS DENIED *
if (!defined("WPINC")) {
    die;
}


// lets register_resources init....
function dvtradesheet_register_resources() {
  /**
  *loads styles
  */
  wp_register_style('dvtradesheet-sticky', plugin_dir_url(__FILE__) . 'assets/css/stickysort.css', array(), '1.1', 'all');
  /**
   *loads scripts
   */
  wp_register_script('dvtradesheet-debounce', plugin_dir_url(__FILE__) . 'assets/js/jquery.ba-throttle-debounce.min.js', array(), '1.2', true);
  wp_register_script('dvtradesheet-sticksort', plugin_dir_url(__FILE__) . 'assets/js/jquery.stickysort.min.js', array(  'jquery'), '1.1', true);
  wp_register_script('dvtradesheet-public-js', plugin_dir_url(__FILE__) . 'assets/js/sat-trade-sheet-public.js', array(  'jquery'), '1.0.0', true);
}
add_action( 'init', 'dvtradesheet_register_resources' );




/** Trade sheets table
 *************************/
function dvu_csvtable_tradesheetdata($csv_content)
{


    $tdpadding = 7;

    $table = "<section id='tradesheet'>";
    $table = "<table id='tradetable' style='font-family: Helvetica,sans-serif !important;'>";
    $table .= "<thead  ><tr style='color: #fff;background: #3C4694 !important; font-weight: 800px;font-size: 15.5px;' >";
    $table .="<th style='padding:7px; background: #3C4694 !important;'><strong> Company </strong></th>";
    $table .="<th style='padding:7px;background: #3C4694 !important;'><strong>  Closing Price ($)</strong></th>";
    $table .="<th style='padding:7px;background: #3C4694 !important;'><strong>  Change (cents)	</strong></th>";
    $table .="<th style='padding:7px;background: #3C4694 !important;'><strong>  P/E Ratio(times)</strong></th>";
    $table .="<th style='padding:7px;background: #3C4694 !important;'><strong>	12 Month Proj. P/E (times)</strong></th>";
    $table .="<th style='padding:7px;background: #3C4694 !important;'><strong>	Price to Book (times)</strong></th>";
    $table .="<th style='padding:7px;background: #3C4694 !important;'><strong>	Div Yield (2017 YTD)</strong></th>";
    $table .="<th style='padding:7px;background: #3C4694 !important;'><strong>	Div Yield (2016 YTD)</strong></th>";

    $rows = str_getcsv($csv_content, "\n");


    // remove the header


    foreach ($rows as &$row) {

    }
    $table .= "</tr></thead>";
    $table .= "<tbody >";
    $rows = str_getcsv($csv_content, "\n");


    // this line removes the header
    $rows_noheader = array_shift($rows);

    foreach ($rows as &$row) {
        $table .= "<tr>";

        $cells = str_getcsv($row);

        //var_dump($cells);
        foreach ($cells as &$cell) {
            //$table .= "<td>$cell</td>";

        }

        $table .= "<td style='padding: " . $tdpadding . "px; width:140px;'> "; // td

        $table .= "  <strong>" . $cells[0] . "</strong></td>"; // Company
        $table .= "<td style='padding: " . $tdpadding . "px;'>" . $cells[2] . "</td>"; // CLOSING PRICE
        $table .= "<td style='padding: " . $tdpadding . "px;'>" . $cells[5] . "</td>"; // CHANGE
        $table .= "<td style='padding: " . $tdpadding . "px;'>" . $cells[13] . "</td>"; // P/E RATIO
        $table .= "<td style='padding: " . $tdpadding . "px;'>" . $cells[14] . "</td>"; // P/E ON 12 MONTH PROJ. EPS
        $table .= "<td style='padding: " . $tdpadding . "px;'>" . $cells[15] . "</td>"; // PRICE TO BOOK
        $table .= "<td style='padding: " . $tdpadding . "px;'>" . $cells[19] . "</td>"; // Dividend Yield (2017 YTD)
        $table .= "<td style='padding: " . $tdpadding . "px;'>" . $cells[20] . "</td>"; // Dividend Yield (2016 YTD)


    }
    $table .= "</tbody></table>";
    $table .= "</section>";
    return $table;
}

/*
 * shortcode [tradesheetdata link="linktocsvfile"]
 * @devuri
 * [tradesheetdata] display the latest tradesheet if no link is set.
 * [tradesheetdata link="https://www.mayberryinv.com/wp-content/uploads/2017/02/Trade-Sheet-August-11-2017.csv"] display the a tradesheet that is linked
 * the option can be updated via the tools menu trade sheet update tool
 **************************/
add_shortcode('tradesheetdata', 'dvu_tradesheetdata');
function dvu_tradesheetdata($atts)
{

    $a = shortcode_atts(array(
        'csvtitle' => 'Trade Sheet',
        'link' => get_option('optminv_tradesheet'),
        'date' => get_option('optminv_tradesheet_date')
    ), $atts);

    // load some stuff here
    wp_enqueue_style('dvtradesheet-sticky');
  	wp_enqueue_script('dvtradesheet-debounce');
  	wp_enqueue_script('dvtradesheet-sticksort');
  	wp_enqueue_script('dvtradesheet-public-js');


    ob_start();
    // csv convert to table
    $thecsv          = file_get_contents($a['link']);
    $csvdownloadlink = $a['link'];
    $tradesheet_date = $a['date'];
    $csvtable        = dvu_csvtable_tradesheetdata($thecsv);
    // the table styled for avada theme
    // csv table
    return 'Date: ' . $tradesheet_date . ' <p/> <div class="table-1" style="padding: 4px; ">' . $csvtable . '</div> <hr/> <a href="' . $csvdownloadlink . '">Click Here to <strong>Download </strong></a>';


    $output_csvtable_obj = ob_get_contents();
    ob_end_clean();
    return $output_csvtable_obj;

} // mrp_csvtable
