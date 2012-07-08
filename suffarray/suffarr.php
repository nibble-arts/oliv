<?
// (C)2008 Maraigue (http://f52.aaa.livedoor.jp/~maraigue/)
// You can copy and modify this script under BSD license.
// See the end of this script for BSD license.

	if($_GET['en']){
		header("content-language: en");
		$e = true;
	}else{
		header("content-language: ja");
		$e = false;
	}
?>
<html>
<head>
<script type="text/javascript" src="sufarr.js"></script>
<title>Suffix Array</title>
<?php
	if($_GET['dl']==1){
		$script = "sufarr.php";
		echo "</head>\n";
		echo "<body>\n";
		
		echo "<p>$script</p>\n";
		echo "<textarea cols=\"100\" rows=\"20\">";
		echo htmlspecialchars(file_get_contents($script));
		echo "</textarea>\n";
		
		echo "<p>sufarr.js</p>\n";
		echo "<textarea cols=\"100\" rows=\"20\">";
		echo htmlspecialchars(file_get_contents("sufarr.js"));
		echo "</textarea>\n";
		
		echo "<p><a href=\"$script";
		if($e){ echo "?en=1"; }
		echo "\">Back</a></p>\n";
		echo "</body>\n";
		echo "</html>\n";
		exit;
	}
?>
</head>
<body>
<p><? if($e){ ?><a href="index.php?en=1">Back to top</a></p>
<? }else{ ?><p><a href="index.php">–ß‚é</a></p>
<? } ?><hr>

<h1>Try Suffix Array!</h1>
<p><a href="?dl=1<? if($e){ echo '&en=1'; }?>">View this script</a><br>
<? if($e){ ?>Note: In this script, suffix sorting with <var>O</var>(<var>n</var>) or <var>O</var>(<var>n</var> log <var>n</var>) time is not implemented!
<? }else{ ?>¦‚±‚ÌƒXƒNƒŠƒvƒg‚Å‚ÍAsuffix‚Ìƒ\[ƒg‚ðŽžŠÔŒvŽZ—Ê<var>O</var>(<var>n</var>) ‚¨‚æ‚Ñ <var>O</var>(<var>n</var> log <var>n</var>)‚Ås‚¤ƒAƒ‹ƒSƒŠƒYƒ€‚Í—p‚¢‚Ä‚¢‚Ü‚¹‚ñB
<? } ?></p>

<form name="sourcetext" action="#" onsubmit="return genSufArr()"><p>
Target text to be sought: <input type="text" name="srctxt" autocomplete="off" value="abracadabra">
<input type="submit" value="Create suffix array">
</p></form>
<form name="pattern" action="#" onsubmit="return searchSufArr()"><p>
Pattern to search: <input type="text" name="pattxt" autocomplete="off" value="cad">
<input type="submit" value="Start searching">
</p></form>
<p id="message"></p>
<div id="suffixarray">
</div>
</body>
</html>
<?php
// Copyright (c) 2008, Maraigue
//
// All rights reserved.
//
// Redistribution and use in source and binary forms, with or without
// modification, are permitted provided that the following conditions
// are met:
//
// Redistributions of source code must retain the above copyright
// notice, this list of conditions and the following disclaimer.
// Redistributions in binary form must reproduce the above copyright
// notice, this list of conditions and the following disclaimer in the
// documentation and/or other materials provided with the distribution.
// Neither the name of the Maraigue nor the names of its contributors
// may be used to endorse or promote products derived from this
// software without specific prior written permission.
//
// THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
// "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
// LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
// FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
// COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
// INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
// BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
// LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
// CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
// LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
// ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
// POSSIBILITY OF SUCH DAMAGE.
?>
