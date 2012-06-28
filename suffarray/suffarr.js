// (C)2008 Maraigue (http://f52.aaa.livedoor.jp/~maraigue/)
// You can copy and modify this script under BSD license.
// See the end of this script for BSD license.

var INTERVAL = 1500; // Interval of waiting time for each comparing(msec)

var txt;
var len;
var plen;
var arr = new Array;
var tmp;
var searchmin;
var searchmax;

function suffix(p){
	return txt.substring(p);
}

function sufsort(i, j){
	var ci, cj, ct, div;
	if(j <= i + 1) return;
	
	div = Math.floor((i + j) / 2);
	sufsort(i, div);
	sufsort(div, j);
	
	ci = i;
	cj = div;
	ct = i;
	while(ci < div && cj < j){
		if(suffix(arr[ci]) < suffix(arr[cj])){
			tmp[ct] = arr[ci];
			ci++;
		}else{
			tmp[ct] = arr[cj];
			cj++;
		}
		ct++;
	}
	
	if(cj == j){
		while(ci < div){
			tmp[ct] = arr[ci];
			ci++;
			ct++;
		}
	}else{
		while(cj < j){
			tmp[ct] = arr[cj];
			cj++;
			ct++;
		}
	}
	
	for(ct = i; ct < j; ct++) arr[ct] = tmp[ct];
}

function genSufArr(){
	var i, buf;
	
	txt = document.sourcetext.srctxt.value;
	len = txt.length;
	arr = new Array(len);
	for(i = 0; i < len; i++) arr[i] = i;
	tmp = new Array(len);
	
	sufsort(0, len);
	
	buf = '<table border="1" style="font-family:monospace">';
	for(i = 0; i < len; i++){
		buf += '<tr id="line' + i + '">';
		buf += '<th>' + arr[i] + '</th>';
		buf += '<td>' + suffix(arr[i]) + "</td></tr>";
	}
	buf += '</table>'
	document.getElementById('suffixarray').innerHTML = buf;
	document.getElementById('message').innerHTML = "Input a pattern string to search.";
	
	return false;
}

function sufSearch(){
	var pos = Math.floor((searchmax + searchmin) / 2);
	var target = txt.substring(arr[pos], arr[pos] + plen);
	var i;
	
	if(searchmin >= searchmax){
		document.getElementById('message').innerHTML = "Not found anywhere.";
		return false;
	}
	
	document.getElementById('line' + pos).style.backgroundColor = "#FF8000";
	if(target == pat){
		document.getElementById('line' + pos).style.backgroundColor = "#00FF00";
		document.getElementById('message').innerHTML = "Found at position " + arr[pos] + "!!";
		return false;
	}else{
		if(target < pat){
			for(i = pos - 1; i >= searchmin; i--){
				document.getElementById('line' + i).style.backgroundColor = "#FFFF00";
			}
			searchmin = pos + 1;
		}else{
			for(i = pos + 1; i < searchmax; i++){
				document.getElementById('line' + i).style.backgroundColor = "#FFFF00";
			}
			searchmax = pos;
		}
		document.getElementById('message').innerHTML = "Not found at position " + arr[pos] + "...";
	}
	
	setTimeout("sufSearch()", INTERVAL);
}

function searchSufArr(){
	var i;
	
	if(arr.length == 0){
		alert('Create suffix array before searching.');
		return false;
	}
	
	pat = document.pattern.pattxt.value;
	plen = pat.length;
	searchmin = 0;
	searchmax = txt.length;
	
	for(i = searchmin; i < searchmax; i++){
		document.getElementById('line' + i).style.backgroundColor = "#DDDDDD";
	}
	document.getElementById('message').innerHTML = "Start searching...";
	
	setTimeout("sufSearch()", INTERVAL);
	
	return false;
}

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
