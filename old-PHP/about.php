<!DOCTYPE html>
<html lang="en">

<? include("head.php"); ?>

<body id="page">

<? include("header.php"); ?>
<? include("nav.php"); ?>

<article id="main">
	<h1>About Us</h1>


<?php
/* Sample code to open a plain text file and put the data into an array */


/* debug is the term used when trying to find a programming error. This 
 * variable "flag" is used with if statments to print information out for 
 * us when we are debugging and or working on our code.
 */
$debug = false;

if(isset($_GET["debug"])){
    $debug = true;
}

/* Data from: http://www.joespondvermont.com/iceout.php
 * the data file needs to be in plain text comma seperated, Mac users
 * sometimes have trouble creating these files
 * the file looks something like this:

Date, Time
1988-04-26, 12:31
1989-05-05, 9:05
*/

$fileExt=".csv";
$myFileName="growth";
$filename = $myFileName . $fileExt;

if ($debug) 
print "\n\nFilename is $filename";



$file=fopen($filename, "r");


/* the variable $file will be empty or false if the file does not open */
if($file){
    if($debug) print "<p>File Opened. Begin reading data into an array.</p>\n";

    /* This reads the first row which in our case is the column headers */
    $headers=fgetcsv($file);
    
    /* the while loop keeps exectuing until we reach the end of the file at
     * which point it stops. the resulting variable $records is an array with
     * all our data.
     */
    while(!feof($file)){
        $records[]=fgetcsv($file);
    }
    
    //closes the file
    fclose($file);
    if($debug) {
        print "<p>Finished reading. File closed.</p>\n";
        print "<p>Contents of my array<p><pre> "; print_r($records); print "</pre></p>";
    }
} else {
    if($debug) print "<p>File Opened Failed.</p>\n";
}
?>
<?php
print '<p>' . $headers[0] . ' ' . $headers[1] . '</p>' . "\n";
foreach($records as $oneRecord){
    if($oneRecord[0]!=""){  //the eof would be a "" 
        print '<p>' . $oneRecord[0] . ' ' . $oneRecord[1] . '</p>' . "\n";
    }
}
?>
<?php
/* print out a chart showing the day the ice broke (NOTE DATE z would be how
 *  many days of the year for example feb 1 would be the 32nd day)
 * note the image itself is very small but we strecth it based on the hieght
 */
foreach($records as $oneRecord){
    if($oneRecord[0]!=""){  //the eof would be a ""
        print '<img src="graph.png" width="10" height="' . date("z",strtotime($oneRecord[0])) . '">' . "\n";
    }
}
?>
        
        


        
        
</article>

<aside id="other">


<? include("aside.php"); ?>

</aside>


<? include("footer.php"); ?>

</body>
</html>