<?php
/*
* Copyright (c) 2012 Jason Hancock <jsnbyh@gmail.com>
*
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is furnished
* to do so, subject to the following conditions:
*
* The above copyright notice and this permission notice shall be included in all
* copies or substantial portions of the Software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
* THE SOFTWARE.
*
* This file is part of the nagios-puppet bundle that can be found
* at https://github.com/jasonhancock/nagios-redis
*/

$_WARN  = '#FFCC00';
$_CRIT  = '#FF0000';
$_DATA1 = '#00FF00';
$_DATA2 = '#0000FF';

// DS[0] is used_memory. DS[1] is used_memory_rss and should have thresholds set
if(!empty($this->DS[1]['WARN']) && !empty($this->DS[1]['CRIT'])) {
    $warn = $this->DS[1]['WARN'];
    $crit = $this->DS[1]['CRIT'];
}


$opt[1]     = "--vertical-label \"Bytes\" --title \"$hostname / Memory Usage\" --lower=0";
$def[1]     = '';
$ds_name[1] = 'Redis Memory Usage';

$def[1]  = rrd::def   ('var1', $this->DS[0]['RRDFILE'], $this->DS[0]['DS'], 'AVERAGE');
$def[1] .= rrd::line2 ('var1', $_DATA1, sprintf('%-15s', $this->DS[0]['NAME']));
$def[1] .= rrd::gprint('var1', array('LAST','MAX','AVERAGE'), '%3.1lf %s');

$def[1] .= rrd::def   ('var2', $this->DS[1]['RRDFILE'], $this->DS[1]['DS'], 'AVERAGE');
$def[1] .= rrd::line2 ('var2', $_DATA2, sprintf('%-15s', $this->DS[1]['NAME']));
$def[1] .= rrd::gprint('var2', array('LAST','MAX','AVERAGE'), '%3.1lf %s');

if(isset($warn) && isset($crit)) {
    $def[1] .= rrd::line2($warn, $_WARN, "Warning  $warn \\n");
    $def[1] .= rrd::line2($crit, $_CRIT, "Critical $crit \\n");
}
