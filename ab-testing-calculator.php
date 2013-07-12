<?php

// Control, Treatment 1, Treatment 2, Treatment 3

// array(visitors, conversions)

// $c = array(182, 35);
//$tA = array(180, 45);
//$tB = array(189, 28);
//$tC = array(188, 61);

$c  = array(15, 2);
$tA = array(15, 6);

// cr: calculation of the conversion rate
// zscore: calculation of the z-score
// cumnormdist: calculation of the cumulative normal distribution
// ssize: Given a conversion rate, calculate a recommended sample size
//        E.g: 0.25 worst, 0.15, 0.05 best at a 95% confidence.

function cr($t)
{
    return $t[1]/$t[0];
}

function zscore($c, $t)
{
    $z = cr($t)-cr($c);
    $s = (cr($t)*(1-cr($t)))/$t[0] + (cr($c)*(1-cr($c)))/$c[0];
    return $z/sqrt($s);
}

function cumnormdist($x)
{
  $b1 =  0.319381530;
  $b2 = -0.356563782;
  $b3 =  1.781477937;
  $b4 = -1.821255978;
  $b5 =  1.330274429;
  $p  =  0.2316419;
  $c  =  0.39894228;

  if($x >= 0.0) {
      $t = 1.0 / ( 1.0 + $p * $x );
      return (1.0 - $c * exp( -$x * $x / 2.0 ) * $t *
      ( $t *( $t * ( $t * ( $t * $b5 + $b4 ) + $b3 ) + $b2 ) + $b1 ));
  }
  else {
      $t = 1.0 / ( 1.0 - $p * $x );
      return ( $c * exp( -$x * $x / 2.0 ) * $t *
      ( $t *( $t * ( $t * ( $t * $b5 + $b4 ) + $b3 ) + $b2 ) + $b1 ));
    }
}

function ssize($conv)
{
    $a = 3.84145882689;
    $res = array();
    $bs = array(0.0625, 0.0225, 0.0025);
    foreach ($bs as $b) {
        $res[] = (int) ((1-$conv)*$a/($b*$conv));
    }
    return $res;
}

function calculate($control_number_visitors, $control_number_conversions, $treatment_number_visitors, $treatment_number_conversions) {
 $c  = array($control_number_visitors, $control_number_conversions);
 $tA = array($treatment_number_visitors, $treatment_number_conversions);

 // Calculate conversion rates.
 $c_conversion_rate  = ($control_number_conversions / $control_number_visitors) * 100;
 $tA_conversion_rate = ($treatment_number_conversions  / $treatment_number_visitors) * 100;
 $c_conversion_rate = $c_conversion_rate . '%';
 $tA_conversion_rate = $tA_conversion_rate . '%';

 // The z-score is ... [explain]
 $zscore = zscore($c, $tA);

 // Calculate the 'cumulative normal distribution' (confidence ratio)
 $confidence = cumnormdist($zscore);

 // If the 'confidence interval is >95%', the test is statistically significant.
 $confidence_as_percentage = $confidence * 100;

 // Pad the strings for output
 $cV = str_pad($control_number_visitors, 16, ' ', STR_PAD_BOTH);
 $cC = str_pad($control_number_conversions, 11, ' ', STR_PAD_BOTH);

 $tV = str_pad($treatment_number_visitors, 16, ' ', STR_PAD_BOTH);
 $tC = str_pad($treatment_number_conversions, 11, ' ', STR_PAD_BOTH);

 $cr_c = str_pad(sprintf('%0.2f', $c_conversion_rate), 15, ' ', STR_PAD_BOTH);
 $cr_t = str_pad($tA_conversion_rate, 15, ' ', STR_PAD_BOTH);

 $zs = str_pad($zscore, 15, ' ', STR_PAD_BOTH);

 $cratio = str_pad((sprintf('%0.2f', $confidence) * 100) . '%', 10, ' ', STR_PAD_BOTH);

 print "Split and AB Testing Confidence Calculator\n\n";
 print "------------------------------------------\n\n";
 print "Treatment | Visitors Treated | Conversions | Conversion Rate |     Z-Score     | Confidence\n";
 print "-------------------------------------------------------------------------------------------\n";
 print "Control   | $cV | $cC | $cr_c |                 |        \n";
 print "Treatment | $tV | $tC | $cr_t | $zs | $cratio        \n";
 print "-------------------------------------------------------------------------------------------\n";

 if ($cratio >= 95) {
  print "STATISTICAL SIGNIFICANCE ACHIEVED!\n\n";
 }
 else {
  print "Warning: Less than 95% statistical significance.\n\n";
 }

}

//Example #1:
//calculate(15, 2, 15, 6);

//Example #2:
//calculate(10, 2, 10, 6);

//print zscore($c, $tA).' - '.cumnormdist(zscore($c, $tA))."\n";
//print zscore($c, $tB).' - '.cumnormdist(zscore($c, $tB))."\n";
//print zscore($c, $tC).' - '.cumnormdist(zscore($c, $tC))."\n";
//print '1.645 - '.cumnormdist(1.645)."\n";
