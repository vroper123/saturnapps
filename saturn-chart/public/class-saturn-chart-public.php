<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       bsquadt.com
 * @since      1.0.0
 *
 * @package    Saturn_Chart
 * @subpackage Saturn_Chart/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Saturn_Chart
 * @subpackage Saturn_Chart/public
 * @author     Vincent Roper <vincent_roper@yahoo.com>
 */
class Saturn_Chart_Public
{
    
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;
    
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;
    
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        
        $this->plugin_name = $plugin_name;
        $this->version     = $version;
        
    }
    
    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Saturn_Chart_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Saturn_Chart_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/saturn-chart-public.css', array(), $this->version, 'all');
		wp_enqueue_style($this->plugin_name.'bootstrap', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', array(), '3.3.7', 'all');
        
    }
    
    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Saturn_Chart_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Saturn_Chart_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/saturn-chart-public.js', array(
            'jquery'
        ), $this->version, false);
        wp_enqueue_script('saturn-chartjs', plugin_dir_url(__FILE__) . 'js/Chart.bundle.min.js', array(), '2.6.0', false);
		
		 wp_enqueue_script($this->plugin_name.'bootstrapjs', plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', array(
            'jquery'
        ), '3.3.7', false);
        
    }
    
    function ImportCSV2Array($filename)
    {
        $row = 0;
        $col = 0;
        
        $handle = @fopen($filename, "r");
        if ($handle) {
            while (($row = fgetcsv($handle, 4096)) !== false) {
                if (empty($fields)) {
                    $fields = $row;
                    continue;
                }
                
                foreach ($row as $k => $value) {
                    $results[$col][$fields[$k]] = $value;
                }
                $col++;
                unset($row);
            }
            if (!feof($handle)) {
                echo "Error: unexpected fgets() failn";
            }
            fclose($handle);
        }
        
        return $results;
    }
    
    public function nir_shortcode()
    {
?>

	   <?php
        $url      = plugin_dir_url(__FILE__) . 'csv/NIR.csv';
        $csvArray = $this->ImportCSV2Array($url);
        foreach ($csvArray as $row) {
            // echo $row['label'];
            $ArrJson[] = $row['label'];
            $col[]     = (float) $row['value'];
            
        }
        
        
        ob_start();
?>
	   
	       <div class="saturn-box">
		    <h4 class="saturn-title">Net International Reserves</h4>
			<div class="chart-container">
				<canvas style="position: relative; height:30vh; width:30vw" id="myChart"></canvas>
			</div>
					<script>
							var ctx = document.getElementById("myChart");
							var datalabel = <?php
        echo json_encode($ArrJson);
?>;
							var datacsv = <?php
        echo json_encode($col);
?>;
							var myChart = new Chart(ctx, {
								type: 'line',
								data: {
									labels: datalabel,
									datasets: [{
										
										data: datacsv,
									    fill: false,
										lineTension: 0, 
										
										borderWidth:1.5,
										borderColor: '#333',
										backgroundColor: '#000',
										pointBorderColor: '#000',
										pointBackgroundColor: '#ddd',
										pointHoverBackgroundColor: '#000',
										pointHoverBorderColor: '#000',
									}]
								},
								options: {
									legend: {
										display: false,
									},
								responsive: true,
									maintainAspectRatio: false,
									scales: {
										    xAxes: [{
											gridLines: {
												display:false
											}
										}],
										yAxes: [{
											 display: true,
											 gridLines: {
												display:false
											},
												scaleLabel: {
													display: true,
													labelString: '(US$ MILLIONS)'
												}
										}]
									}
								}
							});
				</script>
			</div>
		<?php
        return ob_get_clean();
?>
		<?php
    }
    
    
    
    public function unemployment_shortcode()
    {
?>

	   <?php
        $url      = plugin_dir_url(__FILE__) . 'csv/UnemploymentData.csv';
        $csvArray = $this->ImportCSV2Array($url);
        foreach ($csvArray as $row) {
            // echo $row['label'];
            $ArrJson[] = $row['label'];
            $col[]     = $row['value'];
            
        }
        
        
        ob_start();
?>
	   
	       <div class="saturn-box">
		    <h4 class="saturn-title">Unemployment Rate</h4>
			<div class="chart-container">
				<canvas style="position: relative; height:30vh; width:30vw" id="myChart2"></canvas>
			</div>
					<script>
							var ctx = document.getElementById("myChart2");
							var datalabel = <?php
        echo json_encode($ArrJson);
?>;
							var datacsv = <?php
        echo json_encode($col);
?>;
							var myChart2 = new Chart(ctx, {
								type: 'line',
								data: {
									labels: datalabel,
									datasets: [{
										//label: 'Percentage(%)',
										data: datacsv,
										lineTension: 0, 
									    fill: false,
										
										borderWidth:1.5,
										borderColor: '#333',
										backgroundColor: '#000',
										pointBorderColor: '#000',
										pointBackgroundColor: '#ddd',
										pointHoverBackgroundColor: '#000',
										pointHoverBorderColor: '#000',
									}]
								},
								
								options: {
									
                
									legend: {
    display: false,
},
								responsive: true,
									maintainAspectRatio: false,
									scales: {
										    xAxes: [{
												gridLines: {
													display:false
												}
											}],
										yAxes: [{
											 display: true,
											  gridLines:{
														display:false
													},
												scaleLabel: {
													display: true,
													
													labelString: 'Percentage(%)'
												}
										}]
									}
								}
							});
				</script>
			</div>
		<?php
        return ob_get_clean();
?>
		<?php
    }
    
    
    
    public function inflation_shortcode()
    {
?>

	   <?php
        $url      = plugin_dir_url(__FILE__) . 'csv/InflationRate.csv';
        $csvArray = $this->ImportCSV2Array($url);
        foreach ($csvArray as $row) {
            // echo $row['label'];
            $ArrJson[] = $row['label'];
            $col[]     = $row['value'];
            
        }
        
        
        ob_start();
?>
	   
	       <div class="saturn-box">
		    <h4 class="saturn-title">Monthly Inflation Rate</h4>
			<div class="chart-container">
				<canvas style="position: relative; height:30vh; width:30vw" id="myChart3"></canvas>
			</div>
		
					<script>
							var ctx = document.getElementById("myChart3");
							var datalabel = <?php
        echo json_encode($ArrJson);
?>;
							var datacsv = <?php
        echo json_encode($col);
?>;
							var myChart3 = new Chart(ctx, {
								type: 'line',
								data: {
									labels: datalabel,
									datasets: [{
										//label: 'Percentage(%)', bezierCurve : false
										lineTension: 0, 
										data: datacsv,
									    fill: false,
										borderWidth:1.5,
										borderColor: '#333',
										backgroundColor: '#000',
										pointBorderColor: '#000',
										pointBackgroundColor: '#ddd',
										pointHoverBackgroundColor: '#000',
										pointHoverBorderColor: '#000',
									}]
								},
								options: {
							    legend: {
									display: false,
									 fontColor: '#000'
								},
								responsive: true, 
									maintainAspectRatio: false,
									scales: {
										    xAxes: [{
												gridLines: {
													display:false
												}
											}],
										yAxes: [{
											 display: true,
											 gridLines:{
														display:false
													},
												scaleLabel: {
													 //fontColor: 'red',
													display: true,
													
													labelString: 'Percentage(%)'
												}
										}]
									}
								}
							});
							
						
				</script>
			</div>
		<?php
        return ob_get_clean();
?>
		<?php
    }
    
    
    
    public function GDP_shortcode()
    {
?>

	   <?php
        $url      = plugin_dir_url(__FILE__) . 'csv/GDP.csv';
        $csvArray = $this->ImportCSV2Array($url);
        foreach ($csvArray as $row) {
            // echo $row['label'];
            if ($row ==" ") {
                unset($row['lable']);
                unset($row['GDP Growth(%)']);
                unset($row['GDP']);
            } else {
                
                $ArrJson[] = $row['lable'];
                $col[]     = $row['GDP Growth(%)'];
                $col2[]    = $row['GDP'];
            }
            
        }
        
        
        ob_start();
?>
	   
	       <div class="saturn-box">
		    <h4 class="saturn-title">Gross Domestic Product (GDP)</h4>
			<div id="js-legend" class="chart-legend"></div>
			<div class="chart-container">
				<canvas style="position: relative; height:40vh; width:50vw" id="myChart4"></canvas>
			</div>
				
					<script>
      var barChartData = {
            labels:  <?php
        echo json_encode(array_values(array_filter($ArrJson)));
?>,
            datasets: [{
                type: 'line',
                  label: "GDP Growth (%)",
                    data:  <?php
        echo json_encode(array_values(array_filter($col2)));
?>,
                    fill: false,
					lineTension: 0, 
					borderWidth:1.5,
                    borderColor: '#FE0000',
                    backgroundColor: '#FE0000',
                    pointBorderColor: '#FE0000',
                    pointBackgroundColor: '#FFC0CB',
                    pointHoverBackgroundColor: '#FE0000',
                    pointHoverBorderColor: '#FE0000',
                    yAxisID: 'y-axis-2'
                   
            }, {
                label: "GDP  Billions($)",
                    type:'bar',
                    data: <?php
        echo json_encode(array_values(array_filter($col)));
?>,
                    fill: false,
					 //lineTension: 0, 
					 //borderWidth:1.5,
					 borderColor: '#01AEF0',
                    backgroundColor: '#01AEF0',
                    //pointBorderColor: '#01AEF0',
                    //pointBackgroundColor: '#DBE2EA',
                    //pointHoverBackgroundColor: '#01AEF0',
                    //pointHoverBorderColor: '#01AEF0',
                    yAxisID: 'y-axis-1'
					
            } ]
        };
        
        
            var ctx = document.getElementById("myChart4").getContext("2d");
			
             myChart4 = new Chart(ctx, {
                type: 'bar',
                data: barChartData,
                options: {
					legend: {
									display: false,
									 fontColor: '#000'
								},
                responsive: true,
                tooltips: {
                  mode: 'label'
              },
              elements: {
                line: {
                    fill: false
                }
            },
              scales: {
                xAxes: [{
                    display: true,
                    gridLines: {
                        display: false
                    },
                    labels: {
                        show: true,
                    }
					
                }],
                yAxes: [{
					 
			       scaleLabel: {
		  			 //fontColor: 'red',
						display: true,								
					  labelString: 'Percentage Change'
					},
                    type: "linear",
                    display: true,
					  
                    position: "left",
                    id: "y-axis-1",
                    gridLines:{
                        display: false
                    },
                    labels: {
                        show:true,
                        
                    },
					 
					ticks: {
                        min: 0,
                        beginAtZero: true
                    }
					
                }, {
					scaleLabel: {
		  			 //fontColor: 'red',
						display: true,								
					  labelString: 'Billions ($)'
					},
					id: "y-axis-2",
                    type: "linear",
                 	display: true,	
                    position: "right",
                    
                    gridLines:{
                        display: false
                    },
                    labels: {
                        show:true,
                        
                    }

                }]
            }
            }
            });
			document.getElementById('js-legend').innerHTML = myChart4.generateLegend();
     
    </script>
			</div>
		<?php
        return ob_get_clean();
?>
		<?php
    }
    
     public function cgo_shortcode()
    {
?>

	   <?php
        $url      = plugin_dir_url(__FILE__) . 'csv/CentralGovernmentOperations.csv';
        $csvArray = $this->ImportCSV2Array($url);
       
            // echo $row['label'];     
        ob_start();
?>
	   
	       <div class="saturn-box " id="sat-box2" >
		     <h4 class="saturn-title">Central Goverment Operations</h4>
		   <div style="padding:15px;" style="overflow-x:auto;">
					<table class="table"  >
					  <thead >
					  
						<tr style="background:#77B5FE;">

						  <th>IN J$ Millions</th>
						  <th >Prov. Apr-Jun_2017</th>
						  <th >Budget. Apr-Jun_2017</th>
						</tr>
					  </thead>
					  <tbody style="font-size:12px !important;">
					  <?php  foreach ($csvArray as $row) {  ?>
						<tr>
						  <td style="background:aliceblue;" id="no-wr"><?php echo esc_html($row['InJ$Millions']);?></td>
						  <td ><?php echo esc_html($row['Prov. Apr-Jun_2017']);?></td>
						  <td><?php echo esc_html($row['Budget. Apr-Jun_2017']);?></td>
						  <?php  }?> 
						</tr>
					  </tbody>
					</table>
		
			</div>
	     </div>		
		<?php
        return ob_get_clean();
?>
		<?php
    }
    
    
    
    public function satbsqt_shortcode_registration()
    {
        add_shortcode('NIR', array(
            $this,
            'nir_shortcode'
        ));
        
        add_shortcode('UED', array(
            $this,
            'unemployment_shortcode'
        ));
        
        add_shortcode('IFR', array(
            $this,
            'inflation_shortcode'
        ));
        
        add_shortcode('GDP', array(
            $this,
            'GDP_shortcode'
        ));
		
		 add_shortcode('CGO', array(
            $this,
            'cgo_shortcode'
        ));
    }
    
    
}
