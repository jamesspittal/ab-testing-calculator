ab-testing-calculator
=====================

Statistical significance calculator for A/B Testing

Quick and dirty PHP code for calculating statistical significance for A/B testing.

Install
=======
Include it in your project or use it as you require.

Example
=======
Use ``require_once('ab-testing-calculator.php');`` and ``calculate()`` to check whether your A/B test is statistically significant or not.

Example (Using calculate)
=========================

Let's imagine we run an A/B test for A (control) versus B (variant).

A has 15 visitors and 2 conversions (13.33% conversion rate) and B has 15 visitors with 6 conversions (40% convresion rate.)

So, we run:

``
<?php
require_once('ab-testing-calculator.php');

calculate(15, 2, 15, 6);
?>
``

Output to STDOUT is as below:

``
$ php ab-testing-example-1.php
Split and AB Testing Confidence Calculator

------------------------------------------

Treatment | Visitors Treated | Conversions | Conversion Rate |     Z-Score     | Confidence
-------------------------------------------------------------------------------------------
Control   |        15        |      2      |      13.33      |                 |
Treatment |        15        |      6      |       40%       | 1.7320508075689 |    96%
-------------------------------------------------------------------------------------------
STATISTICAL SIGNIFICANCE ACHIEVED!
``


Uses
====
Feel free to edit this proof of concept as you see fit.

I wrote it to demonstrate the risks of calling 'statistical significance' with a low sample set. As you can see from the example above, the example is 'technically' statistically significant from a mathematical point of view but with a sample size this small, flukes do happen.

The morale of the story: make sure your sample sizes are a reasonable size and it's better to test for too long, than not long enough.

It's also useful demonstrating and better understanding some of the mathematics beind A/B testing statistical significance.

